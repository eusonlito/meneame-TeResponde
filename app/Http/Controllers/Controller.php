<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        view()->share(['title' => '']);
    }

    protected function view($template, array $parameters = array())
    {
        $html = view($template, $parameters)->render();

        if (CACHE_ENABLED) {
            file_put_contents(CACHE_FILE, $html);
        }

        return response($html);
    }
}
