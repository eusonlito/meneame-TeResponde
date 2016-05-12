<?php
namespace App\Http\Middleware;

use Closure;
use App\Services\Cache;

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
        if (Cache\Html::exists()) {
            return response(Cache\Html::get());
        }

        return $next($request);
    }
}
