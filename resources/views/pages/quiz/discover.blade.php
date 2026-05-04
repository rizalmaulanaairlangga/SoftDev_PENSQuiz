<x-app-layout>
    {{-- Pembungkus utama untuk Alpine.js state (filterModal) --}}
    <div x-data="{ filterModal: false }" class="relative">
        
        <!-- Banani background gradient top -->
        <div class="absolute top-0 left-0 right-0 h-[600px] bg-gradient-to-b from-[#f0f6fa] to-white/0 rounded-t-3xl pointer-events-none z-0"></div>

        <div class="relative z-10 w-full max-w-[1140px] mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-24 flex flex-col items-center">
            
            {{-- ================================================================ --}}
            {{-- 1. HERO TITLE & SEARCH BAR                                       --}}
            {{-- ================================================================ --}}
            <h1 class="text-3xl sm:text-[40px] font-black text-[#74b2d7] mb-8 font-headings tracking-tight text-center">
                Ready to Test Your Knowledge?
            </h1>
            
            <form method="GET" action="{{ route('quizzes.index') }}" class="flex w-full max-w-[800px] gap-3 sm:gap-4 mb-6 items-center flex-col sm:flex-row">
                
                {{-- Input Pencarian --}}
                <div class="w-full flex-1 flex items-center justify-between bg-white border border-gray-200 rounded-full px-5 sm:px-6 py-2.5 sm:py-3 shadow-[0px_4px_12px_rgba(0,0,0,0.05)]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search folders, quizzes, tags..." class="w-full text-[15px] sm:text-[16px] text-gray-700 font-medium bg-transparent border-none outline-none focus:ring-0 px-0">
                    <button type="submit" class="shrink-0 text-gray-400 hover:text-[#74b2d7] ml-2">
                        <svg class="size-[20px] sm:size-[24px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
                
                {{-- Tombol Filter (Buka Modal) --}}
                <button @click="filterModal = true" type="button" class="w-12 h-12 sm:w-14 sm:h-14 bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-[0px_4px_12px_rgba(0,0,0,0.05)] shrink-0 hover:bg-[#A6D9F0] hover:border-[#528FB9] hover:text-white focus:ring-2 focus:ring-[#528FB9] transition duration-300">
                    <svg class="size-[20px] sm:size-[24px] text-gray-600 hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
            </form>
    
            {{-- Indikator Filter Aktif --}}
            <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 mb-10 sm:mb-16">
                @if(request('search') || request('tags'))
                    <a href="{{ route('quizzes.index') }}" class="px-5 sm:px-6 py-1.5 sm:py-2 bg-white border border-gray-200 rounded-full text-[13px] sm:text-[14px] font-bold text-gray-800 hover:bg-gray-50 transition shadow-sm">
                        Clear Quiz
                    </a>
                @endif
                
                @if(request('search'))
                    <div class="flex items-center gap-2 px-4 sm:px-5 py-1.5 sm:py-2 bg-[#74b2d7] rounded-full text-white text-[13px] sm:text-[14px] font-medium shadow-sm">
                        <span>Search: {{ request('search') }}</span>
                        <a href="{{ route('quizzes.index') }}"><svg class="size-[14px] sm:size-[16px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></a>
                    </div>
                @endif

                @if(!empty($filterTags))
                    @foreach($filterTags as $appliedTagId)
                        @php $tagName = \App\Models\Tag::find($appliedTagId)->name ?? 'Tag'; @endphp
                        <span class="px-4 py-1.5 sm:py-2 bg-[#528FB9] text-white rounded-full text-[13px] sm:text-[14px] font-medium shadow-sm">{{ $tagName }}</span>
                    @endforeach
                @endif
            </div>

            {{-- ================================================================ --}}
            {{-- 2. KONTEN UTAMA (SEARCH RESULTS / KATEGORI DISCOVER)             --}}
            {{-- ================================================================ --}}
            <div class="w-full flex flex-col gap-10 sm:gap-12">

                @if(isset($searchResults))
                    <!-- Search Results Section -->
                    <div class="flex flex-col w-full">
                        <h2 class="text-2xl sm:text-[28px] font-black text-gray-900 mb-4 sm:mb-6 font-headings tracking-tight">Search Results</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @forelse($searchResults as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-full h-full'])
                            @empty
                                <p class="text-gray-500 col-span-full text-center py-10 bg-white/50 rounded-2xl border border-dashed border-gray-300">No quizzes found matching your search/filters.</p>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $searchResults->links() }}
                    </div>
                @else

                    {{-- History Quizzes (Kuis yang telah dikerjakan user) --}}
                    @if(isset($historyQuizzes) && $historyQuizzes->count() > 0)
                    <div class="flex flex-col w-full">
                        <h2 class="text-2xl sm:text-[28px] font-black text-gray-900 mb-4 sm:mb-6 font-headings tracking-tight">History: Quizzes You've Played</h2>
                        <div class="flex gap-4 sm:gap-6 overflow-x-auto pb-8 -mx-4 px-4 sm:-mx-8 sm:px-8 no-scrollbar snap-x">
                            @foreach($historyQuizzes as $quiz)
                                    @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[260px] sm:w-[320px] h-full shrink-0 snap-center sm:snap-start'])
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Recommended Quizzes --}}
                    @if(isset($recommendedQuizzes) && $recommendedQuizzes->count() > 0)
                    <div class="flex flex-col w-full">
                        <h2 class="text-2xl sm:text-[28px] font-black text-gray-900 mb-4 sm:mb-6 font-headings tracking-tight">Recommended For You</h2>
                        <div class="flex gap-4 sm:gap-6 overflow-x-auto pb-8 -mx-4 px-4 sm:-mx-8 sm:px-8 no-scrollbar snap-x">
                            @foreach($recommendedQuizzes as $quiz)
                                    @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[260px] sm:w-[320px] h-full shrink-0 snap-center sm:snap-start'])
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Popular Quizzes --}}
                    @if(isset($popularQuizzes) && $popularQuizzes->count() > 0)
                    <div class="flex flex-col w-full">
                        <h2 class="text-2xl sm:text-[28px] font-black text-gray-900 mb-4 sm:mb-6 font-headings tracking-tight">Popular in Your Major</h2>
                        <div class="flex gap-4 sm:gap-6 overflow-x-auto pb-8 -mx-4 px-4 sm:-mx-8 sm:px-8 no-scrollbar snap-x">
                            @foreach($popularQuizzes as $quiz)
                                    @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[260px] sm:w-[320px] h-full shrink-0 snap-center sm:snap-start'])
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Trending Quizzes --}}
                    @if(isset($trendingQuizzes) && $trendingQuizzes->count() > 0)
                    <div class="flex flex-col w-full">
                        <h2 class="text-2xl sm:text-[28px] font-black text-gray-900 mb-4 sm:mb-6 font-headings tracking-tight">Trending This Week</h2>
                        <div class="flex gap-4 sm:gap-6 overflow-x-auto pb-8 -mx-4 px-4 sm:-mx-8 sm:px-8 no-scrollbar snap-x">
                            @foreach($trendingQuizzes as $quiz)
                                    @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-[260px] sm:w-[320px] h-full shrink-0 snap-center sm:snap-start'])
                            @endforeach
                        </div>
                    </div>
                    @endif

                @endif

            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- 3. FILTER MODAL SECTION                                          --}}
        {{-- ================================================================ --}}
        <div x-show="filterModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="filterModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="filterModal = false" aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="filterModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative"
                     x-data="{
                        searchQuery: '',
                        appliedTags: {{ json_encode(array_values(array_filter(array_map(function($id) { return \App\Models\Tag::find($id); }, $filterTags ?? [])))) }},
                        popularTags: {{ json_encode($popularTags ?? []) }},
                        allTags: {{ json_encode(\App\Models\Tag::all()) }},
                        
                        get searchResults() {
                            if (this.searchQuery.trim() === '') return [];
                            const q = this.searchQuery.toLowerCase();
                            return this.allTags.filter(t => t.name.toLowerCase().includes(q) && !this.appliedTags.some(at => at.id_tag === t.id_tag)).slice(0, 5);
                        },
                        
                        get availablePopularTags() {
                            return this.popularTags.filter(t => !this.appliedTags.some(at => at.id_tag === t.id_tag));
                        },
                        
                        addTag(tag) {
                            if (!this.appliedTags.some(t => t.id_tag === tag.id_tag)) {
                                this.appliedTags.push(tag);
                            }
                            this.searchQuery = '';
                        },
                        
                        removeTag(tagId) {
                            this.appliedTags = this.appliedTags.filter(t => t.id_tag !== tagId);
                        }
                     }">
                    <form action="{{ route('quizzes.index') }}" method="GET" class="p-6 sm:p-8">
                        <!-- Close button -->
                        <button type="button" @click="filterModal = false" class="absolute top-6 right-6 sm:top-8 sm:right-8 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>

                        <!-- Preserve search if exists -->
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        
                        <!-- Hidden inputs for applied tags -->
                        <template x-for="tag in appliedTags" :key="tag.id_tag">
                            <input type="hidden" name="tags[]" :value="tag.id_tag">
                        </template>

                        <h3 class="text-2xl sm:text-3xl font-extrabold text-black mb-6" id="modal-title">Filter</h3>
                        
                        {{-- Tag Search Input --}}
                        <div class="relative mb-6 sm:mb-8">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" x-model="searchQuery" placeholder="Search tags" class="w-full pl-12 pr-4 py-2.5 rounded-full border border-gray-300 bg-gray-50 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm transition text-gray-700">
                            
                            <!-- Live Search Results Dropdown -->
                            <div x-show="searchResults.length > 0" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg">
                                <ul class="py-1">
                                    <template x-for="tag in searchResults" :key="tag.id_tag">
                                        <li @click="addTag(tag)" class="px-4 py-2 hover:bg-[#A6D9F0] hover:text-[#104876] cursor-pointer text-sm text-gray-700 transition">
                                            <span x-text="tag.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        {{-- Applied Tags --}}
                        <div class="mb-6" x-show="appliedTags.length > 0" style="display: none;">
                            <h4 class="text-base sm:text-lg font-medium text-black mb-3">Applied Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in appliedTags" :key="tag.id_tag">
                                    <label @click="removeTag(tag.id_tag)" class="cursor-pointer inline-flex items-center px-4 py-1.5 rounded-full text-[13px] sm:text-sm font-medium bg-[#528FB9] text-white hover:bg-red-500 transition group shadow-sm">
                                        <span x-text="tag.name"></span>
                                        <svg class="w-4 h-4 ml-1.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Popular Tags --}}
                        <div class="mb-8" x-show="availablePopularTags.length > 0" style="display: none;">
                            <h4 class="text-base sm:text-lg font-medium text-black mb-3">Popular Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in availablePopularTags" :key="tag.id_tag">
                                    <label @click="addTag(tag)" class="cursor-pointer inline-flex items-center px-4 py-1.5 rounded-full text-[13px] sm:text-sm font-medium border border-gray-300 text-black hover:border-[#528FB9] hover:bg-[#A6D9F0] hover:text-[#104876] transition duration-300">
                                        <span x-text="tag.name"></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        {{-- Apply Button --}}
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="w-full sm:w-32 bg-[#528FB9] text-white font-medium py-2.5 rounded-full hover:bg-[#3E779F] transition shadow-md">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Utility class untuk menyembunyikan scrollbar di container slider kuis */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</x-app-layout>