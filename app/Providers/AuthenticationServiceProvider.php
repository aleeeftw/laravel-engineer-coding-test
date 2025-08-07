<?php

namespace App\Providers;

use App\Guards\AuthenticationGuard;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::resolved(function (AuthManager $auth) {
            $auth->extend('authentication', fn (): AuthenticationGuard => tap(
                new AuthenticationGuard(
                    $this->app->make('request'),
                ),
                function ($guard): void {
                    App::refresh('request', $guard, 'setRequest');
                },
            ));
        });
    }
}
