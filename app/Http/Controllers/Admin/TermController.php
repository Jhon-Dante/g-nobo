<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Terminos;
use App\Models\Condiciones;
use App\Http\Requests\TermsRequest;

class TermController extends Controller
{
    public function index()
    {
    	$terms = Terminos::orderBy('id', 'DESC')->first();
		$conditions = Condiciones::orderBy('id', 'DESC')->first();
		
		// dd($terms, $conditions);

    	return view('admin.terms.index', [
    		'terms' => $terms,
    		'conditions' => $conditions
    	]);
	}
	
	public function store(TermsRequest $request)
	{
		$terms = Terminos::orderBy('id', 'DESC')->first();
		$conditions = Condiciones::orderBy('id', 'DESC')->first();
		

		$terms->texto = $request->terms_text;
		$terms->english = $request->terms_english;
		
		$conditions->texto = $request->conditions_text;
		$conditions->english = $request->conditions_english;

		$terms->save();
		$conditions->save();
	}
}
