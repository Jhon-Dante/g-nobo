<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if(Auth::user()->nivel == 1){
                return redirect('/');
            }
            return redirect('admin/exchange_rate');
        } else {
            return redirect('/');
        }

        abort(404);
    }

    public function home()
    {
        return redirect('admin/exchange_rate');
    }
}
