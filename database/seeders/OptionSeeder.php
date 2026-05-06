<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $questions = DB::table('questions')->get();

        $options = [];

        foreach ($questions as $question) {

            if ($question->question_type === 'multiple_choice') {
                // multiple_choice: 4 options, exactly 1 correct
                $correctIndex = rand(0, 3);

                for ($i = 0; $i < 4; $i++) {
                    $options[] = [
                        'question_id' => $question->id_question,
                        'content' => "Option " . chr(65 + $i),
                        'is_correct' => $i === $correctIndex,
                        'order_index' => $i,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

            } else {
                // checkbox: 4 options, exactly 2 correct
                $allIndexes = [0, 1, 2, 3];
                shuffle($allIndexes);
                $correctIndexes = array_slice($allIndexes, 0, 2);

                for ($i = 0; $i < 4; $i++) {
                    $options[] = [
                        'question_id' => $question->id_question,
                        'content' => "Option " . chr(65 + $i),
                        'is_correct' => in_array($i, $correctIndexes),
                        'order_index' => $i,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        DB::table('options')->insert($options);
    }
}