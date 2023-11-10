<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /** define a admin user role */
        Gate::define('isSuperAdmin', function($user) {
            return $user->type == '1';
        });

        Gate::define('isAdmin', function($user) {
           return ($user->type == '1' || $user->type == '2');
        });

        Gate::define('isTeacher', function($user) {
            return ($user->type == '1' || $user->type == '2' || $user->type == '3');
        });

        Gate::define('isParent', function($user) {
            return ($user->type == '1' || $user->type == '2' || $user->type == '4');
        });

        Gate::define('isStudent', function($user) {
            return ($user->type == '1' || $user->type == '2' || $user->type == '3' || $user->type == '4' || $user->type == '5');
        });
    }
}
