<?php

namespace App\Providers;

use App\Admin;
use App\Article;
use App\FileUpload;
use App\Observers\ArticleObserver;
use Carbon\Carbon;
use View;
use Route;
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
        View()->composer('admin.*', function($view) {
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
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton('fileUpload', function () {
            return new FileUpload();
        });
    }
}
