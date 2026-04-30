<a href="{{ route('quiz.show', $quiz->id_quiz) }}" class="{{ $class ?? 'w-full' }} bg-white rounded-2xl overflow-hidden shadow-[0px_4px_24px_rgba(0,0,0,0.08)] flex flex-col border border-gray-200 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_35px_rgba(16,72,118,0.25)] hover:border-[#6BA9D0]/30 group">
    <div class="h-[140px] w-full relative bg-slate-100 overflow-hidden">
        <img 
            src="{{ $quiz->cover_image_url ?? asset('assets/default-cover.png') }}"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            onerror="this.src='https://placehold.co/600x400/e2e8f0/64748b?text=No+Cover'"
        />
    </div>
    <div class="p-6 flex flex-col flex-grow bg-white">
        <h3 class="text-[20px] font-bold text-gray-900 mb-1 leading-tight line-clamp-1 group-hover:text-[#6BA9D0]">
            {{ $quiz->title }}
        </h3>
        <div class="flex items-start justify-between text-gray-900 text-[16px] mb-4 font-medium gap-2 mt-1">
            <span class="w-1/2 break-words pr-1">{{ $quiz->major->name ?? 'General' }}</span>
            <span class="w-1/2 break-words text-right pl-1">{{ $quiz->course->name ?? 'Uncategorized' }}</span>
        </div>
        <div class="text-[14px] text-gray-500 mb-4 font-normal">
            {{ $quiz->author ? $quiz->author->first_name . ' ' . $quiz->author->last_name : 'System' }}
        </div>
        <div class="flex flex-wrap items-center gap-4 text-gray-900 font-bold text-[15px] mb-5">
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/images/img_questions.png') }}" alt="" class="w-[18px] h-[18px]">
                <span>{{ $quiz->questions_count ?? 0 }} Questions</span>
            </div>
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/images/img_timer.png') }}" alt="" class="w-[18px] h-[18px]">
                <span>{{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' Min' : 'No Limit' }}</span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2 mt-auto">
            @if($quiz->tags && $quiz->tags->count() > 0)
                @foreach($quiz->tags->take(3) as $tag)
                    <div class="px-4 py-1.5 rounded-full text-[14px] font-medium border bg-transparent border-gray-200 text-gray-500">
                        {{ $tag->name }}
                    </div>
                @endforeach
                @if($quiz->tags->count() > 3)
                    <div class="px-4 py-1.5 rounded-full text-[14px] font-medium border bg-[#f4f4f4] border-transparent text-gray-500">
                        +{{ $quiz->tags->count() - 3 }}
                    </div>
                @endif
            @else
                <div class="px-4 py-1.5 rounded-full text-[14px] font-medium border bg-transparent border-gray-200 text-gray-500 opacity-0 select-none">
                    No Tags
                </div>
            @endif
        </div>
    </div>
</a>
