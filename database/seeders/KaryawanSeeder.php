<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('karyawans')->insert([
            [
                'name' => 'Andi Saputra',
                'position' => 'Staff Administrasi',
                'phone' => '081234567890',
                'email' => 'andi@example.com',
                'address' => 'Jl. Merdeka No. 10',
                'salary' => 4500000,
                'method_payment' => 'transfer',
                'bank_account_name' => 'Andi Saputra',
                'bank_account_number' => '1234567890',
                'bank_name' => 'BCA',
            ],
            [
                'name' => 'Siti Aisyah',
                'position' => 'HRD',
                'phone' => '082345678901',
                'email' => 'siti@example.com',
                'address' => 'Jl. Sudirman No. 25',
                'salary' => 6500000,
                'method_payment' => 'ewallet',
                'bank_account_name' => null,
                'bank_account_number' => null,
                'bank_name' => null,
            ],
            [
                'name' => 'Budi Santoso',
                'position' => 'Finance',
                'phone' => '083456789012',
                'email' => 'budi@example.com',
                'address' => 'Jl. Gatot Subroto No. 5',
                'salary' => 7000000,
                'method_payment' => 'cash',
                'bank_account_name' => null,
                'bank_account_number' => null,
                'bank_name' => null,
            ],
        ]);
    }
}
