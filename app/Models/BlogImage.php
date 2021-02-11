<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class BlogImage extends Model {

		use SoftDeletes;

		protected $table = "blog_images";
		
		public $timestamps = false;
		
		protected $dates = ['deleted_at'];

	    public function blog() {
	    	return $this->belongsTo('App\Models\Blog','blog_id');
	    }
	}
