<x-app-layout>

<div class="max-w-6xl mx-auto p-6" x-data="{ editFolderModal: false }">

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

    <!-- FOLDER HEADER -->
    <div class="flex items-start justify-between mb-10 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 flex-shrink-0">
                <img src="{{ asset('assets/images/img_folder.png') }}" alt="Folder" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-black mb-1">{{ $folder->name }}</h1>
                <p class="text-gray-500 font-medium">{{ $folder->quizzes_count }} items</p>
            </div>
        </div>
        
        <!-- SETTINGS DROPDOWN -->
        <div x-data="{ openSettings: false }" class="relative">
            <button @click="openSettings = !openSettings" @click.away="openSettings = false" class="p-2 rounded-full hover:bg-[#eef8fc] hover:text-[#528FB9] transition text-black border border-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
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

    <!-- QUIZZES LIST -->
    <div id="quizzes-section">
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

            <!-- Sort Filter -->
            <form method="GET" action="{{ route('folders.show', $folder) }}#quizzes-section" class="flex gap-3">
                <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                
                <div x-data="{ open: false, value: '{{ request('sort', 'latest') }}', label: '{{ request('sort', 'latest') == 'latest' ? 'Latest' : 'Oldest' }}' }" class="relative">
                    <input type="hidden" name="sort" x-model="value">
                    <button type="button" @click="open = !open" @click.away="open = false" class="border border-gray-300 rounded-xl text-sm py-1.5 pl-4 pr-3 focus:ring-2 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9] flex items-center justify-between min-w-[110px] bg-white h-full relative">
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
                        <a href="{{ route('my-quizzes.edit', $quiz) }}" class="bg-[#104876] text-white text-center font-bold py-2 rounded-full text-sm hover:bg-[#0c365a] hover:shadow-lg hover:-translate-y-0.5 transform transition-all duration-300">
                            Edit
                        </a>
                        <a href="{{ route('my-quizzes.statistics', $quiz) }}" class="bg-white border border-gray-200 text-black text-center font-bold py-2 rounded-full text-sm shadow-sm hover:bg-gray-50 hover:shadow-md hover:-translate-y-0.5 transform transition-all duration-300">
                            Statistics
                        </a>
                        <form action="{{ route('my-quizzes.destroy', $quiz) }}" method="POST" onsubmit="return confirm('Delete this quiz permanently?')" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-[#ff4d4d] text-white w-full text-center font-bold py-2 rounded-full text-sm hover:bg-[#e60000] hover:shadow-lg hover:-translate-y-0.5 transform transition-all duration-300">
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
                <form method="GET" action="{{ route('folders.show', $folder) }}#quizzes-section">
                    <input type="hidden" name="visibility" value="{{ request('visibility', 'all') }}">
                    <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">

                    <div x-data="{ open: false, value: '{{ request('per_page', 10) }}', label: '{{ request('per_page', 10) }}' }" class="relative">
                        <input type="hidden" name="per_page" x-model="value">
                        <button type="button" @click="open = !open" @click.away="open = false" class="border border-gray-300 rounded-xl text-sm py-1.5 pl-4 pr-3 focus:ring-2 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm cursor-pointer text-gray-600 hover:bg-[#eef8fc] hover:text-[#528FB9] transition duration-300 hover:border-[#528FB9] flex items-center justify-between min-w-[70px] bg-white relative">
                            <span x-text="label" class="pr-3"></span>
                            <svg class="w-4 h-4 text-gray-400 absolute right-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                        <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" class="absolute z-50 mb-1 w-full min-w-[70px] left-0 bottom-full bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden py-1">
                            @foreach([5, 10, 25, 50, 100] as $num)
                                <div @click="value = '{{ $num }}'; label = '{{ $num }}'; open = false; $nextTick(() => $el.closest('form').submit())" class="px-4 py-2 text-sm cursor-pointer transition rounded-lg mx-1 text-center {{ request('per_page', 10) == $num ? 'bg-[#528FB9] text-white' : 'text-gray-700 hover:bg-[#528FB9] hover:text-white' }}">
                                    {{ $num }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="mt-4 sm:mt-0 pagination-custom">
                {{ $quizzes->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
    </div>

    <!-- EDIT FOLDER MODAL -->
    <div x-show="editFolderModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="editFolderModal" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="editFolderModal = false" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="editFolderModal" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('folders.update', $folder) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="bg-white px-6 pt-6 pb-6 relative">
                        <!-- Close Button -->
                        <button type="button" @click="editFolderModal = false" class="absolute top-6 right-6 text-gray-400 hover:text-red-500 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <h3 class="text-3xl font-extrabold text-black mb-6" id="modal-title">Edit Folder</h3>
                        <div class="mb-4">
                            <label for="name" class="block text-lg font-medium text-black mb-2">Folder Name</label>
                            <input type="text" name="name" id="name" value="{{ $folder->name }}" required placeholder="Enter your folder name here" class="w-full border-gray-300 rounded-xl px-4 py-3 bg-gray-50 focus:ring-[#528FB9] focus:border-[#528FB9] shadow-sm text-gray-700">
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
