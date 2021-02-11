<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Http\Requests\DesignRequest;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designs = Design::where('status', '1')->get();
        return view('admin.designs.index')->with(['designs' => $designs]);
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
    public function store(DesignRequest $request)
    {
        $design = new Design;
        $design->name = $request->name;
        $design->name_english = $request->name_english;
        $design->save();

        return response()->json(['result' => true, 'message' => 'Diseño registrado exitosamente']);
    }

    public function allData()
    {
        $designs = Design::where('status', '1')->get();
        return response()->json($designs);
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
    public function update(DesignRequest $request, $id)
    {
        $design = Design::find($id);
        $design->name = $request->name;
        $design->name_english = $request->name_english;
        $design->save();

        return response()->json(['result' => true, 'message' => 'Diseño actualizado exitosamnete']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $design = Design::find($id);
        $design->status = '2';
        $design->save();
        
        return response()->json(['result' => true, 'message' => 'Diseño eliminado exitosamnete']);
    }
}
