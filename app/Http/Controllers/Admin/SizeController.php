<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Http\Requests\SizeRequest;
use App\Models\CategorySize;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::where('status', '1')
        ->get();

        return view('admin.sizes.index')->with(['sizes' => $sizes]);
    }

    public function all()
    {
        $sizes = Size::where('status', '1')
        ->get();
        return response()->json($sizes);
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
    public function store(SizeRequest $request)
    {
        $size = new Size;
        $size->name = strtoupper($request->name);
        $size->status = '1';
        $size->save();

        return response()->json(['result' => true, 'message' => 'Talla registrada exitosamente.']);
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
    public function update(SizeRequest $request, $id)
    {
        $size = Size::find($id);
        $size->name = strtoupper($request->name);
        $size->save();

        return response()->json(['result' => true, 'message' => 'Talla actualizada exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = CategorySize::where('size_id', $id)->get();
        $size = Size::find($id);

        if(count($category) > 0){
            return response()->json([
                'result' => false,
                'error' => "Disculpe, no se pudo eliminar La talla " . $size->name . ". Esta asociada a una categoría"
            ]);
        }

        
        $size->status = '2';
        $size->save();

        return response()->json(['result' => true, 'message' => 'Talla eliminada exitosamente.']);
    }
}
