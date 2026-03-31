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

            if ($question->question_type === 'single') {
                // 4 option, 1 benar
                $correctIndex = rand(0,3);

                for ($i = 0; $i < 4; $i++) {
                    $options[] = [
                        'question_id' => $question->id_question,
                        'content' => "Option " . chr(65+$i),
                        'is_correct' => $i === $correctIndex,
                        'order_index' => $i,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

            } else {
                // multi: 4 option, 2 benar
                $correctIndexes = array_rand([0,1,2,3], 2);

                for ($i = 0; $i < 4; $i++) {
                    $options[] = [
                        'question_id' => $question->id_question,
                        'content' => "Option " . chr(65+$i),
                        'is_correct' => in_array($i, (array)$correctIndexes),
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