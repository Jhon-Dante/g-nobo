<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Blog extends Model {
	    protected $table = "blogs";

	    public function images() {
	    	return $this->hasMany('App\Models\BlogImage','blog_id');
		}
		
		public function getDescriptionMinAttribute($val){
			return (strlen($val) < 300) ? $val : substr($val, 0, 300) . "...";
		}
	}
