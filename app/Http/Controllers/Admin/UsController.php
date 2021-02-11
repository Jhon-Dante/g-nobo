<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsRequest;
use App\Models\Nosotros;

class UsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $us = Nosotros::first();
        return view('admin.us.index')->with(['us' => $us]);
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
    public function store(UsRequest $request)
    {
        $us = new Nosotros;
        $us->texto = $request->texto;
        $us->mision = $request->mision;
        $us->vision = $request->vision;
        $us->english = $request->english;
        $us->mision_english = $request->mision_english;
        $us->vision_english = $request->vision_english;
        $us->save();

        return response()->json(['result' => true, 'message' => 'Información almacenada exitosamnete']);
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
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsRequest $request, $id)
    {
        $us = Nosotros::find($id);
        $us->texto = $request->texto;
        $us->mision = $request->mision;
        $us->vision = $request->vision;
        $us->english = $request->english;
        $us->mision_english = $request->mision_english;
        $us->vision_english = $request->vision_english;
        $us->save();

        return response()->json(['result' => true, 'message' => 'Información actualizada exitosamnete']);

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
