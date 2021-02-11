<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\ShippingFee;

class ShippingFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shippingFees = ShippingFee::orderBy('type', 'desc')->get();
        $minimunPurchase = Setting::getMinimunPurchase();
        
        return view('admin.shipping-fees.index')->with([
            'shippingFees' => $shippingFees, 
            'minimunPurchase' => $minimunPurchase
        ]);
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
        //
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
        $shippingFee = ShippingFee::find($id);
            $shippingFee->amount = $request->amount;
        $shippingFee->save();

        return response()->json(['result' => true, 'message' => 'Tasa de envio actualizada', 'shippingFee' => $shippingFee]);
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

    public function getAll() 
    {
        $shippingFees = ShippingFee::orderBy('type', 'desc')->get();
        return $shippingFees;
    }
}
