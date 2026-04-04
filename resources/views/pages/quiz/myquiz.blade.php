<x-app-layout>

<div class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">My Quizzes</h1>
            <p class="text-gray-500 text-sm">Manage all the quizzes you have created</p>
        </div>

        <a href="{{ route('my-quizzes.create') }}" class="bg-[#104876] text-white px-4 py-2 rounded-lg hover:bg-[#2168a3] focus:bg-[#0a3f6b] transition ease-out">
            + Create a New Quiz
        </a>
    </div>

    <!-- STATS -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="border border-gray-300 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold">{{ $totalQuizzes }}</p>
            <p class="text-gray-500 text-sm">Quizzes Created</p>
        </div>

        <div class="border border-gray-300 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold">{{ $totalQuestions }}</p>
            <p class="text-gray-500 text-sm">Total Questions</p>
        </div>

        <div class="border border-gray-300 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold">{{ $totalAttempts }}</p>
            <p class="text-gray-500 text-sm">Attempts</p>
        </div>

        <div class="border border-gray-300 rounded-xl p-4 text-center">
            <p class="text-2xl font-bold">{{ $completionRate }}%</p>
            <p class="text-gray-500 text-sm">Completion Rate</p>
        </div>
    </div>

    <!-- FILTER -->
    <div class="flex justify-between items-center mb-6">

        <!-- FILTER BUTTON -->
        <div class="flex gap-2">
            @php
                $currentVisibility = request('visibility', 'all');
            @endphp

            @foreach(['all', 'published', 'draft'] as $v)
                <a href="{{ request()->fullUrlWithQuery(['visibility' => $v]) }}"
                class="px-4 py-1 rounded-full border text-sm transition ease-out
                {{ $currentVisibility == $v 
                        ? 'bg-[#104876] text-white border-[#104876]' 
                        : 'border-gray-300 hover:bg-gray-200 focus:bg-gray-100' }}">
                    {{ ucfirst($v) }}
                </a>
            @endforeach
        </div>

        <!-- SORT -->
        <form method="GET" class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Sort by:</span>

            <select name="sort"
                    onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-1 pr-8 text-sm hover:bg-gray-100 cursor-pointer">

                <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>
                    Latest
                </option>

                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                    Oldest
                </option>
            </select>

            <!-- PRESERVE VISIBILITY -->
            <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
        </form>
    </div>

    <div class="space-y-4">
        @foreach($quizzes as $quiz)

            <div class="border border-gray-300 rounded-xl flex overflow-hidden transition hover:shadow-md hover:-translate-y-[2px]">

                <!-- COVER -->
                <div class="w-40 bg-gray-200 border border-gray-300 flex items-center justify-center">
                    <span class="text-gray-400 text-sm">[Cover]</span>
                </div>

                <!-- CONTENT -->
                <div class="flex-1 p-4">
                    <div class="flex items-center gap-2 mb-2">

                        <!-- COURSE -->
                        <span class="border border-gray-300 px-3 py-1 rounded-full text-xs bg-gray-50">
                            {{ $quiz->course->name ?? 'No Course' }}
                        </span>

                        <!-- VISIBILITY -->
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $quiz->visibility == 'published'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($quiz->visibility) }}
                        </span>

                    </div>

                    <h2 class="font-semibold text-lg">{{ $quiz->title }}</h2>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $quiz->questions_count }} Questions • {{ $quiz->attempts_count }} Attempts
                    </p>
                </div>

                <!-- ACTION -->
                <div class="w-44 border-l border-gray-300 p-4 flex flex-col gap-2 justify-center">

                    <!-- EDIT (always available) -->
                    <a href="{{ route('my-quizzes.edit', $quiz) }}"
                    class="bg-[#104876] text-white text-center py-1.5 rounded-lg text-sm
                            hover:bg-[#0d3a60] active:scale-[0.97] transition">
                        {{ $quiz->visibility === 'draft' ? 'Continue Editing' : 'Edit Quiz' }}
                    </a>

                    <!-- STATISTIC -->
                    <a href="#"
                    class="border border-gray-300 text-center py-1.5 rounded-lg text-sm
                            hover:bg-gray-100 active:scale-[0.97] transition">
                        Statistic
                    </a>

                    <!-- DELETE -->
                    <form action="{{ route('my-quizzes.destroy', $quiz) }}" method="POST"
                        onsubmit="return confirm('Delete this quiz permanently?')">
                        @csrf
                        @method('DELETE')

                        <button
                            class="border border-red-500 text-red-500 w-full py-1.5 rounded-lg text-sm
                                hover:bg-red-50 active:scale-[0.97] transition">
                            Delete
                        </button>
                    </form>

                </div>
            </div>

        @endforeach
    </div>

</div>

@if(session('clearDraftKey'))
<script>
    Object.keys(localStorage).forEach((key) => {
        if (key.startsWith('pensquiz-quiz-draft-v5-')) {
            localStorage.removeItem(key);
        }
    });
</script>
@endif

</x-app-layout>