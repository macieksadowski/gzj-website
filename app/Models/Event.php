<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'ev_id';

    public function type()
    {
        return $this->belongsTo(EventType::class,'type_id');
    }

    public function contracts() {
        return $this->hasMany(Contract::class,'ev_id');
    }
}
