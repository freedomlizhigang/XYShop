<?php

namespace App\Providers;

use App\Services\ComService;
use App\Services\TagService;
use Illuminate\Support\ServiceProvider;

class ComServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->singleton('com',function($app){
            return new ComService();
        });

        $this->app->singleton('tag',function($app){
            return new TagService();
        });
    }

    /**
     * 获取由提供者提供的服务.
     *
     * @return array
     */
    // public function provides()
    // {
    //     return ['com'];
    // }


}
