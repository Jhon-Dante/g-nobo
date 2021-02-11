<?php

    namespace App\Http\Middleware;

    use Closure;
    use View;
    use Auth;

    class ActiveUserMiddleware {

        public function handle($request, Closure $next) {
            if (Auth::check() && Auth::user()->status != 1) {
            	Auth::logout();
            	return Redirect('/');
            }
            else {
            	return $next($request);
            }            
        }
    }
