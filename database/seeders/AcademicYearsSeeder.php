<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('academic_years')->insert([
            [
                'label' => '2023/2024',
                'start_date' => '2023-08-01',
                'end_date' => '2024-07-31',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => '2024/2025',
                'start_date' => '2024-08-01',
                'end_date' => '2025-07-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}