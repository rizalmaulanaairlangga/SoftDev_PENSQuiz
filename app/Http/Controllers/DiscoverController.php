<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscoverController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $quizzes = DB::table('quizzes')
            ->leftJoin('users', 'quizzes.author_id', '=', 'users.id_user')
            ->leftJoin('courses', 'quizzes.course_id', '=', 'courses.id_course')
            ->where('quizzes.access', 'public')
            ->when($search, function ($query, $search) {
                $query->where('quizzes.title', 'like', "%{$search}%");
            })
            ->select(
                'quizzes.id_quiz',
                'quizzes.title',
                'quizzes.cover_image_url',
                'courses.name as course_name',
                'users.full_name as creator_name'
            )
            ->paginate(8);

        return view('pages.quiz.discover', compact('quizzes', 'search'));
    }

    public function show($id)
    {
        $quiz = DB::table('quizzes')
            ->leftJoin('users', 'quizzes.author_id', '=', 'users.id_user')
            ->leftJoin('courses', 'quizzes.course_id', '=', 'courses.id_course')
            ->where('quizzes.id_quiz', $id)
            ->select(
                'quizzes.id_quiz',
                'quizzes.title',
                'quizzes.description',
                'quizzes.cover_image_url',
                'courses.name as course_name',
                'users.full_name as creator_name'
            )
            ->first();

        if (!$quiz) abort(404);

        $questionCount = DB::table('questions')
            ->where('quiz_id', $id)
            ->count();

        // ambil related course (simple)
        $relatedCourses = DB::table('courses')
            ->whereNotNull('name')
            ->limit(3)
            ->pluck('name');

        // dummy tags (sementara, karena belum ada tabel tags)
        $tags = ['Quiz', 'Practice', 'Exam'];

        // cek jika sudah ada attempt    
        $existingAttempt = DB::table('attempts')
            ->where('user_id', Auth::user()->id_user)
            ->where('quiz_id', $quiz->id_quiz)
            ->whereNull('submitted_at')
            ->latest()
            ->first();

        

        return view('pages.quiz.show', compact(
            'quiz',
            'questionCount',
            'relatedCourses',
            'tags',
            'existingAttempt'
        ));
    }
}
