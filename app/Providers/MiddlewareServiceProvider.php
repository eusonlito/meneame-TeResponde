<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->app->routeMiddleware([
            'auth' => Middleware\Cache::class,
        ]);
    }
}
