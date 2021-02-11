<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = "filters";

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_filters', 'filter_id', 'category_id');
    }
}
