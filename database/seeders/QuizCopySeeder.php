<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizCopySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $users = DB::table('users')->pluck('id_user')->toArray();

        // Ambil 2 kuis acak yang public dan allow_copy
        $quizzes = DB::table('quizzes')
            ->where('access', 'public')
            ->where('allow_copy', true)
            ->inRandomOrder()
            ->take(2)
            ->get();

        foreach ($quizzes as $quiz) {
            // pilih user yang BUKAN author asli
            $otherUsers = array_filter($users, fn($u) => $u != $quiz->author_id);
            if (empty($otherUsers)) continue;

            $newAuthor = $otherUsers[array_rand($otherUsers)];

            // buat quiz baru (copy)
            $newQuizId = DB::table('quizzes')->insertGetId([
                'author_id' => $newAuthor,
                'title' => $quiz->title . ' (Copy)',
                'description' => $quiz->description,

                'major_id' => $quiz->major_id,
                'course_id' => $quiz->course_id,

                'visibility' => 'draft',
                'access' => 'private',

                'allow_copy' => false,
                'version_number' => 1,
                'has_been_updated' => false,

                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // catat di quiz_copies
            DB::table('quiz_copies')->insert([
                'original_quiz_id' => $quiz->id_quiz,
                'new_quiz_id' => $newQuizId,
                'copied_by_user_id' => $newAuthor,
                'copied_at' => $now,
            ]);
        }
    }
}