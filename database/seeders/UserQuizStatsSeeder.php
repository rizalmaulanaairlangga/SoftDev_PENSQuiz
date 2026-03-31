<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserQuizStatsSeeder extends Seeder
{
    public function run(): void
    {
        $attempts = DB::table('attempts')
            ->select('user_id', 'quiz_id')
            ->distinct()
            ->get();

        foreach ($attempts as $row) {

            $userId = $row->user_id;
            $quizId = $row->quiz_id;

            $userAttempts = DB::table('attempts')
                ->where('user_id', $userId)
                ->where('quiz_id', $quizId)
                ->orderBy('submitted_at')
                ->get();

            if ($userAttempts->isEmpty()) continue;

            $attemptCount = $userAttempts->count();
            $lastScore = $userAttempts->last()->score;
            $avgScore = $userAttempts->avg('score');

            DB::table('user_quiz_stats')->insert([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'attempt_count' => $attemptCount,
                'last_score' => $lastScore,
                'avg_score' => round($avgScore, 2),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}