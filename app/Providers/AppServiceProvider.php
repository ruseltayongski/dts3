<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
<<<<<<< HEAD
        $this->app['request']->server->set('HTTPS', true);
=======
        /*//$this->app['request']->server->set('HTTPS', true);
        $this->app['request']->server->set('HTTPS', true);
        //isset($keyword['keywordLogs']) ? $keyword['keywordLogs']: null;
        //$link = https://mis.cvchd7.com*/
        Paginator::useBootstrap();
>>>>>>> d9b9b55c3a464e05669700695ee53e8acd303dec
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
