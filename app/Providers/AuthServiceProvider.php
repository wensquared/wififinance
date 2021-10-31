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
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('usergate', function($user) {

            if ($user->role->role == 'user') {
                return true;
            }
            elseif ($user->role->role == 'user_verified') {
                return true;
            }
            elseif ($user->role->role == 'admin') {
                return true;
            }
            return false;

        });
        
        Gate::define('user_verified_gate', function($user) {

            if ($user->role->role == 'user_verified') {
                return true;
            }
            elseif ($user->role->role == 'admin') {
                return true;
            }
            return false;

        });  

        Gate::define('admingate', function($user) { 

            if ($user->role->role == 'admin') {
                return true;
            }
            return false;

        });  
    }
}
