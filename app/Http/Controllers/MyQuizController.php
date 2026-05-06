<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Course;
use App\Models\MyQuiz;
use App\Models\Question;
use App\Models\Tag;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MyQuizController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $visibility = $request->input('visibility', 'all');
        $sort = $request->input('sort', 'latest');
        $search = $request->input('search');
        $filterTags = $request->input('tags', []);
        $filterMajor = $request->input('major');
        $filterCourse = $request->input('course');

        $query = MyQuiz::withCount('questions')
            ->where('author_id', $userId)
            ->with(['course', 'major', 'tags']);

        if ($visibility !== 'all') {
            $query->where('visibility', $visibility);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('major', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('course', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('tags', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        if (!empty($filterTags)) {
            $query->whereHas('tags', function ($q) use ($filterTags) {
                $q->whereIn('tags.id_tag', $filterTags);
            });
        }

        if ($filterMajor) {
            $query->where('major_id', $filterMajor);
        }

        if ($filterCourse) {
            $query->where('course_id', $filterCourse);
        }

        if ($sort === 'latest') {
            $query->orderByDesc('updated_at')->orderByDesc('id_quiz');
        } elseif ($sort === 'oldest') {
            $query->orderBy('updated_at', 'asc')->orderBy('id_quiz', 'asc');
        }

        $perPage = $request->input('per_page', 10);
        $quizzes = $query->paginate($perPage)->appends($request->query());

        $folderQuery = Folder::where('user_id', $userId)->withCount('quizzes');
        
        $hasFilters = $search || !empty($filterTags) || $filterMajor || $filterCourse || $visibility !== 'all';
        
        if ($hasFilters) {
            $folderQuery->where(function($q) use ($search, $filterTags, $filterMajor, $filterCourse, $visibility) {
                if ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                }
                
                $q->orWhereHas('quizzes', function($quizQuery) use ($search, $filterTags, $filterMajor, $filterCourse, $visibility) {
                    if ($visibility !== 'all') {
                        $quizQuery->where('visibility', $visibility);
                    }
                    if ($search) {
                        $quizQuery->where(function ($sq) use ($search) {
                            $sq->where('title', 'like', '%' . $search . '%')
                              ->orWhere('description', 'like', '%' . $search . '%')
                              ->orWhereHas('major', function ($q2) use ($search) {
                                  $q2->where('name', 'like', '%' . $search . '%');
                              })
                              ->orWhereHas('course', function ($q2) use ($search) {
                                  $q2->where('name', 'like', '%' . $search . '%');
                              })
                              ->orWhereHas('tags', function ($q2) use ($search) {
                                  $q2->where('name', 'like', '%' . $search . '%');
                              });
                        });
                    }
                    if (!empty($filterTags)) {
                        $quizQuery->whereHas('tags', function ($tq) use ($filterTags) {
                            $tq->whereIn('tags.id_tag', $filterTags);
                        });
                    }
                    if ($filterMajor) {
                        $quizQuery->where('major_id', $filterMajor);
                    }
                    if ($filterCourse) {
                        $quizQuery->where('course_id', $filterCourse);
                    }
                });
            });
        }
        
        $folders = $folderQuery->orderBy('name', 'asc')->get();

        $scrollTo = null;
        if ($hasFilters || request()->has('page')) {
            $scrollTo = $folders->isNotEmpty() ? 'folders-section' : 'quizzes-section';
        }


        $popularTags = Tag::whereHas('quizzes', function ($q) use ($userId) {
            $q->where('author_id', $userId);
        })->withCount('quizzes')->orderByDesc('quizzes_count')->take(10)->get();

        $majors = \App\Models\Major::all();
        $courses = \App\Models\Course::all();

        return view('pages.quiz.myquiz', compact(
            'quizzes',
            'folders',
            'popularTags',
            'majors',
            'courses',
            'search',
            'filterTags',
            'filterMajor',
            'filterCourse',
            'scrollTo',
            'visibility',
            'sort'
        ));
    }

    public function create()
    {
        $quiz = new MyQuiz([
            'visibility' => 'draft',
            'access' => 'private',
            'allow_copy' => false,
            'version_number' => 1,
            'has_been_updated' => false,
        ]);

        $courses = Course::orderBy('name')->get();
        $folders = Folder::where('user_id', Auth::id())->get();
        $majors = \App\Models\Major::all();
        $formQuestions = $this->defaultFormQuestions();
        $tagsString = '';

        return view('pages.quiz.form', compact('quiz', 'courses', 'folders', 'majors', 'formQuestions', 'tagsString'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateQuiz($request);
        $isPublish = $validated['visibility'] === 'published';

        $normalizedQuestions = $this->normalizeQuestions(
            $request->input('questions', []),
            $isPublish
        );

        if ($isPublish && empty($normalizedQuestions)) {
            throw ValidationException::withMessages([
                'questions' => 'Add at least one complete question before publishing.',
            ]);
        }

        DB::transaction(function () use ($request, $validated, $normalizedQuestions) {
            $quiz = MyQuiz::create([
                'author_id' => Auth::id(),
                'folder_id' => $request->input('folder_id'),
                'title' => $validated['title'] ?? 'Untitled Quiz',
                'description' => $validated['description'] ?? null,

                'major_id' => $request->input('major_id'),
                'course_id' => $validated['course_id'] ?? null,
                'time_limit_minutes' => $validated['time_limit_minutes'] ?? null,

                'visibility' => $validated['visibility'],
                'access' => $validated['access'],
                'allow_copy' => $request->boolean('allow_copy'),

                'version_number' => 1,
                'has_been_updated' => false,
                'cover_image_url' => $this->storeCover($request),
            ]);

            $this->syncQuestions($quiz, $normalizedQuestions);
            $this->syncTags($quiz, $request->input('tags', ''));
        });

        return redirect()
            ->route('my-quizzes.index')
            ->with('success', 'Quiz created successfully.')
            ->with('clearDraftKey', 'pensquiz-quiz-draft-v5-new');
    }

    public function edit(MyQuiz $myquiz)
    {
        abort_unless($myquiz->author_id === Auth::id(), 403);

        $myquiz->load(['course', 'questions.options', 'tags']);

        $courses = Course::orderBy('name')->get();
        $folders = Folder::where('user_id', Auth::id())->get();
        $majors = \App\Models\Major::all();
        $formQuestions = $this->questionsFromQuiz($myquiz);
        $tagsString = $myquiz->tags->pluck('name')->implode(', ');

        return view('pages.quiz.form', [
            'quiz' => $myquiz,
            'courses' => $courses,
            'folders' => $folders,
            'majors' => $majors,
            'formQuestions' => $formQuestions,
            'tagsString' => $tagsString,
        ]);
    }

    public function statistics(MyQuiz $myquiz)
    {
        abort_unless($myquiz->author_id === Auth::id(), 403);

        $totalAttempts = $myquiz->attempts()->count();
        $participants = $myquiz->attempts()->distinct('user_id')->count('user_id');
        
        // Let's check how completion is stored, we will use completed_at if it exists or time_completed.
        // Assuming 'score' is present if completed, or just use attempts count if we don't have a specific column.
        // Let's use whereNotNull('score') for completed attempts.
        $completedAttempts = $myquiz->attempts()->whereNotNull('score')->count();
        
        $completionRate = $totalAttempts > 0 ? round(($completedAttempts / $totalAttempts) * 100) : 0;

        return view('pages.quiz.statistics', compact('myquiz', 'totalAttempts', 'participants', 'completionRate'));
    }

    public function update(Request $request, MyQuiz $myquiz)
    {
        abort_unless($myquiz->author_id === Auth::id(), 403);

        $validated = $this->validateQuiz($request);
        $isPublish = $validated['visibility'] === 'published';

        $normalizedQuestions = $this->normalizeQuestions(
            $request->input('questions', []),
            $isPublish
        );

        if ($isPublish && empty($normalizedQuestions)) {
            throw ValidationException::withMessages([
                'questions' => 'Add at least one complete question before publishing.',
            ]);
        }

        DB::transaction(function () use ($request, $validated, $myquiz, $normalizedQuestions) {
            $coverImageUrl = $myquiz->cover_image_url;

            if ($request->boolean('remove_cover')) {
                $coverImageUrl = null;
            }

            if ($request->hasFile('cover_image')) {
                $coverImageUrl = $this->storeCover($request);
            }

            $myquiz->update([
                'title' => $validated['title'] ?? 'Untitled Quiz',
                'description' => $validated['description'] ?? null,
                'folder_id' => $request->input('folder_id', $myquiz->folder_id),

                'major_id' => $request->input('major_id', $myquiz->major_id),
                'course_id' => $validated['course_id'] ?? $myquiz->course_id,
                'time_limit_minutes' => $validated['time_limit_minutes'] ?? $myquiz->time_limit_minutes,

                'visibility' => $validated['visibility'],
                'access' => $validated['access'],
                'allow_copy' => $request->boolean('allow_copy'),

                'has_been_updated' => true,
                'version_number' => (int) $myquiz->version_number + 1,
                'cover_image_url' => $coverImageUrl,
            ]);

            $this->syncQuestions($myquiz, $normalizedQuestions);
            $this->syncTags($myquiz, $request->input('tags', ''));
        });

        return redirect()
            ->route('my-quizzes.index')
            ->with('success', 'Quiz updated successfully.')
            ->with('clearDraftKey', 'pensquiz-quiz-draft-v5-' . $myquiz->id_quiz);
    }

    public function destroy(MyQuiz $myquiz)
    {
        abort_unless($myquiz->author_id === Auth::id(), 403);

        $myquiz->forceDelete();

        return redirect()
            ->route('my-quizzes.index')
            ->with('success', 'Quiz deleted.');
    }

    private function validateQuiz(Request $request): array
    {
        $isPublish = $request->input('visibility') === 'published';

        return $request->validate([
            'title' => [$isPublish ? 'required' : 'nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'course_id' => ['nullable', 'integer', 'exists:courses,id_course'],
            'time_limit_minutes' => ['nullable', 'integer', 'min:1'],

            'access' => ['required', 'in:public,private'],
            'visibility' => ['required', 'in:draft,published'],
            'allow_copy' => ['nullable', 'boolean'],

            'cover_image' => ['nullable', 'image', 'max:4096'],
            'remove_cover' => ['nullable', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'major_id' => [$isPublish ? 'required' : 'nullable', 'integer', 'exists:majors,id_major'],
            'folder_id' => ['nullable', 'integer', 'exists:folders,id_folder'],
        ]);
    }

    private function normalizeQuestions(array $questions, bool $strict): array
    {
        $normalized = [];

        foreach (array_values($questions) as $question) {
            $type = $question['type'] ?? 'multiple_choice';

            if (!in_array($type, ['multiple_choice', 'checkbox'], true)) {
                continue;
            }

            $content = trim((string) ($question['content'] ?? ''));

            $rawOptions = $question['options'] ?? [];
            $options = [];

            foreach (array_values($rawOptions) as $option) {
                $optionContent = trim((string) ($option['content'] ?? ''));
                if ($optionContent !== '') {
                    $options[] = [
                        'id_option' => $option['id_option'] ?? null,
                        'content' => $optionContent,
                    ];
                }
            }

            if ($content === '') {
                if ($strict) {
                    throw ValidationException::withMessages([
                        'questions' => 'Question text is required.',
                    ]);
                }
                continue;
            }

            if ($strict && count($options) < 2) {
                throw ValidationException::withMessages([
                    'questions' => 'Each question must have at least 2 options.',
                ]);
            }

            if ($type === 'multiple_choice') {
                $correctIndexes = isset($question['correct_option']) && $question['correct_option'] !== ''
                    ? [(string) $question['correct_option']]
                    : [];
            } else {
                $correctIndexes = array_values(array_filter(
                    array_map('strval', $question['correct_options'] ?? []),
                    fn ($v) => $v !== ''
                ));
            }

            if ($strict && $type === 'multiple_choice' && count($correctIndexes) !== 1) {
                throw ValidationException::withMessages([
                    'questions' => 'Multiple choice questions need exactly 1 correct option.',
                ]);
            }

            if ($strict && $type === 'checkbox' && count($correctIndexes) < 2) {
                throw ValidationException::withMessages([
                    'questions' => 'Checkbox questions need at least 2 correct options.',
                ]);
            }

            $normalized[] = [
                'id_question' => $question['id_question'] ?? null,
                'type' => $type,
                'content' => $content,
                'options' => $options,
                'correct_indexes' => $correctIndexes,
            ];
        }

        return $normalized;
    }

    private function syncQuestions(MyQuiz $quiz, array $questions): void
    {
        $keepQuestionIds = [];

        foreach (array_values($questions) as $questionIndex => $questionData) {
            $question = null;

            if (!empty($questionData['id_question'])) {
                $question = $quiz->questions()
                    ->where('id_question', $questionData['id_question'])
                    ->first();
            }

            if ($question) {
                $question->update([
                    'content' => $questionData['content'],
                    'question_type' => $questionData['type'],
                    'order_index' => $questionIndex + 1,
                ]);
            } else {
                $question = $quiz->questions()->create([
                    'content' => $questionData['content'],
                    'question_type' => $questionData['type'],
                    'order_index' => $questionIndex + 1,
                ]);
            }

            $keepQuestionIds[] = $question->id_question;

            $keepOptionIds = [];

            foreach ($questionData['options'] as $optionIndex => $optionData) {
                $option = null;

                if (!empty($optionData['id_option'])) {
                    $option = $question->options()
                        ->where('id_option', $optionData['id_option'])
                        ->first();
                }

                $isCorrect = in_array((string) $optionIndex, $questionData['correct_indexes'], true);

                if ($option) {
                    $option->update([
                        'content' => $optionData['content'],
                        'is_correct' => $isCorrect,
                        'order_index' => $optionIndex + 1,
                    ]);
                } else {
                    $option = $question->options()->create([
                        'content' => $optionData['content'],
                        'is_correct' => $isCorrect,
                        'order_index' => $optionIndex + 1,
                    ]);
                }

                $keepOptionIds[] = $option->id_option;
            }

            $question->options()
                ->whereNotIn('id_option', $keepOptionIds)
                ->delete();
        }

        $quiz->questions()
            ->whereNotIn('id_question', $keepQuestionIds)
            ->delete();
    }

    private function storeCover(Request $request): ?string
    {
        if (! $request->hasFile('cover_image')) {
            return null;
        }

        $path = $request->file('cover_image')->store('quiz-covers', 'public');

        return Storage::url($path);
    }

    private function questionsFromQuiz(MyQuiz $quiz): array
    {
        if ($quiz->questions->isEmpty()) {
            return $this->defaultFormQuestions();
        }

        return $quiz->questions->values()->map(function ($question) {
            $options = $question->options->sortBy('order_index')->values();

            $correctIndexes = $options
                ->filter(fn ($option) => (bool) $option->is_correct)
                ->keys()
                ->map(fn ($key) => (string) $key)
                ->all();

            $type = $question->question_type === 'checkbox'
                ? 'checkbox'
                : 'multiple_choice';

            return [
                'id_question' => $question->id_question,
                'type' => $type,
                'content' => $question->content,
                'correct_option' => $correctIndexes[0] ?? '0',
                'correct_options' => $correctIndexes,
                'options' => $options->map(fn ($option) => [
                    'id_option' => $option->id_option,
                    'content' => $option->content,
                ])->all(),
            ];
        })->all();
    }

    private function defaultFormQuestions(): array
    {
        return [
            [
                'type' => 'multiple_choice',
                'content' => '',
                'correct_option' => '0',
                'correct_options' => [],
                'options' => [
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                ],
            ],
            [
                'type' => 'checkbox',
                'content' => '',
                'correct_option' => '0',
                'correct_options' => [],
                'options' => [
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                    ['content' => ''],
                ],
            ],
        ];
    }

    private function syncTags(MyQuiz $quiz, $tagsInput): void
    {
        if (is_string($tagsInput)) {
            $tags = collect(explode(',', $tagsInput))
                ->map(fn($tag) => trim($tag))
                ->filter(fn($tag) => $tag !== '')
                ->unique();
        } else {
            $tags = collect($tagsInput ?? [])
                ->map(fn($tag) => trim((string)$tag))
                ->filter(fn($tag) => $tag !== '')
                ->unique();
        }

        $tagIds = $tags->map(function ($tagName) {
            return Tag::firstOrCreate(['name' => $tagName])->id_tag;
        });

        $quiz->tags()->sync($tagIds);
    }
}