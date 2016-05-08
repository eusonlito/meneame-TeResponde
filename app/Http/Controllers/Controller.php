<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\Cache;

class Controller extends BaseController
{
    public function __construct()
    {
        view()->share(['title' => '']);
    }

    protected function view($template, array $parameters = array())
    {
        $html = view($template, $parameters)->render();

        if (env('APP_CACHE')) {
            Cache\Html::setFromUri($html);
        }

        return response($html);
    }
}
