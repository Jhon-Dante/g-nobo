<?php

    namespace App\Http\Middleware;

    use Closure;
    use View;
    use App\Models\Social;

    class SocialMiddleware {

        public function handle($request, Closure $next) {
            $_sociales = Social::orderBy('id','desc')->first();
            View::share('_sociales',$_sociales);
            return $next($request);
        }
    }
