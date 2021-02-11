<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Municipality;
use App\Models\Parish;
use Illuminate\Http\Request;

// define('VENEZUELA_ID', 95);

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $estados = Estado::where('pais_id', VENEZUELA_ID)->get();

        $municipalities = Municipality::with('estado', 'parishes')
            ->where('status', true)
            ->where('id', $request->id)
            ->latest()
            ->get();

        return  view('admin.municipalities.index', [
            'municipalities' => $municipalities,
            'estados' => $estados
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
        $municipality = Municipality::create($request->all());

        $municipality->parishes()->createMany($request->parishes);

        return $municipality->load('estado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function show(Municipality $municipality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipality $municipality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipality $municipality)
    {
        $municipality->update($request->all());

        foreach($request->parishes as $parish) {
            if($parish['id'] == 0) {
                $municipality->parishes()->create($parish);
            }else {
                if($parish['deleted']) {
                    Parish::where('id', $parish['id'])->delete();
                }
            }
        }

        return $municipality->load('estado', 'parishes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Municipality  $municipality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Municipality $municipality)
    {
        $municipality->status = 2;

        $municipality->save();
    }

    public function free($id)
    {
        $municipality = Municipality::find($id);

        $municipality->free = !$municipality->free;

        $municipality->save();

        if ($municipality->free == 0) {
            Estado::where('id', $municipality->estadod_id)->update(['free' => false]);
        }

        return $municipality;
    }

    public function getByState($id) 
    {
        $municipalities = Municipality::with('parishes')
            ->where('status', true)
            ->where('estado_id', $id)
            ->get();
        
        return $municipalities;
    }
}
