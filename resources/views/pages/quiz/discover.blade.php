<x-app-layout>
    <!-- Banani background gradient top -->
    <div class="absolute top-0 left-0 right-0 h-[600px] bg-gradient-to-b from-[#f0f6fa] to-white/0 pointer-events-none z-0"></div>

    <div class="relative z-10 w-full max-w-[1140px] mx-auto pt-8 pb-24 flex flex-col items-center">
        <h1 class="text-[40px] font-black text-[#74b2d7] mb-8 font-headings tracking-tight text-center">
            Ready to Test Your Knowledge?
        </h1>
        
        <form method="GET" action="{{ route('quizzes.index') }}" class="flex w-full max-w-[800px] gap-4 mb-6 items-center">
            <div class="flex-1 flex items-center justify-between bg-white border border-gray-200 rounded-full px-6 py-3 shadow-[0px_4px_12px_rgba(0,0,0,0.05)]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search quizzes..." class="w-full text-[16px] text-gray-700 font-medium bg-transparent border-none outline-none focus:ring-0 px-0">
                <button type="submit" class="shrink-0 text-gray-400 hover:text-[#74b2d7]">
                    <svg class="size-[24px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </div>
            <button type="button" class="w-14 h-14 bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-[0px_4px_12px_rgba(0,0,0,0.05)] shrink-0 hover:bg-gray-50 transition">
                <svg class="size-[24px] text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </button>
        </form>
 
        <div class="flex flex-wrap items-center justify-center gap-3 mb-16">
            @if(request('search') || request('tags'))
                <a href="{{ route('quizzes.index') }}" class="px-6 py-2 bg-white border border-gray-200 rounded-full text-[14px] font-bold text-gray-800 hover:bg-gray-50 transition">
                    Clear Quiz
                </a>
            @endif
            @if(request('search'))
                <div class="flex items-center gap-2 px-5 py-2 bg-[#74b2d7] rounded-full text-white text-[14px] font-medium">
                    <span>Search: {{ request('search') }}</span>
                    <a href="{{ route('quizzes.index') }}"><svg class="size-[16px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></a>
                </div>
            @endif
        </div>

        <div class="w-full flex flex-col gap-12">

            @if(isset($searchResults))
                <!-- Search Results Section -->
                <div class="flex flex-col w-full">
                    <h2 class="text-[28px] font-black text-gray-900 mb-6 font-headings tracking-tight">Search Results</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($searchResults as $quiz)
                            @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-full h-full'])
                        @empty
                            <p class="text-gray-500">No quizzes found matching your search.</p>
                        @endforelse
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="mt-8">
                    {{ $searchResults->links() }}
                </div>
            @else

                @if(isset($historyQuizzes) && $historyQuizzes->count() > 0)
                <div class="flex flex-col w-full">
                    <h2 class="text-[28px] font-black text-gray-900 mb-6 font-headings tracking-tight">History</h2>
                    <div class="flex gap-6 overflow-x-auto pb-8 -mx-8 px-8 no-scrollbar snap-x">
                        @foreach($historyQuizzes as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[280px] sm:w-[320px] h-full shrink-0'])
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($recommendedQuizzes) && $recommendedQuizzes->count() > 0)
                <div class="flex flex-col w-full">
                    <h2 class="text-[28px] font-black text-gray-900 mb-6 font-headings tracking-tight">Recommended For You</h2>
                    <div class="flex gap-6 overflow-x-auto pb-8 -mx-8 px-8 no-scrollbar snap-x">
                        @foreach($recommendedQuizzes as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[280px] sm:w-[320px] h-full shrink-0'])
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($popularQuizzes) && $popularQuizzes->count() > 0)
                <div class="flex flex-col w-full">
                    <h2 class="text-[28px] font-black text-gray-900 mb-6 font-headings tracking-tight">Popular in Your Major</h2>
                    <div class="flex gap-6 overflow-x-auto pb-8 -mx-8 px-8 no-scrollbar snap-x">
                        @foreach($popularQuizzes as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[280px] sm:w-[320px] h-full shrink-0'])
                        @endforeach
                    </div>
                </div>
                @endif

                @if(isset($trendingQuizzes) && $trendingQuizzes->count() > 0)
                <div class="flex flex-col w-full">
                    <h2 class="text-[28px] font-black text-gray-900 mb-6 font-headings tracking-tight">Trending This Week</h2>
                    <div class="flex gap-6 overflow-x-auto pb-8 -mx-8 px-8 no-scrollbar snap-x">
                        @foreach($trendingQuizzes as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[280px] sm:w-[320px] h-full shrink-0'])
                        @endforeach
                    </div>
                </div>
                @endif

            @endif

        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>