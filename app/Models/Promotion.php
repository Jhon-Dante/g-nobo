<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'limit', 'discount_percentage', 'start_date', 'end_date'];

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_SOLD_OUT = 2;

    protected $appends = [
        'status_name'
    ];

    public function getStatusNameAttribute()
	{
		switch ($this->status) {
			case self::STATUS_INACTIVE:
				return 'Sin Publicar';
				break;
			case self::STATUS_ACTIVE:
				return 'Publicada';
                break;
            case self::STATUS_SOLD_OUT:
                return 'Productos Sin Existencia';
                break;
			default:
				return 'Estado';
				break;
		}
	}

    public function uses()
    {
        return $this->hasMany(PromotionUser::class, 'promotion_id');
    }
    
    public function products()
    {
        return $this->hasMany(PromotionProduct::class, 'promotion_id');
    }
}
