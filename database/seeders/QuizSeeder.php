<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ambil 2 user pertama
        $users = DB::table('users')
            ->orderBy('id_user')
            ->limit(2)
            ->pluck('id_user')
            ->toArray();

        if (count($users) < 2) {
            throw new \Exception('Minimal harus ada 2 user untuk QuizSeeder');
        }

        // ambil reference data (ambil random nanti)
        $majorIds = DB::table('majors')->pluck('id_major')->toArray();
        $courseIds = DB::table('courses')->pluck('id_course')->toArray();
        $lecturerIds = DB::table('lecturers')->pluck('id_lecturer')->toArray();
        $yearIds = DB::table('academic_years')->pluck('id_academic_year')->toArray();
        $classIds = DB::table('classes')->pluck('id_class')->toArray();

        $titles = [
            'Quiz Laravel Dasar',
            'Pemrograman Web Lanjut',
            'Struktur Data Quiz',
            'Database Design Basics',
            'Algoritma & Kompleksitas',
            'Frontend Development',
            'Backend API Design',
            'OOP Concept Quiz',
            'Software Engineering',
            'Fullstack Challenge'
        ];

        $quizzes = [];

        $baseDate = now()->subDays(10);

        for ($i = 0; $i < 10; $i++) {

            $createdAt = $baseDate->copy()
                ->addDays($i)
                ->setTime(rand(8, 22), rand(0, 59), rand(0, 59)); // jam random

            $quizzes[] = [
                'author_id' => $users[$i % 2],

                'title' => $titles[$i],
                'description' => 'Quiz tentang ' . $titles[$i],

                'major_id' => $this->randomOrNull($majorIds),
                'course_id' => $this->randomOrNull($courseIds),
                'lecturer_id' => $this->randomOrNull($lecturerIds),
                'academic_year_id' => $this->randomOrNull($yearIds),
                'class_id' => $this->randomOrNull($classIds),

                'semester' => rand(1, 8),

                'visibility' => rand(0, 1) ? 'draft' : 'published',
                'access' => rand(0, 1) ? 'public' : 'private',

                'allow_copy' => rand(0, 1),
                'version_number' => 1,
                'has_been_updated' => false,

                'cover_image_url' => null,

                // ✅ FIX UTAMA
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addMinutes(rand(10, 180)),
            ];
        }

        DB::table('quizzes')->insert($quizzes);
    }

    private function randomOrNull(array $data)
    {
        return empty($data) ? null : $data[array_rand($data)];
    }
}