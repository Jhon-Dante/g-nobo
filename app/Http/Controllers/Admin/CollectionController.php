<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionRequest;
use App\Models\Collection;
use App\Models\Design;
use App\Models\Product;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Collection::where('status', '1')
        ->with([
            'designs' => function($query) {
                $query->select()->withCount('products')->where('status', '1');
            }
        ])
        ->get();

        return view('admin.collection.index')->with([
            'collections' => $collections
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
    public function store(CollectionRequest $request)
    {
        $collection = Collection::create($request->all());

        foreach ($request->designs as $key => $design) {
            $collection->designs()->create($design);
        }

        return response()->json(["result" => true, "message" => "Collección agregada exitosamente"]);
        
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
    public function update(CollectionRequest $request, $id)
    {
        $collection = Collection::find($id)->update(['name' => $request->name, 'name_english' => $request->name_english]);

        $design_ids = array();

        foreach ($request->designs as $key => $design) {
            if($design['id'] > 0){
                Design::find($design['id'])->update(['name' => $design['name'], 'name_english' => $design['name_english']]);
            }else{
                $design = Collection::find($id)->designs()->create($design);
            }

            $design_ids[] = $design['id'];
        }

        // foreach($request->deletes as $id) {
        //     Design::find($id);

        //     $design->delete();
        // }

        Design::where('collection_id', $id)->whereNotIn('id', $design_ids)->doesnthave('products')->update(['status' => '2']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collection = Collection::find($id);

        $products = Product::where('collection_id', $id)->where('status', '!=', '2')->get();

        if(count($products) > 0){
            return response()->json([
                'result' => false,
                'error' => "Disculpe, no se pudo eliminar la colección " . $collection->name . ". Esta asociada a un producto"
            ]);
        }
            $collection->status = '2';
        $collection->save();

        Design::where('collection_id', $id)->doesnthave('products')->update(['status' => '2']);

        return response()->json(["result" => true, "message" => "Colección eliminada exitosamente"]);
        
    }
}
