<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function type() {
        return $this->belongsTo(EnumType::class);
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
