<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->validateCsrfTokens(except: [
            'api/stripe/webhook',
        ]);
        // $middleware->statefulApi();
        //$middleware->web(): Use this if you need to add custom middleware specifically to the web route group.
        
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (AuthenticationException $e, $request) {
            return redirect()->route('login');
            });
            
        $exceptions->render(function (UnauthorizedException $e, $request) {
            $user=$request->user();
            if($user && $user->hasAnyRole(['Super Admin','employee'])){
                return redirect()->route('dashboard')->with('info','Already Logged in');
            }
            return redirect()->route('home')->with('info','Already Logged in');
            });
        
    })->create();
