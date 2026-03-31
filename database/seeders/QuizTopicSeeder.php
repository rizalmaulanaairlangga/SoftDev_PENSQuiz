<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuizTopicSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $quizIds = DB::table('quizzes')->pluck('id_quiz')->toArray();
        $topicIds = DB::table('topics')->pluck('id_topic')->toArray();

        $data = [];

        foreach ($quizIds as $quizId) {
            if (empty($topicIds)) continue;

            // ambil 1–2 topic per quiz
            $randomTopics = array_rand($topicIds, min(2, count($topicIds)));
            $randomTopics = is_array($randomTopics) ? $randomTopics : [$randomTopics];

            foreach ($randomTopics as $index => $key) {
                $data[] = [
                    'quiz_id' => $quizId,
                    'topic_id' => $topicIds[$key],
                    'is_primary' => $index === 0,
                    'created_at' => $now,
                ];
            }
        }

        DB::table('quiz_topics')->insert($data);
    }
}