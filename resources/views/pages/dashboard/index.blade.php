{{-- resources/views/pages/dashboard/index.blade.php --}}
<x-app-layout>
    {{-- Wrapper utama dengan jarak vertikal yang responsif (lebih rapat di mobile, lebih longgar di tablet/desktop) --}}
    <div class="space-y-6 sm:space-y-8">
        
        {{-- ================================================================ --}}
        {{-- 1. GREETING SECTION                                              --}}
        {{-- Menampilkan pesan selamat datang untuk user yang sedang login.   --}}
        {{-- Menggunakan gradient biru PENS dan efek drop-shadow untuk teks.  --}}
        {{-- ================================================================ --}}
        <section class="relative overflow-hidden rounded-[24px] sm:rounded-[32px] bg-[linear-gradient(145deg,#a8dbf1_0%,#79b7dc_52%,#4b87b2_100%)] px-6 py-8 sm:px-8 sm:py-10 shadow-lg">
            <div class="relative z-10">
                <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl lg:text-5xl drop-shadow-md">
                    Helloo, <span class="text-[#fdc02a]">{{ auth()->user()->first_name }}!</span>
                </h1>
                <p class="mt-3 sm:mt-4 max-w-2xl text-base sm:text-lg font-medium text-white drop-shadow">
                    Welcome back to PENS<span class="text-[#fdc02a]">Quiz</span>. Your centralized hub for <span class="text-[#fdc02a]">creating</span>, <span class="text-[#fdc02a]">managing</span>, and <span class="text-[#fdc02a]">exploring</span> high-quality educational quizzes.
                </p>
            </div>
            <!-- Dekorasi latar belakang (lingkaran blur) -->
            <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
        </section>

        {{-- ================================================================ --}}
        {{-- 2. STATISTICS SECTION                                            --}}
        {{-- Grid statistik data kuis. Responsif dari 2 kolom (mobile),       --}}
        {{-- 3 kolom (tablet), hingga 5 kolom (desktop lebar).                --}}
        {{-- ================================================================ --}}
        {{-- ================================================================ --}}
        {{-- 2. STATISTICS SECTION                                            --}}
        {{-- Menampilkan statistik data kuis dengan state kosong yang cerdas. --}}
        {{-- ================================================================ --}}
        <section class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-5">
            {{-- A. CREATOR STATISTICS (2 Kolom) --}}
            <div class="lg:col-span-2 grid grid-cols-2 gap-3 sm:gap-4 p-5 sm:p-6 rounded-[24px] sm:rounded-[32px] bg-white border border-slate-100 shadow-sm">
                <div class="col-span-2 flex items-center justify-between mb-2">
                    <h3 class="text-sm sm:text-base font-black text-slate-900 uppercase tracking-wider">Creator Stats</h3>
                    @if($quizCount > 0)
                        <a href="{{ route('my-quizzes.create') }}" class="text-xs font-bold text-blue-500 hover:underline">+ New Quiz</a>
                    @endif
                </div>
                
                @if($quizCount > 0)
                    @php
                        $creatorStats = [
                            ['label' => 'Quizzes Created', 'value' => $quizCount, 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13M3 19c1.5-1 3-1.5 4.5-1.5S10.5 18 12 19m9-12C19.168 5.477 17.586 5 15.832 5c-1.746 0-3.332.477-4.5 1.253v13C12.5 18.477 14.168 18 15.832 18s3.332.477 4.5 1.253V6.253z', 'color' => 'bg-blue-500'],
                            ['label' => 'Total Questions', 'value' => $questionCount, 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-indigo-500'],
                        ];
                    @endphp
                    @foreach($creatorStats as $stat)
                        <div class="group flex flex-col items-center justify-center p-3 sm:p-4 rounded-2xl bg-slate-50 transition-all duration-300 hover:bg-white hover:shadow-md border border-transparent hover:border-blue-100">
                            <div class="flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-xl {{ $stat['color'] }} text-white shadow-lg shadow-blue-500/10">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center">
                                <p class="text-lg sm:text-xl font-black text-slate-900" x-data="{ count: 0, target: {{ $stat['value'] }} }" x-init="setTimeout(() => { let start = 0; let duration = 1500; let step = (timestamp) => { if (!start) start = timestamp; let progress = Math.min((timestamp - start) / duration, 1); count = Math.floor(progress * target); if (progress < 1) { window.requestAnimationFrame(step); } }; window.requestAnimationFrame(step); }, 100)" x-text="count"></p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-2 flex flex-col items-center justify-center py-4 text-center">
                        <div class="mb-3 rounded-full bg-blue-50 p-4 text-blue-500">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-600 mb-4">No quizzes created yet.</p>
                        <a href="{{ route('my-quizzes.create') }}" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-6 py-2.5 text-sm font-black text-white shadow-md transition hover:scale-105 active:scale-95">
                            Start Create Quiz
                        </a>
                    </div>
                @endif
            </div>

            {{-- B. ENGAGEMENT STATISTICS (3 Kolom) --}}
            <div class="lg:col-span-3 grid grid-cols-3 gap-3 sm:gap-4 p-5 sm:p-6 rounded-[24px] sm:rounded-[32px] bg-white border border-slate-100 shadow-sm">
                <div class="col-span-3 flex items-center justify-between mb-2">
                    <h3 class="text-sm sm:text-base font-black text-slate-900 uppercase tracking-wider">Engagement Stats</h3>
                    @if($attempts == 0)
                        <span class="text-[10px] font-bold text-slate-400">Share your quizzes to get data</span>
                    @endif
                </div>

                @if($attempts > 0)
                    @php
                        $engStats = [
                            ['label' => 'Attempts', 'value' => $attempts, 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'bg-[#6BA9D0]'],
                            ['label' => 'Participants', 'value' => $participants, 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'bg-emerald-500'],
                            ['label' => 'Completion', 'value' => $completionRate . '%', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'bg-orange-500'],
                        ];
                    @endphp
                    @foreach($engStats as $stat)
                        <div class="group flex flex-col items-center justify-center p-3 sm:p-4 rounded-2xl bg-slate-50 transition-all duration-300 hover:bg-white hover:shadow-md border border-transparent hover:border-emerald-100">
                            <div class="flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-xl {{ $stat['color'] }} text-white shadow-lg">
                                <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center">
                                <p class="text-lg sm:text-xl font-black text-slate-900" 
                                   x-data="{ count: 0, target: {{ (int) filter_var($stat['value'], FILTER_SANITIZE_NUMBER_INT) }} }" 
                                   x-init="setTimeout(() => { let start = 0; let duration = 1500; let step = (timestamp) => { if (!start) start = timestamp; let progress = Math.min((timestamp - start) / duration, 1); count = Math.floor(progress * target); if (progress < 1) { window.requestAnimationFrame(step); } }; window.requestAnimationFrame(step); }, 100)">
                                    <span x-text="count"></span>{{ strpos($stat['value'], '%') !== false ? '%' : '' }}
                                </p>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $stat['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-3 flex flex-col items-center justify-center py-4 text-center">
                        <div class="mb-3 rounded-full bg-emerald-50 p-4 text-emerald-500">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-bold text-slate-600 mb-4">No engagement recorded yet.</p>
                        <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 px-6 py-2.5 text-sm font-black text-white shadow-md transition hover:scale-105 active:scale-95">
                            Start Play Quiz
                        </a>
                    </div>
                @endif
            </div>
        </section>

        {{-- ================================================================ --}}
        {{-- 3. MAIN CONTENT GRID                                             --}}
        {{-- Grid utama yang terbagi menjadi 2 bagian utama:                  --}}
        {{-- Kiri (Recently Opened) - memakan 2/3 layar pada desktop.         --}}
        {{-- Kanan (Charts & Performance) - memakan 1/3 layar pada desktop.   --}}
        {{-- ================================================================ --}}
        <div class="grid grid-cols-1 gap-6 sm:gap-8 lg:grid-cols-3">
            
            {{-- ---------------------------------------------------------------- --}}
            {{-- A. KIRI: RECENTLY OPENED QUIZZES                                 --}}
            {{-- ---------------------------------------------------------------- --}}
            <section class="lg:col-span-2">
                <div class="flex h-full flex-col overflow-hidden rounded-[24px] sm:rounded-[32px] bg-white shadow-sm border border-slate-100">
                    {{-- Header Section --}}
                    <div class="flex items-center justify-between border-b border-slate-50 px-6 py-5 sm:px-8 sm:py-6">
                        <h2 class="text-lg sm:text-xl font-black tracking-tight text-slate-900">Recently Opened</h2>
                        <a href="{{ route('quizzes.index') }}" class="text-xs sm:text-sm font-bold text-[#6BA9D0] hover:underline">View All</a>
                    </div>
                    
                    {{-- Kontainer Kuis (Bisa di-scroll) --}}
                    <div class="flex-1 overflow-y-auto p-5 sm:p-8 max-h-[500px] sm:max-h-[600px] scrollbar-hide">
                        {{-- Grid internal untuk daftar kuis --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @forelse($recentlyOpened as $quiz)
                                @include('pages.quiz.partials.quiz-card', ['quiz' => $quiz, 'class' => 'w-full h-full'])
                            @empty
                                <div class="col-span-full py-16 flex flex-col items-center justify-center text-center">
                                    <div class="mb-4 rounded-full bg-slate-50 p-6 text-slate-300">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13M3 19c1.5-1 3-1.5 4.5-1.5S10.5 18 12 19m9-12C19.168 5.477 17.586 5 15.832 5c-1.746 0-3.332.477-4.5 1.253v13C12.5 18.477 14.168 18 15.832 18s3.332.477 4.5 1.253V6.253z"></path>
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-400 mb-6">No quizzes recently opened.</p>
                                    <a href="{{ route('quizzes.index') }}" class="inline-flex items-center gap-2 rounded-full bg-[#6BA9D0] px-8 py-3 text-sm font-black text-white shadow-lg transition hover:scale-105 active:scale-95">
                                        Start Play Quiz
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    {{-- Efek fade out di bagian bawah kontainer scroll --}}
                    <div class="h-8 bg-gradient-to-t from-white to-transparent pointer-events-none"></div>
                </div>
            </section>

            {{-- ---------------------------------------------------------------- --}}
            {{-- B. KANAN: DATA AREA (CHART & PERFORMANCE)                        --}}
            {{-- ---------------------------------------------------------------- --}}
            <section class="space-y-6 sm:space-y-8">
                
                {{-- Chart Area --}}
                <div class="rounded-[24px] sm:rounded-[32px] bg-white p-6 sm:p-8 shadow-sm border border-slate-100">
                    <h2 class="text-lg sm:text-xl font-black tracking-tight text-slate-900">Quiz Completed</h2>
                    <div class="mt-6 sm:mt-8 h-[200px] sm:h-[240px]">
                        <canvas id="quizChart"></canvas>
                    </div>
                </div>

                {{-- Performance Overview Area --}}
                <div class="rounded-[24px] sm:rounded-[32px] bg-white p-6 sm:p-8 shadow-sm border border-slate-100">
                    <h2 class="text-lg sm:text-xl font-black tracking-tight text-slate-900">Performance Overview</h2>
                    <div class="mt-6 sm:mt-8 space-y-3 sm:space-y-4">
                        @foreach([
                            ['label' => 'Total Attempts', 'value' => $attempts, 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                            ['label' => 'Active Participants', 'value' => $participants, 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                            ['label' => 'Completion Rate', 'value' => $completionRate . '%', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ] as $perf)
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 p-3 sm:p-4 transition-all duration-300 hover:-translate-y-1 hover:bg-white hover:shadow-[0_8px_25px_rgba(16,72,118,0.2)]">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-white text-[#6BA9D0] shadow-sm">
                                        <svg class="h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $perf['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm font-bold text-slate-500 uppercase tracking-wide">{{ $perf['label'] }}</span>
                                </div>
                                <span class="text-base sm:text-lg font-black text-slate-900">{{ $perf['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- 4. JAVASCRIPT / CHART SETUP                                      --}}
    {{-- Konfigurasi Chart.js untuk menampilkan grafik aktivitas kuis.    --}}
    {{-- ================================================================ --}}
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
                    borderRadius: 8, // Sedikit disesuaikan agar proporsional di semua layar
                    borderSkipped: false,
                    barThickness: window.innerWidth < 640 ? 12 : 24, // Bar sedikit lebih ramping di mobile
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

        // Event listener tambahan untuk me-resize barThickness saat orientasi berubah
        window.addEventListener('resize', () => {
            const chart = Chart.getChart("quizChart");
            if (chart) {
                chart.data.datasets[0].barThickness = window.innerWidth < 640 ? 12 : 24;
                chart.update();
            }
        });
    </script>
</x-app-layout>
