<?php

namespace App\Http\Middleware;
use App\Models\Estado;
use App\Libraries\IpCheck;
use App\Models\Municipality;
use App\Models\Parish;
use Closure;
use View;
define('VENEZUELA_ID', 95);

class IpMiddleware {

    public function handle($request, Closure $next) {
        // View::share('_ip',IpCheck::get());
        $estadoss = Estado::orderBy('nombre','asc')->where('pais_id', VENEZUELA_ID)
            ->where('status', Estado::STATUS_ACTIVE)
            ->get()
            ->pluck('nombre','id');
        $municipios = Municipality::orderBy('name','asc')->where('status', Municipality::STATUS_ACTIVE)->get();
        $parroquias = Parish::orderBy('name','asc')->get();
        View::share('estadoss', $estadoss);
        View::share('municipios', $municipios);
        View::share('parroquias', $parroquias);

        return $next($request);
    }
}
