<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	

	class AliadoFoto extends Model {
		use SoftDeletes;

		protected $table="aliados_fotos";

	    public function aliado() {
	    	return $this->belongsTo('App\Models\Aliado','aliado_id');
	    }
	}
