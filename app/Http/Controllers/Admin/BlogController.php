<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\BlogImage;

use App\Libraries\SetNameImage;
use App\Libraries\ResizeImage;
use File;

class BlogController extends Controller
{
    private $url = "img/blogs/";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::where('status', '1')
        ->with([
            'images'
        ])
        ->get();

        return view('admin.blogs.index')->with(['blogs' => $blogs]);
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
    public function store(BlogRequest $request)
    {
        $blog = new Blog;
        $blog->title = $request->title;
        $blog->title_english = $request->title_english;
        $blog->description = $request->description;
        $blog->description_english = $request->description_english;
        $blog->save();

        if ($request->count > 0) {
            for ($i=1; $i <= $request->count; $i++) { 
                $file = $request->file('file'.$i);
                $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());

                $file->move($this->url, $file_name);
                ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $this->url);

                $photo = new BlogImage;
                $photo->file = $file_name;
                $photo->blog_id = $ally->blog;
                $photo->save();
            }
        }

        return response()->json(['result' => true, 'message' => 'Blog registrado exitosamente']);
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
    public function update(BlogRequest $request, $id)
    {
        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->title_english = $request->title_english;
        $blog->description = $request->description;
        $blog->description_english = $request->description_english;
        $blog->save();

        return response()->json(['result' => true, 'message' => 'Blog actualizado exitosamete.']);
    }

    public function updateImages(Request $request)
    {
        if ($request->id == 0) {
            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($this->url, $file_name);
            
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $this->url);

            $photo = new BlogImage;
            $photo->file = $file_name;
            $photo->blog_id = $request->blog_id;
            $photo->save();
            $id = $photo->id;
        } else {
            $item = BlogImage::find($request->id);
            $odlFile = $item->file;

            $file = $request->file('file');
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($this->url, $file_name);

            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $this->url);
            File::delete(public_path($this->url.$odlFile));

            $item->file = $file_name;
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

        return response()->json(['result' => true, 'message' => 'Blog eliminado exitosamnete']);
    }

    public function delete(Request $request)
    {
        $item = BlogImage::find($request->id);
        $file = $item->file;
        File::delete(public_path($this->url.$file));
        $item->delete();

        return response()->json(['result' => true]);
    }
}
