<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register()
    {
        foreach (glob(base_path('config/*.php')) as $file) {
            $this->app->configure(explode('.', basename($file), 2)[0]);
        }
    }
}
