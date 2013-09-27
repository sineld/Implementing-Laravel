<?php

use Impl\Repo\Status\StatusInterface;
use Impl\Repo\Article\ArticleInterface;
use Impl\Service\Form\Article\ArticleForm;

class ArticleController extends BaseController {

    protected $layout = 'layout';

    protected $article;
    protected $articleform;
    protected $status;

    public function __construct(ArticleInterface $article, ArticleForm $articleform, StatusInterface $status)
    {
        $this->article = $article;
        $this->articleform = $articleform;
        $this->status = $status;
    }

    /**
     * Article'ları listele
     * GET /admin/article
     */
    public function index()
    {
        $page = Input::get('page', 1);

        // Config öğesine talip ol
        $perPage = 3;

        $pagiData = $this->article->byPage($page, $perPage, true);

        $articles = Paginator::make($pagiData->items, $pagiData->totalItems, $perPage);

        $this->layout->content = View::make('admin.article_list')->with('articles', $articles);
    }

    /**
     * Tek article görüntüle. Yalnızca düzenleme formunu görüntülemek istiyoruz
     * @param  int $id Article ID
     * @return Redirect
     */
    public function show($id)
    {
        return Redirect::to('/admin/article/'.$id.'/edit');
    }

    /**
     * Article oluşturma formu
     * GET /admin/article/create
     */
    public function create()
    {
        $statuses = $this->status->all();

        $this->layout->content = View::make('admin.article_create', array(
            'statuses' => $statuses,
            'input' => Session::getOldInput(),
        ));
    }

    /**
     * Article oluşturma formunu işleme
     * POST /admin/article
     */
    public function store()
    {
        $input = array_merge(Input::all(), array('user_id' => 1));

        if( $this->articleform->save( $input ) )
        {
            // Başarılı!
            return Redirect::to('/admin/article')
                    ->with('status', 'success');
        } else {

            return Redirect::to('/admin/article/create')
                    ->withInput()
                    ->withErrors( $this->articleform->errors() )
                    ->with('status', 'error');
        }
    }

    /**
     * Article düzenleme formu
     * GET /admin/article/{id}/edit
     */
    public function edit($id)
    {
        $article = $this->article->byId($id);
        $statuses = $this->status->all();

        $tags = '';
        $article->tags->each(function($tag) use(&$tags)
        {
            $tags .= $tag->tag.', ';
        });

        $tags = substr($tags, 0, -2);

        $this->layout->content = View::make('admin.article_edit', array(
            'article' => $article,
            'tags' => $tags,
            'statuses' => $statuses,
            'input' => Session::getOldInput()
        ));
    }

    /**
     * Article güncelleme formu
     * PUT /admin/article/{id}
     */
    public function update()
    {
        $input = array_merge(Input::all(), array('user_id' => 1));

        if( $this->articleform->update( $input ) )
        {
            // Başarılı!
            return Redirect::to('admin/article')
                    ->with('status', 'success');
        } else {

            // Article ID'ye ihtiyaç var
            return Redirect::to('admin/article/'.Input::get('id').'/edit')
                    ->withInput()
                    ->withErrors( $this->articleform->errors() )
                    ->with('status', 'error');
        }
    }

}