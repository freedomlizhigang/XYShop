<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        /*if (session()->has('console')) {
            $this->registerPolicies();
            // 取出所有后台权限label，并注册成功
            $allLabel = \App\Models\Menu::with('role')->get();
            foreach ($allLabel as $v) {
                Gate::define($v->label,function($user) use($v) {
                    return $user->hasRole($v->role);
                });
            }
        }*/
    }
}
