<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RegisterServiceProvider extends ServiceProvider
{
    /**
    * @return void
    */
    public function register()
    {
        $this->app->register(AppServiceProvider::class);
        $this->app->register(ConfigServiceProvider::class);
    }
}
