<a href="{{ route('quiz.show', $quiz->id_quiz) }}" class="{{ $class ?? 'w-full' }} bg-white rounded-2xl overflow-hidden shadow-[0px_4px_24px_rgba(0,0,0,0.08)] flex flex-col border border-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(16,72,118,0.25)] hover:border-[#6BA9D0]/30 group h-[480px]">
    <div class="h-[140px] w-full relative bg-slate-100 overflow-hidden shrink-0">
        <img 
            src="{{ $quiz->cover_image_url ?? asset('assets/default-cover.png') }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            onerror="this.src='https://placehold.co/600x400/e2e8f0/64748b?text=No+Cover'"
        />
    </div>
    <div class="p-6 flex flex-col flex-grow bg-white overflow-hidden">
        <div class="marquee-container min-h-[30px] mb-1">
            <div class="animate-marquee whitespace-nowrap">
                <span class="text-[20px] font-bold text-gray-900 group-hover:text-[#74b2d7] marquee-content">{{ $quiz->title }}</span>
                <span class="text-[20px] font-bold text-gray-900 group-hover:text-[#74b2d7] marquee-content opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $quiz->title }}</span>
            </div>
        </div>

        <div class="flex items-center text-gray-900 text-[14px] mb-3 font-medium gap-2">
            <div class="w-1/2 marquee-container">
                <div class="animate-marquee whitespace-nowrap">
                    <span class="marquee-content pr-4">{{ $quiz->major->name ?? 'General' }}</span>
                    <span class="marquee-content pr-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $quiz->major->name ?? 'General' }}</span>
                </div>
            </div>
            <span class="text-gray-300 font-bold">&bull;</span>
            <div class="w-1/2 marquee-container text-right">
                <div class="animate-marquee whitespace-nowrap">
                    <span class="marquee-content pl-4">{{ $quiz->course->name ?? 'Uncategorized' }}</span>
                    <span class="marquee-content pl-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $quiz->course->name ?? 'Uncategorized' }}</span>
                </div>
            </div>
        </div>
        <div class="text-[13px] text-gray-500 mb-4 font-normal flex flex-col shrink-0">
            <span class="line-clamp-1">By {{ $quiz->author ? $quiz->author->first_name . ' ' . $quiz->author->last_name : 'System' }}</span>
            @if($quiz->lecturer)
                <span class="text-[#6BA9D0] font-semibold mt-0.5 line-clamp-1">Lecturer: {{ $quiz->lecturer->full_name }}</span>
            @else
                <span class="text-transparent mt-0.5 select-none">No Lecturer</span>
            @endif
        </div>
        <div class="flex items-center gap-2 text-gray-900 font-bold text-[14px] mb-4 shrink-0">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/images/img_questions.png') }}" alt="" class="w-[16px] h-[16px]">
                <span>{{ $quiz->questions_count ?? 0 }} Questions</span>
            </div>
            <span class="text-gray-300 font-bold px-1">&bull;</span>
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/images/img_timer.png') }}" alt="" class="w-[16px] h-[16px]">
                <span>{{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' Min' : 'No Limit' }}</span>
            </div>
        </div>
        <div class="flex items-center gap-2 mt-2 overflow-hidden">
            @if($quiz->tags && $quiz->tags->count() > 0)
                <div class="flex items-center gap-2 overflow-hidden flex-nowrap">
                    @foreach($quiz->tags->take(2) as $tag)
                        <div class="px-3 py-1 rounded-full text-[12px] font-medium border bg-transparent border-gray-200 text-gray-500 whitespace-nowrap shrink-0">
                            {{ $tag->name }}
                        </div>
                    @endforeach
                </div>
                @if($quiz->tags->count() > 2)
                    <div class="px-3 py-1 rounded-full text-[12px] font-medium border bg-[#f4f4f4] border-transparent text-gray-500 shrink-0">
                        +{{ $quiz->tags->count() - 2 }}
                    </div>
                @endif
            @else
                <div class="px-3 py-1 rounded-full text-[12px] font-medium border bg-transparent border-gray-200 text-gray-500 opacity-0 select-none">
                    No Tags
                </div>
            @endif
        </div>
    </div>
</a>
