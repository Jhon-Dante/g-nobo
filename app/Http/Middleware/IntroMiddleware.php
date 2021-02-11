<?php

    namespace App\Http\Middleware;

    use Closure;
    use View;

    class IntroMiddleware {

        public function handle($request, Closure $next) {
            // View::share('_no_header',true);
            return $next($request);
        }
    }
