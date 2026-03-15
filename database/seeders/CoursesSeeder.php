<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'code' => 'IF101',
                'name' => 'Algoritma dan Pemrograman',
                'major_id' => 1,
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'IF202',
                'name' => 'Struktur Data',
                'major_id' => 1,
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'IF303',
                'name' => 'Basis Data',
                'major_id' => 1,
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'IF404',
                'name' => 'Pemrograman Web',
                'major_id' => 1,
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}