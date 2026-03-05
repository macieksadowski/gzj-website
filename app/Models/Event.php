<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Event extends Model
{
    use HasFactory;

    public function contracts() {
        return $this->hasMany(Contract::class);
    }
    public function type() {
        return $this->belongsTo(EnumType::class, 'type_id');
    }
    
    public function setlistEntries() {
        return $this->hasMany(SetlistEntry::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'ev_id', 'id');
    }

    protected $fillable = ['date', 'name'];
}
