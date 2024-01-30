<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'highlighted'
    ];

    public function recordSet()
    {
        return $this->belongsTo('App\RecordSet');
    }
}
