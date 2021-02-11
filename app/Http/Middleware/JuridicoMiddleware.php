<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class JuridicoMiddleware
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
        if (Auth::check() && Auth::user()->type == 2)
            return $next($request);
        else {
            $url = str_replace(URL('/').'/','',$request->url());
            return Redirect(str_replace('juridico/','',$url));
        }
    }
}
