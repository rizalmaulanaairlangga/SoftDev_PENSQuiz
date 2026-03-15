<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('majors')->insert([
            [
                'code' => 'IT',
                'name' => 'Teknik Informatika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'ELKA',
                'name' => 'Teknik Elektronika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'TELKOM',
                'name' => 'Teknik Telekomunikasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MKT',
                'name' => 'Mekatronika',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}