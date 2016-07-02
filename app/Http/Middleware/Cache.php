<?php
namespace App\Http\Middleware;

use Closure;
use App\Services\Cache\Html;

class Cache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Html::exists()) {
            return response(Html::get());
        }

        return $next($request);
    }
}
