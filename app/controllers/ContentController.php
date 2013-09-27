<?php

use Impl\Repo\Article\ArticleInterface;

class ContentController extends BaseController {

    protected $layout = 'layout';

    protected $article;

    public function __construct(ArticleInterface $article)
    {
        $this->article = $article;
    }

    /**
     * Sayfalandırılmış Article'lar
     * GET /
     */
    public function home()
    {
        $page = Input::get('page', 1);

        // Config öğesine talip ol
        $perPage = 3;

        $pagiData = $this->article->byPage($page, $perPage);

        $articles = Paginator::make($pagiData->items, $pagiData->totalItems, $perPage);

        $this->layout->content = View::make('home')->with('articles', $articles);
    }

    /**
     * Tek article
     * GET /{slug}
     */
    public function article($slug)
    {
        $article = $this->article->bySlug($slug);

        if( ! $article )
        {
            App::abort(404);
        }

        $this->layout->content = View::make('article')->with('article', $article);
    }

}