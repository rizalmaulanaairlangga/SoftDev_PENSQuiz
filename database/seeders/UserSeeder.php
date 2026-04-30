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
                'first_name' => 'Rizal',
                'last_name' => 'Maulana',
                'username' => 'rizalmln',
                'email' => 'rizal@student.pens.ac.id',
                'password' => Hash::make('password'),
                'major_id' => 1,
                'year_of_entry' => 2023,
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Andi',
                'last_name' => 'Saputra',
                'username' => 'andisap',
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
