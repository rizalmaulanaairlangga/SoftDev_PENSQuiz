<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttemptSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $users = DB::table('users')->pluck('id_user')->toArray();
        $snapshots = DB::table('quiz_snapshots')->get();

        foreach ($snapshots as $snapshot) {

            // tiap quiz punya 2–4 attempt
            $attemptCount = rand(2, 4);

            for ($i = 0; $i < $attemptCount; $i++) {

                $userId = $users[array_rand($users)];

                $start = $now->copy()->subMinutes(rand(10, 120));
                $end = $start->copy()->addMinutes(rand(5, 30));

                // insert attempt
                $attemptId = DB::table('attempts')->insertGetId([
                    'user_id' => $userId,
                    'quiz_id' => $snapshot->quiz_id,
                    'snapshot_id' => $snapshot->id_snapshot,
                    'started_at' => $start,
                    'submitted_at' => $end,
                    'duration_seconds' => $start->diffInSeconds($end),
                    'score' => 0, // sementara
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $questions = DB::table('snapshot_questions')
                    ->where('snapshot_id', $snapshot->id_snapshot)
                    ->get();

                $correctCount = 0;

                foreach ($questions as $question) {

                    $attemptAnswerId = DB::table('attempt_answers')->insertGetId([
                        'attempt_id' => $attemptId,
                        'snapshot_question_id' => $question->id_snapshot_question,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $options = DB::table('snapshot_options')
                        ->where('snapshot_question_id', $question->id_snapshot_question)
                        ->get();

                    $selectedOptions = [];

                    if ($question->question_type === 'single') {

                        // pilih 1 random
                        $selected = $options->random();
                        $selectedOptions = [$selected];

                        if ($selected->is_correct) {
                            $correctCount++;
                        }

                    } else {
                        // multi-answer: pilih random subset
                        foreach ($options as $opt) {
                            if (rand(0,1)) {
                                $selectedOptions[] = $opt;
                            }
                        }

                        // pastikan minimal 1 dipilih
                        if (empty($selectedOptions)) {
                            $selectedOptions[] = $options->random();
                        }

                        // cek benar (HARUS sama persis dengan semua correct)
                        $correctOptions = $options->where('is_correct', true)->pluck('id_snapshot_option')->toArray();
                        $selectedIds = collect($selectedOptions)->pluck('id_snapshot_option')->toArray();

                        sort($correctOptions);
                        sort($selectedIds);

                        if ($correctOptions === $selectedIds) {
                            $correctCount++;
                        }
                    }

                    // insert selected options
                    foreach ($selectedOptions as $opt) {
                        DB::table('attempt_answer_options')->insert([
                            'attempt_answer_id' => $attemptAnswerId,
                            'snapshot_option_id' => $opt->id_snapshot_option,
                        ]);
                    }
                }

                // hitung score (%)
                $total = count($questions);
                $score = $total > 0 ? ($correctCount / $total) * 100 : 0;

                DB::table('attempts')
                    ->where('id_attempt', $attemptId)
                    ->update([
                        'score' => round($score, 2),
                    ]);
            }
        }
    }
}