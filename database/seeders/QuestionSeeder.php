<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $quizIds = DB::table('quizzes')->pluck('id_quiz')->toArray();

        $questions = [];

        foreach ($quizIds as $quizId) {

            // tiap quiz 3–5 soal
            $count = rand(3, 5);

            for ($i = 0; $i < $count; $i++) {

                $questions[] = [
                    'quiz_id' => $quizId,
                    'content' => "Soal ke-" . ($i + 1) . " untuk quiz ID $quizId",
                    'question_type' => rand(0,1) ? 'single_answer' : 'multiple_answer',
                    'order_index' => $i,
                    'explanation' => 'Penjelasan singkat soal ini.',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('questions')->insert($questions);
    }
}