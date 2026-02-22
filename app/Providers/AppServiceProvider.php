<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::define('is-customer',function(User $user) {
            return $user->role === 'customer';
        });
        Gate::define('is-employee',function(User $user) {
            return $user->role === 'employee';
        });
        Gate::define('is-admin',function(User $user) {
            return $user->role === 'admin';
        });
        Model::unguard();
        Paginator::useTailwind();
        Authenticate::redirectUsing(function ($request) {
        session()->flash('error', 'Access denied! Authentication required.');
        return route('login');
    });
    }
}
