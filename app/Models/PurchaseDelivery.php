<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDelivery extends Model
{
    protected $table = "purchase_deliveries";

    const TURNS = ['', 'Mañana', 'Tarde', 'Noche'];

    protected $fillable = [
        'purchase_id', 'state_id', 'municipality_id', 'parish_id', 'address', 'type', 'date', 'turn'
    ];

    public function purchase() {
        return $this->belongsTo('App\Models\Purchase','purchase_id');
    }
    
    public function state() {
        return $this->belongsTo('App\Models\Estado','state_id');
    }

    public function municipality() {
        return $this->belongsTo('App\Models\Municipality','municipality_id');
    }

    public function parish() {
        return $this->belongsTo('App\Models\Parish','parish_id');
    }

    public function getTurnFormatedAttribute()
    {
        return $this::TURNS[$this->turn];
    }

    public function getDateFormatedAttribute()
    {
        return \Carbon\Carbon::parse($this->date)->format('m/d/Y');
    }
}
