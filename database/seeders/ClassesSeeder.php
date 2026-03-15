<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('classes')->insert([
            [
                'name' => 'IF-A',
                'course_id' => 1,
                'academic_year_id' => 2,
                'semester' => 1,
                'lecturer_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IF-B',
                'course_id' => 2,
                'academic_year_id' => 2,
                'semester' => 2,
                'lecturer_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}