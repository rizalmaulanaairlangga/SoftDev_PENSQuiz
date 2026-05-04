<x-app-layout>

<div class="max-w-7xl mx-auto bg-white p-6 md:p-10 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] my-8">

    <!-- Back -->
    <a href="{{ route('quizzes.index') }}"
    class="inline-flex items-center gap-2 font-bold text-gray-800 hover:text-black transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
        Back
    </a>

    <!-- Cover -->
    <div class="mt-6 w-full h-[240px] rounded-[24px] overflow-hidden bg-[#f5f5f5]">
        <img 
            src="{{ $quiz->cover_image_url ?? asset('assets/default-cover.png') }}"
            class="w-full h-full object-cover"
            onerror="this.src='{{ asset('assets/dashboard/icons/img_books.png') }}'; this.classList.add('object-contain', 'p-8');"
        >
    </div>

    <!-- Title -->
    <div class="mt-8 flex flex-col md:flex-row justify-between items-start gap-4">
        <div>
            <h1 class="text-[32px] font-black text-gray-900 leading-tight">{{ $quiz->title }}</h1>
            <div class="text-[18px] font-bold text-gray-800 mt-1 flex items-center gap-2">
                <a href="{{ route('quizzes.index', ['search' => $quiz->major_name]) }}" class="relative group/link transition-colors hover:text-[#74b2d7]">
                    {{ $quiz->major_name ?? 'General' }}
                    <span class="absolute -bottom-1 left-0 w-full h-[3px] bg-[#74b2d7] rounded-full opacity-0 scale-x-0 group-hover/link:opacity-100 group-hover/link:scale-x-100 transition-all duration-300"></span>
                </a>
                <span class="text-gray-400">&bull;</span>
                <a href="{{ route('quizzes.index', ['search' => $quiz->course_name]) }}" class="relative group/link transition-colors hover:text-[#74b2d7]">
                    {{ $quiz->course_name ?? 'General' }}
                    <span class="absolute -bottom-1 left-0 w-full h-[3px] bg-[#74b2d7] rounded-full opacity-0 scale-x-0 group-hover/link:opacity-100 group-hover/link:scale-x-100 transition-all duration-300"></span>
                </a>
            </div>
            @if($quiz->lecturer)
                <div class="text-[16px] font-semibold text-[#74b2d7] mt-2">
                    Lecturer: 
                    <a href="{{ route('quizzes.index', ['search' => $quiz->lecturer->full_name]) }}" class="relative group/link transition-colors hover:text-[#74b2d7]">
                        {{ $quiz->lecturer->full_name }}
                        <span class="absolute -bottom-1 left-0 w-full h-[2px] bg-[#74b2d7] rounded-full opacity-0 scale-x-0 group-hover/link:opacity-100 group-hover/link:scale-x-100 transition-all duration-300 origin-center"></span>
                    </a>
                </div>
            @endif
        </div>
        <div class="flex flex-col items-end gap-1 shrink-0">
            <a href="{{ route('quizzes.index', ['search' => $quiz->creator_name]) }}" class="text-[18px] font-semibold text-gray-400 relative group/link transition-colors hover:text-[#74b2d7]">
                By {{ $quiz->creator_name }}
                <span class="absolute -bottom-1 left-0 w-full h-[3px] bg-[#74b2d7] rounded-full opacity-0 scale-x-0 group-hover/link:opacity-100 group-hover/link:scale-x-100 transition-all duration-300"></span>
            </a>
            <span class="text-[14px] text-gray-400 font-medium">Created {{ $quiz->created_at->format('M d, Y') }}</span>
        </div>
    </div>

    <!-- Info Card -->
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-6 
            bg-[#fcfdfd] border-[1.5px] border-[#74b2d7] rounded-[24px] p-6 relative">
        

        <!-- Chips -->
        <div class="flex flex-col gap-3 relative z-10 w-full md:w-auto bg-[#fcfdfd] md:pr-4">
            <span class="flex items-center gap-3 border border-dashed border-gray-400 bg-white rounded-full px-5 py-2.5 text-[15px] font-bold text-gray-900 w-full md:w-max">
                <img src="{{ asset('assets/images/img_questions.png') }}" class="w-5 h-5 object-contain" onerror="this.style.display='none'"> 
                {{ $questionCount }} Questions
            </span>
            <span class="flex items-center gap-3 border border-dashed border-gray-400 bg-white rounded-full px-5 py-2.5 text-[15px] font-bold text-gray-900 w-full md:w-max">
                <img src="{{ asset('assets/images/img_timer.png') }}" class="w-5 h-5 object-contain" onerror="this.style.display='none'"> 
                {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' Minutes' : 'No Time Limit' }}
            </span>
        </div>

        <!-- Button -->
        <div class="relative z-10 bg-[#fcfdfd] md:pl-4 w-full md:w-auto flex flex-col md:flex-row gap-4">
        @if($quiz->allow_copy && $quiz->author_id !== Auth::id())
            <div x-data="{ showOptions: false }" class="relative w-full md:w-auto">
                <!-- Popup Options -->
                <div x-show="showOptions" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                     @click.away="showOptions = false"
                     class="absolute bottom-full left-0 md:left-1/2 md:-translate-x-1/2 mb-4 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 z-50 flex flex-col gap-2"
                     style="display: none;">
                    
                    <form action="{{ route('quiz.copy', $quiz->id_quiz) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="copy">
                        <button type="submit" class="w-full bg-[#528EB8] text-white py-3 rounded-xl font-bold text-sm hover:bg-[#3E779F] transition-all duration-300 hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                            Direct Copy
                        </button>
                    </form>

                    <form action="{{ route('quiz.copy', $quiz->id_quiz) }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="edit">
                        <button type="submit" class="w-full bg-[#528EB8]/5 border-2 border-[#528EB8] text-[#528EB8] py-3 rounded-xl font-bold text-sm hover:bg-[#528EB8] hover:text-white transition-all duration-300 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit First
                        </button>
                    </form>

                    <!-- Triangle Arrow -->
                    <div class="absolute -bottom-2 left-10 md:left-1/2 md:-translate-x-1/2 w-4 h-4 bg-white border-r border-b border-gray-100 rotate-45"></div>
                </div>

                <!-- Main Trigger Button -->
                <button type="button" @click="showOptions = !showOptions" class="bg-[#528EB8]/5 border-2 border-[#528EB8] text-[#528EB8] font-bold text-[16px] px-8 py-4 rounded-[16px] w-full md:w-auto flex items-center justify-center gap-2 hover:bg-[#528EB8] hover:text-white hover:shadow-[0_0_30px_-5px_rgba(82,142,184,0.6)] hover:-translate-y-0.5 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" /></svg>
                    Copy quiz
                </button>
            </div>
        @endif

        @if($existingAttempt)
            <a href="{{ route('attempt.play', $existingAttempt->id_attempt) }}"
            class="bg-[#16a34a] text-white font-bold text-[16px] px-8 py-4 rounded-[16px] w-full md:w-auto flex items-center justify-center gap-2 hover:bg-[#15803d] transition shadow-lg shadow-green-500/20">
                Continue Quiz
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
            </a>
        @else
            <form id="startQuizForm" method="POST" action="{{ route('quiz.start', $quiz->id_quiz) }}" class="w-full md:w-auto">
                @csrf
                <button type="button" onclick="toggleModal(true)" class="bg-[#74b2d7] text-white font-bold text-[16px] px-8 py-4 rounded-[16px] w-full md:w-auto flex items-center justify-center gap-2 hover:bg-[#5fa2c8] hover:shadow-[0_0_30px_-5px_rgba(116,178,215,0.6)] hover:-translate-y-0.5 transition-all duration-300">
                    Start quiz now
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </button>
            </form>
        @endif
        </div>

    </div>

    <!-- Description -->
    <div class="mt-8">
        <h3 class="text-[20px] font-bold text-gray-900">Description</h3>
        <p class="text-[16px] mt-2 text-gray-600 leading-relaxed">
            {{ $quiz->description ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.' }}
        </p>
    </div>

    <!-- Tags -->
    @if(count($tags) > 0)
    <div class="mt-8">
        <h4 class="font-bold text-[20px] text-gray-900">Tags</h4>
        <div class="flex flex-wrap gap-3 mt-3">
            @foreach($tags as $tag)
                <a href="{{ route('quizzes.index', ['search' => $tag]) }}" class="border border-gray-200 bg-[#f5f5f5] rounded-full px-5 py-2 text-[15px] font-medium text-gray-500 hover:bg-[#74b2d7] hover:border-[#74b2d7] hover:text-white hover:-translate-y-1.5 hover:shadow-[0_15px_30px_-8px_rgba(18,77,119,0.5)] transition-all duration-300">
                    {{ $tag }}
                </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

<!-- Related Quizzes -->
@if($relatedQuizzes->isNotEmpty())
<div class="max-w-7xl mx-auto mt-12 mb-20 px-4 md:px-0">
    <h4 class="font-black text-[28px] text-[#74b2d7] mb-6 font-headings tracking-tight">Related Quizzes</h4>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedQuizzes as $related)
            @include('pages.quiz.partials.quiz-card', ['quiz' => $related, 'class' => 'w-full h-full'])
        @endforeach
    </div>
</div>
@endif


<!-- Start Quiz Confirmation Modal -->
<div id="startQuizModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-md transition-all duration-300">
    <div id="startQuizContent" class="bg-white w-full max-w-4xl rounded-[40px] p-8 md:p-16 shadow-[0_20px_70px_-10px_rgba(0,0,0,0.1)] relative opacity-0 scale-95 transition-all duration-300 ease-out">
        
        <!-- Back Button -->
        <button onclick="toggleModal(false)" class="absolute top-10 left-10 flex items-center gap-2 font-bold text-gray-800 hover:text-black transition group">
            <div class="bg-gray-100 p-2 rounded-full group-hover:bg-gray-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
            </div>
            Back
        </button>

        <div class="flex flex-col items-center justify-center py-20 text-center">
            <h2 class="text-[36px] md:text-[48px] font-black text-gray-900 leading-tight tracking-tight">
                Are you sure you want to start this quiz?
            </h2>
            <p class="mt-6 text-[18px] md:text-[22px] font-medium text-gray-500">
                @if($quiz->time_limit_minutes)
                    A <span class="font-bold text-gray-900">{{ $quiz->time_limit_minutes }}-minute</span> time count will start if you start now.
                @else
                    No time limit will be applied to this quiz.
                @endif
            </p>
        </div>

        <!-- Start Button -->
        <div class="flex justify-end mt-4">
            <button onclick="document.getElementById('startQuizForm').submit()" class="bg-[#124d77] text-white font-bold text-[18px] px-10 py-5 rounded-[20px] flex items-center gap-3 hover:bg-[#0e3a5a] transition-all hover:scale-105 active:scale-95 shadow-2xl shadow-[#124d77]/30 group">
                Start quiz now
                <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    function toggleModal(show) {
        const modal = document.getElementById('startQuizModal');
        const content = document.getElementById('startQuizContent');
        
        if (show) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Force reflow
            void modal.offsetWidth;
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('opacity-100', 'scale-100');
            document.body.style.overflow = 'hidden';
        } else {
            content.classList.add('opacity-0', 'scale-95');
            content.classList.remove('opacity-100', 'scale-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }

    // Close modal on escape key
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            toggleModal(false);
        }
    });

    // Close modal on clicking backdrop
    document.getElementById('startQuizModal').addEventListener('click', (e) => {
        if (e.target.id === 'startQuizModal') {
            toggleModal(false);
        }
    });
</script>

</x-app-layout>
