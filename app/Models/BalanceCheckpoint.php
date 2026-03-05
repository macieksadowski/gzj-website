<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceCheckpoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkpoint_date',
        'balance',
        'notes',
    ];
}
