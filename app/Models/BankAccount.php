<?php

	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;
	class BankAccount extends Model {
		use SoftDeletes;

		const ZELLE = 2;
		const NACIONAL = 1;

	    protected $table="bank_accounts";

	    public function bank() {
	    	return $this->belongsTo('App\Models\Bank','bank_id');
	    }

	    public function getFullNameAttribute() {
	    	return $this->bank->name.' - '.$this->name.' - '.$this->number;
	    }
	}
