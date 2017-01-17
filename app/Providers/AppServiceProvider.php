<?php

namespace App\Providers;

use App\Admin;
use App\Article;
use App\BallotAnswer;
use App\Comment;
use App\CommentLike;
use App\SortLink;
use App\SortPhoto;
use View;
use Blade;
use App\FileUpload;
use App\Observers\ArticleObserver;
use App\Observers\BallotAnswerObserver;
use App\Observers\CommentObserver;
use App\Observers\CommentLikeObserver;
use App\Observers\SortLinkObserver;
use App\Observers\SortPhotoObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Carbon::setLocale('zh');
        Article::observe(ArticleObserver::class);
        BallotAnswer::observe(BallotAnswerObserver::class);
        Comment::observe(CommentObserver::class);
        CommentLike::observe(CommentLikeObserver::class);
        SortLink::observe(SortLinkObserver::class);
        SortPhoto::observe(SortPhotoObserver::class);
        Blade::directive('define', function($expression) {
            return "<?php $expression; ?>";
        });
        View::composer(['admin.partials.sidebar',], function($view) {
            $view->with('menu', Admin::menu());
        });
        View::composer(['layouts.admin', 'layouts.auto'], function($view) {
            //dd(Admin::activeSidebar());
            $view->with('active_sidebar', Admin::activeSidebar());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
//        if ($this->app->environment() !== 'production') {
//            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
//        }
//        if ($this->app->environment() == 'local') {
//            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
//        }

        $this->app->singleton('fileUpload', function () {
            return new FileUpload();
        });
    }
}
