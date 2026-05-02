<x-app-layout>

<div class="max-w-6xl mx-auto p-6" x-data="{ filterModal: false, folderModal: false }">

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-24 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" class="fixed top-24 right-6 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 flex flex-col gap-2">
            <div class="flex justify-between items-center">
                <span class="font-bold">Error</span>
                <button @click="show = false" class="text-white hover:text-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <ul class="text-sm list-disc pl-4">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- HERO HEADER -->
    <div class="text-center mt-4 mb-8">
        <h1 class="text-4xl font-extrabold text-[#528FB9] mb-6">Ready to Create More Challenges?</h1>
        
        <!-- SEARCH & FILTER -->
        <div class="max-w-3xl mx-auto flex flex-col gap-3">
            <div class="flex items-center gap-4">
                <form action="{{ route('my-quizzes.index') }}#quizzes" method="GET" class="flex-1 relative">
                    <!-- Keep other filters -->
                    <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
                    <input type="hidden" name="major" value="{{ request('major') }}">
                    <input type="hidden" name="course" value="{{ request('course') }}">
                    @foreach(request('tags', []) as $tag)
                        <input type="hidden" name="tags[]" value="{{ $tag }}">
                    @endforeach
                    
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search folders, quizzes, tags..." class="w-full pl-12 pr-10 py-3 rounded-full border border-gray-300 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm transition">
                    
                    @if(request('search'))
                        <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <svg class="w-5 h-5 text-gray-400 hover:text-red-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </form>
                
                <button @click="filterModal = true" class="p-3 border border-gray-300 rounded-full hover:bg-[#A6D9F0] hover:border-[#528FB9] hover:text-white focus:ring-2 focus:ring-[#528FB9] transition duration-300 flex items-center justify-center bg-white shadow-sm">
                    <svg class="w-6 h-6 text-gray-600 hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
            </div>

            <!-- APPLIED TAGS BELOW SEARCH -->
            @if(!empty($filterTags))
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <a href="{{ request()->fullUrlWithQuery(['tags' => null]) }}" class="px-3 py-1 bg-red-100 text-red-600 border border-red-200 rounded-full text-xs font-bold hover:bg-red-200 transition flex items-center gap-1 shadow-sm">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Clear Tags
                    </a>
                    @foreach($filterTags as $appliedTagId)
                        @php $tagName = \App\Models\Tag::find($appliedTagId)->name ?? 'Tag'; @endphp
                        <span class="px-3 py-1 bg-[#528FB9] text-white rounded-full text-xs font-medium shadow-sm">{{ $tagName }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if(!request('search') && empty($filterTags))
    <!-- STATS -->
    <div class="grid grid-cols-4 gap-6 mb-10">
        <div class="rounded-xl p-6 text-center shadow-md bg-gradient-to-br from-[#528FB9] to-[#80c0e5] text-white hover:scale-105 hover:shadow-lg transition duration-300">
            <p class="text-4xl font-bold mb-2">{{ $totalQuizzes }}</p>
            <p class="text-blue-50 text-sm font-medium uppercase tracking-wider">Quizzes Created</p>
        </div>

        <div class="rounded-xl p-6 text-center shadow-md bg-gradient-to-br from-[#68a0c7] to-[#A6D9F0] text-white hover:scale-105 hover:shadow-lg transition duration-300">
            <p class="text-4xl font-bold mb-2">{{ $totalQuestions }}</p>
            <p class="text-blue-50 text-sm font-medium uppercase tracking-wider">Total Questions</p>
        </div>

        <div class="rounded-xl p-6 text-center shadow-md bg-gradient-to-br from-[#5b97c0] to-[#91cdef] text-white hover:scale-105 hover:shadow-lg transition duration-300">
            <p class="text-4xl font-bold mb-2">{{ $totalAttempts }}</p>
            <p class="text-blue-50 text-sm font-medium uppercase tracking-wider">Attempts</p>
        </div>

        <div class="rounded-xl p-6 text-center shadow-md bg-gradient-to-br from-[#4d8ab3] to-[#76b7df] text-white hover:scale-105 hover:shadow-lg transition duration-300">
            <p class="text-4xl font-bold mb-2">{{ $completionRate }}%</p>
            <p class="text-blue-50 text-sm font-medium uppercase tracking-wider">Completion Rate</p>
        </div>
    </div>
    @endif

    <!-- FOLDERS -->
    <div id="folders-section" class="mb-10 scroll-mt-24">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Folders</h2>
            <button @click="folderModal = true" class="bg-[#528FB9] text-white px-5 py-2 rounded-full font-medium hover:bg-[#3E779F] transition shadow-sm flex items-center gap-2">
                New Folder
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </button>
        </div>

        <!-- Folder List -->
        <div class="grid grid-cols-2 gap-4 max-h-[220px] overflow-y-auto px-2 custom-scrollbar overflow-x-visible">
            @foreach($folders as $folder)
                <div class="flex items-center gap-4 bg-[#f8fafc] p-4 rounded-xl border border-gray-200 hover:shadow-lg hover:shadow-[#528FB9]/50 hover:border-[#A6D9F0] transition duration-300 cursor-pointer">
                    <div class="w-16 h-16 flex-shrink-0 relative">
                        <img src="{{ asset('assets/images/img_folder.png') }}" alt="Folder" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg text-black">{{ $folder->name }}</h3>
                        <p class="text-sm font-medium text-black">{{ $folder->quizzes_count }} items</p>
                        <p class="text-[13px] text-gray-400 mt-1">Modified {{ $folder->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
            @if($folders->isEmpty())
                <div class="col-span-2 text-center py-6 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    No folders created yet.
                </div>
            @endif
        </div>
    </div>

    <!-- ALL QUIZZES -->
    <div id="quizzes-section" class="pt-2 scroll-mt-24">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-extrabold text-black">My Quizzes</h2>
            <a href="{{ route('my-quizzes.create') }}" class="bg-[#528FB9] text-white px-5 py-2 rounded-full font-medium hover:bg-[#3E779F] transition shadow-sm flex items-center gap-2">
                New Quiz
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            </a>
        </div>

        <div class="flex justify-between items-center mb-6">
            <!-- Visibility Filters -->
            <div class="flex gap-3">
                @php $currentVisibility = request('visibility', 'all'); @endphp
                @foreach(['all', 'published', 'draft'] as $v)
                    <a href="{{ request()->fullUrlWithQuery(['visibility' => $v]) }}#quizzes-section"
                       class="px-5 py-1.5 rounded-full border text-sm font-medium transition duration-300 ease-out hover:bg-[#A6D9F0] hover:border-[#528FB9] hover:text-[#104876]
                       {{ $currentVisibility == $v ? 'bg-[#528FB9] text-white border-[#528FB9]' : 'bg-white text-black border-gray-300' }}">
                        {{ ucfirst($v) }}
                    </a>
                @endforeach
            </div>

            <!-- Sort and Dropdown Filters -->
            <form method="GET" action="{{ route('my-quizzes.index') }}#quizzes-section" class="flex gap-3">
                <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                @foreach(request('tags', []) as $tag)
                    <input type="hidden" name="tags[]" value="{{ $tag }}">
                @endforeach
                
                <select name="major" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-sm py-1.5 pl-4 pr-8 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9]">
                    <option value="">Major</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->id_major }}" {{ request('major') == $m->id_major ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>

                <select name="course" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-sm py-1.5 pl-4 pr-8 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9]">
                    <option value="">Course</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id_course }}" {{ request('course') == $c->id_course ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>

                <select name="sort" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-sm py-1.5 pl-4 pr-8 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9]">
                    <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($quizzes as $quiz)
                <div class="bg-white border border-gray-200 rounded-2xl flex overflow-hidden shadow-sm hover:shadow-lg hover:shadow-[#528FB9]/40 hover:border-[#A6D9F0] transition duration-300">
                    <!-- COVER -->
                    <div class="w-48 bg-gray-100 flex-shrink-0 relative">
                        @if($quiz->cover_image_url)
                            <img src="{{ $quiz->cover_image_url }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#528FB9]/10">
                                <svg class="w-12 h-12 text-[#528FB9]/40" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/></svg>
                            </div>
                        @endif
                    </div>

                    <!-- CONTENT -->
                    <div class="flex-1 p-5 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-1">
                            <h2 class="font-bold text-xl text-black">{{ $quiz->title }}</h2>
                            <span class="px-3 py-0.5 rounded-full text-xs font-medium border border-gray-300 text-black">
                                {{ ucfirst($quiz->visibility) }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-[15px] text-black mb-3">
                            <span>{{ $quiz->major->name ?? 'Quiz Major' }}</span>
                            <span class="font-bold">&middot;</span>
                            <span>{{ $quiz->course->name ?? 'Quiz Subject' }}</span>
                        </div>

                        <div>
                            <p class="text-[15px] text-black font-medium mb-1">Description</p>
                            <p class="text-[15px] text-black leading-snug line-clamp-1">
                                {{ $quiz->description ?: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit...' }}
                            </p>
                        </div>
                        
                        <div class="mt-4 text-[14px] font-medium text-gray-400">
                            Modified {{ $quiz->updated_at->diffForHumans() }}
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="w-48 border-l border-gray-100 p-5 flex flex-col gap-3 justify-center">
                        <a href="{{ route('my-quizzes.edit', $quiz) }}" class="bg-[#528FB9] text-white text-center font-medium py-2 rounded-full text-sm hover:bg-[#3E779F] hover:shadow-lg hover:-translate-y-0.5 transform transition-all duration-300">
                            Edit
                        </a>
                        <a href="{{ route('quiz.show', $quiz->id_quiz) }}" class="bg-white border border-gray-300 text-black text-center font-medium py-2 rounded-full text-sm hover:bg-gray-50 hover:shadow-lg hover:-translate-y-0.5 hover:text-[#528FB9] hover:border-[#528FB9] transform transition-all duration-300">
                            Statistics
                        </a>
                        <form action="{{ route('my-quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz permanently?')" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button class="bg-[#ff4d4d] text-white w-full text-center font-medium py-2 rounded-full text-sm hover:bg-[#e60000] hover:shadow-lg hover:-translate-y-0.5 transform transition-all duration-300">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            
            @if($quizzes->isEmpty())
                <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500">No quizzes found.</p>
                </div>
            @endif
        </div>

        <!-- PAGINATION -->
        @if($quizzes->hasPages() || $quizzes->total() > 5)
        <div class="mt-8 flex flex-wrap justify-between items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-500">Per page:</span>
                <form method="GET" action="{{ route('my-quizzes.index') }}#quizzes-section">
                    <!-- Keep all current filters -->
                    <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
                    <input type="hidden" name="major" value="{{ request('major') }}">
                    <input type="hidden" name="course" value="{{ request('course') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @foreach(request('tags', []) as $tag)
                        <input type="hidden" name="tags[]" value="{{ $tag }}">
                    @endforeach

                    <select name="per_page" onchange="this.form.submit()" class="border-gray-300 rounded-xl text-sm py-1.5 focus:ring-[#528FB9] focus:border-[#528FB9] cursor-pointer hover:bg-[#eef8fc] transition duration-300">
                        @foreach([5, 10, 25, 50, 100] as $num)
                            <option value="{{ $num }}" {{ request('per_page', 10) == $num ? 'selected' : '' }}>{{ $num }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            
            <div class="mt-4 sm:mt-0 pagination-custom">
                {{ $quizzes->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>

    <!-- NEW FOLDER MODAL -->
    <div x-show="folderModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="folderModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="folderModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="folderModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('folders.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-6 pt-6 pb-6 relative">
                        <!-- Close Button -->
                        <button type="button" @click="folderModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <h3 class="text-3xl font-extrabold text-black mb-6" id="modal-title">New Folder</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-lg font-medium text-black mb-2">Folder Name</label>
                            <input type="text" name="name" id="name" required placeholder="Enter your folder name here" class="w-full border-gray-300 rounded-xl px-4 py-3 bg-gray-50 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm text-gray-700">
                        </div>
                    </div>
                    <div class="px-6 pb-6 flex justify-end">
                        <button type="submit" class="w-32 bg-[#528FB9] text-white font-medium py-2.5 rounded-full hover:bg-[#3E779F] transition shadow-md">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- FILTER MODAL -->
    <div x-show="filterModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="filterModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="filterModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="filterModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full relative"
                 x-data="{
                    searchQuery: '',
                    appliedTags: {{ json_encode(array_values(array_filter(array_map(function($id) { return \App\Models\Tag::find($id); }, $filterTags)))) }},
                    popularTags: {{ json_encode($popularTags) }},
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
                <!-- Form handled carefully to interact with JS dynamically -->
                <form action="{{ route('my-quizzes.index') }}#quizzes-section" method="GET" class="p-8">
                    <!-- Close button -->
                    <button type="button" @click="filterModal = false" class="absolute top-8 right-8 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <!-- Preserve existing filters except tags -->
                    <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
                    <input type="hidden" name="major" value="{{ request('major') }}">
                    <input type="hidden" name="course" value="{{ request('course') }}">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    
                    <!-- Hidden inputs for applied tags to submit -->
                    <template x-for="tag in appliedTags" :key="tag.id_tag">
                        <input type="hidden" name="tags[]" :value="tag.id_tag">
                    </template>

                    <h3 class="text-3xl font-extrabold text-black mb-6" id="modal-title">Filter</h3>
                    
                    <div class="relative mb-8">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" x-model="searchQuery" placeholder="Search tags" class="w-full pl-12 pr-4 py-2.5 rounded-full border border-gray-300 bg-gray-50 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm transition text-gray-700">
                        
                        <!-- Live Search Results Dropdown -->
                        <div x-show="searchResults.length > 0" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg">
                            <ul class="py-1">
                                <template x-for="tag in searchResults" :key="tag.id_tag">
                                    <li @click="addTag(tag)" class="px-4 py-2 hover:bg-[#A6D9F0] hover:text-[#104876] cursor-pointer text-sm text-gray-700 transition">
                                        <span x-text="tag.name"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>

                    <div class="mb-6" x-show="appliedTags.length > 0" style="display: none;">
                        <h4 class="text-lg font-medium text-black mb-3">Applied</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in appliedTags" :key="tag.id_tag">
                                <label @click="removeTag(tag.id_tag)" class="cursor-pointer inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-[#528FB9] text-white hover:bg-red-500 transition group shadow-sm">
                                    <span x-text="tag.name"></span>
                                    <svg class="w-4 h-4 ml-1.5 opacity-70 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="mb-10" x-show="availablePopularTags.length > 0" style="display: none;">
                        <h4 class="text-lg font-medium text-black mb-3">Popular Tags</h4>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in availablePopularTags" :key="tag.id_tag">
                                <label @click="addTag(tag)" class="cursor-pointer inline-flex items-center px-5 py-1.5 rounded-full text-sm font-medium border border-gray-300 text-black hover:border-[#528FB9] hover:bg-[#A6D9F0] hover:text-[#104876] transition duration-300">
                                    <span x-text="tag.name"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="w-32 bg-[#528FB9] text-white font-medium py-2.5 rounded-full hover:bg-[#3E779F] transition shadow-md">
                            Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent; 
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1; 
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8; 
}

/* Customizing Tailwind Pagination Links for styling consistency */
.pagination-custom nav {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.pagination-custom nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between {
    display: flex !important;
    justify-content: flex-end;
    width: 100%;
}
.pagination-custom nav > div.hidden.sm\:flex-1.sm\:flex.sm\:items-center.sm\:justify-between > div:first-child {
    display: none; /* Hide 'Showing 1 to 10 of 50 results' */
}
.pagination-custom span.relative.inline-flex.items-center,
.pagination-custom a.relative.inline-flex.items-center {
    border-radius: 0.5rem !important;
    transition: all 0.3s ease;
}
.pagination-custom a.relative.inline-flex.items-center:hover {
    background-color: #A6D9F0 !important;
    color: #104876 !important;
    border-color: #528FB9 !important;
}
.pagination-custom [aria-current="page"] span {
    background-color: #528FB9 !important;
    color: white !important;
    border-color: #528FB9 !important;
}
</style>

@if(session('clearDraftKey'))
<script>
    Object.keys(localStorage).forEach((key) => {
        if (key.startsWith('pensquiz-quiz-draft-v5-')) {
            localStorage.removeItem(key);
        }
    });
</script>
@endif

@if(!empty($scrollTo))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const element = document.getElementById("{{ $scrollTo }}");
        if (element) {
            setTimeout(() => {
                element.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 100);
        }
    });
</script>
@endif

</x-app-layout>