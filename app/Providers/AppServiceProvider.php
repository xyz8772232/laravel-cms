<?php

namespace App\Providers;

use App\Admin;
use App\Article;
use App\BallotAnswer;
use App\Comment;
use View;
use Blade;
use App\FileUpload;
use App\Observers\ArticleObserver;
use App\Observers\BallotAnswerObserver;
use App\Observers\CommentObserver;
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
        Blade::directive('define', function($expression) {
            return "<?php $expression; ?>";
        });
        View::composer(['admin.partials.sidebar',], function($view) {
            //dd(Admin::menu(), Admin::activeSidebar());
            $view->with('active_sidebar', Admin::activeSidebar());
            $view->with('menu', Admin::menu());
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

        $this->app->singleton('fileUpload', function () {
            return new FileUpload();
        });
    }
}
