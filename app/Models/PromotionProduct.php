<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionProduct extends Model
{
    protected $table="promotion_product";

    public function product_amount()
    {
        return $this->belongsTo(ProductAmount::class, 'product_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}
