<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';

    public function events() {
        return $this->hasMany(Event::class);
    }
}
