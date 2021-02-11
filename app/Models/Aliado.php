<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Aliado extends Model {

		use SoftDeletes;

	    protected $table="aliados";

	    public function fotos() {
	    	return $this->hasMany('App\Models\AliadoFoto','aliado_id');
		}
		
		protected $dates = ['deleted_at'];
	}
