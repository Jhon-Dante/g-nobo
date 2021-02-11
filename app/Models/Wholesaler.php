<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    protected $fillable = [
        'name', 
        'name_english', 
        'description', 
        'description_english',
        'price', 
        'quantity', 
        'principal', 
        'coin',
        'filter_id'
    ];

    public function images () {
        return $this->hasMany(WholesalerImage::class);
    }
}
