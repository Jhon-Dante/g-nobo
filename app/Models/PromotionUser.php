<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PromotionUser extends Model
{
    protected $table="promotion_user";

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }
}
