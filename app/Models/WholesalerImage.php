<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    // use Illuminate\Database\Eloquent\SoftDeletes;


    class WholesalerImage extends Model
    {
        // use SoftDeletes;

        // protected $table = "wholesaler_images";

        // public $timestamp = false;

        // protected $dates = ['deleted_at'];

        public function wholesaler () {
            return $this->belongsTo('App\Models\Wholesaler', 'wholesaler_id');
        }
    }
