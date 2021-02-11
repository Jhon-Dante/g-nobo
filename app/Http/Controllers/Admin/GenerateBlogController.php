<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\BlogImage;
use App\Libraries\ResizeImage;
use App\Libraries\SetNameImage;
use File;


class GenerateBlogController extends Controller
{

    private $width_file = 600;
    private $height_file = 600;
    private $both_file = true;

    public function index(){
        $blog = Blog::select('id', 'title', 'title_english', 'description', 'description as description_min', 'description_english', 'created_at')
            ->where('status', "!=", '2')
            ->with(["images" => function ($query){
                $query->select('blog_id', 'file');
            }])
            ->get();
        
        return view('admin.blogs.index')->with(['posts' => $blog]);
    }

    public function create(){
        return view('admin.blogs.create');
    }

    public function store(BlogRequest $request){
        $blog = new Blog;
        $blog->title = $request->title;
        $blog->title_english = $request->title_english;
        $blog->description = $request->description;
        $blog->description_english = $request->description_english;
        $blog->save();
        
        // Images
        $url = "img/blogs/";
        
        for ($i=0; $i < $request->count; $i++) { 
            $file = $request->file('image'.$i);
            // File
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            // File Miniature
            $file_name_miniature = SetNameImage::set("miniature_" . $file->getClientOriginalName(), $file->getClientOriginalExtension());
            File::copy($url . $file_name, $url . $file_name_miniature);
            // Resize
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url);
            ResizeImage::dimenssion($file_name_miniature, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);

            // Instance Blog Image
            $blog_image = new BlogImage;
                $blog_image->file = $file_name;
                $blog_image->file_miniature = $file_name_miniature;
                $blog_image->blog_id = $blog->id;
            $blog_image->save();
        }

    }

    public function edit($id){
        $blog = Blog::where('id', $id)
            ->first();
        $blog_images = BlogImage::where('blog_id', $id)->get();
        return view('admin.blogs.edit')->with(['posts' => $blog, 'images' => $blog_images]);
    }

    public function update(BlogRequest $request, $id){
        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->title_english = $request->title_english;
        $blog->description = $request->description;
        $blog->description_english = $request->description_english;
        $blog->save();
    }


    public function updateImage(Request $request){
        $url = "img/blogs/";
        if ($request->id == 0) {
            $file = $request->file('file');
            // File
            $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
            $file->move($url, $file_name);
            // Miniature
            $file_name_miniature = SetNameImage::set("miniature_" . $file->getClientOriginalName(), $file->getClientOriginalExtension());
            File::copy($url . $file_name, $url . $file_name_miniature);
            // Resize
            ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url);
            ResizeImage::dimenssion($file_name_miniature, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
            $detail = new BlogImage;
                $detail->file = $file_name;
                $detail->file_miniature = $file_name_miniature;
                $detail->blog_id = $request->blog_id;
            $detail->save();
            $fileId = $detail->id;
        } else {
            // Query
            $item = BlogImage::find($request->id);
                $odlFile = $item->file;
                $oldFileMiniature = $item->file_miniature;
                //*** Process File ***/ 
                // File
                $file = $request->file('file');
                $file_name = SetNameImage::set($file->getClientOriginalName(), $file->getClientOriginalExtension());
                $file->move($url, $file_name);
                // Miniature
                $file_name_miniature = SetNameImage::set("miniature_" . $file->getClientOriginalName(), $file->getClientOriginalExtension());
                File::copy($url . $file_name, $url . $file_name_miniature);
                // Resize
                ResizeImage::dimenssion($file_name, $file->getClientOriginalExtension(), $url);
                ResizeImage::dimenssion($file_name_miniature, $file->getClientOriginalExtension(), $url, $this->width_file, $this->height_file);
                
                if(File::exists($url.$odlFile)){
                    File::delete(public_path($url.$odlFile));
                }
                
                if(File::exists($url.$odlFile)){
                    File::delete(public_path($url.$oldFileMiniature));
                }

                $item->file = $file_name;
                $item->file_miniature = $file_name_miniature;
            $item->save();
            $fileId = $request->id;
        }
        return response()->json(['result' => true, 'id' => $fileId, 'file' => $file_name]);
    }

    public function deleteImage(Request $request){
        $url = "img/blogs/";
        $item = BlogImage::find($request->id);
        $file = $item->file;
        File::delete(public_path($url.$file));
        $item->delete();
        return response()->json(['result' => true]);
    }

    public function destroy($id){
        $blog = Blog::find($id);
        $blog->status = "2";
        $blog->save();
    }
}
