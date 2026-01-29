<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{


    protected $fillable = [
        'karyawan_id',
        'main_salary',
        'overtime_pay',
        'meal_pay',
        'transportation_pay',
        'bonus',
        'bonus_description',
        'late_deduction',
        'absent_deduction',
        'break_stuff_deduction',
        'other_deduction',
        'other_deduction_description',
        'total_salary',
        'total_deduction',
        'total_net_salary',
        'status',
        'period_start',
        'period_end'
    ];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
