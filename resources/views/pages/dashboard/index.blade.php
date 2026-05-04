{{-- resources/views/pages/dashboard/index.blade.php --}}
<x-app-layout>
    <div class="space-y-8">
        <!-- 1. GREETING SECTION -->
        <section class="relative overflow-hidden rounded-[32px] bg-[linear-gradient(145deg,#a8dbf1_0%,#79b7dc_52%,#4b87b2_100%)] px-8 py-10 shadow-lg">
            <div class="relative z-10">
                <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl drop-shadow-md">
                    Helloo, <span class="text-[#fdc02a]">{{ auth()->user()->first_name }}!</span>
                </h1>
                <p class="mt-4 max-w-2xl text-lg font-medium text-white drop-shadow">
                    Welcome back to PENS<span class="text-[#fdc02a]">Quiz</span>. Your centralized hub for <span class="text-[#fdc02a]">creating</span>, <span class="text-[#fdc02a]">managing</span>, and <span class="text-[#fdc02a]">exploring</span> high-quality educational quizzes.
                </p>
            </div>
            <!-- Subtle background decoration -->
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
        </section>

        <!-- 2. STATISTICS (HORIZONTAL ROW) -->
        <section class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5 lg:gap-6">
            @php
                $stats = [
                    ['label' => 'Quizzes Created', 'value' => $quizCount, 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13M3 19c1.5-1 3-1.5 4.5-1.5S10.5 18 12 19m9-12C19.168 5.477 17.586 5 15.832 5c-1.746 0-3.332.477-4.5 1.253v13C12.5 18.477 14.168 18 15.832 18s3.332.477 4.5 1.253V6.253z', 'color' => 'bg-blue-500'],
                    ['label' => 'Total Questions', 'value' => $questionCount, 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-indigo-500'],
                    ['label' => 'Attempts', 'value' => $attempts, 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'bg-[#6BA9D0]'],
                    ['label' => 'Participants', 'value' => $participants, 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'bg-emerald-500'],
                    ['label' => 'Completion Rate', 'value' => $completionRate . '%', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-orange-500'],
                ];
            @endphp

            @foreach($stats as $stat)
                <div class="group flex flex-col items-center justify-center rounded-[24px] bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_10px_30px_rgba(16,72,118,0.25)]">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $stat['color'] }} text-white shadow-lg shadow-{{ explode('-', $stat['color'])[1] }}-500/20">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="mt-4 text-center">
                        <p class="text-2xl font-black tracking-tight text-slate-900" 
                           x-data="{ count: 0, target: {{ (int) filter_var($stat['value'], FILTER_SANITIZE_NUMBER_INT) }} }" 
                           x-init="setTimeout(() => { 
                               let start = 0;
                               let duration = 1500;
                               let step = (timestamp) => {
                                   if (!start) start = timestamp;
                                   let progress = Math.min((timestamp - start) / duration, 1);
                                   count = Math.floor(progress * target);
                                   if (progress < 1) {
                                       window.requestAnimationFrame(step);
                                   }
                               };
                               window.requestAnimationFrame(step);
                           }, 100)">
                            <span x-text="count"></span>{{ strpos($stat['value'], '%') !== false ? '%' : '' }}
                        </p>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</p>
                    </div>
                </div>
            @endforeach
        </section>

        <!-- 3. MAIN CONTENT GRID (3 COLUMNS) -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            
            <!-- LEFT (2 COLUMNS): RECENTLY OPENED -->
            <section class="lg:col-span-2">
                <div class="flex h-full flex-col overflow-hidden rounded-[32px] bg-white shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between border-b border-slate-50 px-8 py-6">
                        <h2 class="text-xl font-black tracking-tight text-slate-900">Recently Opened</h2>
                        <a href="{{ route('quizzes.index') }}" class="text-sm font-bold text-[#6BA9D0] hover:underline">View All</a>
                    </div>
                    
                    <!-- SCROLLABLE CONTAINER -->
                    <div class="flex-1 overflow-y-auto p-8 max-h-[600px] scrollbar-hide">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @forelse($recentlyOpened as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-full h-full'])
                            @empty
                                <div class="col-span-full py-20 text-center">
                                    <p class="font-bold text-slate-400">No quizzes recently opened.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <!-- Fade effect at bottom -->
                    <div class="h-8 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                </div>
            </section>

            <!-- RIGHT (1 COLUMN): DATA AREA -->
            <section class="space-y-8">
                <!-- CHART -->
                <div class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black tracking-tight text-slate-900">Quiz Completed</h2>
                    <div class="mt-8 h-[240px]">
                        <canvas id="quizChart"></canvas>
                    </div>
                </div>

                <!-- PERFORMANCE PANEL -->
                <div class="rounded-[32px] bg-white p-8 shadow-sm border border-slate-100">
                    <h2 class="text-xl font-black tracking-tight text-slate-900">Performance Overview</h2>
                    <div class="mt-8 space-y-4">
                        @foreach([
                            ['label' => 'Total Attempts', 'value' => $attempts, 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            ['label' => 'Active Participants', 'value' => $participants, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Completion Rate', 'value' => $completionRate . '%', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ] as $perf)
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-4 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-[0_8px_25px_rgba(16,72,118,0.2)]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#6BA9D0] shadow-sm">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $perf['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-bold text-slate-500 uppercase tracking-wide">{{ $perf['label'] }}</span>
                                </div>
                                <span class="text-lg font-black text-slate-900">{{ $perf['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('quizChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                    data: chartData,
                    backgroundColor: '#6BA9D0',
                    borderRadius: 12,
                    borderSkipped: false,
                    barThickness: 24,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart',
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default') {
                            delay = context.dataIndex * 150;
                        }
                        return delay;
                    }
                },
                animations: {
                    y: {
                        from: (ctx) => ctx.chart.scales.y.getPixelForValue(0),
                        duration: 2000,
                        easing: 'easeOutQuart'
                    }
                },
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f8fafc', drawBorder: false },
                        ticks: { font: { weight: 'bold' }, color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' }, color: '#94a3b8' }
                    }
                }
            }
        });
    </script>
</x-app-layout>
