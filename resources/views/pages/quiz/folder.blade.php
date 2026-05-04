<x-app-layout>

{{-- ================================================================ --}}
{{-- MAIN CONTAINER                                                   --}}
{{-- Alpine State:                                                    --}}
{{-- editFolderModal: Kontrol popup edit folder                       --}}
{{-- addQuizModal: Kontrol popup pilih kuis untuk ditambah            --}}
{{-- confirmModal: Kontrol popup konfirmasi penambahan kuis           --}}
{{-- selectedQuizId, selectedQuizTitle: Menyimpan data kuis pilihan   --}}
{{-- ================================================================ --}}
<div class="max-w-6xl mx-auto p-4 sm:p-6" x-data="{ 
    editFolderModal: false, 
    addQuizModal: false,
    confirmModal: false,
    selectedQuizId: null,
    selectedQuizTitle: ''
}">

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="fixed top-24 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi Error --}}
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

    {{-- ================================================================ --}}
    {{-- FOLDER HEADER                                                    --}}
    {{-- Menampilkan info folder, tombol Add Quiz, dan Settings (Edit/Del)--}}
    {{-- ================================================================ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 sm:mb-10 bg-white p-5 sm:p-6 rounded-2xl shadow-sm border border-gray-100 gap-4 sm:gap-0">
        <div class="flex items-center gap-4 sm:gap-6">
            <div class="w-16 h-16 sm:w-24 sm:h-24 flex-shrink-0">
                <img src="{{ asset('assets/images/img_folder.png') }}" alt="Folder" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-black mb-1 break-all">{{ $folder->name }}</h1>
                <p class="text-gray-500 font-medium text-sm sm:text-base">{{ $folder->quizzes_count }} items</p>
            </div>
        </div>
        
        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
            {{-- Tombol Add Quiz ke Folder --}}
            <button @click="addQuizModal = true" class="bg-[#528FB9] text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-sm sm:text-base font-bold hover:bg-[#3E779F] transition shadow-sm flex items-center gap-2">
                <span class="hidden sm:inline">Add Quiz</span>
                <span class="sm:hidden">Add</span>
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
            </button>

            <!-- SETTINGS DROPDOWN -->
            <div x-data="{ openSettings: false }" class="relative">
                <button @click="openSettings = !openSettings" @click.away="openSettings = false" class="p-2 sm:p-2.5 rounded-full hover:bg-[#eef8fc] hover:text-[#528FB9] transition text-black border border-gray-300">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </button>
                
                <div x-show="openSettings" x-transition.opacity.duration.200ms style="display: none;" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-2 z-10 overflow-hidden">
                    <div @click="editFolderModal = true; openSettings = false" class="px-4 py-2.5 text-sm font-bold text-black hover:bg-[#528FB9] hover:text-white cursor-pointer transition flex items-center gap-3">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        Edit Folder
                    </div>
                    <div class="h-px bg-gray-200 my-1"></div>
                    <form action="{{ route('folders.destroy', $folder) }}" method="POST" onsubmit="return confirm('Delete this folder? This will delete all quizzes inside it permanently.');" class="w-full m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-[#ff4d4d] hover:text-white cursor-pointer transition flex items-center gap-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                            Delete Folder
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- DAFTAR KUIS DALAM FOLDER                                         --}}
    {{-- ================================================================ --}}
    <div id="quizzes-section">
        <!-- SEARCH BAR -->
        <form method="GET" action="{{ route('folders.show', $folder) }}#quizzes-section" class="w-full relative mb-6">
            <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
            <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">
            @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif
            
            <div class="relative group w-full">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 sm:pl-5 pointer-events-none text-gray-400 group-focus-within:text-[#528FB9] transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="search" name="search" value="{{ request('search') }}"
                    class="block w-full p-4 sm:p-5 pl-12 sm:pl-14 text-[15px] sm:text-base text-gray-900 border-2 border-gray-100 rounded-[20px] sm:rounded-full bg-white focus:ring-0 focus:border-[#528FB9] transition-all duration-300 shadow-sm hover:border-gray-200" 
                    placeholder="Search folder by title, tag, major, or course...">
                @if(request('search'))
                    <a href="{{ route('folders.show', ['folder' => $folder, 'visibility' => request('visibility', 'all'), 'sort' => request('sort', 'latest'), 'per_page' => request('per_page')]) }}#quizzes-section" class="absolute inset-y-0 right-4 flex items-center pr-3 text-gray-400 hover:text-red-500 transition-colors z-10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </form>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
            <!-- Visibility Filters -->
            <div class="flex flex-wrap gap-2 sm:gap-3 w-full sm:w-auto">
                @php $currentVisibility = request('visibility', 'all'); @endphp
                @foreach(['all', 'published', 'draft'] as $v)
                    <a href="{{ request()->fullUrlWithQuery(['visibility' => $v]) }}#quizzes-section"
                       class="px-4 sm:px-5 py-1.5 rounded-full border text-[13px] sm:text-sm font-medium transition duration-300 ease-out hover:bg-[#A6D9F0] hover:border-[#528FB9] hover:text-[#104876] flex-1 sm:flex-none text-center
                       {{ $currentVisibility == $v ? 'bg-[#528FB9] text-white border-[#528FB9]' : 'bg-white text-black border-gray-300' }}">
                        {{ ucfirst($v) }}
                    </a>
                @endforeach
            </div>

            <!-- Sort Filter -->
            <form method="GET" action="{{ route('folders.show', $folder) }}#quizzes-section" class="flex gap-3 w-full sm:w-auto">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                
                <div x-data="{ open: false, value: '{{ request('sort', 'latest') }}', label: '{{ request('sort', 'latest') == 'latest' ? 'Latest' : 'Oldest' }}' }" class="relative w-full sm:w-auto">
                    <input type="hidden" name="sort" x-model="value">
                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full sm:w-auto border border-gray-300 rounded-xl text-sm py-2 sm:py-1.5 pl-4 pr-3 focus:ring-2 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9] flex items-center justify-between min-w-[110px] bg-white relative">
                        <span x-text="label" class="truncate pr-4"></span>
                        <svg class="w-4 h-4 ml-2 text-gray-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute z-50 mt-1 w-full min-w-[120px] right-0 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden py-1">
                        <div @click="value = 'latest'; label = 'Latest'; open = false; $nextTick(() => $el.closest('form').submit())" class="px-4 py-2 text-sm cursor-pointer transition rounded-lg mx-1 {{ request('sort', 'latest') == 'latest' ? 'bg-[#528FB9] text-white' : 'text-gray-700 hover:bg-[#528FB9] hover:text-white' }}">Latest</div>
                        <div @click="value = 'oldest'; label = 'Oldest'; open = false; $nextTick(() => $el.closest('form').submit())" class="px-4 py-2 text-sm cursor-pointer transition rounded-lg mx-1 {{ request('sort') == 'oldest' ? 'bg-[#528FB9] text-white' : 'text-gray-700 hover:bg-[#528FB9] hover:text-white' }}">Oldest</div>
                    </div>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @foreach($quizzes as $quiz)
                <div class="bg-white border border-gray-200 rounded-2xl flex flex-col sm:flex-row overflow-hidden shadow-sm hover:shadow-lg hover:shadow-[#528FB9]/40 hover:border-[#A6D9F0] transition duration-300">
                    <!-- COVER -->
                    <div class="w-full sm:w-48 h-32 sm:h-auto bg-gray-100 flex-shrink-0 relative">
                        @if($quiz->cover_image_url)
                            <img src="{{ $quiz->cover_image_url }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-[#528FB9]/10">
                                <svg class="w-12 h-12 text-[#528FB9]/40" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/></svg>
                            </div>
                        @endif
                    </div>

                    <!-- CONTENT -->
                    <div class="flex-1 p-4 sm:p-5 flex flex-col justify-center border-b sm:border-b-0 border-gray-100">
                        <div class="flex items-center gap-2 sm:gap-3 mb-1 flex-wrap">
                            <h2 class="font-bold text-lg sm:text-xl text-black">{{ $quiz->title }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] sm:text-xs font-medium border border-gray-300 text-black">
                                {{ ucfirst($quiz->visibility) }}
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-1 sm:gap-2 text-[13px] sm:text-[15px] text-black mb-3">
                            <span>{{ $quiz->major->name ?? 'Quiz Major' }}</span>
                            <span class="font-bold hidden sm:inline">&middot;</span>
                            <span class="font-bold sm:hidden">|</span>
                            <span>{{ $quiz->course->name ?? 'Quiz Subject' }}</span>
                        </div>

                        <div>
                            <p class="text-[14px] sm:text-[15px] text-black font-medium mb-1">Description</p>
                            <p class="text-[13px] sm:text-[15px] text-black leading-snug line-clamp-2 sm:line-clamp-1">
                                {{ $quiz->description ?: 'No description provided.' }}
                            </p>
                        </div>
                        
                        <div class="mt-3 sm:mt-4 text-[12px] sm:text-[14px] font-medium text-gray-400">
                            Modified {{ $quiz->updated_at->diffForHumans() }}
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="w-full sm:w-48 sm:border-l border-gray-100 p-4 sm:p-5 flex flex-row sm:flex-col gap-2 sm:gap-3 justify-center bg-gray-50/50 sm:bg-white">
                        <a href="{{ route('my-quizzes.edit', $quiz) }}" class="flex-1 sm:flex-none bg-[#104876] text-white text-center font-bold py-2 sm:py-2 rounded-full text-[13px] sm:text-sm hover:bg-[#0c365a] hover:shadow-lg hover:-translate-y-0.5 transform transition-all duration-300">
                            Edit
                        </a>
                        <a href="{{ route('my-quizzes.statistics', $quiz) }}" class="flex-1 sm:flex-none bg-white border border-gray-200 text-black text-center font-bold py-2 sm:py-2 rounded-full text-[13px] sm:text-sm shadow-sm hover:bg-gray-50 hover:shadow-md hover:-translate-y-0.5 transform transition-all duration-300">
                            Stats
                        </a>
                        <!-- Remove from Folder button? User can just delete the quiz, but usually folders means we can remove it from folder. We use edit quiz to change folder. So we keep delete as is. -->
                        <form action="{{ route('my-quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz permanently?')" class="flex-1 sm:flex-none w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-[#ff4d4d] text-white w-full text-center font-bold py-2 sm:py-2 rounded-full text-[13px] sm:text-sm hover:bg-[#e60000] hover:shadow-lg hover:-translate-y-0.5 transform transition-all duration-300">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
            
            @if($quizzes->isEmpty())
                <div class="text-center py-10 sm:py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <p class="text-gray-500 font-medium text-[15px]">This folder is empty.</p>
                    <p class="text-gray-400 text-sm mt-1 mb-4">Add your existing quizzes into this folder.</p>
                    <button @click="addQuizModal = true" class="text-[#528FB9] font-bold hover:underline">Add Quiz Now</button>
                </div>
            @endif
        </div>

        <!-- PAGINATION -->
        @if($quizzes->hasPages() || $quizzes->total() > 5)
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-xl border border-gray-200 shadow-sm gap-4 sm:gap-0">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <span class="text-[13px] sm:text-sm font-medium text-gray-500">Per page:</span>
                <form method="GET" action="{{ route('folders.show', $folder) }}#quizzes-section" class="flex-1 sm:flex-none">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">

                    <div x-data="{ open: false, value: '{{ request('per_page', 10) }}', label: '{{ request('request', 10) }}' }" class="relative w-full sm:w-auto">
                        <input type="hidden" name="per_page" x-model="value">
                        <button type="button" @click="open = !open" @click.away="open = false" class="w-full sm:w-auto border border-gray-300 rounded-xl text-sm py-2 sm:py-1.5 pl-4 pr-3 focus:ring-2 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9] flex items-center justify-between min-w-[70px] bg-white relative">
                            <span x-text="value" class="pr-3"></span>
                            <svg class="w-4 h-4 text-gray-400 absolute right-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute z-50 mb-1 w-full min-w-[70px] left-0 bottom-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden py-1">
                            @foreach([5, 10, 25, 50, 100] as $num)
                                <div @click="value = '{{ $num }}'; open = false; $nextTick(() => $el.closest('form').submit())" class="px-4 py-2 text-sm cursor-pointer transition rounded-lg mx-1 text-center {{ request('per_page', 10) == $num ? 'bg-[#528FB9] text-white' : 'text-gray-700 hover:bg-[#528FB9] hover:text-white' }}">
                                    {{ $num }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="w-full sm:w-auto pagination-custom overflow-x-auto no-scrollbar">
                {{ $quizzes->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL: ADD QUIZ TO FOLDER                                        --}}
    {{-- ================================================================ --}}
    <div x-show="addQuizModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="addQuizModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="addQuizModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="addQuizModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-t-2xl sm:rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full flex flex-col max-h-[85vh]">
                <div class="bg-white px-5 sm:px-6 pt-5 sm:pt-6 pb-4 sm:pb-5 relative border-b border-gray-100 flex-shrink-0">
                    <button type="button" @click="addQuizModal = false" class="absolute top-5 right-5 sm:top-6 sm:right-6 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-black pr-8">Add Quiz to Folder</h3>
                    <p class="text-sm text-gray-500 mt-1">Select a quiz from your collection to add to "{{ $folder->name }}".</p>
                </div>
                
                <div class="overflow-y-auto flex-1 p-4 sm:p-6 bg-gray-50">
                    @if(isset($availableQuizzes) && $availableQuizzes->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                            @foreach($availableQuizzes as $availableQuiz)
                                <button type="button" 
                                    @click="selectedQuizId = {{ $availableQuiz->id_quiz }}; selectedQuizTitle = '{{ addslashes($availableQuiz->title) }}'; addQuizModal = false; setTimeout(() => confirmModal = true, 300);"
                                    class="text-left bg-white border border-gray-200 rounded-xl p-4 hover:border-[#528FB9] hover:shadow-md hover:-translate-y-1 transition-all duration-300 group focus:outline-none focus:ring-2 focus:ring-[#528FB9] focus:ring-offset-2">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-[#eef8fc] text-[#528FB9] flex items-center justify-center shrink-0 group-hover:bg-[#528FB9] group-hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-[15px] text-gray-900 line-clamp-2 group-hover:text-[#528FB9] transition-colors">{{ $availableQuiz->title }}</h4>
                                            <p class="text-[12px] text-gray-500 mt-1">{{ $availableQuiz->questions_count ?? 0 }} questions &middot; {{ ucfirst($availableQuiz->visibility) }}</p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <p class="text-gray-600 font-medium">No available quizzes to add.</p>
                            <p class="text-gray-400 text-sm mt-1">All your quizzes are already in a folder or you haven't created any.</p>
                            <a href="{{ route('my-quizzes.create') }}" class="inline-block mt-4 text-[#528FB9] font-bold hover:underline">Create New Quiz</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL: CONFIRM ADD QUIZ                                          --}}
    {{-- ================================================================ --}}
    <div x-show="confirmModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="confirmModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="confirmModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="confirmModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full p-6 sm:p-8">
                <div class="w-16 h-16 bg-[#eef8fc] text-[#528FB9] rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                </div>
                
                <h3 class="text-xl sm:text-2xl font-black text-center text-gray-900 mb-2">Confirm Action</h3>
                <p class="text-center text-gray-600 mb-8 text-sm sm:text-base">
                    Are you sure you want to add the quiz <br>
                    <strong class="text-black" x-text="selectedQuizTitle"></strong> <br>
                    to the folder <strong>"{{ $folder->name }}"</strong>?
                </p>

                <form action="{{ route('folders.addQuiz', $folder) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="hidden" name="quiz_id" x-bind:value="selectedQuizId">
                    <button type="button" @click="confirmModal = false" class="w-full sm:flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="w-full sm:flex-1 py-3 px-4 bg-[#528FB9] hover:bg-[#3E779F] text-white font-bold rounded-xl transition shadow-md hover:shadow-lg">
                        Yes, Add Quiz
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- MODAL: EDIT FOLDER (EXISTING)                                    --}}
    {{-- ================================================================ --}}
    <div x-show="editFolderModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="editFolderModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editFolderModal = false" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="editFolderModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-t-2xl sm:rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form action="{{ route('folders.update', $folder) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-5 sm:px-6 pt-5 sm:pt-6 pb-6 relative">
                        <button type="button" @click="editFolderModal = false" class="absolute top-5 sm:top-6 right-5 sm:right-6 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-black mb-6" id="modal-title">Edit Folder</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-base sm:text-lg font-medium text-black mb-2">Folder Name</label>
                            <input type="text" name="name" id="name" value="{{ $folder->name }}" required placeholder="Enter your folder name here" class="w-full border-gray-300 rounded-xl px-4 py-3 bg-gray-50 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm text-gray-700">
                        </div>
                    </div>
                    <div class="px-5 sm:px-6 pb-5 sm:pb-6 flex justify-end">
                        <button type="submit" class="w-full sm:w-32 bg-[#528FB9] text-white font-medium py-3 sm:py-2.5 rounded-xl sm:rounded-full hover:bg-[#3E779F] transition shadow-md">
                            Save
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

/* Hide scrollbar for horizontal scrolling elements */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
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
    background-color: white !important;
    color: #4b5563 !important;
    border-color: #d1d5db !important;
}
.pagination-custom a.relative.inline-flex.items-center:hover {
    background-color: #528EB8 !important;
    color: white !important;
    border-color: #528EB8 !important;
}
.pagination-custom [aria-current="page"] span {
    background-color: #eef8fc !important;
    color: #104876 !important;
    border-color: #104876 !important;
    font-weight: bold;
}
</style>

</x-app-layout>
