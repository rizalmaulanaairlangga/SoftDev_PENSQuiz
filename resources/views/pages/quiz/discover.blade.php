<x-app-layout>

    <div class="flex flex-col gap-4">

        <!-- Title -->
        <h1 class="text-[28px] font-extrabold text-black">Discover Quizzes</h1>

        <!-- Grid -->
        <div class="grid gap-4"
            style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));">

            @foreach($quizzes as $quiz)
                <a href="{{ route('quiz.show', $quiz->id_quiz) }}"
                class="block w-full min-w-0 rounded-[20px] bg-white p-4 
                        shadow-[0_2px_12px_rgba(0,0,0,0.05)] 
                        hover:shadow-lg hover:-translate-y-0.5 transition">

                    <div class="w-full h-[140px] rounded-[16px] overflow-hidden bg-gray-200">
                        <img 
                            src="{{ $quiz->cover_image_url ?? asset('assets/default-cover.png') }}"
                            class="w-full h-full object-cover"
                        >
                    </div>

                    <div class="mt-4">
                        <div class="text-[18px] font-bold text-black leading-tight line-clamp-2">
                            {{ $quiz->title }}
                        </div>

                        <div class="mt-1 text-[14px] text-black/80">
                            {{ $quiz->course_name ?? 'General' }}
                        </div>

                        <div class="mt-2 text-[13px] text-black/50">
                            {{ $quiz->creator_name }}
                        </div>
                    </div>

                </a>
            @endforeach

        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $quizzes->links() }}
        </div>

    </div>

</x-app-layout>