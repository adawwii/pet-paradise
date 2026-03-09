<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
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
        Model::unguard();

        //customizing email verfication
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Click the button below to verify your email address.')
            ->action('Verify Email Address', $url);
    });
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
        Gate::define('is-admin-employee',function(User $user) {
            return Gate::allows('is-admin') || Gate::allows('is-employee');
        });
        Paginator::useTailwind();
        Authenticate::redirectUsing(function ($request) {
        session()->flash('error', 'Access denied! Authentication required.');
        return route('login');
        //auth middleware will redirect to home page if user customer is authenticated using auth middleware in routes/web.php
        if (auth()->check() && auth()->user()->role === 'customer') {
            return route('home');
        }
        //auth middleware will redirect to admin dashboard if user employee or admin is authenticated
        if (auth()->check() && (auth()->user()->role === 'employee' || auth()->user()->role ==='admin')) {
            return route('admin.dashboard');
        }
    });
    }
}
