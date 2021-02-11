<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AllyRequest;
use App\Models\Aliado;
use App\Models\AliadoFoto;

use App\Libraries\SetNameImage;
use App\Libraries\ResizeImage;
use File;

class AllyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $url = "img/aliados/";
    private $width_file = 600;
    private $height_file = 600;

    public function index()
    {
        $allies = Aliado::with([
            'fotos' => function ($query)
            {
                $query->select('id', 'aliado_id', 'file');
            }
        ])
        ->get();

        return view('admin.allies.index')->with(['allies' => $allies]);
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
    public function store(AllyRequest $request)
    {

        $ally = new Aliado;
        $ally->name = $request->name;
        $ally->facebook = $request->facebook == "" ? "https://www.facebook.com" : $request->facebook;
        $ally->twitter = $request->twitter == "" ? "https://twitter.com" : $request->twitter;
        $ally->instagram = $request->instagram == "" ? "https://www.instagram.com" : $request->instagram;
        $ally->address = $request->address;
        $ally->save();
        for ($i=0; $i < $request->count; $i++) {
            // File
            $file = $request->file('image'.$i);
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($this->url, $file_name);
            // Miniature
            $file_name_miniature = SetNameImage::set("miniature_" . $file->getClientOriginalName(), $file->getClientOriginalExtension());
            File::copy($this->url . $file_name, $this->url . $file_name_miniature);
            // Resize
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $this->url);
            ResizeImage::dimenssion($file_name_miniature, $file->getClientOriginalExtension(), $this->url, $this->width_file, $this->height_file);
            $photo = new AliadoFoto;
            $photo->file = $file_name;
            $photo->file_miniature = $file_name_miniature;
            $photo->aliado_id = $ally->id;
            $photo->save();
        }
        return response()->json(['result' => true, 'message' => 'Aliado registrado exitosamente']);
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
    public function update(AllyRequest $request, $id)
    {
        $ally = Aliado::find($id);
        $ally->name = $request->name;
        $ally->facebook = $request->facebook == "" ? "https://www.facebook.com" : $request->facebook;
        $ally->twitter = $request->twitter == "" ? "https://twitter.com" : $request->twitter;
        $ally->instagram = $request->instagram == "" ? "https://www.instagram.com" : $request->instagram;
        $ally->address = $request->address;
        $ally->save();

        return response()->json(['result' => true, 'message' => 'Aliado actualizado exitosamete.']);
    }

    public function updateImages(Request $request)
    {
        if ($request->id == 0) {
            // File
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($this->url, $file_name);
            // Miniature
            $file_name_miniature = SetNameImage::set("miniature_" . $file->getClientOriginalName(), $file->getClientOriginalExtension());
            File::copy($this->url . $file_name, $this->url . $file_name_miniature);
            // Resize
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $this->url);
            ResizeImage::dimenssion($file_name_miniature, $file->getClientOriginalExtension(), $this->url, $this->width_file, $this->height_file);

            $photo = new AliadoFoto;
                $photo->file = $file_name;
                $photo->file_miniature = $file_name_miniature;
                $photo->aliado_id = $request->aliado_id;
            $photo->save();
            $id = $photo->id;
        } else {
            // Query
            $item = AliadoFoto::find($request->id);
            $odlFile = $item->file;
            $oldFileMiniature = $item->file_miniature;
            // File
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($this->url, $file_name);
            // Miniature
            $file_name_miniature = SetNameImage::set("miniature_" . $file->getClientOriginalName(), $file->getClientOriginalExtension());
            File::copy($this->url . $file_name, $this->url . $file_name_miniature);
            // Resize
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $this->url);
            ResizeImage::dimenssion($file_name_miniature, $file->getClientOriginalExtension(), $this->url, $this->width_file, $this->height_file);

            if(File::exists(public_path($this->url.$odlFile))){
                File::delete(public_path($this->url.$odlFile));
            }

            if(File::exists(public_path($this->url.$odlFile))){
                File::delete(public_path($this->url.$odlFile));
            }
            
                $item->file = $file_name;
                $item->file_miniature = $file_name_miniature;
            $item->save();
            $id = $request->id;
        }

        return response()->json(['result' => true, 'id' => $id, 'file' => $file_name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ally = Aliado::find($id);
        $ally->delete();

        return response()->json(['result' => true, 'message' => 'Aliado eliminado exitosamnete']);
    }

    public function delete(Request $request)
    {
        $item = AliadoFoto::find($request->id);
        $file = $item->file;
        File::delete(public_path($this->url.$file));
        $item->delete();

        return response()->json(['result' => true]);
    }
}
