<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SetlistEntry extends Model
{
    use HasFactory;

    public function song() : HasOne
    {
        return $this->hasOne(Song::class);
    }

    public function event() : BelongsTo {
        return $this->belongsTo(Event::class);
    }

    protected $fillable = [
        'order',
    ];
}
