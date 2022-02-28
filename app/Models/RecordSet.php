<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'cover',
    ];

    public function links() {
        return $this->hasMany(RecordLink::class);
    }
}
