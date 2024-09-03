<?php

namespace App\Models;

use EnumTypeDiscriminator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnumType extends Model
{
    use HasFactory;

    protected $casts = [
        'discriminator' => EnumTypeDiscriminator::class
    ];
}
