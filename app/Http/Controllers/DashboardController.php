<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id_user;

        /**
         * =========================
         * CREATOR STATISTICS
         * =========================
         */

        // 1. Quizzes created
        $quizCount = DB::table('quizzes')
            ->where('author_id', $userId)
            ->count();

        // 2. Total questions
        $questionCount = DB::table('questions')
            ->join('quizzes', 'questions.quiz_id', '=', 'quizzes.id_quiz')
            ->where('quizzes.author_id', $userId)
            ->count();

        /**
         * =========================
         * ENGAGEMENT
         * =========================
         */

        $attemptQuery = DB::table('attempts')
            ->join('quizzes', 'attempts.quiz_id', '=', 'quizzes.id_quiz')
            ->where('quizzes.author_id', $userId);

        // 3. Total attempts
        $attempts = (clone $attemptQuery)->count();

        // 4. Participants (unique users)
        $participants = (clone $attemptQuery)
            ->distinct('attempts.user_id')
            ->count('attempts.user_id');

        // 5. Completion rate
        $submitted = (clone $attemptQuery)
            ->whereNotNull('attempts.submitted_at')
            ->count();

        $completionRate = $attempts > 0
            ? ($submitted / $attempts) * 100
            : 0;

        /**
         * =========================
         * CHART DATA (FIXED)
         * =========================
         */

        // ambil jumlah attempt per hari (1=Sun ... 7=Sat)
        $rawData = DB::table('attempts')
            ->join('quizzes', 'attempts.quiz_id', '=', 'quizzes.id_quiz')
            ->where('quizzes.author_id', $userId)
            ->whereNotNull('attempts.submitted_at')
            ->selectRaw('DAYOFWEEK(attempts.submitted_at) as day, COUNT(*) as total')
            ->groupBy('day')
            ->pluck('total', 'day');

        // mapping ke array lengkap (Sun-Sat)
        $chartData = [];

        for ($i = 1; $i <= 7; $i++) {
            $chartData[] = $rawData[$i] ?? 0;
        }

        return view('pages.dashboard.index', [
            'quizCount' => $quizCount,
            'questionCount' => $questionCount,
            'attempts' => $attempts,
            'participants' => $participants,
            'completionRate' => round($completionRate),

            // chart
            'chartData' => $chartData,
        ]);
    }
}