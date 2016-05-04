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
        setlocale(LC_TIME, 'es_ES.utf8', 'es_ES', 'es');

        foreach (glob(base_path('config/*.php')) as $file) {
            $this->app->configure(explode('.', basename($file), 2)[0]);
        }
    }
}
