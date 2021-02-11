<?php

namespace App\Http\Controllers\Admin;

use App\Models\Wholesaler;
use App\Models\WholesalerImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Filter;
use App\Libraries\SetNameImage;
use App\Libraries\ResizeImage;
use App\Http\Requests\StoreWholesalerRequest;
use File;

class WholesalerController extends Controller
{
    
    private $url = "img/wholesalers/";
    private $width_file = 550;
    private $height_file = 800;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.wholesalers.index', [
            'wholesalers' => Wholesaler::where('status', '!=', '2')->orderBy('id', 'desc')->with('images')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $filters = Filter::pluck('name', 'id');
        
        return view('admin.wholesalers.create', ['filters' => $filters]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWholesalerRequest $request)
    {
        $wholesaler = Wholesaler::create($request->all());
        $url = "img/products/";
        $main = $request->file('main');
        $main_name = SetNameImage::set($main->getClientOriginalName(), $main->getClientOriginalExtension());
        $main->move($url, $main_name);
        ResizeImage::dimenssion($main_name, $main->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
        $first = new WholesalerImage;
        $first->file = $main_name;
        $first->wholesaler_id = $wholesaler->id;
        $first->main = '1';
        $first->save();

        for ($i=1; $i <= $request->count; $i++) {
            $file = $request->file('file'.$i);
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $second = new WholesalerImage;
            $second->file = $file_name;
            $second->wholesaler_id = $wholesaler->id;
            $second->main = '0';
            $second->save();
        }

        return $wholesaler->load('images');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wholesaler  $wholesaler
     * @return \Illuminate\Http\Response
     */
    public function show(Wholesaler $wholesaler)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wholesaler  $wholesaler
     * @return \Illuminate\Http\Response
     */
    public function edit(Wholesaler $wholesaler)
    {
        return view('admin.wholesalers.edit', ['wholesaler' => $wholesaler->load('images')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wholesaler  $wholesaler
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wholesaler $wholesaler)
    {
        $wholesaler->name = $request->name;
        $wholesaler->name_english = $request->name_english;
        $wholesaler->price = $request->price;
        $wholesaler->quantity = $request->quantity;
        $wholesaler->description = $request->description;
        $wholesaler->description_english = $request->description_english;
        $wholesaler->filter_id = $request->filter_id;
        $wholesaler->coin = $request->coin;

        $wholesaler->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wholesaler  $wholesaler
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wholesaler $wholesaler)
    {
        $wholesaler->status = 2;
        $wholesaler->save();
    }

    public function getFilters() 
    {
        return Filter::pluck('name', 'id');
    }

    public function updateImages(Request $request)
    {
        $url = "img/products/";
        if ($request->id == NULL || $request->id == 'null') {
            $item = WholesalerImage::where('wholesaler_id', $request->wholesaler_id)->where('main', '1')->first();
            $odlFile = $item->file;
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            File::delete(public_path($url.$odlFile));
            $item->file = $file_name;
            $item->save();
            $fileId = $item->id;
        }
        else if ($request->id == 0) {
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $detail = new WholesalerImage;
            $detail->file = $file_name;
            $detail->wholesaler_id = $request->wholesaler_id;
            $detail->save();
            $fileId = $detail->id;
        } else {
            $item = WholesalerImage::find($request->id);
            $odlFile = $item->file;
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            File::delete(public_path($url.$odlFile));
            $item->file = $file_name;
            $item->save();
            $fileId = $request->id;
        }

        return response()->json(['result' => true, 'id' => $fileId, 'file' => $file_name]);
    }

    public function deleteImages(Request $request)
    {
        $url = "img/products/";
        $item = WholesalerImage::find($request->id);
        $file = $item->file;
        File::delete(public_path($url.$file));
        $item->delete();

        return response()->json(['result' => true]);
    }

}
