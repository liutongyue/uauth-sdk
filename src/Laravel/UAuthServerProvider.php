<?php
/**
 * Created by PhpStorm.
 * User: liutongyue
 * Date: 2018/4/3
 * Time: 下午5:33
 */

namespace UAuth\SDK\Laravel;

use Illuminate\Support\ServiceProvider;
use UAuth\SDK\UAuthManage;

class UAuthServerProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../../config/config.php' => $this->app->basePath() . '/config/entrust.php',
        ]);

        $this->loadViewsFrom(__DIR__.'/../../views', 'entrust');

    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/config.php', 'entrust'
        );

        $this->app->singleton(UAuthManage::class, function($app){
            return new UAuthManage($app['config']->get('entrust'));
        });

    }
}