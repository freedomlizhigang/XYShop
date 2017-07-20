<?php

namespace App\Providers;

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
        // 时间中文化
        \Carbon\Carbon::setLocale('zh');
        //分享静态文件地址给视图
        $temp = array('url'=>config('app.url'),'static'=>config('app.url').config('app.static'));
        view()->share('sites',$temp);
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
