<?php namespace Impl\Repo;

use Tag;
use Status;
use Article;
use Impl\Repo\Tag\EloquentTag;
use Impl\Service\Cache\LaravelCache;
use Impl\Repo\Status\EloquentStatus;
use Impl\Repo\Article\EloquentArticle;
use Illuminate\Support\ServiceProvider;

class RepoServiceProvider extends ServiceProvider {

    /**
     * Yeni servis sağlayıcısını kaydet.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->bind('Impl\Repo\Article\ArticleInterface', function($app)
        {
            return new EloquentArticle(
                new Article,
                $app->make('Impl\Repo\Tag\TagInterface'),
                new LaravelCache($app['cache'], 'articles', 10)
            );
        });

        $app->bind('Impl\Repo\Tag\TagInterface', function($app)
        {
            return new EloquentTag(
                new Tag,
                new LaravelCache($app['cache'], 'tags', 10)
            );
        });

        $app->bind('Impl\Repo\Status\StatusInterface', function($app)
        {
            return new EloquentStatus(
                new Status
            );
        });
    }

}