<x-app-layout>

@php
    $isEdit = $quiz->exists;
    $questionsData = old('questions', $formQuestions);
    $allTags = \App\Models\Tag::pluck('name')->toArray();
@endphp

<div class="min-h-screen px-4 py-8 lg:px-6" x-data="quizFormHandler()">
    <div class="mx-auto max-w-4xl">

        <form
            id="quizForm"
            action="{{ $isEdit ? route('my-quizzes.update', $quiz) : route('my-quizzes.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-8"
        >
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <input type="hidden" name="visibility" x-model="visibility">

            <!-- STAGE 1: QUIZ DETAIL -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                <!-- Header -->
                <div class="flex items-center justify-between mb-8 relative">
                    <button type="button" @click="showCancelModal = true" class="absolute left-0 flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-black transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        Cancel
                    </button>
                    <h1 class="text-2xl font-extrabold text-black w-full text-center">Quiz Detail</h1>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-[32px] shadow-sm border border-gray-200">
                    <!-- Default Cover Banner -->
                    <div class="h-32 w-full bg-gray-100 relative rounded-t-[32px] overflow-hidden">
                        <img src="{{ asset('assets/default-cover.png') }}" class="w-full h-full object-cover" alt="Quiz Cover">
                    </div>

                    <div class="p-8 lg:p-12 space-y-8">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Quiz Title</label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="title" 
                                    x-model="title"
                                    maxlength="100"
                                    placeholder="Enter your quiz title here" 
                                    class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black placeholder:text-gray-300 transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none"
                                >
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-[10px] font-bold text-gray-300 uppercase tracking-wider">
                                    <span x-text="title.length"></span>/100 Characters
                                </div>
                            </div>
                            @error('title') <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Major & Course -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Quiz Major</label>
                                <div x-data="{ open: false, value: '{{ old('major_id', $quiz->major_id ?? '') }}', label: '{{ old('major_id', $quiz->major_id) && $majors->firstWhere('id_major', old('major_id', $quiz->major_id)) ? $majors->firstWhere('id_major', old('major_id', $quiz->major_id))->name : 'Choose your quiz major here' }}' }" class="relative">
                                    <input type="hidden" name="major_id" x-model="value" @change="major_id = value; isDirty = true">
                                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none flex items-center justify-between shadow-sm hover:border-[#528FB9]">
                                        <span x-text="label" class="truncate pr-4"></span>
                                        <svg class="w-4 h-4 text-gray-400 pointer-events-none transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-transition style="display: none;" class="absolute z-[60] mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto py-1">
                                        <div @click="value = ''; label = 'Choose your quiz major here'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl">Choose your quiz major here</div>
                                        @foreach($majors as $major)
                                            <div @click="value = '{{ $major->id_major }}'; label = '{{ $major->name }}'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl" :class="value == '{{ $major->id_major }}' ? 'bg-[#eef8fc] text-[#528FB9]' : ''">{{ $major->name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Quiz Course</label>
                                <div x-data="{ open: false, value: '{{ old('course_id', $quiz->course_id ?? '') }}', label: '{{ old('course_id', $quiz->course_id) && $courses->firstWhere('id_course', old('course_id', $quiz->course_id)) ? $courses->firstWhere('id_course', old('course_id', $quiz->course_id))->name : 'Choose your quiz course here' }}' }" class="relative">
                                    <input type="hidden" name="course_id" x-model="value" @change="course_id = value; isDirty = true">
                                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none flex items-center justify-between shadow-sm hover:border-[#528FB9]">
                                        <span x-text="label" class="truncate pr-4"></span>
                                        <svg class="w-4 h-4 text-gray-400 pointer-events-none transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-transition style="display: none;" class="absolute z-[60] mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto py-1">
                                        <div @click="value = ''; label = 'Choose your quiz course here'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl">Choose your quiz course here</div>
                                        @foreach($courses as $course)
                                            <div @click="value = '{{ $course->id_course }}'; label = '{{ $course->name }}'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl" :class="value == '{{ $course->id_course }}' ? 'bg-[#eef8fc] text-[#528FB9]' : ''">{{ $course->name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Folder Selection -->
                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Quiz Folder (Optional)</label>
                            <div x-data="{ open: false, value: '{{ old('folder_id', $quiz->folder_id ?? '') }}', label: '{{ old('folder_id', $quiz->folder_id) && $folders->firstWhere('id_folder', old('folder_id', $quiz->folder_id)) ? $folders->firstWhere('id_folder', old('folder_id', $quiz->folder_id))->name : 'No Folder' }}' }" class="relative">
                                <input type="hidden" name="folder_id" x-model="value" @change="folder_id = value; isDirty = true">
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none flex items-center justify-between shadow-sm hover:border-[#528FB9]">
                                    <span x-text="label" class="truncate pr-4"></span>
                                    <svg class="w-4 h-4 text-gray-400 pointer-events-none transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div x-show="open" x-transition style="display: none;" class="absolute z-[60] mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl max-h-60 overflow-y-auto py-1">
                                    <div @click="value = ''; label = 'No Folder'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl">No Folder</div>
                                    @foreach($folders as $folder)
                                        <div @click="value = '{{ $folder->id_folder }}'; label = '{{ $folder->name }}'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl" :class="value == '{{ $folder->id_folder }}' ? 'bg-[#eef8fc] text-[#528FB9]' : ''">{{ $folder->name }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-black mb-3">Description</label>
                            <div class="relative">
                                <textarea 
                                    name="description" 
                                    rows="4" 
                                    x-model="description"
                                    maxlength="300"
                                    placeholder="Enter your quiz description here" 
                                    class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black placeholder:text-gray-300 transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none resize-none"
                                ></textarea>
                                <div class="absolute right-5 bottom-4 text-[10px] font-bold text-gray-300 uppercase tracking-wider">
                                    <span x-text="description.length"></span>/300 Characters
                                </div>
                            </div>
                        </div>

                        <!-- Duration & Access -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Duration (minutes)</label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="time_limit_minutes" 
                                        x-model="time_limit_minutes"
                                        placeholder="e.g. 60" 
                                        class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none"
                                    >
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-black mb-3">Access</label>
                                <div x-data="{ open: false, value: '{{ old('access', $quiz->access ?? 'private') }}', label: '{{ old('access', $quiz->access) == 'public' ? 'Public' : 'Private' }}' }" class="relative">
                                    <input type="hidden" name="access" x-model="value" @change="access = value; isDirty = true">
                                    <button type="button" @click="open = !open" @click.away="open = false" class="w-full rounded-2xl border border-gray-200 bg-white px-5 py-4 text-sm text-black transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none flex items-center justify-between shadow-sm hover:border-[#528FB9]">
                                        <span x-text="label" class="truncate pr-4"></span>
                                        <svg class="w-4 h-4 text-gray-400 pointer-events-none transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div x-show="open" x-transition style="display: none;" class="absolute z-[60] mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl overflow-hidden py-1">
                                        <div @click="value = 'private'; label = 'Private'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl" :class="value == 'private' ? 'bg-[#eef8fc] text-[#528FB9]' : ''">Private</div>
                                        <div @click="value = 'public'; label = 'Public'; open = false" class="px-5 py-3 text-sm text-gray-700 hover:bg-[#528FB9] hover:text-white cursor-pointer transition mx-1 rounded-xl" :class="value == 'public' ? 'bg-[#eef8fc] text-[#528FB9]' : ''">Public</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Allow Copy -->
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer group">
                                <input type="checkbox" name="allow_copy" value="1" x-model="allow_copy" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#104876]"></div>
                                <span class="ml-3 text-sm font-bold text-black group-hover:text-[#104876] transition">Allow Copy</span>
                            </label>
                        </div>

                        <!-- Tags System -->
                        <div>
                            <label class="block text-sm font-bold text-black mb-4">Tags</label>
                            
                            <!-- Selected Tags -->
                            <div class="flex flex-wrap gap-2 mb-4 min-h-[36px]">
                                <template x-for="(tag, index) in tags" :key="index">
                                    <div class="flex items-center gap-2 bg-[#528EB8] text-white px-4 py-1.5 rounded-full text-xs font-bold transition hover:bg-[#3E779F]">
                                        <span x-text="tag"></span>
                                        <button type="button" @click="removeTag(index)" class="hover:text-red-200 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                        <input type="hidden" name="tags[]" :value="tag">
                                    </div>
                                </template>
                            </div>

                            <!-- Tag Search & Add -->
                            <div class="flex items-center gap-3 relative">
                                <div class="relative flex-1 max-w-sm">
                                    <input 
                                        type="text" 
                                        x-model="tagSearch" 
                                        @input="handleTagSearch"
                                        @keydown.enter.prevent="addTag(tagSearch)"
                                        placeholder="Search or add tags..." 
                                        class="w-full rounded-full border border-gray-200 bg-white px-5 py-2.5 text-sm text-black transition focus:border-[#528FB9] focus:ring-2 focus:ring-[#528FB9]/20 focus:outline-none shadow-sm"
                                    >
                                    
                                    <!-- Recommendations Dropdown -->
                                    <div 
                                        x-show="showTagRecs && tagRecs.length > 0" 
                                        @click.away="showTagRecs = false"
                                        style="display: none;"
                                        class="absolute top-full left-0 w-full mt-2 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden py-1"
                                    >
                                        <template x-for="rec in tagRecs" :key="rec">
                                            <button 
                                                type="button" 
                                                @click="addTag(rec)"
                                                class="w-full text-left px-5 py-2.5 text-sm font-medium hover:bg-[#eef8fc] hover:text-[#528FB9] transition"
                                                x-text="rec"
                                            ></button>
                                        </template>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    @click="addTag(tagSearch)"
                                    class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-[#528FB9] hover:text-white transition shadow-sm font-bold text-xl"
                                >
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Action -->
                <div class="mt-12 flex justify-end">
                    <button 
                        type="button" 
                        id="continue-step-1"
                        @click="step = 2"
                        class="bg-[#104876] text-white w-full sm:w-auto px-10 py-4 rounded-full font-bold shadow-lg hover:bg-[#0c365a] hover:shadow-xl hover:-translate-y-1 transform transition-all flex items-center justify-center gap-3"
                    >
                        Continue
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </button>
                </div>
            </div>

            <!-- STAGE 2: CREATE QUESTIONS -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;" class="relative">
                <!-- Header -->
                <div class="flex items-center justify-between mb-12 relative">
                    <button type="button" @click="showCancelModal = true" class="absolute left-0 flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-black transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        Cancel
                    </button>
                    <h1 class="text-3xl font-extrabold text-black w-full text-center">Questions</h1>
                </div>

                <div class="space-y-16">
                    <template x-for="(q, qIdx) in questions" :key="qIdx">
                        <div class="relative group" :style="{ zIndex: (questions.length - qIdx) * 10 }">
                            <div class="bg-white rounded-[48px] shadow-sm border border-gray-100 p-10 lg:p-14 relative">
                                <!-- Question Header -->
                                <div class="flex items-center justify-between mb-10">
                                    <h2 class="text-2xl font-extrabold text-black" x-text="'Question ' + (qIdx + 1)"></h2>
                                    
                                    <!-- Question Type Dropdown -->
                                    <div x-data="{ open: false }" class="relative">
                                        <button type="button" @click="open = !open" @click.away="open = false" class="rounded-2xl border border-gray-100 bg-white px-6 py-3 text-sm font-bold text-gray-500 transition hover:border-[#528FB9] flex items-center gap-4 min-w-[180px] justify-between">
                                            <span x-text="q.type === 'multiple_choice' ? 'Multiple Choice' : 'Checkbox'"></span>
                                            <svg class="w-4 h-4 text-gray-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl py-1 overflow-hidden">
                                            <button type="button" @click="q.type = 'multiple_choice'; open = false" class="w-full text-left px-5 py-3 text-sm font-bold hover:bg-[#eef8fc] hover:text-[#528FB9] transition">Multiple Choice</button>
                                            <button type="button" @click="q.type = 'checkbox'; open = false" class="w-full text-left px-5 py-3 text-sm font-bold hover:bg-[#eef8fc] hover:text-[#528FB9] transition">Checkbox</button>
                                        </div>
                                        <input type="hidden" :name="'questions['+qIdx+'][type]'" :value="q.type">
                                    </div>
                                </div>

                                <div class="space-y-12">
                                    <!-- Question Content -->
                                    <div>
                                        <label class="block text-xl font-extrabold text-black mb-6">Question</label>
                                        <div class="relative">
                                            <textarea 
                                                :name="'questions['+qIdx+'][content]'" 
                                                x-model="q.content"
                                                rows="5" 
                                                placeholder="Enter your question here" 
                                                class="w-full rounded-[32px] border border-gray-100 bg-white px-8 py-7 text-sm font-medium text-black placeholder:text-gray-200 transition focus:border-[#528FB9] focus:ring-0 resize-none"
                                            ></textarea>
                                            <div class="absolute right-8 top-7 text-[10px] font-bold text-gray-200 uppercase tracking-widest">
                                                <span x-text="q.content.length"></span>/250 Characters
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Choices -->
                                    <div>
                                        <label class="block text-xl font-extrabold text-black mb-6">Choices</label>
                                        <div class="space-y-4 mb-8">
                                            <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                                <div class="flex items-center gap-4 group/choice">
                                                    <div class="relative flex-1">
                                                        <div class="absolute left-6 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-50 text-sm font-bold text-black" x-text="String.fromCharCode(65 + oIdx)"></div>
                                                        <input 
                                                            type="text" 
                                                            :name="'questions['+qIdx+'][options]['+oIdx+'][content]'"
                                                            x-model="opt.content"
                                                            placeholder="Enter your choice here" 
                                                            class="w-full rounded-3xl border-none pl-20 pr-12 py-6 text-sm font-medium text-black placeholder:text-gray-300 transition focus:ring-2 focus:ring-[#528FB9]/20 shadow-sm"
                                                            :class="opt.content ? 'bg-[#eef8fc]' : 'bg-gray-50/50 border border-dashed border-gray-200'"
                                                        >
                                                        <input type="hidden" :name="'questions['+qIdx+'][options]['+oIdx+'][id_option]'" :value="opt.id_option">
                                                        
                                                        <button 
                                                            type="button" 
                                                            @click="removeOption(qIdx, oIdx)" 
                                                            class="absolute right-[-14px] top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full border border-red-500 bg-white text-red-500 hover:bg-red-50 transition shadow-md z-20"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        <button type="button" @click="addOption(qIdx)" class="w-full py-6 rounded-[32px] bg-gray-50/50 text-sm font-bold text-black hover:bg-gray-100 transition border border-dashed border-gray-200">
                                            Add Choice
                                        </button>
                                    </div>

                                    <!-- Correct Answer -->
                                    <div>
                                        <label class="block text-xl font-extrabold text-black mb-6">Correct Answer</label>
                                        
                                        <!-- Multiple Choice Dropdown -->
                                        <template x-if="q.type === 'multiple_choice'">
                                            <div x-data="{ open: false }" class="relative">
                                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full rounded-[32px] border border-gray-100 bg-gray-50/50 px-8 py-7 text-sm font-bold text-black transition flex items-center justify-between hover:border-[#528FB9]">
                                                    <div class="flex items-center gap-6">
                                                        <div x-show="q.correct_option !== null" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-100 text-sm font-bold" x-text="String.fromCharCode(65 + parseInt(q.correct_option))"></div>
                                                        <span x-text="q.options[q.correct_option]?.content || 'Select correct answer'"></span>
                                                    </div>
                                                    <svg class="w-5 h-5 text-gray-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                                </button>
                                                <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-2xl shadow-xl py-1 overflow-hidden">
                                                    <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                                        <button type="button" @click="q.correct_option = oIdx; open = false" class="w-full text-left px-8 py-5 text-sm font-medium hover:bg-[#eef8fc] hover:text-[#528FB9] transition flex items-center gap-6">
                                                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-sm font-bold" x-text="String.fromCharCode(65 + oIdx)"></div>
                                                            <span x-text="opt.content || '(Empty choice)'"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                                <input type="hidden" :name="'questions['+qIdx+'][correct_option]'" :value="q.correct_option">
                                            </div>
                                        </template>

                                        <!-- Checkbox (Multiple Answers) -->
                                        <template x-if="q.type === 'checkbox'">
                                            <div class="space-y-6">
                                                <template x-for="(corIdx, cIdx) in q.correct_options" :key="cIdx">
                                                    <div x-data="{ open: false }" class="relative">
                                                        <div @click="open = !open" @click.away="open = false" class="w-full rounded-[32px] border border-gray-100 bg-gray-50/50 px-8 py-7 text-sm font-bold text-black transition flex items-center justify-between hover:border-[#528FB9] cursor-pointer">
                                                            <div class="flex items-center gap-6">
                                                                <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-50 text-sm font-bold" x-text="String.fromCharCode(65 + parseInt(corIdx))"></div>
                                                                <span x-text="q.options[corIdx]?.content || 'Select correct answer'"></span>
                                                            </div>
                                                            <div class="flex items-center gap-4 pr-4">
                                                                <svg class="w-5 h-5 text-gray-300" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                                            </div>
                                                        </div>

                                                        <!-- Circular Delete Button for Correct Answer slot -->
                                                        <button 
                                                            type="button" 
                                                            @click.stop="q.correct_options.splice(cIdx, 1)" 
                                                            class="absolute right-[-14px] top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-full border border-red-500 bg-white text-red-500 hover:bg-red-50 transition shadow-md z-20"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>

                                                        <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-2 w-full bg-white border border-gray-100 rounded-[32px] shadow-xl py-2 overflow-hidden">
                                                            <template x-for="(opt, oIdx) in q.options" :key="oIdx">
                                                                <button 
                                                                    type="button" 
                                                                    @click="q.correct_options[cIdx] = oIdx; open = false" 
                                                                    x-show="!q.correct_options.includes(oIdx) || corIdx === oIdx"
                                                                    class="w-full text-left px-8 py-5 text-sm font-medium hover:bg-[#eef8fc] hover:text-[#528FB9] transition flex items-center gap-6"
                                                                >
                                                                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-50 border border-gray-50 text-sm font-bold" x-text="String.fromCharCode(65 + oIdx)"></div>
                                                                    <span x-text="opt.content || '(Empty choice)'"></span>
                                                                </button>
                                                            </template>
                                                        </div>
                                                        <input type="hidden" :name="'questions['+qIdx+'][correct_options][]'" :value="corIdx">
                                                    </div>
                                                </template>
                                                <button 
                                                    type="button" 
                                                    @click="let available = q.options.findIndex((_, idx) => !q.correct_options.includes(idx)); if(available !== -1) q.correct_options.push(available)" 
                                                    class="w-full py-7 rounded-[32px] bg-gray-50/50 text-sm font-bold text-black hover:bg-gray-100 transition border border-dashed border-gray-200"
                                                >
                                                    Add Correct Answer
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <!-- Individual Card Toolbar -->
                            <div class="absolute right-[-80px] top-0 flex flex-col gap-4 bg-white/40 backdrop-blur-md rounded-[32px] p-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                                <button type="button" @click="addQuestion(qIdx)" class="w-14 h-14 flex items-center justify-center rounded-[24px] bg-[#104876] text-white hover:bg-[#0c365a] transition shadow-xl scale-90 hover:scale-100 active:scale-95">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                                <button type="button" @click="questions.splice(qIdx, 1)" class="w-14 h-14 flex items-center justify-center rounded-[24px] bg-white text-gray-300 hover:text-red-500 transition shadow-lg border border-gray-50 scale-90 hover:scale-100 active:scale-95">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer Navigation -->
                <div class="mt-12 sm:mt-24 flex flex-col sm:flex-row justify-between items-stretch sm:items-center pb-24 gap-4 sm:gap-0">
                    <button 
                        type="button" 
                        @click="step = 1"
                        class="bg-white border border-gray-100 text-black px-8 sm:px-12 py-4 sm:py-6 rounded-full sm:rounded-[32px] font-bold shadow-sm hover:bg-gray-50 transition flex items-center justify-center gap-4 order-3 sm:order-1"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path></svg>
                        Previous
                    </button>
                    
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 order-1 sm:order-2">
                        <button 
                            type="button" 
                            @click="submitQuizForm('draft')"
                            class="bg-white border border-gray-100 text-gray-400 px-8 sm:px-12 py-4 sm:py-6 rounded-full sm:rounded-[32px] font-bold shadow-sm hover:bg-gray-50 transition order-2 sm:order-1"
                        >
                            Save as Draft
                        </button>
                        <button 
                            type="button" 
                            id="continue-step-2"
                            @click="goToSummary()"
                            class="bg-[#104876] text-white px-8 sm:px-12 py-4 sm:py-6 rounded-full sm:rounded-[32px] font-bold shadow-xl hover:bg-[#0c365a] hover:shadow-2xl hover:-translate-y-1 transform transition-all flex items-center justify-center gap-4 order-1 sm:order-2"
                        >
                            Continue
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- STAGE 3: SUMMARY -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" style="display: none;" class="relative">
                <!-- Header -->
                <div class="flex items-center justify-between mb-12 relative">
                    <button type="button" @click="step = 2" class="absolute left-0 flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-black transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        Previous
                    </button>
                    <h1 class="text-3xl font-extrabold text-black w-full text-center">Summary</h1>
                </div>

                <div class="bg-white rounded-[48px] shadow-sm border border-gray-100 overflow-hidden">
                    <!-- Banner Image -->
                    <div class="h-64 lg:h-80 w-full overflow-hidden">
                        <img src="{{ asset('assets/default-cover.png') }}" alt="Quiz Cover" class="h-full w-full object-cover">
                    </div>

                    <!-- Content -->
                    <div class="p-10 lg:p-14">
                        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-8 mb-12">
                            <div>
                                <h1 class="text-4xl font-black text-black mb-4" x-text="title || 'Untitled Quiz'"></h1>
                                <div class="flex items-center gap-3 text-lg font-bold text-gray-400">
                                    <span x-text="getSelectedMajorName() || 'No Major'"></span>
                                    <span>•</span>
                                    <span x-text="getSelectedCourseName() || 'No Course'"></span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-300 uppercase tracking-widest">Quiz Creator</span>
                                <div class="text-lg font-extrabold text-black mt-1">{{ auth()->user()->full_name }}</div>
                            </div>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                            <div class="bg-gray-50/50 border border-gray-100 rounded-3xl p-8 flex items-center gap-6">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-[#528FB9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-black text-black" x-text="questions.length"></div>
                                    <div class="text-sm font-bold text-gray-400">Questions</div>
                                </div>
                            </div>
                            <div class="bg-gray-50/50 border border-gray-100 rounded-3xl p-8 flex items-center gap-6">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-[#528FB9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <template x-if="time_limit_minutes">
                                        <div class="text-2xl font-black text-black" x-text="time_limit_minutes + 'm'"></div>
                                    </template>
                                    <template x-if="!time_limit_minutes">
                                        <div class="text-2xl font-black text-black py-1">
                                            <svg class="w-10 h-10 text-[#528FB9]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="M18.121 8.879a3 3 0 10-4.242 4.242 3 3 0 004.242-4.242zM5.879 15.121a3 3 0 104.242-4.242 3 3 0 00-4.242 4.242z" />
                                                <path d="M13.879 13.121l-3.758-2.242M13.879 10.879l-3.758 2.242" />
                                            </svg>
                                        </div>
                                    </template>
                                    <div class="text-sm font-bold text-gray-400">Time Limit</div>
                                </div>
                            </div>
                            <div class="bg-gray-50/50 border border-gray-100 rounded-3xl p-8 flex items-center gap-6">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                    <svg class="w-7 h-7 text-[#528FB9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-black text-black" x-text="access === 'public' ? 'Public' : 'Private'"></div>
                                    <div class="text-sm font-bold text-gray-400">Access</div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-12">
                            <h3 class="text-xl font-extrabold text-black mb-4">Description</h3>
                            <p class="text-gray-500 leading-relaxed text-lg" x-text="description || 'No description provided.'"></p>
                        </div>

                        <!-- Tags -->
                        <div class="mb-12">
                            <h3 class="text-xl font-extrabold text-black mb-4">Tags</h3>
                            <div class="flex flex-wrap gap-3">
                                <template x-for="tag in tags" :key="tag">
                                    <span class="px-5 py-2.5 bg-[#eef8fc] text-[#528FB9] rounded-full text-sm font-bold" x-text="tag"></span>
                                </template>
                                <template x-if="tags.length === 0">
                                    <span class="text-gray-400 italic">No tags selected.</span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="mt-12 flex flex-col gap-4 sm:gap-6 items-center">
                    <button 
                        type="button" 
                        @click="submitQuizForm('draft')"
                        class="w-full bg-[#eef8fc] border-2 border-[#528FB9] text-[#528FB9] py-5 sm:py-8 rounded-full sm:rounded-[32px] text-lg sm:text-xl font-black shadow-sm hover:bg-[#dff0f8] transition"
                    >
                        Save as Draft
                    </button>
                    <button 
                        type="button" 
                        id="publish-btn"
                        @click="submitQuizForm('published')"
                        class="w-full bg-[#528FB9] text-white py-5 sm:py-8 rounded-full sm:rounded-[32px] text-lg sm:text-xl font-black shadow-xl hover:bg-[#3E779F] hover:shadow-2xl hover:-translate-y-1 transform transition-all"
                    >
                        Publish Quiz
                    </button>
                    <button 
                        type="button" 
                        @click="step = 2"
                        class="w-full bg-white border-2 border-gray-100 text-gray-400 py-5 sm:py-8 rounded-full sm:rounded-[32px] text-lg sm:text-xl font-black shadow-sm hover:bg-gray-50 transition"
                    >
                        Back to Questions
                    </button>
                </div>
                <div id="form-bottom"></div>
            </div>
        </form>
    </div>

    <!-- VALIDATION MODAL -->
    <div 
        x-show="showValidationModal" 
        x-cloak
        class="fixed inset-0 z-[110] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
        @click.self="showValidationModal = false"
    >
        <div class="w-full max-w-md rounded-[32px] bg-white p-10 shadow-2xl">
            <div class="w-16 h-16 bg-red-50 rounded-2xl flex items-center justify-center mb-8 mx-auto">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 14c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            
            <h3 class="text-2xl font-black text-black mb-4 text-center">Incomplete Data</h3>
            <p class="text-gray-500 text-center mb-8">Please fix the following issues before publishing:</p>
            
            <div class="space-y-4 mb-10">
                <template x-for="err in validationErrors" :key="err.msg">
                    <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl">
                        <div class="w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                        <div class="flex-1">
                            <div class="text-sm font-bold text-black" x-text="err.msg"></div>
                            <button @click="step = err.targetStep; showValidationModal = false" class="text-xs font-bold text-[#528FB9] hover:underline mt-1">
                                Go to <span x-text="err.targetStep === 1 ? 'Detail Stage' : 'Questions Stage'"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <button 
                type="button" 
                @click="showValidationModal = false"
                class="w-full py-4 rounded-full bg-gray-100 text-sm font-bold text-black transition hover:bg-gray-200"
            >
                Close
            </button>
        </div>
    </div>

    <!-- CANCEL POPUP -->
    <div 
        x-show="showCancelModal" 
        x-cloak
        class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm px-4"
        @click.self="showCancelModal = false"
    >
        <div class="w-full max-w-sm rounded-[32px] bg-white p-10 shadow-2xl text-center">
            <h3 class="text-xl font-extrabold text-black mb-8">Are you sure you want to cancel?</h3>
            
            <div class="flex flex-col gap-4">
                <button 
                    type="button" 
                    @click="discardQuiz"
                    class="w-full rounded-full border border-red-500 bg-white py-4 text-sm font-bold text-red-500 transition hover:bg-red-50 shadow-sm"
                >
                    Discard Quiz
                </button>
                <button 
                    type="button" 
                    @click="saveAsDraftAndExit"
                    class="w-full rounded-full bg-[#528FB9] py-4 text-sm font-bold text-white transition hover:bg-[#3E779F] shadow-md"
                >
                    Save as Draft
                </button>
                <button 
                    type="button" 
                    @click="showCancelModal = false"
                    class="w-full rounded-full border border-gray-200 bg-white py-4 text-sm font-bold text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 shadow-sm"
                >
                    Back to edit
                </button>
            </div>
        </div>
    </div>
    <!-- Scroll to Bottom Button -->
    <div x-show="showScrollButton" x-transition class="fixed bottom-10 right-10 lg:right-[calc(50%-550px)] z-40 hidden md:block">
        <button 
            type="button" 
            @click="scrollToFormBottom()"
            class="group bg-white border-2 border-[#528EB8] text-[#528EB8] p-4 rounded-2xl shadow-xl hover:bg-[#528EB8] hover:text-white transition-all duration-300 flex flex-col items-center gap-1"
            title="Scroll to bottom"
        >
            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7-7-7m14-8l-7 7-7-7" /></svg>
            <span class="text-[10px] text-[#528EB8] group-hover:text-white font-black uppercase tracking-widest transition-colors duration-300">Bottom</span>
        </button>
    </div>
</div>

<script>
    function quizFormHandler() {
        return {
            step: 1,
            showCancelModal: false,
            showValidationModal: false,
            validationErrors: [],
            showScrollButton: true,
            isDirty: {{ isset($quiz) && $quiz->exists ? 'true' : 'false' }},
            isSubmitting: false,
            pendingUrl: null,
            isEdit: {{ isset($quiz) && $quiz->exists ? 'true' : 'false' }},
            
            // ==========================================
            // DETAIL FIELDS BINDING
            // Data from old() or database injected here
            // ==========================================
            title: '{{ old("title", $quiz->title ?? "") }}',
            major_id: '{{ old("major_id", $quiz->major_id ?? "") }}',
            course_id: '{{ old("course_id", $quiz->course_id ?? "") }}',
            description: '{{ old("description", $quiz->description ?? "") }}',
            folder_id: '{{ old("folder_id", $quiz->folder_id ?? "") }}',
            time_limit_minutes: '{{ old("time_limit_minutes", $quiz->time_limit_minutes ?? "") }}',
            access: '{{ old("access", $quiz->access ?? "private") }}',
            allow_copy: {{ old("allow_copy", $quiz->allow_copy ?? false) ? 'true' : 'false' }},

            // Major/Course Data for Summary
            majors: {!! json_encode($majors->map(fn($m) => ['id' => $m->id, 'name' => $m->name])) !!},
            courses: {!! json_encode($courses->map(fn($c) => ['id' => $c->id, 'name' => $c->name])) !!},

            // Questions System (Alpine Managed)
            questions: {!! json_encode($questionsData) !!},

            // ==========================================
            // INITIALIZATION LOGIC
            // Watchers untuk menandai perubahan (isDirty) 
            // dan mengatur event listener utama
            // ==========================================
            init() {
                this.$watch('title', () => this.isDirty = true);
                this.$watch('description', () => this.isDirty = true);
                this.$watch('time_limit_minutes', () => this.isDirty = true);
                this.$watch('allow_copy', () => this.isDirty = true);
                this.$watch('tags', () => this.isDirty = true);
                this.$watch('major_id', () => this.isDirty = true);
                this.$watch('course_id', () => this.isDirty = true);
                this.$watch('folder_id', () => this.isDirty = true);
                this.$watch('questions', () => this.isDirty = true, { deep: true });

                this.$watch('step', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    this.$nextTick(() => this.updateScrollButtonState());
                });

                window.addEventListener('scroll', () => this.updateScrollButtonState(), { passive: true });
                window.addEventListener('resize', () => this.updateScrollButtonState());

                this.$nextTick(() => this.updateScrollButtonState());

                // Browser back/refresh warning
                window.addEventListener('beforeunload', (e) => {
                    if (this.isDirty && !this.isSubmitting) {
                        e.preventDefault();
                        e.returnValue = 'You have unsaved changes!';
                        return e.returnValue;
                    }
                });

                // Consolidate Global link interception (Header, Footer, Sidebar, etc.)
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('a');
                    if (link) {
                        const href = link.getAttribute('href');
                        // Skip if it's a submit link, hash link, or javascript link
                        if (this.isDirty && !this.isSubmitting && !this.isSubmitLink(link) && href && href !== '#' && !href.startsWith('javascript:')) {
                            e.preventDefault();
                            this.pendingUrl = href;
                            this.showCancelModal = true;
                        }
                    }
                }, true); // Use capture phase to catch events early
            },

            // ==========================================
            // FORM SUBMISSION LOGIC
            // Memvalidasi data sebelum di-publish dan
            // mensubmit form ke backend jika sukses.
            // ==========================================
            submitQuizForm(vis) {
                // Validation for published quiz
                if (vis === 'published') {
                    this.validationErrors = [];

                    // 1. Detail Fields (except description)
                    if (!this.title || !this.major_id || !this.course_id || !this.access) {
                        this.validationErrors.push({ msg: 'Basic quiz details are incomplete (Title, Major, Course, etc.)', targetStep: 1 });
                    }

                    // 2. Questions Validation
                    for (let i = 0; i < this.questions.length; i++) {
                        const q = this.questions[i];
                        
                        if (!q.content.trim()) {
                            this.validationErrors.push({ msg: `Question ${i + 1} content is empty.`, targetStep: 2 });
                        }

                        if (q.type === 'checkbox') {
                            if (q.correct_options.length < 2) {
                                this.validationErrors.push({ msg: `Question ${i + 1} (Checkbox) must have at least 2 correct answers.`, targetStep: 2 });
                            }
                            if (q.options.length < 3) {
                                this.validationErrors.push({ msg: `Question ${i + 1} (Checkbox) must have at least 3 choices.`, targetStep: 2 });
                            }
                        }

                        const correctCount = q.type === 'checkbox' ? q.correct_options.length : 1;
                        if (q.options.length <= correctCount) {
                            this.validationErrors.push({ msg: `Question ${i + 1} must have more choices than correct answers.`, targetStep: 2 });
                        }

                        if (q.options.some(opt => !opt.content.trim())) {
                            this.validationErrors.push({ msg: `Question ${i + 1} has empty choices.`, targetStep: 2 });
                        }
                    }

                    if (this.validationErrors.length > 0) {
                        this.showValidationModal = true;
                        return;
                    }
                }

                this.visibility = vis;
                this.isSubmitting = true;
                this.isDirty = false;
                
                this.$nextTick(() => {
                    document.getElementById('quizForm').submit();
                });
            },

            scrollToFormBottom() {
                const targetId = this.step === 1
                    ? 'continue-step-1'
                    : this.step === 2
                        ? 'continue-step-2'
                        : 'publish-btn';

                const el = document.getElementById(targetId) || document.getElementById('form-bottom') || document.getElementById('quizForm');

                if (el) {
                    const rect = el.getBoundingClientRect();
                    const scrollTarget = window.pageYOffset + rect.bottom - window.innerHeight + 96;
                    window.scrollTo({ top: Math.max(0, scrollTarget), behavior: 'smooth' });
                } else {
                    window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                }

                this.$nextTick(() => this.updateScrollButtonState());
            },

            getBottomTargetElement() {
                return document.getElementById(this.step === 1
                    ? 'continue-step-1'
                    : this.step === 2
                        ? 'continue-step-2'
                        : 'publish-btn')
                    || document.getElementById('form-bottom');
            },

            updateScrollButtonState() {
                const el = this.getBottomTargetElement();

                if (el) {
                    const rect = el.getBoundingClientRect();
                    this.showScrollButton = !(rect.top < window.innerHeight && rect.bottom > 0);
                    return;
                }

                this.showScrollButton = window.pageYOffset + window.innerHeight < document.body.scrollHeight - 24;
            },

            isSubmitLink(link) {
                return link.closest('#quizForm') !== null || link.getAttribute('href').startsWith('javascript:');
            },

            // ==========================================
            // TAGS SYSTEM LOGIC
            // Fungsi untuk mencari, menambah, dan
            // menghapus tag secara dinamis.
            // ==========================================
            tagSearch: '',
            tags: {!! json_encode(old('tags', $quiz->tags->pluck('name')->toArray())) !!},
            allTags: {!! json_encode($allTags) !!},
            tagRecs: [],
            showTagRecs: false,

            handleTagSearch() {
                if (this.tagSearch.length < 1) {
                    this.showTagRecs = false;
                    return;
                }
                const term = this.tagSearch.toLowerCase();
                this.tagRecs = this.allTags.filter(t => 
                    t.toLowerCase().includes(term) && !this.tags.map(v => v.toLowerCase()).includes(t.toLowerCase())
                ).slice(0, 5);
                this.showTagRecs = true;
            },

            addTag(tag) {
                const normalized = tag.trim().toLowerCase();
                if (normalized && !this.tags.includes(normalized)) {
                    this.tags.push(normalized);
                    this.isDirty = true;
                }
                this.tagSearch = '';
                this.showTagRecs = false;
            },

            removeTag(index) {
                this.tags.splice(index, 1);
                this.isDirty = true;
            },

            discardQuiz() {
                this.isDirty = false;
                window.location.href = this.pendingUrl || "{{ route('my-quizzes.index') }}";
            },

            saveAsDraftAndExit() {
                this.isDirty = false;
                this.visibility = 'draft';
                this.$nextTick(() => {
                    document.getElementById('quizForm').submit();
                });
            },

            // ==========================================
            // QUESTION MANAGEMENT HELPERS
            // Fungsi-fungsi untuk memanipulasi struktur
            // array pertanyaan dan pilihan ganda.
            // ==========================================
            addQuestion(afterIdx = null) {
                const newQuestion = {
                    type: 'multiple_choice',
                    content: '',
                    options: [
                        { content: '', id_option: null },
                        { content: '', id_option: null },
                        { content: '', id_option: null },
                        { content: '', id_option: null }
                    ],
                    correct_option: 0,
                    correct_options: [0]
                };

                if (afterIdx !== null) {
                    this.questions.splice(afterIdx + 1, 0, newQuestion);
                } else {
                    this.questions.push(newQuestion);
                }
                this.isDirty = true;
            },

            addOption(qIdx) {
                this.questions[qIdx].options.push({ content: '', id_option: null });
            },

            removeOption(qIdx, oIdx) {
                if (this.questions[qIdx].options.length <= 2) return;
                this.questions[qIdx].options.splice(oIdx, 1);
                
                // Update correct answer indices if needed
                if (this.questions[qIdx].type === 'multiple_choice') {
                    if (this.questions[qIdx].correct_option >= oIdx) {
                        this.questions[qIdx].correct_option = Math.max(0, this.questions[qIdx].correct_option - 1);
                    }
                } else {
                    this.questions[qIdx].correct_options = this.questions[qIdx].correct_options
                        .filter(idx => idx !== oIdx)
                        .map(idx => idx > oIdx ? idx - 1 : idx);
                }
            },

            // Summary Helpers
            getSelectedMajorName() {
                const major = this.majors.find(m => m.id == this.major_id);
                return major ? major.name : '';
            },

            getSelectedCourseName() {
                const course = this.courses.find(c => c.id == this.course_id);
                return course ? course.name : '';
            },

            goToSummary() {
                this.step = 3;
            }
        };
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
</x-app-layout>