<?php namespace Impl\Repo\Tag;

use Impl\Repo\RepoAbstract;
use Impl\Service\Cache\CacheInterface;
use Illuminate\Database\Eloquent\Model;

class EloquentTag extends RepoAbstract implements TagInterface {

    protected $tag;
    protected $cache;

    // Sınıf bağımlılığı: Eloquent modeli
    public function __construct(Model $tag, CacheInterface $cache)
    {
        $this->tag = $tag;
        $this->cache = $cache;
    }

    /**
     * Mevcut tag'ları bul veya mevcut değilse oluştur
     *
     * @param  array $tags  Değişlkenler dizisi, her biri bir tag'ı ifade eder
     * @return array        Tag nesnesi Arrayable collection'ı dizisi
     */
    public function findOrCreate(array $tags)
    {
        $foundTags = $this->tag->whereIn('tag', $tags)->get();

        $returnTags = array();

        if( $foundTags )
        {
            foreach( $foundTags as $tag )
            {
                $pos = array_search($tag->tag, $tags);

                // Yanıt olarak dönen tag'ları diziye ekle
                if( $pos !== false )
                {
                    $returnTags[] = $tag;
                    unset($tags[$pos]);
                }
            }
        }

        // Kalan tag'ları yeni olarak ekle
        foreach( $tags as $tag )
        {
            $returnTags[] = $this->tag->create(array(
                                'tag' => $tag,
                                'slug' => $this->slug($tag),
                            ));
        }

        return $returnTags;
    }

}