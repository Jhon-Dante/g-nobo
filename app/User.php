<?php

namespace App;

use App\Models\Favorite;
use App\Models\Municipality;
use App\Models\Parish;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const NATURAL = 1;
    const JURIDICO = 2;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_REMOVED = 2;

    protected $fillable = [
        'name', 'type', 'person', 'identificacion', 'email', 'password', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'full_document',
        'status_name',
        'es_date'
    ];

    public function getTypePerson($typePerson)
    {
        switch ($typePerson) {
            case static::NATURAL:
                return 'N';
            case static::JURIDICO:
                return 'J';
        }
    }

    public function getFullDocumentAttribute()
    {
        return $this->getTypePerson($this->persona) . '-' . $this->identificacion;
    }

    public function getEsDateAttribute()
    {
        return Carbon::parse($this->created_at)->format('d-m-Y');
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case static::STATUS_INACTIVE:
                return 'Inactivo';
            case static::STATUS_ACTIVE:
                return 'Activo';
            case static::STATUS_REMOVED:
                return 'Eliminado';
        }
    }

    public function estado()
    {
        return $this->belongsTo('App\Models\Estado', 'estado_id');
    }

    public function pais()
    {
        return $this->belongsTo('App\Models\Pais', 'pais_id');
    }

    public function pedidos()
    {
        return $this->hasMany('App\Models\Purchase', 'user_id');
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function parish()
    {
        return $this->belongsTo(Parish::class)->withTrashed();
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
