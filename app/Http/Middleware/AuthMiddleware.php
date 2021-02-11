<?php

    namespace App\Http\Middleware;

    use Closure;
    use Auth;

    class AuthMiddleware {

        public function handle($request, Closure $next) {
            if (Auth::check())
                return $next($request);
            else
                return Redirect('/');
        }
    }
