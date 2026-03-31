<x-app-layout>

<div class="max-w-full bg-white p-8 rounded-3xl">

    <!-- Back -->
    <a href="{{ route('quizzes.index') }}"
    class="inline-flex items-center gap-2 font-semibold text-black/70 hover:text-black">
        ← Back
    </a>

    <!-- Cover -->
    <div class="mt-4 w-full h-52 rounded-[20px] overflow-hidden bg-[#f5f5f5]">
        <img 
            src="{{ $quiz->cover_image_url ?? asset('assets/default-cover.png') }}"
            class="w-full h-full object-cover"
        >
    </div>

    <!-- Title -->
    <div class="mt-6">
        <h1 class="text-[28px] font-extrabold">{{ $quiz->title }}</h1>
        <div class="text-[18px] font-semibold">{{ $quiz->course_name ?? 'General' }}</div>
        <div class="text-[18px] font-semibold text-gray-500">{{ $quiz->creator_name }}</div>
    </div>

    <!-- Info Card -->
    <div class="mt-4 flex flex-col lg:flex-row items-center justify-between gap-4 
            bg-white border border-[#F20000] rounded-[20px] p-4">

        <!-- Chips -->
        <div class="flex flex-wrap gap-2">

            <span class="border border-dashed border-black bg-[#f5f5f5] rounded-full px-4 py-1 text-sm">
                {{ $questionCount }} Questions
            </span>

        </div>

        <!-- Button -->
        <form method="POST" action="{{ route('quiz.start', $quiz->id_quiz) }}">
            @csrf
                @if($existingAttempt)
                    <a href="{{ route('attempt.play', $existingAttempt->id_attempt) }}"
                    class="bg-[#16a34a] text-white px-6 py-3 rounded-[16px] inline-block">
                        ▶ Continue Quiz
                    </a>
                @else
                    <form method="POST" action="{{ route('quiz.start', $quiz->id_quiz) }}">
                        @csrf
                        <button class="bg-[#104876] text-white px-6 py-3 rounded-[16px]">
                            ▶ Start Quiz Now
                        </button>
                    </form>
                @endif
        </form>

    </div>

    <!-- Description -->
    <div class="mt-6">
        <h3 class="text-xl font-semibold">Description</h3>
        <p class="text-[14px] mt-1 text-black/80">
            {{ $quiz->description ?? 'No description available.' }}
        </p>
    </div>

    <!-- Related + Tags -->
    <div class="mt-6 flex flex-col lg:flex-row gap-6">

        <!-- Related Courses -->
        <div class="flex-1">
            <h4 class="text-xl font-semibold">Related Courses</h4>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($relatedCourses as $course)
                    <span class="border border-dashed border-black bg-[#f5f5f5] rounded-full px-4 py-1 text-sm">
                        {{ $course }}
                    </span>
                @endforeach
            </div>
        </div>

        <!-- Tags -->
        <div class="flex-1">
            <h4 class="font-semibold">Tags</h4>
            <div class="flex flex-wrap gap-2 mt-2">
                @foreach($tags as $tag)
                    <span class="border border-dashed border-black bg-[#f5f5f5] rounded-full px-4 py-1 text-sm">
                        {{ $tag }}
                    </span>
                @endforeach
            </div>
        </div>

    </div>

</div>

</x-app-layout>
