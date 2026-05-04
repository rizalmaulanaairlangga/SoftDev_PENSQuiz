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
        $filterTags = $request->input('tags', []);

        // Fetch popular tags for the filter modal
        $popularTags = Tag::whereHas('quizzes', function ($q) {
            $q->where('access', 'public');
        })->withCount('quizzes')->orderByDesc('quizzes_count')->take(10)->get();

        // Base query for public quizzes
        if ($search || !empty($filterTags)) {
            $baseQuery = MyQuiz::with(['course', 'major', 'author', 'tags'])
                ->withCount('questions')
                ->where('access', 'public');
                
            if ($search) {
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
                      ->orWhereHas('lecturer', function($q) use ($search) {
                          $q->where('full_name', 'like', "%{$search}%");
                      })
                      ->orWhereHas('tags', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            if (!empty($filterTags)) {
                $baseQuery->whereHas('tags', function ($q) use ($filterTags) {
                    $q->whereIn('tags.id_tag', $filterTags);
                });
            }

            $searchResults = $baseQuery->paginate(12)->appends($request->query());
            return view('pages.quiz.discover', compact('searchResults', 'search', 'filterTags', 'popularTags'));
        }

        // History: Quizzes the user has actually attempted
        $historyQuizzes = collect();
        if (Auth::check()) {
            $attempts = \App\Models\Attempt::with(['quiz' => function($q) {
                    $q->with(['course', 'major', 'author', 'tags'])->withCount('questions')->where('access', 'public');
                }])
                ->where('user_id', Auth::id())
                ->latest('updated_at')
                ->get()
                ->unique('quiz_id')
                ->take(10);
                
            $historyQuizzes = $attempts->map(function($a) {
                return $a->quiz;
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
            'trendingQuizzes',
            'filterTags',
            'popularTags'
        ));
    }

    public function show($id)
    {
        $quiz = MyQuiz::with(['course', 'major', 'author', 'lecturer'])
            ->withCount('questions')
            ->find($id);

        if (!$quiz) abort(404);

        // Add virtual properties for backward compatibility if needed, 
        // but it's better to update the view to use $quiz directly.
        $quiz->course_name = $quiz->course->name ?? null;
        $quiz->major_name = $quiz->major->name ?? null;
        $quiz->creator_name = $quiz->author ? $quiz->author->first_name . ' ' . ($quiz->author->last_name ?? '') : 'System';
        
        $questionCount = $quiz->questions_count;

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
    public function copy(Request $request, $id)
    {
        $originalQuiz = MyQuiz::with(['questions.options', 'tags'])->findOrFail($id);

        if (!$originalQuiz->allow_copy || $originalQuiz->author_id === Auth::id()) {
            return back()->with('error', 'You are not allowed to copy this quiz.');
        }

        DB::beginTransaction();
        try {
            // Duplicate Quiz
            $newQuiz = $originalQuiz->replicate([
                'author_id',
                'folder_id',
                'version_number',
                'has_been_updated',
                'created_at',
                'updated_at'
            ]);
            
            $newQuiz->author_id = Auth::id();
            $newQuiz->folder_id = null; // Don't copy to original folder
            $newQuiz->version_number = 1;
            $newQuiz->has_been_updated = false;
            $newQuiz->title = $originalQuiz->title . ' (Copy)';
            $newQuiz->visibility = 'draft'; // Set as draft initially
            $newQuiz->save();

            // Duplicate Tags
            $newQuiz->tags()->sync($originalQuiz->tags->pluck('id_tag'));

            // Duplicate Questions and Options
            foreach ($originalQuiz->questions as $question) {
                $newQuestion = $question->replicate(['quiz_id', 'created_at', 'updated_at']);
                $newQuestion->quiz_id = $newQuiz->id_quiz;
                $newQuestion->save();

                foreach ($question->options as $option) {
                    $newOption = $option->replicate(['question_id', 'created_at', 'updated_at']);
                    $newOption->question_id = $newQuestion->id_question;
                    $newOption->save();
                }
            }

            DB::commit();

            if ($request->input('action') === 'edit') {
                return redirect()
                    ->route('my-quizzes.edit', $newQuiz->id_quiz)
                    ->with('success', 'Quiz copied successfully! You are now editing your copy.');
            }

            return redirect()
                ->route('my-quizzes.index')
                ->with('success', 'Quiz copied successfully to your collection!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to copy quiz: ' . $e->getMessage());
        }
    }
}
