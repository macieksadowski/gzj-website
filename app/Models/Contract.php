<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    public function member()
    {
        return $this->belongsTo(Member::class,'id');
    }


    public function event()
    {
        return $this->belongsTo(Event::class,'ev_id','ev_id');
    }
}
