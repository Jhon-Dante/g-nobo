<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;

	class Transfer extends Model {
		protected $table="transfers";
		
		const TRANSFER_TYPE = 1;
		const PAGOMOVIL_TYPE = 2;
		const ZELLE_TYPE = 3;
		const STRIPE_TYPE = 4;

	    public function to() {
	    	return $this->belongsTo('App\Models\BankAccount','to_bank_id');
	    }

	    public function from() {
	    	return $this->belongsTo('App\Models\Bank','from_bank_id');
		}
		
		public function bankAccount() {
			return $this->belongsTo('App\Models\BankAccount','bank_id')->withTrashed();
		}

	    public function user() {
	    	return $this->belongsTo('App\User','user_id');
	    }
	}
