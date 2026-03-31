<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizSnapshotSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $quizzes = DB::table('quizzes')->get();

        foreach ($quizzes as $quiz) {

            // 1. buat snapshot
            $snapshotId = DB::table('quiz_snapshots')->insertGetId([
                'quiz_id' => $quiz->id_quiz,
                'version_number' => $quiz->version_number,
                'created_at' => $now,
            ]);

            // 2. ambil questions asli
            $questions = DB::table('questions')
                ->where('quiz_id', $quiz->id_quiz)
                ->get();

            foreach ($questions as $question) {

                // 3. insert snapshot_question
                $snapshotQuestionId = DB::table('snapshot_questions')->insertGetId([
                    'snapshot_id' => $snapshotId,
                    'original_question_id' => $question->id_question,
                    'content' => $question->content,
                    'question_type' => $question->question_type,
                    'order_index' => $question->order_index,
                ]);

                // 4. ambil options
                $options = DB::table('options')
                    ->where('question_id', $question->id_question)
                    ->get();

                foreach ($options as $option) {

                    // 5. insert snapshot_option
                    DB::table('snapshot_options')->insert([
                        'snapshot_question_id' => $snapshotQuestionId,
                        'original_option_id' => $option->id_option,
                        'content' => $option->content,
                        'is_correct' => $option->is_correct,
                        'order_index' => $option->order_index,
                    ]);
                }
            }
        }
    }
}