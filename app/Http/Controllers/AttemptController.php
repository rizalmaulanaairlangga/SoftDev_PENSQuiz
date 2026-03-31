<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttemptController extends Controller
{
    // =========================
    // START QUIZ
    // =========================
    public function start($id)
    {
        $userId = Auth::user()->id_user;

        // 🔥 cek attempt yang belum submit
        $existingAttempt = DB::table('attempts')
            ->where('user_id', $userId)
            ->where('quiz_id', $id)
            ->whereNull('submitted_at')
            ->latest()
            ->first();

        if ($existingAttempt) {
            return redirect()->route('attempt.play', $existingAttempt->id_attempt);
        }

        // ambil snapshot terbaru
        $snapshot = DB::table('quiz_snapshots')
            ->where('quiz_id', $id)
            ->orderByDesc('version_number')
            ->first();

        if (!$snapshot) {
            abort(500, 'Snapshot not found');
        }

        // buat attempt baru
        $attemptId = DB::table('attempts')->insertGetId([
            'user_id' => $userId,
            'quiz_id' => $id,
            'snapshot_id' => $snapshot->id_snapshot,
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('attempt.play', $attemptId);
    }


    // =========================
    // PLAY QUIZ (SNAPSHOT BASED)
    // =========================
    public function play($id)
    {
        $attempt = DB::table('attempts')
            ->where('id_attempt', $id)
            ->first();

        if (!$attempt) abort(404);

        $questionsRaw = DB::table('snapshot_questions')
            ->where('snapshot_id', $attempt->snapshot_id)
            ->orderBy('order_index')
            ->get();

        $optionsRaw = DB::table('snapshot_options')
            ->whereIn('snapshot_question_id', $questionsRaw->pluck('id_snapshot_question'))
            ->orderBy('order_index')
            ->get()
            ->groupBy('snapshot_question_id');

        $savedAnswers = DB::table('attempt_answers as aa')
            ->join('attempt_answer_options as aao', 'aa.id_attempt_answer', '=', 'aao.attempt_answer_id')
            ->where('aa.attempt_id', $attempt->id_attempt)
            ->get()
            ->groupBy('snapshot_question_id')
            ->map(function ($items) {
                return $items->pluck('snapshot_option_id')->values()->toArray();
            })
            ->toArray();

        $questions = $questionsRaw->values()->map(function ($q, $index) use ($optionsRaw) {
            $opts = collect($optionsRaw[$q->id_snapshot_question] ?? [])->values();

            return [
                'id_snapshot_question' => $q->id_snapshot_question,
                'number' => $index + 1,
                'content' => $q->content,
                'question_type' => $q->question_type,   // penting
                'explanation' => $q->explanation,       // penting
                'correct_count' => $opts->where('is_correct', 1)->count(), // opsional
                'options' => $opts->map(function ($opt) {
                    return [
                        'id_snapshot_option' => $opt->id_snapshot_option,
                        'content' => $opt->content,
                        'is_correct' => $opt->is_correct, // kalau memang mau dipakai saat review
                        'number' => $opt->order_index + 1,
                    ];
                }),
            ];
        });

        return view('pages.attempt.play', compact('attempt', 'questions', 'savedAnswers'));
    }

    public function saveAnswer(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:attempts,id_attempt',
            'question_id' => 'required|exists:snapshot_questions,id_snapshot_question',
            'option_ids' => 'required|array',
            'option_ids.*' => 'exists:snapshot_options,id_snapshot_option',
        ]);

        DB::beginTransaction();

        try {
            // 1. cari / buat attempt_answer
            $attemptAnswer = DB::table('attempt_answers')
                ->where('attempt_id', $request->attempt_id)
                ->where('snapshot_question_id', $request->question_id)
                ->first();

            if (!$attemptAnswer) {
                $attemptAnswerId = DB::table('attempt_answers')->insertGetId([
                    'attempt_id' => $request->attempt_id,
                    'snapshot_question_id' => $request->question_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $attemptAnswerId = $attemptAnswer->id_attempt_answer;

                // hapus opsi lama (replace strategy)
                DB::table('attempt_answer_options')
                    ->where('attempt_answer_id', $attemptAnswerId)
                    ->delete();
            }

            // 2. insert opsi baru
            $insertData = collect($request->option_ids)->map(function ($optId) use ($attemptAnswerId) {
                return [
                    'attempt_answer_id' => $attemptAnswerId,
                    'snapshot_option_id' => $optId,
                ];
            })->toArray();

            DB::table('attempt_answer_options')->insert($insertData);

            DB::commit();

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'failed'], 500);
        }
    }

    public function exit(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:attempts,id_attempt',
            'action' => 'required|in:save,discard',
        ]);

        $attempt = DB::table('attempts')
            ->where('id_attempt', $request->attempt_id)
            ->where('user_id', Auth::user()->id_user)
            ->first();

        if (!$attempt) {
            return response()->json(['ok' => false, 'message' => 'Attempt not found'], 404);
        }

        if ($request->action === 'discard') {
            DB::table('attempts')
                ->where('id_attempt', $attempt->id_attempt)
                ->delete();
        }

        return response()->json(['ok' => true]);
    }
}