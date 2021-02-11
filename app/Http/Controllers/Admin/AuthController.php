<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Auth;

class AuthController extends Controller
{
    public function singIn(LoginRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::attempt($data,true)){
            if (Auth::user()->nivel == '2') {
                return response()->json([
                    'result' => true,
                    'location' => url('/admin/exchange_rate')
                ]);
            } else {
                return response()->json([
                    'result' => false,
                    'error' => 'No tienes permisos para acceder.'
                ]);                
            }
        }else{
            return response()->json([
                'result' => false,
                'error' => "Correo Electrónico o Contraseña incorrectos"
            ]);
        }
    }
}
