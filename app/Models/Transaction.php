<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $primaryKey = 'tr_id';

    public function category()
    {
        return $this->belongsTo(FinanceCategory::class,'cat_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class,'ev_id');
    }
}
