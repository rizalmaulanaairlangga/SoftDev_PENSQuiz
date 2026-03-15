<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('topics')->insert([
            [
                'name' => 'Array',
                'description' => 'Konsep dasar array dalam pemrograman',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Linked List',
                'description' => 'Struktur data linked list',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Database Normalization',
                'description' => 'Normalisasi database',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laravel MVC',
                'description' => 'Konsep MVC pada Laravel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}