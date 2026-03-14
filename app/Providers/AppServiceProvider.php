<?php

namespace App\Providers;

// use App\Models\User;
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
        Gate::before(function ($user, $ability) {
        return $user->hasRole('Super Admin') ? true : null;
         });

        Paginator::useTailwind();

        Authenticate::redirectUsing(function ($request) {
        session()->flash('error', 'Access denied! Authentication required.');
        return route('login');
        // //auth middleware will redirect to home page if user customer is authenticated using auth middleware in routes/web.php
        // if (auth()->check() && auth()->user()->hasRole('customer')) {
        //     return route('home');
        // }
        // //auth middleware will redirect to admin dashboard if user employee or admin is authenticated
        // if (auth()->check() && auth()->user()->can('view dashboard')) {
        //     return route('admin.dashboard');
        // }
        });
    }
}
