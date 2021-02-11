<?php

namespace App\Http\Controllers\Admin;

use App\Models\Taxe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.taxes.index', [
            'taxes' => Taxe::latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Taxe::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Taxe  $taxe
     * @return \Illuminate\Http\Response
     */
    public function show(Taxe $taxe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Taxe  $taxe
     * @return \Illuminate\Http\Response
     */
    public function edit($taxeId)
    {
        $taxe = Taxe::findOrFail($taxeId);

        return view('admin.taxes.edit', [
            'taxe' => $taxe,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Taxe  $taxe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $taxeId)
    {
        $taxe = Taxe::findOrFail($taxeId);

        $taxe->fill($request->all());

        $taxe->save();

        return $taxe;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Taxe  $taxe
     * @return \Illuminate\Http\Response
     */
    public function destroy($taxeId)
    {
        $taxe = Taxe::findOrFail($taxeId);

        $taxe->delete();
    }


    public function status($taxeId)
    {
        $taxe = Taxe::findOrFail($taxeId);

        $taxe->status = !$taxe->status;

        $taxe->save();
    }
}
