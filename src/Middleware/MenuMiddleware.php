<?php

namespace Baytek\Laravel\Menu\Middleware;

use Closure;

class MenuMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $menu
     * @return mixed
     */
    public function handle($request, Closure $next, $menu)
    {
        dump($menu);

        return $next($request);
    }

}