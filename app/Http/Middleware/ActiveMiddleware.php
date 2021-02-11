<?php

    namespace App\Http\Middleware;

    use Closure;
    use View;

    class ActiveMiddleware {

        public function handle($request, Closure $next, $active = 0) {
            View::share('_active',$active);
            return $next($request);
        }
    }
