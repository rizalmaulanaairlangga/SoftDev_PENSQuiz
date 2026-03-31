<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MajorsSeeder::class,
            CoursesSeeder::class,
            LecturersSeeder::class,
            AcademicYearsSeeder::class,
            UserSeeder::class,
            TopicsSeeder::class,
            ClassesSeeder::class,
            QuizSeeder::class,
            QuizTopicSeeder::class,
            QuestionSeeder::class,
            OptionSeeder::class,
            QuizSnapshotSeeder::class,
            AttemptSeeder::class,
            QuizCopySeeder::class,
            UserQuizStatsSeeder::class,
        ]);
    }
}