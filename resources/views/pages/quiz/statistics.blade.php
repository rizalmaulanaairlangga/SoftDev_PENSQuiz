<x-app-layout>

<div class="max-w-4xl mx-auto p-6">
    
    <!-- QUIZ HEADER CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <!-- Cover Image -->
        <div class="h-32 w-full bg-gray-100 relative">
            <img src="{{ asset('assets/default-cover.png') }}" alt="Quiz Cover" class="w-full h-full object-cover">
        </div>
        <!-- Title and Info -->
        <div class="p-6">
            <h1 class="text-2xl font-extrabold text-black mb-1">{{ $myquiz->title }}</h1>
            <div class="flex items-center gap-2 text-sm text-black font-medium">
                <span>{{ $myquiz->major->name ?? 'Quiz Major' }}</span>
                <span class="text-gray-400">&bull;</span>
                <span>{{ $myquiz->course->name ?? 'Quiz Subject' }}</span>
            </div>
        </div>
    </div>

    <!-- STATISTICS CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <!-- Header -->
        <div class="relative flex items-center justify-center mb-8">
            <a href="{{ url()->previous() == url()->current() ? route('my-quizzes.index') : url()->previous() }}" class="absolute left-0 flex items-center gap-2 text-sm font-bold text-black hover:text-gray-600 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                Back
            </a>
            <h2 class="text-xl font-extrabold text-black">Statistics</h2>
        </div>

        <!-- Content -->
        <div>
            <h3 class="text-base font-bold text-black mb-4">Engagements</h3>
            
            <div class="space-y-3">
                <!-- Users Clicked -->
                <div class="flex items-center justify-between bg-[#f8fafc] border border-gray-200 rounded-xl px-6 py-4">
                    <span class="text-[15px] font-bold text-black">Users Clicked</span>
                    <span class="text-[15px] font-bold text-black">{{ $totalAttempts }}</span>
                </div>

                <!-- Participants -->
                <div class="flex items-center justify-between bg-[#f8fafc] border border-gray-200 rounded-xl px-6 py-4">
                    <span class="text-[15px] font-bold text-black">Participants</span>
                    <span class="text-[15px] font-bold text-black">{{ $participants }}</span>
                </div>

                <!-- Completion Rate -->
                <div class="flex items-center justify-between bg-[#f8fafc] border border-gray-200 rounded-xl px-6 py-4">
                    <span class="text-[15px] font-bold text-black">Completion Rate</span>
                    <span class="text-[15px] font-bold text-black">{{ $completionRate }}%</span>
                </div>
            </div>
        </div>
    </div>

</div>

</x-app-layout>
