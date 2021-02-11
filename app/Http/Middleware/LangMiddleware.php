<?php

    namespace App\Http\Middleware;

    use Closure;
    use App;
    use Carbon\Carbon;

    class LangMiddleware {

        public function handle($request, Closure $next) {
            $lang = "es";
            if ($request->cookie('WaraLang') != null) {
                $lang = \Crypt::decrypt($request->cookie('WaraLang'));
            }
            App::setLocale($lang);
            Carbon::setLocale($lang);
            return $next($request);
        }
    }
