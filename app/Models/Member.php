<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'street',
        'house_no',
        'postal_code',
        'town',
        'pesel',
        'birth_place',
        'account_no',
        'tax_office'
    ];

    public function contracts() {
        return $this->hasMany(Contract::class);
    }


}
