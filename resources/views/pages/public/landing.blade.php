<x-guest-layout>
    {{-- ================================================================ --}}
    {{-- 1. DATA INITIALIZATION SECTION                                   --}}
    {{-- Menginisialisasi data statis untuk fitur dan ulasan pengguna     --}}
    {{-- agar mudah dikelola dan dimodifikasi tanpa menyentuh struktur UI --}}
    {{-- ================================================================ --}}
    @php
        // Data fitur utama aplikasi (Take Quiz, Make Quiz, See Courses)
        $features = [
            [
                'title' => 'Take Quiz',
                'image' => 'img_image_1.png',
                'href' => route('quizzes.index'),
            ],
            [
                'title' => 'Make Quiz',
                'image' => 'img_image_2.png',
                'href' => route('my-quizzes.create'),
            ],
            [
                'title' => 'See Courses',
                'image' => 'img_image_3.png',
                'href' => route('login'),
            ],
        ];

        // Data ulasan dari pengguna (digunakan untuk animasi marquee)
        $reviews = [
            ['major' => 'Informatics Eng. Major', 'initial' => 'A'],
            ['major' => 'Informatics Eng. Major', 'initial' => 'N'],
            ['major' => 'Electronic Eng. Major', 'initial' => 'R'],
            ['major' => 'Electronic Eng. Major', 'initial' => 'D'],
            ['major' => 'Applied Data Science Major', 'initial' => 'S'],
            ['major' => 'Applied Data Science Major', 'initial' => 'F'],
        ];
    @endphp

    {{-- ================================================================ --}}
    {{-- 2. CUSTOM STYLES SECTION                                         --}}
    {{-- Mendefinisikan styling khusus yang sulit dicapai hanya dengan    --}}
    {{-- kelas utilitas Tailwind CSS standar (mis. animasi kompleks).     --}}
    {{-- ================================================================ --}}
    <style>
        /* Animasi garis bawah pada link navigasi (Hover state) */
        .landing-nav-link {
            position: relative;
        }

        .landing-nav-link::after {
            position: absolute;
            right: 0;
            bottom: -0.45rem;
            left: 0;
            height: 0.18rem;
            content: "";
            background: #fdc02a;
            border-radius: 9999px;
            opacity: 0;
            transform: scaleX(0.35);
            transition: opacity 180ms ease, transform 180ms ease;
        }

        /* State saat link navigasi sedang aktif */
        .landing-nav-link.is-active {
            color: #fdc02a;
        }

        .landing-nav-link.is-active::after {
            opacity: 1;
            transform: scaleX(1);
        }

        /* Tombol Login dengan gradien khusus dan efek kilau */
        .landing-login-button {
            position: relative;
            overflow: hidden;
            background-image: linear-gradient(to top left, #17426a 0%, #2f74a0 45%, #8fc7d8 100%);
            background-size: 220% 220%;
            background-position: left bottom;
            transition: box-shadow 220ms ease;
        }

        /* Efek kilau (shine) pada hover tombol login */
        .landing-login-button::before {
            position: absolute;
            inset: 0;
            content: "";
            background-image: linear-gradient(to top left, rgba(128, 204, 225, 0.78) 0%, rgba(194, 235, 243, 0.64) 48%, rgba(255, 255, 255, 0.18) 100%);
            background-size: 230% 230%;
            background-position: 82% 118%;
            border-radius: inherit;
            opacity: 0;
            transition: opacity 220ms ease 120ms, background-position 540ms ease 120ms;
        }

        .landing-login-button span {
            position: relative;
            z-index: 1;
        }

        .landing-login-button:hover {
            box-shadow: 0 14px 28px rgba(23, 66, 106, 0.24);
        }

        .landing-login-button:hover::before {
            background-position: 24% 0%;
            opacity: 1;
        }

        /* Container untuk animasi marquee review */
        .landing-review-window {
            max-height: 34rem;
        }

        /* Track utama untuk pergerakan review */
        .landing-review-track {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            animation-duration: 10s;
            animation-iteration-count: infinite;
            animation-direction: alternate;
            animation-timing-function: linear;
            will-change: transform;
        }

        /* Jeda animasi saat hover untuk membaca review */
        .landing-review-track:hover {
            animation-play-state: paused;
        }

        .landing-review-set {
            display: grid;
            gap: 1rem;
        }

        /* Arah animasi: Atas ke bawah dan sebaliknya */
        .landing-review-track-left {
            animation-name: landingReviewLoopDown;
        }

        .landing-review-track-right {
            animation-name: landingReviewLoopUp;
        }

        /* Keyframes untuk pergerakan track kiri (ke bawah) */
        @keyframes landingReviewLoopDown {
            from {
                transform: translateY(calc(-50% - 0.5rem));
            }

            to {
                transform: translateY(0);
            }
        }

        /* Keyframes untuk pergerakan track kanan (ke atas) */
        @keyframes landingReviewLoopUp {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(calc(-50% - 0.5rem));
            }
        }

        /* Mematikan animasi jika pengguna mengaktifkan preferensi reduced-motion di OS-nya */
        @media (prefers-reduced-motion: reduce) {
            .landing-review-track {
                animation: none;
            }
        }
    </style>

    {{-- ================================================================ --}}
    {{-- 3. MAIN CONTAINER WRAPPER                                        --}}
    {{-- Bungkus utama halaman dengan overflow tersembunyi agar animasi   --}}
    {{-- tidak menyebabkan scroll horizontal.                             --}}
    {{-- ================================================================ --}}
    <main class="min-h-screen overflow-x-hidden bg-[#eaf2f8] font-sans text-slate-900">
        
        {{-- ---------------------------------------------------------------- --}}
        {{-- A. HEADER / NAVIGATION BAR                                       --}}
        {{-- Navbar akan bersifat fixed di atas dan latar belakangnya akan    --}}
        {{-- berubah ketika di-scroll melalui JavaScript (lihat bawah file).  --}}
        {{-- ---------------------------------------------------------------- --}}
        <header data-landing-header class="fixed inset-x-0 top-0 z-50 bg-transparent transition-colors duration-300">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-5 py-4 sm:px-8 lg:px-10">
                {{-- Logo PENSQuiz: Menyesuaikan ukuran berdasarkan view device (w-[154px] di mobile, sm:w-[176px] di tablet/desktop) --}}
                <x-brand-logo href="#home" data-landing-scroll ariaLabel="PENSQuiz home" class="w-[154px] sm:w-[176px]" />

                {{-- Tautan Navigasi: Disembunyikan di layar HP (hidden), muncul di tablet ke atas (md:flex) --}}
                <nav class="hidden items-center gap-10 text-base font-semibold text-white md:flex" aria-label="Primary navigation">
                    <a href="#home" data-landing-scroll data-landing-nav class="landing-nav-link transition hover:text-[#fdc02a] focus:outline-none">Home</a>
                    <a href="#features" data-landing-scroll data-landing-nav class="landing-nav-link text-white/80 transition hover:text-[#fdc02a] focus:outline-none">Features</a>
                    <a href="#reviews" data-landing-scroll data-landing-nav class="landing-nav-link text-white/80 transition hover:text-[#fdc02a] focus:outline-none">Reviews</a>
                </nav>

                {{-- Tombol Aksi (Register & Login) --}}
                <div class="flex items-center gap-4 sm:gap-8">
                    {{-- Teks Register: Tersembunyi di HP, muncul di tablet ke atas --}}
                    <a href="{{ route('register') }}" class="hidden text-base font-semibold text-white transition hover:text-[#fdc02a] focus:outline-none sm:inline-flex">
                        Register
                    </a>
                    {{-- Tombol Login: Responsif ukurannya dari HP ke tablet (sm:min-w-40) --}}
                    <a href="{{ route('login') }}" class="landing-login-button inline-flex min-w-36 justify-center rounded-full px-10 py-2.5 text-sm font-bold text-white shadow-lg shadow-slate-900/10 focus:outline-none sm:min-w-40 sm:px-11 sm:py-2.5 sm:text-base">
                        <span>Login</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- ---------------------------------------------------------------- --}}
        {{-- B. HERO SECTION (HOME)                                           --}}
        {{-- Bagian teratas dengan gradien biru, menggunakan grid yang        --}}
        {{-- responsif: kolom tumpuk (1 kolom) di HP, 2 kolom di desktop (lg) --}}
        {{-- ---------------------------------------------------------------- --}}
        <section id="home" class="relative bg-[linear-gradient(145deg,#99d2f0_0%,#4d8ab8_50%,#15416b_100%)] pb-48 pt-32 [clip-path:polygon(0_0,100%_0,100%_82%,0_100%)] sm:pb-56 sm:pt-40 lg:pb-60">
            <div class="mx-auto grid max-w-7xl items-center gap-10 px-5 sm:px-8 lg:grid-cols-[1.1fr_0.9fr] lg:px-10">
                
                {{-- Bagian Kiri: Teks Judul dan Deskripsi --}}
                <div class="max-w-2xl z-50">
                    <h1 class="text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
                        Learn More Easily
                        <span class="block">with PENS<span class="text-[#fdc02a]">Quiz</span></span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base font-medium leading-8 text-white/95 sm:text-lg">
                        Create, share, and take interactive quizzes with the academic community of the Electronic Engineering Polytechnic Institute of Surabaya.
                    </p>
                </div>

                {{-- Bagian Kanan: Ilustrasi Piala / Trophy --}}
                <div class="flex justify-center lg:justify-end">
                    {{-- Container ilustrasi yang ukurannya disesuaikan per view device (w-60 -> sm:w-[340px] -> lg:w-[370px]) --}}
                    <div class="relative isolate w-60 sm:w-[340px] lg:w-[370px]">
                        {{-- Efek Glow/Blur di belakang piala --}}
                        <span class="absolute left-1/2 top-[60%] z-0 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-white/45 blur-3xl sm:h-80 sm:w-80" aria-hidden="true"></span>
                        
                        {{-- Dekorasi Confetti --}}
                        <img
                            src="{{ asset('assets/images/img_confetti.png') }}"
                            alt=""
                            class="absolute left-[40%] top-[-24%] z-[1] w-32 -translate-x-1/2 -translate-y-1/4 -rotate-[16deg] object-contain sm:w-44 lg:w-52"
                            aria-hidden="true"
                        >
                        
                        {{-- Gambar Piala Utama --}}
                        <img
                            src="{{ asset('assets/images/img_trophy_1.png') }}"
                            alt="Golden trophy for quiz achievement"
                            class="relative z-10 w-full -rotate-[7deg] object-contain drop-shadow-[0_24px_36px_rgba(0,0,0,0.16)]"
                        >
                    </div>
                </div>
            </div>
        </section>

        {{-- ---------------------------------------------------------------- --}}
        {{-- C. PLATFORM STATISTICS SECTION                                   --}}
        {{-- Kartu statistik melayang yang menjembatani area Hero dan Konten  --}}
        {{-- ---------------------------------------------------------------- --}}
        <section aria-label="Platform statistics" class="relative z-20 mx-auto -mt-28 max-w-5xl px-5 sm:px-8 lg:px-10">
            {{-- Grid layout: 1 kolom di HP, 3 kolom berjejer di tablet/desktop (md:grid-cols-3) --}}
            <div class="grid gap-5 rounded-3xl bg-white p-5 shadow-[0_20px_50px_rgba(0,0,0,0.08)] md:grid-cols-3">
                
                {{-- Stat 1: Users Online --}}
                <article class="flex items-center justify-center gap-5 rounded-2xl border border-slate-900 px-6 py-8">
                    <img src="{{ asset('assets/images/img_student.png') }}" alt="" class="h-14 w-14 shrink-0 object-contain sm:h-16 sm:w-16" aria-hidden="true">
                    <div>
                        <p class="text-3xl font-extrabold leading-none text-slate-900">2.4K</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900 sm:text-[15px]">Users online</p>
                    </div>
                </article>

                {{-- Stat 2: Quizzes (Highlighted dengan background biru) --}}
                <article class="flex items-center justify-center gap-5 rounded-2xl bg-[#5b8cb6] px-6 py-8 text-white">
                    <img src="{{ asset('assets/images/img_brain.png') }}" alt="" class="h-14 w-14 shrink-0 object-contain sm:h-16 sm:w-16" aria-hidden="true">
                    <div>
                        <p class="text-3xl font-extrabold leading-none">100+</p>
                        <p class="mt-2 text-sm font-semibold sm:text-[15px]">Quizzes</p>
                    </div>
                </article>

                {{-- Stat 3: Courses --}}
                <article class="flex items-center justify-center gap-5 rounded-2xl border border-slate-900 px-6 py-8">
                    <img src="{{ asset('assets/images/img_course.png') }}" alt="" class="h-14 w-14 shrink-0 object-contain sm:h-16 sm:w-16" aria-hidden="true">
                    <div>
                        <p class="text-3xl font-extrabold leading-none text-slate-900">20+</p>
                        <p class="mt-2 text-sm font-semibold text-slate-900 sm:text-[15px]">Courses</p>
                    </div>
                </article>
            </div>
        </section>

        {{-- ---------------------------------------------------------------- --}}
        {{-- D. FEATURES SECTION                                              --}}
        {{-- Daftar fitur aplikasi dalam bentuk kartu bergambar besar         --}}
        {{-- ---------------------------------------------------------------- --}}
        <section id="features" class="px-5 pb-20 pt-28 text-center sm:px-8 sm:pt-36 lg:px-10 lg:pb-24">
            <div class="mx-auto max-w-7xl">
                {{-- Judul Seksi Fitur --}}
                <h2 class="mx-auto max-w-4xl text-3xl font-extrabold leading-snug text-slate-900 sm:text-4xl">
                    Take Quizzes, Craft Your Own Quizzes,<br class="hidden md:block"> and Explore Class Courses.
                </h2>

                {{-- Grid untuk Kartu Fitur: 1 kolom di HP, 3 kolom di tablet/desktop (md:grid-cols-3) --}}
                <div class="mt-14 grid gap-8 md:grid-cols-3">
                    {{-- Looping melalui data fitur yang diinisialisasi di atas --}}
                    @foreach ($features as $feature)
                        <a href="{{ $feature['href'] }}" class="group relative min-h-[360px] overflow-hidden rounded-3xl text-left shadow-[0_20px_40px_rgba(0,0,0,0.1)] outline-none transition hover:-translate-y-1 focus:ring-4 focus:ring-[#fdc02a]/60 sm:min-h-[440px]">
                            {{-- Gambar latar belakang fitur --}}
                            <img
                                src="{{ asset('assets/images/' . $feature['image']) }}"
                                alt="{{ $feature['title'] }}"
                                class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105"
                            >
                            {{-- Overlay gradien gelap agar teks di atasnya terbaca jelas --}}
                            <span class="absolute inset-0 bg-gradient-to-b from-black/85 via-black/20 to-transparent" aria-hidden="true"></span>
                            
                            {{-- Konten teks dalam kartu --}}
                            <span class="relative z-10 block p-8 sm:p-10">
                                <span class="block text-3xl font-bold text-white">{{ $feature['title'] }}</span>
                                <span class="mt-3 inline-flex items-center gap-2 text-base font-semibold text-white">
                                    Start now
                                    {{-- Ikon Panah (Animated on hover) --}}
                                    <svg class="h-5 w-5 transition group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ---------------------------------------------------------------- --}}
        {{-- E. USER REVIEWS SECTION                                          --}}
        {{-- Menampilkan ulasan pengguna menggunakan layout marquee statis    --}}
        {{-- ---------------------------------------------------------------- --}}
        <section id="reviews" class="px-5 pb-24 pt-10 sm:px-8 lg:px-10 lg:pb-28">
            {{-- Grid Review: 1 kolom di HP, 2 kolom proporsional (0.85fr vs 1.35fr) di desktop --}}
            <div class="mx-auto grid max-w-7xl gap-10 rounded-[32px] bg-white p-6 shadow-[0_20px_50px_rgba(0,0,0,0.05)] sm:p-10 lg:grid-cols-[0.85fr_1.35fr] lg:items-center lg:gap-16 lg:p-14">
                
                {{-- Teks Intro Review --}}
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl">User Reviews</h2>
                    <p class="mt-4 max-w-sm text-base font-medium leading-7 text-slate-900 sm:text-lg">
                        See what your fellow students are saying about their learning journey with
                        <span class="font-extrabold">PENS<span class="text-[#fdc02a]">Quiz</span></span>
                    </p>
                    <a href="{{ route('register') }}" class="landing-login-button mt-8 inline-flex min-w-40 justify-center rounded-full px-8 py-3.5 text-base font-bold text-white shadow-lg shadow-slate-900/10 focus:outline-none focus:ring-4 focus:ring-[#17426a]/25">
                        <span>Join Now</span>
                    </a>
                </div>

                {{-- Area Animasi Review --}}
                <div class="landing-review-window grid gap-4 overflow-hidden py-4 sm:grid-cols-2">
                    
                    {{-- Track Kolom Kiri (Berjalan ke bawah) --}}
                    <div class="landing-review-track landing-review-track-left">
                        {{-- Repeat ulasan agar animasi terlihat continuous (tanpa jeda kosong) --}}
                        @for ($repeat = 0; $repeat < 2; $repeat++)
                            <div class="landing-review-set" aria-hidden="{{ $repeat === 1 ? 'true' : 'false' }}">
                                @foreach ($reviews as $review)
                                    {{-- Mengambil index ganjil untuk kolom kiri --}}
                                    @continue($loop->index % 2 !== 0)

                                    <article class="rounded-[28px] bg-[#1b456e] p-6 text-white">
                                        {{-- Rating Bintang --}}
                                        <div class="flex gap-1 text-[#fdc02a]" aria-label="5 out of 5 stars">
                                            @for ($i = 0; $i < 5; $i++)
                                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="mt-4 text-sm font-medium leading-6 text-white/95">
                                            PENSQuiz makes practice sessions easier to follow and helps me review class materials before exams.
                                        </p>
                                        {{-- Identitas Reviewer --}}
                                        <div class="mt-5 flex items-center gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold ring-1 ring-white/20" aria-hidden="true">
                                                {{ $review['initial'] }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold leading-none">User</p>
                                                <p class="mt-1 text-xs font-semibold text-white/80">{{ $review['major'] }}</p>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endfor
                    </div>

                    {{-- Track Kolom Kanan (Berjalan ke atas) --}}
                    <div class="landing-review-track landing-review-track-right">
                        @for ($repeat = 0; $repeat < 2; $repeat++)
                            <div class="landing-review-set" aria-hidden="{{ $repeat === 1 ? 'true' : 'false' }}">
                                @foreach ($reviews as $review)
                                    {{-- Mengambil index genap untuk kolom kanan --}}
                                    @continue($loop->index % 2 === 0)

                                    <article class="rounded-[28px] bg-[#1b456e] p-6 text-white">
                                        <div class="flex gap-1 text-[#fdc02a]" aria-label="5 out of 5 stars">
                                            @for ($i = 0; $i < 5; $i++)
                                                <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="mt-4 text-sm font-medium leading-6 text-white/95">
                                            PENSQuiz makes practice sessions easier to follow and helps me review class materials before exams.
                                        </p>
                                        <div class="mt-5 flex items-center gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/15 text-xs font-bold ring-1 ring-white/20" aria-hidden="true">
                                                {{ $review['initial'] }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold leading-none">User</p>
                                                <p class="mt-1 text-xs font-semibold text-white/80">{{ $review['major'] }}</p>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </section>

        {{-- ---------------------------------------------------------------- --}}
        {{-- F. FOOTER SECTION                                                --}}
        {{-- Menggunakan komponen footer standar dari aplikasi                --}}
        {{-- ---------------------------------------------------------------- --}}
        <x-site-footer />
    </main>

    {{-- ================================================================ --}}
    {{-- 4. JAVASCRIPT LOGIC SECTION                                      --}}
    {{-- Menangani animasi scroll halus, penanda menu aktif, dan          --}}
    {{-- perubahan background header ketika disekrol ke bawah.            --}}
    {{-- ================================================================ --}}
    <script>
        (() => {
            // Mengambil semua elemen yang dibutuhkan dari DOM
            const scrollLinks = document.querySelectorAll('[data-landing-scroll]');
            const navLinks = document.querySelectorAll('[data-landing-nav]');
            const header = document.querySelector('header');
            const landingHeader = document.querySelector('[data-landing-header]');
            const sections = [...navLinks].map((link) => document.querySelector(link.getAttribute('href'))).filter(Boolean);
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let scrollAnimationId = 0;

            // Fungsi Easing untuk efek scroll halus (Smooth Scroll)
            const easeOutCubic = (value) => 1 - Math.pow(1 - value, 3);

            // Fungsi utama penggerak scroll animasi
            const animateScroll = (to, duration, animationId) => new Promise((resolve) => {
                const from = window.scrollY;
                const distance = to - from;
                const start = performance.now();

                const step = (time) => {
                    // Batalkan jika animasi scroll baru telah dipicu
                    if (animationId !== scrollAnimationId) {
                        resolve(false);
                        return;
                    }

                    const progress = Math.min((time - start) / duration, 1);
                    window.scrollTo(0, from + (distance * easeOutCubic(progress)));

                    if (progress < 1) {
                        requestAnimationFrame(step);
                        return;
                    }

                    resolve(true);
                };

                requestAnimationFrame(step);
            });

            // Fungsi untuk menyorot link navigasi yang aktif saat ini
            const setActiveNav = (targetId) => {
                navLinks.forEach((link) => {
                    const isActive = link.getAttribute('href') === targetId;

                    link.classList.toggle('is-active', isActive);
                    link.classList.toggle('text-white/80', !isActive);
                });
            };

            // Menambahkan event listener pada setiap link navigasi (smooth scrolling ke seksi tertentu)
            scrollLinks.forEach((link) => {
                link.addEventListener('click', async (event) => {
                    const targetId = link.getAttribute('href');
                    const target = targetId ? document.querySelector(targetId) : null;

                    if (!target) {
                        return;
                    }

                    event.preventDefault();
                    scrollAnimationId += 1;

                    // Mengkalkulasi offset agar header tidak menutupi judul section
                    const headerOffset = header ? header.offsetHeight : 0;
                    const sectionOffset = targetId === '#features' ? 118 : 0;
                    const targetTop = target.getBoundingClientRect().top + window.scrollY - headerOffset + sectionOffset;

                    // Jika pengguna mematikan animasi pada OS, scroll langsung tanpa efek
                    if (prefersReducedMotion) {
                        window.scrollTo(0, targetTop);
                        setActiveNav(targetId);
                        history.pushState(null, '', targetId);
                        return;
                    }

                    const direction = targetTop > window.scrollY ? 1 : -1;
                    const overshoot = Math.max(targetTop + (direction * 54), 0);

                    const animationId = scrollAnimationId;
                    const didOvershoot = await animateScroll(overshoot, 360, animationId);

                    if (!didOvershoot) {
                        return;
                    }

                    await animateScroll(Math.max(targetTop, 0), 320, animationId);
                    setActiveNav(targetId);
                    history.pushState(null, '', targetId);
                });
            });

            // Mengubah style header (background & bayangan) saat halaman di-scroll ke bawah
            const syncHeaderBackground = () => {
                if (!landingHeader) {
                    return;
                }

                landingHeader.classList.toggle('bg-[linear-gradient(145deg,#9bd4ee_0%,#64a5cf_56%,#3476a4_100%)]', window.scrollY > 28);
                landingHeader.classList.toggle('shadow-[0_10px_30px_rgba(21,65,107,0.14)]', window.scrollY > 28);
            };

            syncHeaderBackground();
            window.addEventListener('scroll', syncHeaderBackground, { passive: true });

            // Mengidentifikasi section mana yang sedang dilihat berdasarkan posisi scroll
            const syncActiveNav = () => {
                const headerOffset = header ? header.offsetHeight : 0;
                const marker = window.scrollY + headerOffset + 90;
                const current = sections.reduce((active, section) => {
                    return section.offsetTop <= marker ? section : active;
                }, sections[0]);

                if (current) {
                    setActiveNav(`#${current.id}`);
                }
            };

            syncActiveNav();
            window.addEventListener('scroll', syncActiveNav, { passive: true });
        })();
    </script>
</x-guest-layout>
