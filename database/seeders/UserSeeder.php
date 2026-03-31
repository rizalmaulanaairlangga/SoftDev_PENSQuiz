<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nrp' => '312310001',
                'username' => 'Rrrizal',
                'full_name' => 'Rizal Maulana',
                'email' => 'rizal@student.pens.ac.id',
                'password' => Hash::make('password'),
                'major_id' => 1,
                'year_of_entry' => 2023,
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nrp' => '312310002',
                'username' => 'andikun',
                'full_name' => 'Andi Saputra',
                'email' => 'andi@student.pens.ac.id',
                'password' => Hash::make('password'),
                'major_id' => 2,
                'year_of_entry' => 2023,
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
