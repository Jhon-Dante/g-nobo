<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const STR_LIMIT = 60;

    const TYPE_SIMPLE = 0;
    const TYPE_VARIABLE = 1;

    const STATUS_ACTIVE = '1';
    const STATUS_INACTIVE = '0';
    const STATUS_DELETED = '2';

    protected $table = "products";

    protected $fillable = [
        'name', 'name_english', 'description', 'description_english', 'coin', 'variable', 'price_1', 'price_2',
        'category_id', 'subcategory_id', 'collection_id', 'design_id', 'retail', 'wholesale', 'status'
    ];

    protected $appends = [
        'image_url',
        'es_name',
        'en_name',
        'es_date',
        'es_update',
        'type_variable',
        'offer',
        'discount',
        'taxe'
    ];

    const UNITS = [
        1 => 'Gr',
        2 => 'Kg',
        3 => 'Ml',
        4 => 'L',
        5 => 'Cm'
    ];

    public function srtLenght($name)
    {
        return strlen($name);
    }

    public function getEsNameAttribute()
    {
        return $this->srtLenght($this->name) >= static::STR_LIMIT ? mb_substr($this->name, 0, static::STR_LIMIT) . '...' : $this->name;
    }

    public function getEnNameAttribute()
    {
        return $this->srtLenght($this->name_english) >= static::STR_LIMIT ? mb_substr($this->name_english, 0, static::STR_LIMIT) . '...' : $this->name_english;
    }

    public function getEsDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    public function getEsUpdateAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d-m-Y');
    }

    public function getTypevariableAttribute()
    {
        switch ($this->variable) {
            case static::TYPE_SIMPLE:
                return 'Simple';
            case static::TYPE_VARIABLE:
                return 'Variable';
        }
    }

    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategories()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function designs()
    {
        return $this->belongsTo(Design::class, 'design_id');
    }

    public function collections()
    {
        return $this->belongsTo(Collection::class, 'collection_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class, 'product_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->images()->count() > 0) {
            $file = $this->images[0]->file;

            if (substr($file, 0, 4) === "http") {
                return $file;
            }

            return asset('img/products/' . $file);
        } else {
            return null;
        }
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function taxe()
    {
        return $this->belongsTo(Taxe::class)->where('status', Taxe::STATUS_ACTIVE);
    }

    public function offersActive()
    {
        return $this->offers()->where('status', Offer::ACTIVE);
    }

    public function getOfferAttribute()
    {
        if ($this->offers->count() > 0) {
            return $this->offersActive()->whereDate('start', '<=', date('Y-m-d'))->first();
        }
        return null;
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class);
    }

    public function discountsActive()
    {
        return $this->discounts()->where('status', Discount::ACTIVE);
    }

    public function getDiscountAttribute()
    {
        if ($this->discounts->count() > 0) {
            return $this->discountsActive()->whereDate('start', '<=', date('Y-m-d'))->first();
        }
        return null;
    }

    public function getTaxeAttribute()
    {
        return $this->taxe()->first();
    }
}
