<x-app-layout>

@php
    $isEdit = $quiz->exists;

    $questionsData = old('questions');
    if (!is_array($questionsData) || empty($questionsData)) {
        $questionsData = $formQuestions;
    }

    $selectedVisibility = old('visibility', $quiz->visibility ?? 'draft');
    $selectedAccess = old('access', $quiz->access ?? 'private');
    $selectedAllowCopy = old('allow_copy', $quiz->allow_copy ?? false);

    $draftKey = 'pensquiz-quiz-draft-v5-' . ($quiz->exists ? $quiz->id_quiz : 'new');

    $originalQuizState = [
        'title' => $quiz->title ?? '',
        'description' => $quiz->description ?? '',
        'course_id' => $quiz->course_id ?? '',
        'semester' => $quiz->semester ?? '',
        'access' => $quiz->access ?? 'private',
        'visibility' => $quiz->visibility ?? 'draft',
        'allow_copy' => (bool) ($quiz->allow_copy ?? false),
        'cover_image_url' => $quiz->cover_image_url ?? null,
        'questions' => $formQuestions,
    ];
@endphp

<div class="min-h-screen bg-[#f5f5f5] px-4 py-6 lg:px-6">
    <div class="mx-auto max-w-7xl space-y-6">

        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                Please check the form before continuing.
            </div>
        @endif

        <div id="clientErrorBox" class="hidden rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul id="clientErrorList" class="list-disc space-y-1 pl-5"></ul>
        </div>

        <form
            id="quizForm"
            action="{{ $isEdit ? route('my-quizzes.update', $quiz) : route('my-quizzes.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <input type="hidden" name="visibility" id="visibilityInput" value="{{ $selectedVisibility }}">
            <input type="hidden" name="remove_cover" id="removeCoverInput" value="0">

            <!-- COVER -->
            <section class="rounded-[18px] border border-gray-300 bg-[#e8e8e8] p-4 shadow-sm">
                <div class="flex min-h-[220px] items-center justify-center overflow-hidden rounded-[16px] border border-gray-300 bg-[#efefef]">
                    @if(!empty($quiz->cover_image_url))
                        <img id="coverPreview" src="{{ $quiz->cover_image_url }}" class="h-full w-full object-cover" alt="Quiz cover">
                        <span id="coverPlaceholder" class="hidden text-gray-400">[Cover]</span>
                    @else
                        <img id="coverPreview" src="" class="hidden h-full w-full object-cover" alt="Quiz cover">
                        <span id="coverPlaceholder" class="text-gray-400">[Cover]</span>
                    @endif
                </div>

                <div class="mt-4 flex flex-wrap justify-center gap-3">
                    <button type="button" onclick="document.getElementById('coverInput').click()"
                        class="cursor-pointer rounded-xl border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]">
                        Change Cover
                    </button>
                    <button type="button" onclick="document.getElementById('coverInput').click()"
                        class="cursor-pointer rounded-xl border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]">
                        Edit Cover
                    </button>
                    <button type="button" onclick="removeCover()"
                        class="cursor-pointer rounded-xl border border-gray-400 bg-white px-5 py-2 text-sm font-medium text-gray-700 transition hover:bg-red-50 hover:text-red-600 hover:shadow-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400">
                        Remove Cover
                    </button>
                </div>

                <input type="file" name="cover_image" id="coverInput" class="hidden" accept="image/*">
            </section>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.55fr)_minmax(340px,0.95fr)]">
                <!-- LEFT -->
                <section class="space-y-4">
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">Make Quizzes</h1>
                        <p class="mt-2 text-sm text-gray-500">Fill in the quiz details, add questions, then publish.</p>
                    </div>

                    <div class="rounded-[20px] border border-gray-300 bg-white p-5 shadow-sm">
                        <div class="grid gap-4">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800">Quiz Title</label>
                                <input
                                    id="quizTitleInput"
                                    name="title"
                                    type="text"
                                    value="{{ old('title', $quiz->title) }}"
                                    placeholder="Pemrograman Dasar - Pertemuan 2"
                                    class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                >
                                @error('title') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-gray-800">Description</label>
                                <textarea
                                    id="quizDescriptionInput"
                                    name="description"
                                    rows="3"
                                    placeholder="This quiz covers the material..."
                                    class="w-full resize-none rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                >{{ old('description', $quiz->description) }}</textarea>
                                @error('description') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-800">Course</label>
                                    <select
                                        id="courseSelect"
                                        name="course_id"
                                        class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                    >
                                        <option value="">Select course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id_course }}" @selected((string) old('course_id', $quiz->course_id) === (string) $course->id_course)>
                                                {{ $course->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-800">Semester</label>
                                    <input
                                        id="semesterInput"
                                        type="number"
                                        name="semester"
                                        min="1"
                                        max="14"
                                        value="{{ old('semester', $quiz->semester) }}"
                                        placeholder="4"
                                        class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                    >
                                    @error('semester') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-gray-800">Access</label>
                                    <select
                                        id="accessSelect"
                                        name="access"
                                        class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                    >
                                        <option value="private" @selected($selectedAccess === 'private')>Private</option>
                                        <option value="public" @selected($selectedAccess === 'public')>Public</option>
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <label class="flex w-full cursor-pointer items-center justify-between rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-800 transition hover:bg-gray-50">
                                        <span>Allow Copy</span>
                                        <input
                                            id="allowCopyInput"
                                            type="checkbox"
                                            name="allow_copy"
                                            value="1"
                                            class="h-4 w-4 cursor-pointer rounded border-gray-300 text-[#104876] focus:ring-[#104876]"
                                            @checked((bool) $selectedAllowCopy)
                                        >
                                    </label>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-300 pt-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-700">List of Questions</span>
                                        <span id="questionCountBadge" class="rounded-md border border-orange-200 bg-orange-50 px-2 py-1 text-xs font-semibold text-orange-500">
                                            {{ count($questionsData) }} Questions
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        onclick="addQuestionTop()"
                                        class="cursor-pointer rounded-xl bg-[#104876] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1a5a8a] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]/30 active:scale-[0.98]"
                                    >
                                        + Add Questions
                                    </button>
                                </div>
                            </div>

                            <div id="questionsList" class="space-y-4">
                                @foreach($questionsData as $index => $question)
                                    @php
                                        $questionType = old("questions.$index.type", $question['type'] ?? 'single_answer');
                                        $questionContent = old("questions.$index.content", $question['content'] ?? '');
                                        $questionExplanation = old("questions.$index.explanation", $question['explanation'] ?? '');
                                        $correctOption = old("questions.$index.correct_option", $question['correct_option'] ?? '0');
                                        $correctOptions = old("questions.$index.correct_options", $question['correct_options'] ?? []);
                                        $options = old("questions.$index.options", $question['options'] ?? []);

                                        if (!is_array($options) || empty($options)) {
                                            $options = [
                                                ['content' => ''],
                                                ['content' => ''],
                                                ['content' => ''],
                                                ['content' => ''],
                                            ];
                                        }
                                    @endphp

                                    <div class="question-card rounded-[18px] border border-gray-300 bg-white p-4 shadow-sm transition hover:shadow-md" data-question-card data-question-index="{{ $index }}">
                                        <input type="hidden" name="questions[{{ $index }}][id_question]" value="{{ $question['id_question'] ?? '' }}">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3">
                                                <span data-question-number class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#104876] text-sm font-bold text-white">
                                                    {{ $loop->iteration }}
                                                </span>

                                                <select
                                                    name="questions[{{ $index }}][type]"
                                                    class="question-type-select cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                                    onchange="toggleQuestionType(this)"
                                                >
                                                    <option value="single_answer" @selected($questionType === 'single_answer')>Single Answer</option>
                                                    <option value="multiple_answer" @selected($questionType === 'multiple_answer')>Multiple Answer</option>
                                                </select>
                                            </div>

                                            <button
                                                type="button"
                                                onclick="removeQuestion(this)"
                                                class="cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400"
                                            >
                                                Delete
                                            </button>
                                        </div>

                                        <div class="mt-4 space-y-4">
                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-gray-800">Question</label>
                                                <textarea
                                                    name="questions[{{ $index }}][content]"
                                                    rows="3"
                                                    placeholder="Write the question here..."
                                                    class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                                >{{ $questionContent }}</textarea>
                                                @error("questions.$index.content") <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                                            </div>

                                            <div>
                                                <label class="mb-2 block text-sm font-medium text-gray-800">Explanation / Rubric</label>
                                                <textarea
                                                    name="questions[{{ $index }}][explanation]"
                                                    rows="2"
                                                    placeholder="Optional explanation..."
                                                    class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                                >{{ $questionExplanation }}</textarea>
                                            </div>

                                            <div data-options-wrapper class="space-y-3 {{ $questionType === 'multiple_answer' ? '' : '' }}">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-700">
                                                        {{ $questionType === 'multiple_answer' ? 'Select all correct answers' : 'Choose one correct answer' }}
                                                    </span>

                                                    <button
                                                        type="button"
                                                        onclick="addOption(this)"
                                                        class="cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]/20"
                                                    >
                                                        + Option
                                                    </button>
                                                </div>

                                                <div data-options-list class="space-y-2">
                                                    @foreach($options as $oIndex => $option)
                                                        <div class="option-row flex items-start gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-3">
                                                            <input type="hidden" name="questions[{{ $index }}][options][{{ $oIndex }}][id_option]" value="{{ $option['id_option'] ?? '' }}">
                                                            <div data-correct-control class="mt-1">
                                                                @if($questionType === 'multiple_answer')
                                                                    <input
                                                                        type="checkbox"
                                                                        name="questions[{{ $index }}][correct_options][]"
                                                                        value="{{ $oIndex }}"
                                                                        class="h-4 w-4 cursor-pointer text-[#104876] focus:ring-[#104876]"
                                                                        @checked(in_array((string) $oIndex, array_map('strval', (array) $correctOptions), true))
                                                                    >
                                                                @else
                                                                    <input
                                                                        type="radio"
                                                                        name="questions[{{ $index }}][correct_option]"
                                                                        value="{{ $oIndex }}"
                                                                        class="h-4 w-4 cursor-pointer text-[#104876] focus:ring-[#104876]"
                                                                        @checked((string) $correctOption === (string) $oIndex)
                                                                    >
                                                                @endif
                                                            </div>

                                                            <input
                                                                type="text"
                                                                name="questions[{{ $index }}][options][{{ $oIndex }}][content]"
                                                                value="{{ $option['content'] ?? '' }}"
                                                                placeholder="Option {{ $oIndex + 1 }}"
                                                                class="min-w-0 flex-1 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                                            >

                                                            <button
                                                                type="button"
                                                                onclick="removeOption(this)"
                                                                class="cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400"
                                                            >
                                                                ×
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <!-- RIGHT -->
                <aside class="space-y-4 lg:sticky lg:top-6">
                    <div class="rounded-[20px] border border-gray-300 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <h2 class="text-base font-semibold text-gray-900">Quiz Summary</h2>
                            <span class="rounded-md border border-orange-200 bg-orange-50 px-2 py-1 text-xs font-semibold text-orange-500">Live Update</span>
                        </div>

                        <div class="my-4 border-t border-dashed border-gray-300"></div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between gap-3 border-b border-dashed border-gray-200 py-2">
                                <span class="text-gray-700">Quiz Title</span>
                                <span id="summaryTitle" class="max-w-[55%] truncate font-medium text-gray-900">{{ old('title', $quiz->title) ?: '—' }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-b border-dashed border-gray-200 py-2">
                                <span class="text-gray-700">Course</span>
                                <span id="summaryCourse" class="max-w-[55%] truncate font-medium text-gray-900">
                                    {{ $quiz->course->name ?? 'No course' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-b border-dashed border-gray-200 py-2">
                                <span class="text-gray-700">Semester</span>
                                <span id="summarySemester" class="font-medium text-gray-900">{{ old('semester', $quiz->semester) ?: '—' }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-b border-dashed border-gray-200 py-2">
                                <span class="text-gray-700">Access</span>
                                <span id="summaryAccess" class="font-medium text-gray-900">{{ ucfirst($selectedAccess) }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 border-b border-dashed border-gray-200 py-2">
                                <span class="text-gray-700">Visibility</span>
                                <span id="summaryVisibility" class="font-medium text-gray-900">{{ ucfirst($selectedVisibility) }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-3 py-2">
                                <span class="text-gray-700">Allow Copy</span>
                                <span id="summaryAllowCopy" class="font-medium text-gray-900">{{ $selectedAllowCopy ? 'Enabled' : 'Disabled' }}</span>
                            </div>
                        </div>

                        <div class="mt-4 rounded-[18px] border border-gray-300 bg-white p-4 text-center shadow-sm">
                            <div id="summaryQuestionCount" class="text-4xl font-extrabold text-gray-900">{{ count($questionsData) }}</div>
                            <div class="mt-1 text-xs text-gray-500">Number of Questions</div>
                        </div>
                    </div>

                        <button
                            type="button"
                            onclick="submitQuizForm('published')"
                            class="w-full cursor-pointer rounded-[18px] border border-gray-300 bg-[#104876] px-5 py-4 text-base font-semibold text-white shadow-sm transition hover:bg-[#1a5a8a] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]/30 active:scale-[0.99]"
                        >
                            {{ $isEdit ? 'Update & Publish Quiz' : 'Publish Quiz' }}
                        </button>

                        <button
                            type="button"
                            onclick="submitQuizForm('draft')"
                            class="w-full cursor-pointer rounded-[18px] border border-gray-300 bg-white px-5 py-4 text-base font-semibold text-gray-900 shadow-sm transition hover:bg-gray-50 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-300 active:scale-[0.99]"
                        >
                            {{ $isEdit ? 'Update as Draft' : 'Save as Draft' }}
                        </button>

                        @if($quiz->exists)
                            <button
                                type="button"
                                onclick="discardChanges()"
                                class="w-full cursor-pointer rounded-[18px] border border-red-200 bg-white px-5 py-4 text-base font-semibold text-red-600 shadow-sm transition hover:bg-red-50 hover:shadow-md"
                            >
                                Discard Changes
                            </button>

                            <button
                                type="submit"
                                form="deleteQuizForm"
                                class="w-full cursor-pointer rounded-[18px] border border-red-200 bg-white px-5 py-4 text-base font-semibold text-red-600 shadow-sm transition hover:bg-red-50 hover:shadow-md"
                            >
                                Delete Quiz
                            </button>
                        @else
                            <button
                                type="button"
                                onclick="resetCreateForm()"
                                class="w-full cursor-pointer rounded-[18px] border border-red-200 bg-white px-5 py-4 text-base font-semibold text-red-600 shadow-sm transition hover:bg-red-50 hover:shadow-md"
                            >
                                Discard Quiz
                            </button>
                        @endif
                </aside>
            </div>

            <template id="questionTemplate">
                <div class="question-card rounded-[18px] border border-gray-300 bg-white p-4 shadow-sm transition hover:shadow-md" data-question-card data-question-index="__INDEX__">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span data-question-number class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#104876] text-sm font-bold text-white">__NUM__</span>

                            <select
                                name="questions[__INDEX__][type]"
                                class="question-type-select cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                                onchange="toggleQuestionType(this)"
                            >
                                <option value="single_answer">Single Answer</option>
                                <option value="multiple_answer">Multiple Answer</option>
                            </select>
                        </div>

                        <button
                            type="button"
                            onclick="removeQuestion(this)"
                            class="cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400"
                        >
                            Delete
                        </button>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-800">Question</label>
                            <textarea
                                name="questions[__INDEX__][content]"
                                rows="3"
                                placeholder="Write the question here..."
                                class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                            ></textarea>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-800">Explanation / Rubric</label>
                            <textarea
                                name="questions[__INDEX__][explanation]"
                                rows="2"
                                placeholder="Optional explanation..."
                                class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                            ></textarea>
                        </div>

                        <div data-options-wrapper class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-700">Choose one correct answer</span>
                                <button
                                    type="button"
                                    onclick="addOption(this)"
                                    class="cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]/20"
                                >
                                    + Option
                                </button>
                            </div>

                            <div data-options-list class="space-y-2">
                                ${buildOptionRow('__INDEX__', 0, 'single_answer')}
                                ${buildOptionRow('__INDEX__', 1, 'single_answer')}
                                ${buildOptionRow('__INDEX__', 2, 'single_answer')}
                                ${buildOptionRow('__INDEX__', 3, 'single_answer')}
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </form>

<!-- FORM DELETE DI LUAR -->
@if($quiz->exists)
    <form 
        id="deleteQuizForm"
        action="{{ route('my-quizzes.destroy', $quiz) }}" 
        method="POST"
        onsubmit="clearAllQuizDrafts(); return confirm('Delete this quiz permanently?');"
    >
        @csrf
        @method('DELETE')
    </form>
@endif
    </div>
</div>

<div id="leaveModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-5 shadow-xl">
        <h3 id="leaveModalTitle" class="text-lg font-bold text-gray-900">Leave this quiz form?</h3>
        <p id="leaveModalText" class="mt-2 text-sm text-gray-600">
            You have unsaved changes. Save them as draft, discard them, or stay on this page.
        </p>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <button
                type="button"
                onclick="saveDraftThenLeave()"
                class="rounded-xl bg-[#104876] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#1a5a8a] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]/30"
            >
                Save Draft
            </button>

            <button
                type="button"
                onclick="proceedLeaveWithoutSave()"
                class="rounded-xl border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-300"
            >
                Discard
            </button>

            <button
                type="button"
                onclick="closeLeaveModal()"
                class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-800 transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-300"
            >
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    const isEdit = @json($isEdit);
    const draftKey = @json($draftKey);
    const hasServerErrors = @json($errors->any());
    const originalQuizState = @json($originalQuizState);

    const quizForm = document.getElementById('quizForm');
    const questionsList = document.getElementById('questionsList');
    const leaveModal = document.getElementById('leaveModal');
    const leaveModalText = document.getElementById('leaveModalText');

    let questionIndex = getNextQuestionIndex();
    let saveTimer = null;
    let dirty = false;
    let isSubmitting = false;
    let pendingNavigationUrl = null;
    let skipDraftSave = false;

    function clearAllQuizDrafts() {
        Object.keys(localStorage).forEach((key) => {
            if (key.startsWith('pensquiz-quiz-draft-v5-')) {
                localStorage.removeItem(key);
            }
        });
    }

    function clearLegacyDrafts() {
        Object.keys(localStorage).forEach((key) => {
            if (key.startsWith('pensquiz-quiz-draft-v3-') || key.startsWith('pensquiz-quiz-draft-v4-')) {
                localStorage.removeItem(key);
            }
        });
    }

    function clearDraftState() {
        localStorage.removeItem(draftKey);
    }

    function openLeaveModal(url) {
        pendingNavigationUrl = url;

        if (leaveModalText) {
            leaveModalText.textContent = isEdit
                ? 'You have unsaved changes. Save them as draft, discard the changes, or stay on this page.'
                : 'You have unsaved progress. Save it as draft, discard it, or stay on this page.';
        }

        if (leaveModal) {
            leaveModal.classList.remove('hidden');
            leaveModal.classList.add('flex');
        }
    }

    function closeLeaveModal() {
        pendingNavigationUrl = null;

        if (leaveModal) {
            leaveModal.classList.add('hidden');
            leaveModal.classList.remove('flex');
        }
    }

    function applyCoverPreview(url) {
        const preview = document.getElementById('coverPreview');
        const placeholder = document.getElementById('coverPlaceholder');

        if (!preview || !placeholder) return;

        if (url) {
            preview.src = url;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    function applyQuizState(state) {
        if (!state) return;

        const titleInput = document.getElementById('quizTitleInput');
        const descriptionInput = document.getElementById('quizDescriptionInput');
        const courseSelect = document.getElementById('courseSelect');
        const semesterInput = document.getElementById('semesterInput');
        const accessSelect = document.getElementById('accessSelect');
        const visibilityInput = document.getElementById('visibilityInput');
        const allowCopyInput = document.getElementById('allowCopyInput');
        const removeCoverInput = document.getElementById('removeCoverInput');
        const list = document.getElementById('questionsList');

        if (titleInput && state.title !== undefined) titleInput.value = state.title ?? '';
        if (descriptionInput && state.description !== undefined) descriptionInput.value = state.description ?? '';
        if (courseSelect && state.course_id !== undefined) courseSelect.value = state.course_id ?? '';
        if (semesterInput && state.semester !== undefined) semesterInput.value = state.semester ?? '';
        if (accessSelect && state.access !== undefined) accessSelect.value = state.access ?? 'private';
        if (visibilityInput && state.visibility !== undefined) visibilityInput.value = state.visibility ?? 'draft';
        if (allowCopyInput && state.allow_copy !== undefined) allowCopyInput.checked = !!state.allow_copy;
        if (removeCoverInput) removeCoverInput.value = '0';
        if (state.cover_image_url !== undefined) applyCoverPreview(state.cover_image_url);

        if (list && Array.isArray(state.questions)) {
            list.innerHTML = state.questions.map((question, index) => buildQuestionCard(index, question)).join('');
        }

        updateQuestionNumbers();
        updateSummary();
        questionIndex = getNextQuestionIndex();
    }

    function resetCreateForm() {
        if (!confirm('Discard all progress?')) return;

        skipDraftSave = true;
        clearAllQuizDrafts();
        dirty = false;
        window.location.href = @json(route('my-quizzes.create'));
    }

    function discardChanges() {
        if (isEdit) {
            if (!confirm('Discard all changes and restore the quiz to the saved database version?')) return;

            skipDraftSave = true;
            clearDraftState();
            dirty = false;
            applyQuizState(originalQuizState);
            updateSummary();
            return;
        }

        resetCreateForm();
    }

    function proceedLeaveWithoutSave() {
        if (!pendingNavigationUrl) return;

        clearDraftState();
        dirty = false;
        window.location.href = pendingNavigationUrl;
    }

    function saveDraftThenLeave() {
        if (!pendingNavigationUrl) return;

        submitQuizForm('draft');
    }

    function setVisibility(value) {
        const visibilityInput = document.getElementById('visibilityInput');
        if (visibilityInput) {
            visibilityInput.value = value;
        }

        updateSummary();
        markDirty();
    }

    function removeCover() {
        const removeCoverInput = document.getElementById('removeCoverInput');
        if (removeCoverInput) {
            removeCoverInput.value = '1';
        }

        applyCoverPreview('');
        markDirty();
        updateSummary();
    }

    document.getElementById('coverInput')?.addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('coverPreview');
        const placeholder = document.getElementById('coverPlaceholder');
        const reader = new FileReader();

        reader.onload = function (e) {
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }

            if (placeholder) {
                placeholder.classList.add('hidden');
            }

            const removeCoverInput = document.getElementById('removeCoverInput');
            if (removeCoverInput) {
                removeCoverInput.value = '0';
            }

            markDirty();
            updateSummary();
        };

        reader.readAsDataURL(file);
    });

    function buildOptionRow(questionIndexValue, optionIndex, questionType, optionData = {}) {
        const inputType = questionType === 'multiple_answer' ? 'checkbox' : 'radio';
        const inputName = questionType === 'multiple_answer'
            ? `questions[${questionIndexValue}][correct_options][]`
            : `questions[${questionIndexValue}][correct_option]`;

        const content = optionData.content ?? '';
        const checked = optionData.checked ?? (optionIndex === 0 && questionType === 'single_answer');
        const optionId = optionData.id_option ?? '';

        return `
            <div class="option-row flex items-start gap-3 rounded-2xl border border-gray-200 bg-gray-50 p-3">
                <input type="hidden" name="questions[${questionIndexValue}][options][${optionIndex}][id_option]" value="${escapeHtml(optionId)}">

                <div data-correct-control class="mt-1">
                    <input
                        type="${inputType}"
                        name="${inputName}"
                        value="${optionIndex}"
                        class="h-4 w-4 cursor-pointer text-[#104876] focus:ring-[#104876]"
                        ${checked ? 'checked' : ''}
                    >
                </div>

                <input
                    type="text"
                    name="questions[${questionIndexValue}][options][${optionIndex}][content]"
                    value="${escapeHtml(content)}"
                    placeholder="Option ${optionIndex + 1}"
                    class="min-w-0 flex-1 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                >

                <button
                    type="button"
                    onclick="removeOption(this)"
                    class="cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400"
                >
                    ×
                </button>
            </div>
        `;
    }

    function buildQuestionCard(index, data = null) {
        const type = data?.type || 'single_answer';
        const questionId = data?.id_question || '';
        const content = data?.content || '';
        const explanation = data?.explanation || '';
        const options = Array.isArray(data?.options) && data.options.length
            ? data.options
            : [
                { content: '' },
                { content: '' },
                { content: '' },
                { content: '' },
            ];

        const correctOption = data?.correct_option ?? '0';
        const correctOptions = Array.isArray(data?.correct_options) ? data.correct_options.map(String) : [];

        const optionRows = options.map((option, oIndex) => {
            const checked = type === 'multiple_answer'
                ? correctOptions.includes(String(oIndex))
                : String(correctOption) === String(oIndex);

            return buildOptionRow(index, oIndex, type, {
                id_option: option.id_option ?? '',
                content: option.content ?? '',
                checked: checked,
            });
        }).join('');

        return `
            <div class="question-card rounded-[18px] border border-gray-300 bg-white p-4 shadow-sm transition hover:shadow-md" data-question-card data-question-index="${index}">
                <input type="hidden" name="questions[${index}][id_question]" value="${escapeHtml(questionId)}">

                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span data-question-number class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#104876] text-sm font-bold text-white">${index + 1}</span>

                        <select
                            name="questions[${index}][type]"
                            class="question-type-select cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                            onchange="toggleQuestionType(this)"
                        >
                            <option value="single_answer" ${type === 'single_answer' ? 'selected' : ''}>Single Answer</option>
                            <option value="multiple_answer" ${type === 'multiple_answer' ? 'selected' : ''}>Multiple Answer</option>
                        </select>
                    </div>

                    <button
                        type="button"
                        onclick="removeQuestion(this)"
                        class="cursor-pointer rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-red-50 hover:text-red-600 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-400"
                    >
                        Delete
                    </button>
                </div>

                <div class="mt-4 space-y-4">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-800">Question</label>
                        <textarea
                            name="questions[${index}][content]"
                            rows="3"
                            placeholder="Write the question here..."
                            class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                        >${escapeHtml(content)}</textarea>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-800">Explanation / Rubric</label>
                        <textarea
                            name="questions[${index}][explanation]"
                            rows="2"
                            placeholder="Optional explanation..."
                            class="w-full rounded-2xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder:text-gray-400 transition focus:border-[#104876] focus:ring-2 focus:ring-[#104876]/20 focus:outline-none"
                        >${escapeHtml(explanation)}</textarea>
                    </div>

                    <div data-options-wrapper class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wide text-gray-700">
                                ${type === 'multiple_answer' ? 'Select all correct answers' : 'Choose one correct answer'}
                            </span>

                            <button
                                type="button"
                                onclick="addOption(this)"
                                class="cursor-pointer rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#104876]/20"
                            >
                                + Option
                            </button>
                        </div>

                        <div data-options-list class="space-y-2">
                            ${optionRows}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    function addQuestionTop() {
        questionsList.insertAdjacentHTML('afterbegin', buildQuestionCard(questionIndex));
        questionIndex++;
        updateQuestionNumbers();
        updateSummary();
        markDirty();
        saveDraftState();
    }

    function removeQuestion(button) {
        const card = button.closest('[data-question-card]');
        if (card) {
            card.remove();
        }

        reindexQuestions();
        updateQuestionNumbers();
        updateSummary();
        markDirty();
        saveDraftState();
    }

    function addOption(button) {
        const card = button.closest('[data-question-card]');
        if (!card) return;

        const qIndex = card.dataset.questionIndex;
        const list = card.querySelector('[data-options-list]');
        const optionIndex = list.querySelectorAll('.option-row').length;
        const type = card.querySelector('.question-type-select')?.value || 'single_answer';

        list.insertAdjacentHTML('beforeend', buildOptionRow(qIndex, optionIndex, type));
        updateSummary();
        markDirty();
        saveDraftState();
    }

    function removeOption(button) {
        const row = button.closest('.option-row');
        if (row) {
            row.remove();
        }

        reindexQuestions();
        updateSummary();
        markDirty();
        saveDraftState();
    }

    function syncOptionControls(card) {
        if (!card) return;

        const type = card.querySelector('.question-type-select')?.value || 'single_answer';
        const qIndex = card.dataset.questionIndex;
        const rows = Array.from(card.querySelectorAll('.option-row'));

        const checkedIndices = rows
            .map((row, idx) => {
                const currentInput = row.querySelector('input[type="radio"], input[type="checkbox"]');
                return currentInput?.checked ? idx : null;
            })
            .filter((value) => value !== null);

        rows.forEach((row, idx) => {
            const currentControl = row.querySelector('[data-correct-control]');
            if (!currentControl) return;

            currentControl.innerHTML = '';

            const input = document.createElement('input');
            input.type = type === 'multiple_answer' ? 'checkbox' : 'radio';
            input.name = type === 'multiple_answer'
                ? `questions[${qIndex}][correct_options][]`
                : `questions[${qIndex}][correct_option]`;
            input.value = idx;
            input.className = 'h-4 w-4 cursor-pointer text-[#104876] focus:ring-[#104876]';

            if (type === 'multiple_answer') {
                input.checked = checkedIndices.includes(idx);
            } else {
                input.checked = (checkedIndices[0] ?? 0) === idx;
            }

            currentControl.appendChild(input);
        });

        const label = card.querySelector('[data-options-wrapper] span');
        if (label) {
            label.textContent = type === 'multiple_answer'
                ? 'Select all correct answers'
                : 'Choose one correct answer';
        }
    }

    function toggleQuestionType(select) {
        const card = select.closest('[data-question-card]');
        syncOptionControls(card);
        updateSummary();
        markDirty();
        saveDraftState();
    }

    function reindexQuestions() {
        const cards = document.querySelectorAll('[data-question-card]');

        cards.forEach((card, qIndex) => {
            card.dataset.questionIndex = qIndex;

            card.querySelectorAll('[name]').forEach((el) => {
                el.name = el.name.replace(/questions\[\d+\]/, `questions[${qIndex}]`);
            });

            const optionRows = card.querySelectorAll('.option-row');
            optionRows.forEach((row, oIndex) => {
                row.querySelectorAll('[name]').forEach((el) => {
                    el.name = el.name
                        .replace(/questions\[\d+\]/, `questions[${qIndex}]`)
                        .replace(/options\[\d+\]/, `options[${oIndex}]`);
                });

                const input = row.querySelector('input[type="radio"], input[type="checkbox"]');
                if (input) input.value = oIndex;
            });
        });
    }

    function updateQuestionNumbers() {
        const cards = document.querySelectorAll('[data-question-card]');
        cards.forEach((card, idx) => {
            card.dataset.questionIndex = idx;
            const number = card.querySelector('[data-question-number]');
            if (number) {
                number.textContent = idx + 1;
            }
        });

        const count = cards.length;
        const badge = document.getElementById('questionCountBadge');
        const summaryCount = document.getElementById('summaryQuestionCount');

        if (badge) badge.textContent = `${count} Questions`;
        if (summaryCount) summaryCount.textContent = count;

        questionIndex = cards.length;
    }

    function updateSummary() {
        const titleInput = document.getElementById('quizTitleInput');
        const courseSelect = document.getElementById('courseSelect');
        const semesterInput = document.getElementById('semesterInput');
        const accessSelect = document.getElementById('accessSelect');
        const visibilityInput = document.getElementById('visibilityInput');
        const allowCopyInput = document.getElementById('allowCopyInput');

        const summaryTitle = document.getElementById('summaryTitle');
        const summaryCourse = document.getElementById('summaryCourse');
        const summarySemester = document.getElementById('summarySemester');
        const summaryAccess = document.getElementById('summaryAccess');
        const summaryVisibility = document.getElementById('summaryVisibility');
        const summaryAllowCopy = document.getElementById('summaryAllowCopy');
        const summaryQuestionCount = document.getElementById('summaryQuestionCount');
        const questionCountBadge = document.getElementById('questionCountBadge');

        if (summaryTitle) summaryTitle.textContent = titleInput?.value?.trim() || '—';

        if (summaryCourse) {
            const selectedText = courseSelect?.selectedOptions?.[0]?.text?.trim();
            summaryCourse.textContent = selectedText || 'No course';
        }

        if (summarySemester) summarySemester.textContent = semesterInput?.value || '—';

        if (summaryAccess) {
            const value = accessSelect?.value || '';
            summaryAccess.textContent = value ? value.charAt(0).toUpperCase() + value.slice(1) : '—';
        }

        if (summaryVisibility) {
            const value = visibilityInput?.value || '';
            summaryVisibility.textContent = value ? value.charAt(0).toUpperCase() + value.slice(1) : '—';
        }

        if (summaryAllowCopy) {
            summaryAllowCopy.textContent = allowCopyInput?.checked ? 'Enabled' : 'Disabled';
        }

        const cards = document.querySelectorAll('[data-question-card]');
        const count = cards.length;

        if (summaryQuestionCount) summaryQuestionCount.textContent = count;
        if (questionCountBadge) questionCountBadge.textContent = `${count} Questions`;
    }

    function markDirty() {
        dirty = true;
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveDraftState, 250);
    }

    function collectQuestionData() {
        return Array.from(document.querySelectorAll('[data-question-card]')).map((card) => {
            const type = card.querySelector('.question-type-select')?.value || 'single_answer';
            const questionId = card.querySelector('input[name$="[id_question]"]')?.value || '';
            const content = card.querySelector('textarea[name$="[content]"]')?.value || '';
            const explanation = card.querySelector('textarea[name$="[explanation]"]')?.value || '';

            const options = Array.from(card.querySelectorAll('.option-row')).map((row) => ({
                id_option: row.querySelector('input[type="hidden"][name*="[id_option]"]')?.value || '',
                content: row.querySelector('input[type="text"]')?.value || '',
                checked: row.querySelector('input[type="radio"], input[type="checkbox"]')?.checked || false,
            }));

            const correctOptions = options
                .map((opt, idx) => opt.checked ? String(idx) : null)
                .filter(Boolean);

            return {
                id_question: questionId,
                type,
                content,
                explanation,
                correct_option: correctOptions[0] ?? '0',
                correct_options: correctOptions,
                options: options.map(({ id_option, content }) => ({
                    id_option,
                    content,
                })),
            };
        });
    }

    function saveDraftState() {
        const payload = {
            title: document.getElementById('quizTitleInput')?.value || '',
            description: document.getElementById('quizDescriptionInput')?.value || '',
            course_id: document.getElementById('courseSelect')?.value || '',
            semester: document.getElementById('semesterInput')?.value || '',
            access: document.getElementById('accessSelect')?.value || 'private',
            visibility: document.getElementById('visibilityInput')?.value || 'draft',
            allow_copy: document.getElementById('allowCopyInput')?.checked ? 1 : 0,
            questions: collectQuestionData(),
        };

        localStorage.setItem(draftKey, JSON.stringify(payload));
    }

    function restoreDraftState() {
        const raw = localStorage.getItem(draftKey);
        if (!raw) return;

        try {
            const payload = JSON.parse(raw);
            const questions = Array.isArray(payload.questions) ? payload.questions : [];

            if (isEdit && questions.length > 0) {
                const validEditDraft = questions.every((q) => q && q.id_question);
                if (!validEditDraft) return;
            }

            applyQuizState({
                title: payload.title ?? '',
                description: payload.description ?? '',
                course_id: payload.course_id ?? '',
                semester: payload.semester ?? '',
                access: payload.access ?? 'private',
                visibility: payload.visibility ?? 'draft',
                allow_copy: !!Number(payload.allow_copy ?? 0),
                questions: questions,
            });

            markDirty();
        } catch (e) {
            console.error('Invalid draft data', e);
        }
    }

    function getNextQuestionIndex() {
        const cards = document.querySelectorAll('[data-question-card]');
        if (!cards.length) return 0;

        let max = -1;
        cards.forEach(card => {
            const idx = parseInt(card.dataset.questionIndex || '0', 10);
            if (!Number.isNaN(idx)) {
                max = Math.max(max, idx);
            }
        });

        return max + 1;
    }

    function submitQuizForm(visibility) {
        if (!quizForm || isSubmitting) return;

        setVisibility(visibility);
        saveDraftState();
        skipDraftSave = true;
        isSubmitting = true;

        quizForm.requestSubmit();
    }

    function shouldInterceptLink(anchor) {
        if (!anchor || !anchor.href) return false;

        const href = anchor.getAttribute('href') || '';
        if (!href || href.startsWith('#')) return false;
        if (anchor.target === '_blank') return false;
        if (anchor.hasAttribute('download')) return false;

        const url = new URL(anchor.href, window.location.origin);
        if (url.origin !== window.location.origin) return false;

        return true;
    }

    document.addEventListener('click', function (event) {
        const anchor = event.target.closest('a[href]');
        if (!anchor) return;
        if (isSubmitting || !dirty) return;

        if (!shouldInterceptLink(anchor)) return;

        event.preventDefault();
        openLeaveModal(anchor.href);
    }, true);

    quizForm?.addEventListener('submit', function () {
        isSubmitting = true;
    });

    quizForm?.addEventListener('input', function () {
        updateSummary();
        markDirty();
    });

    quizForm?.addEventListener('change', function () {
        updateSummary();
        markDirty();
    });

    window.addEventListener('beforeunload', function () {
        if (skipDraftSave || isSubmitting) return;
        if (dirty) saveDraftState();
    });

    window.addEventListener('pagehide', function () {
        if (skipDraftSave || isSubmitting) return;
        if (dirty) saveDraftState();
    });

    clearLegacyDrafts();

    if (!hasServerErrors) {
        restoreDraftState();
    }else {
        updateQuestionNumbers();
        updateSummary();
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
</script>

@if(session('clearDraftKey'))
<script>
    localStorage.removeItem(@json(session('clearDraftKey')));
</script>
@endif


</x-app-layout>