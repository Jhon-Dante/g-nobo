<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use DB;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $changes = ExchangeRate::select('id', 'change', DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y %h:%i:%s %p") as date'))
        ->orderBy('id', 'DESC')
        ->get();
        
        return view('admin.exchange_rate.index')->with(['changes' => $changes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $change = new ExchangeRate;
            $change->change = $request->change;
        $change->save();
        
        $change = ExchangeRate::select('id', 'change', DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y %h:%i:%s %p") as date'))->where('id', $change->id)->first();
        
        return response()->json(['result' => true, 'message' => 'Tasa de cambio actualizada', 'change' => $change]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
