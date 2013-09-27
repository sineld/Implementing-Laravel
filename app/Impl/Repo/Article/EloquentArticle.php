<?php namespace Impl\Repo\Article;

use Impl\Repo\RepoAbstract;
use Impl\Repo\Tag\TagInterface;
use Impl\Service\Cache\CacheInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentArticle extends RepoAbstract implements ArticleInterface {

    protected $article;
    protected $tag;
    protected $cache;

    // Sınıf bağımlılığı: Eloquent modeli
    public function __construct(Model $article, TagInterface $tag, CacheInterface $cache)
    {
        $this->article = $article;
        $this->tag = $tag;
        $this->cache = $cache;
    }

    /**
     * Status'a kayıtsız kalarak
     * ID'ye göre tek bir makale getirir
     *
     * @param  int $id Makale ID
     * @return stdObject Makale bilgisinden oluşan nesne
     */
    public function byId($id)
    {
        // Her makale slug'ı için, benzersiz cache key'i inşa et
        $key = md5('id.'.$id);

        if( $this->cache->has($key) )
        {
            return $this->cache->get($key);
        }

        // Öğe cache'de yoksa modeli getir
        $article = $this->article->with('status')
                            ->with('author')
                            ->with('tags')
                            ->where('id', $id)
                            ->first();

        // Sonraki istekler için cache'de sakla
        $this->cache->put($key, $article);

        return $article;
    }

    /**
     * Sayfalandırılmış makaleleri getirir
     *
     * @param int $page Sayfa başına makale sayısı
     * @param int $limit Sayfa başına sonuç
     * @param boolean $all Yayınlanmış olanları veya tümünü görüntüle
     * @return StdClass Sayfalama için $items ve $totalItems'den oluşan StdClass nesnesi
     */
    public function byPage($page=1, $limit=10, $all=false)
    {
        // Cache öğe keyini tag, sayfa numarası ve limit başına benzersiz oluşturuyoruz
        // tümünü listelemiyorsak limit kullan
        $allkey = ($all) ? '.all' : '';
        $key = md5('page.'.$page.'.'.$limit.$allkey);

        if( $this->cache->has($key) )
        {
            return $this->cache->get($key);
        }

        // Öğe cache'de yoksa modeli getir
        $query = $this->article->with('status')
                               ->with('author')
                               ->with('tags')
                               ->orderBy('created_at', 'desc');

        // Tüm makaleler veya yalnızca yayınlanmış olanlar
        if( ! $all )
        {
            $query->where('status_id', 1);
        }

        $articles = $query->skip( $limit * ($page-1) )
                        ->take($limit)
                        ->get();

        // Sonraki istekler için cache'de sakla
        $cached = $this->cache->putPaginated(
            $page,
            $limit,
            $this->totalArticles($all),
            $articles->all(),
            $key
        );

        return $cached;
    }

    /**
     * URL'ye göre tek bir makale getirir
     *
     * @param string  Makalenin URL slug'ı
     * @return object  Makale bilgisinden oluşan nesne
     */
    public function bySlug($slug)
    {
        // Makale slug'ı başına benzersiz olacak şekilde cache keyini oluştur
        $key = md5('slug.'.$slug);

        if( $this->cache->has($key) )
        {
            return $this->cache->get($key);
        }

        // Öğe cache'de yoksa modeli getir
        $article = $this->article->with('status')
                            ->with('author')
                            ->with('tags')
                            ->where('slug', $slug)
                            ->where('status_id', 1)
                            ->first();

        // Sonraki istekler için cache'de sakla
        $this->cache->put($key, $article);

        return $article;

    }

   /**
     * Taglarına göre makaleleri getirir
     *
     * @param string  Tagın URL slug'ı
     * @param int Sayfa başına makale sayısı
     * @return StdClass Object with $items and $totalItems for pagination
     */
    public function byTag($tag, $page=1, $limit=10)
    {
        // Cache öğe keyini tag, sayfa numarası ve limit başına benzersiz oluşturuyoruz
        $key = md5('tag.'.$tag.'.'.$page.'.'.$limit);

        if( $this->cache->has($key) )
        {
            return $this->cache->get($key);
        }

        // Öğe cache'de yoksa modeli getir
        $foundTag = $this->tag->where('slug', $tag)->first();

        if( !$foundTag )
        {
            // Belki bir hata, böyle bir tag olmadığı döndürülebilir
            return false;
        }

        $articles = $this->tag->articles()
                        ->where('articles.status_id', 1)
                        ->orderBy('articles.created_at', 'desc')
                        ->skip( $limit * ($page-1) )
                        ->take($limit)
                        ->get();

        // Sonraki istekler için cache'de sakla
        $cached = $this->cache->put(
            $page,
            $limit,
            $this->totalByTag(),
            $articles->all(),
            $key
        );

        return $cached;

    }

    /**
     * Yeni bir makale oluştur
     *
     * @param array  Yeni bir nesne oluşturma verisi
     * @return boolean
     */
    public function create(array $data)
    {
        // Makaleyi oluştur
        $article = $this->article->create(array(
            'user_id' => $data['user_id'],
            'status_id' => $data['status_id'],
            'title' => $data['title'],
            'slug' => $this->slug($data['title']),
            'excerpt' => $data['excerpt'],
            'content' => $data['content'],
        ));

        if( ! $article )
        {
            return false;
        }

        $this->syncTags($article, $data['tags']);

        return true;
    }

    /**
     * Mevcut bir makaleyi güncelle
     *
     * @param array  Bir makaleyi güncelleme verisi
     * @return boolean
     */
    public function update(array $data)
    {
        $article = $this->article->find($data['id']);
        $article->user_id = $data['user_id'];
        $article->status_id = $data['status_id'];
        $article->title = $data['title'];
        $article->slug = $this->slug($data['title']);
        $article->excerpt = $data['excerpt'];
        $article->content = $data['content'];
        $article->save();

        $this->syncTags($article, $data['tags']);

        return true;
    }

    /**
     * Tagları article'larla senkronize eder
     *
     * @param \Illuminate\Database\Eloquent\Model  $article
     * @param array  $tags
     * @return void
     */
    protected function syncTags(Model $article, array $tags)
    {
        // Mevcut tagları ve oluşturulan yeni tagları
        // elde ettikten sonra döndür
        $found = $this->tag->findOrCreate( $tags );

        $tagIds = array();

        foreach($found as $tag)
        {
            $tagIds[] = $tag->id;
        }

        // Article'a bu tagları ata
        $article->tags()->sync($tagIds);
    }

    /**
     * Toplam makale sayısını getir
     *
     * @return int  Toplam makale
     */
    protected function totalArticles($all = false)
    {
        if( ! $all )
        {
            return $this->article->where('status_id', 1)->count();
        }

        return $this->article->count();
    }

    /**
     * Tag başına toplam makale sayısını getir
     *
     * @param  string  $tag  Tag slug'ı
     * @return int     Tag başına makale sayısı
     */
    protected function totalByTag($tag)
    {
        return $this->tag->bySlug($tag)
                    ->articles()
                    ->where('status_id', 1)
                    ->count();
    }


}