<?php

    namespace App\Http\Middleware;

    use Closure;
    use Auth;
    use View;

    class LoginMiddleware {

        public function handle($request, Closure $next) {
            View::share('_login',true);
            if (Auth::check())
                return Redirect('home');
            else
                return $next($request);
        }
    }
