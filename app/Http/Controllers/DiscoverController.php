<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MyQuiz;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiscoverController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Base query for public quizzes
        if ($search) {
            $baseQuery = MyQuiz::with(['course', 'major', 'author', 'tags'])
                ->withCount('questions')
                ->where('access', 'public');
                
            $baseQuery->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('major', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('course', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('author', function($q) use ($search) {
                      $q->where(DB::raw("CONCAT(first_name, ' ', COALESCE(last_name, ''))"), 'like', "%{$search}%");
                  })
                  ->orWhereHas('tags', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
            $searchResults = $baseQuery->paginate(12);
            return view('pages.quiz.discover', compact('searchResults', 'search'));
        }

        // History
        $historyQuizzes = collect();
        if (Auth::check()) {
            $historyRecords = \App\Models\QuizHistory::with([
                'quiz' => function($q) {
                    $q->with(['course', 'major', 'author', 'tags'])->withCount('questions')->where('access', 'public');
                }
            ])
            ->where('user_id', Auth::id())
            ->latest('last_opened_at')
            ->take(10)
            ->get();
            
            $historyQuizzes = $historyRecords->map(function($h) {
                return $h->quiz;
            })->filter(); // remove nulls
        }

        // Recommended: Based on tags of historyQuizzes
        $recommendedQuery = MyQuiz::with(['course', 'major', 'author', 'tags'])
            ->withCount('questions')
            ->where('access', 'public');

        if ($historyQuizzes->isNotEmpty()) {
            $userTagIds = collect();
            foreach ($historyQuizzes as $hq) {
                if ($hq->tags) {
                    $userTagIds = $userTagIds->merge($hq->tags->pluck('id_tag'));
                }
            }
            $userTagIds = $userTagIds->unique()->toArray();

            if (!empty($userTagIds)) {
                $recommendedQuery->whereHas('tags', function($q) use ($userTagIds) {
                    $q->whereIn('tags.id_tag', $userTagIds);
                });
                
                $historyIds = $historyQuizzes->pluck('id_quiz')->toArray();
                if (!empty($historyIds)) {
                    $recommendedQuery->whereNotIn('id_quiz', $historyIds);
                }
            }
        }
        
        $recommendedQuizzes = $recommendedQuery->latest()->take(10)->get();

        // Fallback for recommended if empty
        if ($recommendedQuizzes->isEmpty()) {
            $recommendedQuizzes = MyQuiz::with(['course', 'major', 'author', 'tags'])
                ->withCount('questions')
                ->where('access', 'public')
                ->latest()
                ->take(10)
                ->get();
        }

        // Popular: Quizzes having most attempts in user's major
        $popularQuery = MyQuiz::with(['course', 'major', 'author', 'tags'])
            ->withCount(['questions', 'attempts'])
            ->where('access', 'public')
            ->orderByDesc('attempts_count');

        if (Auth::check() && Auth::user()->major_id) {
            $popularQuery->where('major_id', Auth::user()->major_id);
        }

        $popularQuizzes = $popularQuery->take(10)->get();

        // Trending: Most opened by other users in the last 7 days
        $oneWeekAgo = now()->subDays(7);
        $trendingQuery = \App\Models\QuizHistory::select('quiz_id')
            ->selectRaw('COUNT(user_id) as opens_count')
            ->where('last_opened_at', '>=', $oneWeekAgo);

        if (Auth::check()) {
            $trendingQuery->where('user_id', '!=', Auth::id());
        }

        $trendingQuizIds = $trendingQuery->groupBy('quiz_id')
            ->orderByDesc('opens_count')
            ->take(10)
            ->pluck('quiz_id')
            ->toArray();

        if (!empty($trendingQuizIds)) {
            $trendingQuizzesRaw = MyQuiz::with(['course', 'major', 'author', 'tags'])
                ->withCount('questions')
                ->where('access', 'public')
                ->whereIn('id_quiz', $trendingQuizIds)
                ->get();

            // Sort by popularity in the history
            $trendingQuizzes = collect();
            foreach ($trendingQuizIds as $tId) {
                $found = $trendingQuizzesRaw->firstWhere('id_quiz', $tId);
                if ($found) {
                    $trendingQuizzes->push($found);
                }
            }
        } else {
            // Fallback
            $trendingQuizzes = MyQuiz::with(['course', 'major', 'author', 'tags'])
                ->withCount('questions')
                ->where('access', 'public')
                ->orderByDesc('created_at')
                ->take(10)
                ->get();
        }

        return view('pages.quiz.discover', compact(
            'search',
            'historyQuizzes',
            'recommendedQuizzes',
            'popularQuizzes',
            'trendingQuizzes'
        ));
    }

    public function show($id)
    {
        $quiz = DB::table('quizzes')
            ->leftJoin('users', 'quizzes.author_id', '=', 'users.id_user')
            ->leftJoin('courses', 'quizzes.course_id', '=', 'courses.id_course')
            ->leftJoin('majors', 'quizzes.major_id', '=', 'majors.id_major')
            ->where('quizzes.id_quiz', $id)
            ->select(
                'quizzes.id_quiz',
                'quizzes.title',
                'quizzes.description',
                'quizzes.cover_image_url',
                'quizzes.time_limit_minutes',
                'quizzes.course_id',
                'courses.name as course_name',
                'majors.name as major_name',
                DB::raw("CONCAT(users.first_name, ' ', COALESCE(users.last_name, '')) as creator_name")
            )
            ->first();

        if (!$quiz) abort(404);

        $questionCount = DB::table('questions')
            ->where('quiz_id', $id)
            ->count();

        // hapus relatedCourses (di-request dihapus)

        // related quizzes based on course
        $relatedQuizzes = collect();
        if ($quiz->course_id) {
            $relatedQuizzes = MyQuiz::with(['course', 'major', 'author', 'tags'])
                ->withCount('questions')
                ->where('access', 'public')
                ->where('course_id', $quiz->course_id)
                ->where('id_quiz', '!=', $quiz->id_quiz)
                ->take(4)
                ->get();
        }

        // ambil real tags
        $tags = DB::table('quiz_tags')
            ->join('tags', 'quiz_tags.tag_id', '=', 'tags.id_tag')
            ->where('quiz_tags.quiz_id', $id)
            ->pluck('tags.name')
            ->toArray();

        // cek jika sudah ada attempt    
        $existingAttempt = DB::table('attempts')
            ->where('user_id', Auth::user()->id_user)
            ->where('quiz_id', $quiz->id_quiz)
            ->whereNull('submitted_at')
            ->latest()
            ->first();

        // Track quiz history
        if (Auth::check()) {
            \App\Models\QuizHistory::updateOrCreate(
                [
                    'user_id' => Auth::user()->id_user,
                    'quiz_id' => $quiz->id_quiz,
                ],
                [
                    'last_opened_at' => now(),
                ]
            );
        }

        $usersClicked = \App\Models\QuizHistory::where('quiz_id', $quiz->id_quiz)->count();
        $participants = \App\Models\Attempt::where('quiz_id', $quiz->id_quiz)->distinct('user_id')->count('user_id');
        
        $totalAttempts = \App\Models\Attempt::where('quiz_id', $quiz->id_quiz)->count();
        $completedAttempts = \App\Models\Attempt::where('quiz_id', $quiz->id_quiz)->whereNotNull('submitted_at')->count();
        $completionRate = $totalAttempts > 0 ? round(($completedAttempts / $totalAttempts) * 100) : 0;

        return view('pages.quiz.show', compact(
            'quiz',
            'questionCount',
            'relatedQuizzes',
            'tags',
            'existingAttempt',
            'usersClicked',
            'participants',
            'completionRate'
        ));
    }
}
