<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceCategory extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(EnumType::class, 'enum_type_id');
    }

}
