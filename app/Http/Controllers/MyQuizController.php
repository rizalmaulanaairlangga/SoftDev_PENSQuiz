<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Course;
use App\Models\MyQuiz;
use App\Models\Question;
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

        $query = MyQuiz::where('author_id', $userId)
            ->with('course')
            ->withCount(['questions', 'attempts']);

        if ($visibility !== 'all') {
            $query->where('visibility', $visibility);
        }

        if ($sort === 'latest') {
            $query->orderByDesc('created_at');
        } elseif ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        }

        $quizzes = $query->get();

        $totalQuizzes = MyQuiz::where('author_id', $userId)->count();

        $totalQuestions = Question::whereHas('quiz', function ($q) use ($userId) {
            $q->where('author_id', $userId);
        })->count();

        $totalAttempts = Attempt::whereHas('quiz', function ($q) use ($userId) {
            $q->where('author_id', $userId);
        })->count();

        $completionRate = $totalAttempts > 0 ? 100 : 0;

        return view('pages.quiz.myquiz', compact(
            'quizzes',
            'totalQuizzes',
            'totalQuestions',
            'totalAttempts',
            'completionRate'
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
        $formQuestions = $this->defaultFormQuestions();

        return view('pages.quiz.form', compact('quiz', 'courses', 'formQuestions'));
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
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,

                'major_id' => $request->input('major_id'),
                'course_id' => $validated['course_id'] ?? null,
                'lecturer_id' => $request->input('lecturer_id'),
                'academic_year_id' => $request->input('academic_year_id'),
                'class_id' => $request->input('class_id'),

                'semester' => $validated['semester'] ?? null,
                'visibility' => $validated['visibility'],
                'access' => $validated['access'],
                'allow_copy' => $request->boolean('allow_copy'),

                'version_number' => 1,
                'has_been_updated' => false,
                'cover_image_url' => $this->storeCover($request),
            ]);

            $this->syncQuestions($quiz, $normalizedQuestions);
        });

        return redirect()
            ->route('my-quizzes.index')
            ->with('success', 'Quiz created successfully.')
            ->with('clearDraftKey', 'pensquiz-quiz-draft-v5-new');
    }

    public function edit(MyQuiz $myquiz)
    {
        abort_unless($myquiz->author_id === Auth::id(), 403);

        $myquiz->load(['course', 'questions.options']);

        $courses = Course::orderBy('name')->get();
        $formQuestions = $this->questionsFromQuiz($myquiz);

        return view('pages.quiz.form', [
            'quiz' => $myquiz,
            'courses' => $courses,
            'formQuestions' => $formQuestions,
        ]);
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
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,

                'major_id' => $request->input('major_id', $myquiz->major_id),
                'course_id' => $validated['course_id'] ?? $myquiz->course_id,
                'lecturer_id' => $request->input('lecturer_id', $myquiz->lecturer_id),
                'academic_year_id' => $request->input('academic_year_id', $myquiz->academic_year_id),
                'class_id' => $request->input('class_id', $myquiz->class_id),

                'semester' => $validated['semester'] ?? $myquiz->semester,
                'visibility' => $validated['visibility'],
                'access' => $validated['access'],
                'allow_copy' => $request->boolean('allow_copy'),

                'has_been_updated' => true,
                'version_number' => (int) $myquiz->version_number + 1,
                'cover_image_url' => $coverImageUrl,
            ]);

            $this->syncQuestions($myquiz, $normalizedQuestions);
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
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'course_id' => ['nullable', 'integer', 'exists:courses,id_course'],
            'semester' => ['nullable', 'integer', 'min:1', 'max:14'],

            'access' => ['required', 'in:public,private'],
            'visibility' => ['required', 'in:draft,published'],
            'allow_copy' => ['nullable', 'boolean'],

            'cover_image' => ['nullable', 'image', 'max:4096'],
            'remove_cover' => ['nullable', 'boolean'],
        ]);
    }

    private function normalizeQuestions(array $questions, bool $strict): array
    {
        $normalized = [];

        foreach (array_values($questions) as $question) {
            $type = $question['type'] ?? 'single_answer';

            if (!in_array($type, ['single_answer', 'multiple_answer'], true)) {
                continue;
            }

            $content = trim((string) ($question['content'] ?? ''));
            $explanation = trim((string) ($question['explanation'] ?? ''));

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

            if ($type === 'single_answer') {
                $correctIndexes = isset($question['correct_option']) && $question['correct_option'] !== ''
                    ? [(string) $question['correct_option']]
                    : [];
            } else {
                $correctIndexes = array_values(array_filter(
                    array_map('strval', $question['correct_options'] ?? []),
                    fn ($v) => $v !== ''
                ));
            }

            if ($strict && $type === 'single_answer' && count($correctIndexes) !== 1) {
                throw ValidationException::withMessages([
                    'questions' => 'Single answer questions need exactly 1 correct option.',
                ]);
            }

            if ($strict && $type === 'multiple_answer' && count($correctIndexes) < 2) {
                throw ValidationException::withMessages([
                    'questions' => 'Multiple answer questions need at least 2 correct options.',
                ]);
            }

            $normalized[] = [
                'id_question' => $question['id_question'] ?? null,
                'type' => $type,
                'content' => $content,
                'explanation' => $explanation !== '' ? $explanation : null,
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
                    'explanation' => $questionData['explanation'],
                ]);
            } else {
                $question = $quiz->questions()->create([
                    'content' => $questionData['content'],
                    'question_type' => $questionData['type'],
                    'order_index' => $questionIndex + 1,
                    'explanation' => $questionData['explanation'],
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

            $type = $question->question_type === 'multiple_answer'
                ? 'multiple_answer'
                : 'single_answer';

            return [
                'id_question' => $question->id_question,
                'type' => $type,
                'content' => $question->content,
                'explanation' => $question->explanation,
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
                'type' => 'single_answer',
                'content' => '',
                'explanation' => '',
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
                'type' => 'multiple_answer',
                'content' => '',
                'explanation' => '',
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
}