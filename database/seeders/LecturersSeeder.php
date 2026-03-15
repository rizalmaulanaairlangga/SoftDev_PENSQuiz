<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LecturersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('lecturers')->insert([
            [
                'staff_number' => 'D001',
                'full_name' => 'Dr. Budi Santoso',
                'email' => 'budi@pens.ac.id',
                'major_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_number' => 'D002',
                'full_name' => 'Dr. Siti Rahmawati',
                'email' => 'siti@pens.ac.id',
                'major_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'staff_number' => 'D003',
                'full_name' => 'Dr. Andi Pratama',
                'email' => 'andi@pens.ac.id',
                'major_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}