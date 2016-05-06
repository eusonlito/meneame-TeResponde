<?php
namespace App\Http\Controllers;

class Exception extends Controller
{
    /**
     * @return object
     */
    public function error404()
    {
        return response(view('pages.error.404'), 404);
    }
}
