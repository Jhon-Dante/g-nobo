<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use App\Models\Blog;

	class BlogController extends Controller {
	    
	    public function get() {
	    	$blogs = Blog::where('status','1')->orderBy('id','desc')->with(['images'])->paginate(6);
	    	return View('blog.home')->with(['blogs' => $blogs]);
	    }

	    public function view($id) {
	    	$blog = Blog::where('status','1')->where('id',$id)->with(['images'])->first();
	    	return View('blog.view')->with(['blog' => $blog]);
	    }
	}
