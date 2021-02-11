<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class CurrencyController extends Controller
{
    public function change($currency) {
        Session::put('currentCurrency', $currency);
        return Back();
    }
}
