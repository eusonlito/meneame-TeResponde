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
        if ($cache = Cache\Html::exists()) {
            return response($cache);
        }

        return $next($request);
    }
}
