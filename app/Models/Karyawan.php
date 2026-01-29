<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{


    protected $fillable = [
        'name',
        'position',
        'phone',
        'email',
        'address',
        'salary',
        'method_payment',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
    ];
}
