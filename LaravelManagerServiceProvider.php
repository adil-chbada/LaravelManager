<?php
/**
 * Created by PhpStorm.
 * User: adil
 * Date: 19/09/2018
 * Time: 11:39
 */

namespace Adilchbada\LaravelManager;

use Illuminate\Support\ServiceProvider;

class LaravelManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views/','laravelManager');

//        view()->composer('view', function () {
//            //
//        });
    }
    public function register()
    {
//        $this->app->singleton(Connection::class, function ($app) {
//            return new Connection(config('riak'));
//        });
    }
}
