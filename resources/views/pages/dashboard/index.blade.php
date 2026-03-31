{{-- resources/views/pages/dashboard/index.blade.php --}}
<x-app-layout>
    <!-- Main Dashboard Content -->
    <div class="flex flex-col gap-4 lg:flex-row lg:gap-5">

        <!-- Left Content Section -->
        <section class="flex min-w-0 flex-1 flex-col gap-4 lg:gap-5">

            <!-- Welcome Section -->
            <div class="rounded-[20px] bg-[linear-gradient(90deg,rgba(16,72,118,0.12)_0%,rgba(255,255,255,0.75)_55%,rgba(255,255,255,0.95)_100%)] px-6 py-5 shadow-[0_2px_12px_rgba(0,0,0,0.05)] sm:px-8 sm:py-6 lg:px-10 lg:py-8">
                <h1 class="text-[26px] font-extrabold leading-tight text-[#104876] sm:text-[34px] lg:text-[42px]">
                    Hello, {{ auth()->user()->full_name ?? 'User' }}!
                </h1>
                <p class="mt-2 max-w-4xl text-[14px] leading-6 text-black/90 sm:text-[16px] lg:text-[18px]">
                    Welcome back. Browse, make, and do quizzes as a PENS student here on PENSQuiz!
                </p>
            </div>

            <!-- Action Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 lg:gap-5">
                <a href="{{ url('/quizzes') }}" class="flex flex-col items-center justify-center rounded-[20px] bg-white px-4 py-6 text-center shadow-[0_2px_12px_rgba(0,0,0,0.05)] transition hover:-translate-y-0.5 hover:shadow-lg sm:px-5 sm:py-7 lg:px-6 lg:py-8">
                    <img src="{{ asset('assets/dashboard/icons/img_four_squares.png') }}" alt="Browse quizzes" class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16">
                    <span class="mt-4 text-[14px] font-medium text-black sm:text-[15px] lg:text-[16px]">Browse Quizzes</span>
                </a>

                <a href="{{ url('/courses') }}" class="flex flex-col items-center justify-center rounded-[20px] bg-white px-4 py-6 text-center shadow-[0_2px_12px_rgba(0,0,0,0.05)] transition hover:-translate-y-0.5 hover:shadow-lg sm:px-5 sm:py-7 lg:px-6 lg:py-8">
                    <img src="{{ asset('assets/dashboard/icons/img_books.png') }}" alt="Courses" class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16">
                    <span class="mt-4 text-[14px] font-medium text-black sm:text-[15px] lg:text-[16px]">Courses</span>
                </a>

                <a href="{{ url('/quiz/create') }}" class="flex flex-col items-center justify-center rounded-[20px] bg-white px-4 py-6 text-center shadow-[0_2px_12px_rgba(0,0,0,0.05)] transition hover:-translate-y-0.5 hover:shadow-lg sm:px-5 sm:py-7 lg:px-6 lg:py-8">
                    <img src="{{ asset('assets/dashboard/icons/img_create_order.png') }}" alt="Create quiz" class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16">
                    <span class="mt-4 text-[14px] font-medium text-black sm:text-[15px] lg:text-[16px]">Create Quiz</span>
                </a>
            </div>

            <!-- Quiz Completed Chart Section -->
            <div class="rounded-[20px] bg-white px-5 py-5 shadow-[0_2px_12px_rgba(0,0,0,0.05)] sm:px-7 sm:py-7 lg:px-8 lg:py-8">
                <h2 class="text-[22px] font-extrabold leading-tight text-black sm:text-[26px] lg:text-[30px]">
                    Quiz Completed
                </h2>
                <div class="mt-4 h-[230px] w-full sm:h-[260px] lg:h-[300px]">
                    <canvas id="quizChart" aria-label="Bar chart showing quiz completion data by day" role="img"></canvas>
                </div>
            </div>
        </section>

        <!-- Right Statistics Section -->
        <section class="flex w-full flex-col gap-4 lg:w-[34%] lg:gap-5">
            <div class="rounded-[20px] bg-white px-5 py-5 shadow-[0_2px_12px_rgba(0,0,0,0.05)] sm:px-7 sm:py-7 lg:px-8 lg:py-8">
                <h2 class="text-[20px] font-extrabold leading-tight text-black sm:text-[24px] lg:text-[28px]">
                    Creator Statistics
                </h2>

                <div class="mt-4 space-y-3 sm:mt-5 sm:space-y-4">
                    <div class="flex items-center gap-3 rounded-[18px] bg-[#f5f5f5] p-3 sm:p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-white text-[18px] font-extrabold text-black shadow-sm sm:h-14 sm:w-14 sm:text-[22px]">
                            {{ $quizCount }}
                        </div>
                        <span class="text-[14px] text-black sm:text-[15px] lg:text-[16px]">Quizzes Created</span>
                    </div>

                    <div class="flex items-center gap-3 rounded-[18px] bg-[#f5f5f5] p-3 sm:p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-white text-[18px] font-extrabold text-black shadow-sm sm:h-14 sm:w-14 sm:text-[22px]">
                            {{ $questionCount }}
                        </div>
                        <span class="text-[14px] text-black sm:text-[15px] lg:text-[16px]">Total Questions</span>
                    </div>
                </div>

                <h3 class="mt-6 text-[20px] font-extrabold leading-tight text-black sm:text-[24px] lg:text-[28px]">
                    Engagements
                </h3>

                <div class="mt-4 space-y-3 sm:mt-5 sm:space-y-4">
                    <div class="flex items-center gap-3 rounded-[18px] bg-[#f5f5f5] p-3 sm:p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-white text-[18px] font-extrabold text-black shadow-sm sm:h-14 sm:w-14 sm:text-[22px]">
                            {{ $attempts }}
                        </div>
                        <span class="text-[14px] text-black sm:text-[15px] lg:text-[16px]">Attempts</span>
                    </div>

                    <div class="flex items-center gap-3 rounded-[18px] bg-[#f5f5f5] p-3 sm:p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-white text-[18px] font-extrabold text-black shadow-sm sm:h-14 sm:w-14 sm:text-[22px]">
                            {{ $participants }}
                        </div>
                        <span class="text-[14px] text-black sm:text-[15px] lg:text-[16px]">Participants</span>
                    </div>

                    <div class="flex items-center gap-3 rounded-[18px] bg-[#f5f5f5] p-3 sm:p-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-white text-[14px] font-extrabold text-black shadow-sm sm:h-14 sm:w-14 sm:text-[18px]">
                            {{ $completionRate }}%
                        </div>
                        <span class="text-[14px] text-black sm:text-[15px] lg:text-[16px]">Completion Rate</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const canvas = document.getElementById('quizChart');

        if (canvas && typeof Chart !== 'undefined') {
            const ctx = canvas.getContext('2d');

            const chartData = @json($chartData);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    datasets: [{
                        data: chartData,
                        backgroundColor: '#104876',
                        borderRadius: 8,
                        borderSkipped: false,
                        barThickness: 34,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#7a7a7a',
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.08)'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#7a7a7a',
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
