@php
    $user = auth()->user();
    $userHandle = $user?->email ? explode('@', $user->email)[0] : 'user';
    $userLabel = $user?->fullName ?: 'User';
    $userInitial = strtoupper(substr($userLabel, 0, 1));
@endphp

<style>
    /* 
     * Link Navigasi (Desktop) 
     * Memberikan efek underline kuning yang muncul saat hover atau aktif.
     */
    .site-nav-link {
        position: relative;
    }

    .site-nav-link::after {
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

    .site-nav-link.is-active {
        color: #fdc02a;
    }

    .site-nav-link.is-active::after {
        opacity: 1;
        transform: scaleX(1);
    }

    .site-nav-link:hover::after,
    .site-nav-link:focus-visible::after {
        opacity: 1;
        transform: scaleX(1);
    }

    /* Rotasi ikon panah pada menu dropdown <details> */
    .site-user-menu[open] summary svg {
        transform: rotate(180deg);
    }

    .site-user-menu summary::-webkit-details-marker {
        display: none;
    }

    .site-menu-panel {
        display: grid;
        gap: 0.25rem;
        min-width: 15rem;
    }
</style>

{{-- 
    HEADER UTAMA 
    Menggunakan Alpine.js (x-data) untuk mengontrol state menu mobile.
--}}
<header 
    x-data="{ mobileMenuOpen: false }" 
    class="sticky top-0 z-50 px-5 pt-4 sm:px-8 lg:px-10 lg:pt-5 pb-2"
>
    {{-- Kontainer navigasi dengan background gradient dan shadow premium --}}
    <div class="mx-auto max-w-7xl rounded-[28px] bg-[linear-gradient(145deg,#a8dbf1_0%,#79b7dc_52%,#4b87b2_100%)] shadow-[0_16px_40px_rgba(21,65,107,0.14)]">
        
        {{-- TOP BAR: Logo, Navigasi Desktop, dan Profil --}}
        <div class="flex items-center justify-between gap-6 px-6 py-4 sm:px-8 sm:py-5 lg:px-10">
            
            {{-- Bagian Kiri: Logo --}}
            <x-brand-logo href="{{ route('dashboard') }}" ariaLabel="PENSQuiz dashboard" class="w-[140px] sm:w-[176px] lg:w-[194px]" />

            {{-- Bagian Tengah: Navigasi Desktop (Disembunyikan di Mobile) --}}
            <nav class="hidden items-center gap-10 text-base font-semibold text-white md:flex" aria-label="Primary navigation">
                <a href="{{ route('dashboard') }}" class="site-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Dashboard</a>
                <a href="{{ route('quizzes.index') }}" class="site-nav-link {{ request()->routeIs('quizzes.*') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Quizzes</a>
                
                {{-- Dropdown My Quizzes (Desktop) --}}
                <details class="site-user-menu relative">
                    <summary class="site-nav-link {{ request()->routeIs('my-quizzes.*') ? 'is-active' : 'text-white/80' }} flex cursor-pointer list-none items-center gap-2 transition hover:text-[#fdc02a] focus:outline-none">
                        My Quizzes
                        <svg class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>

                    <div class="site-menu-panel absolute left-1/2 top-[calc(100%+1.15rem)] z-50 -translate-x-1/2 rounded-[24px] border border-slate-200/70 bg-white p-2 text-slate-900 shadow-[0_18px_40px_rgba(15,23,42,0.16)]">
                        <a href="{{ route('my-quizzes.index') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-[#eef8fc] hover:text-[#528FB9] focus:outline-none">
                            <img src="{{ asset('assets/images/img_myquizzes.png') }}" class="h-5 w-5 shrink-0 object-contain" alt="My Quizzes">
                            My Quizzes
                        </a>
                        <a href="{{ route('my-quizzes.create') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-[#eef8fc] hover:text-[#528FB9] focus:outline-none">
                            <img src="{{ asset('assets/images/img_create_quiz.png') }}" class="h-5 w-5 shrink-0 object-contain" alt="Create a Quiz">
                            Create a Quiz
                        </a>
                    </div>
                </details>
            </nav>

            {{-- Bagian Kanan: Profil & Hamburger (Mobile) --}}
            <div class="flex items-center gap-2 sm:gap-4">
                
                {{-- Tombol Hamburger (Hanya tampil di Mobile) --}}
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    type="button"
                    class="flex md:hidden h-10 w-10 items-center justify-center rounded-full text-white hover:bg-white/10 transition focus:outline-none"
                    aria-label="Toggle mobile menu"
                >
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                {{-- User Profile Dropdown --}}
                <details class="site-user-menu relative shrink-0">
                    <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full px-1 py-1 text-white transition hover:bg-white/10 focus:outline-none">
                        <div class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-[#fdc02a] text-base sm:text-lg font-extrabold text-[#17426a] ring-2 ring-white/20">
                            {{ $userInitial }}
                        </div>
                        <div class="hidden text-left md:block">
                            <p class="text-base font-bold leading-none">{{ $userLabel }}</p>
                            <p class="mt-1 text-sm font-semibold text-white/80">&#64;{{ auth()->user()->username }}</p>
                        </div>
                        <svg class="hidden sm:block h-5 w-5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>

                    {{-- Menu Dropdown User --}}
                    <div class="absolute right-0 top-[calc(100%+0.85rem)] z-50 min-w-[240px] rounded-[24px] border border-slate-100 bg-white p-2 shadow-[0_20px_50px_rgba(15,23,42,0.18)]">
                        <div class="mb-1 rounded-xl px-4 py-3">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Account</p>
                            <p class="mt-1 text-sm font-black text-slate-900">{{ $userLabel }}</p>
                        </div>
                        
                        <div class="h-px w-full bg-slate-50 my-1"></div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-[#eef8fc] hover:text-[#528FB9] focus:outline-none">
                            <img src="{{ asset('assets/images/img_profile.png') }}" class="h-6 w-6 shrink-0 object-contain" alt="Profile">
                            My Profile
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-[#eef8fc] hover:text-[#528FB9] focus:outline-none">
                            <img src="{{ asset('assets/images/img_settings.png') }}" class="h-6 w-6 shrink-0 object-contain" alt="Settings">
                            Settings
                        </a>

                        <div class="h-px w-full bg-slate-50 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-4 rounded-[18px] px-4 py-3.5 text-sm font-bold text-red-500 transition hover:bg-red-50 focus:outline-none">
                                <img src="{{ asset('assets/images/img_logout.png') }}" class="h-6 w-6 shrink-0 object-contain" alt="Logout">
                                Logout
                            </button>
                        </form>
                    </div>
                </details>
            </div>
        </div>

        {{-- ================================================================ --}}
        {{-- MOBILE DROPDOWN MENU                                             --}}
        {{-- Menampilkan navigasi saat hamburger diklik di layar mobile.      --}}
        {{-- ================================================================ --}}
        <div 
            x-show="mobileMenuOpen" 
            x-cloak
            x-collapse
            class="md:hidden border-t border-white/20 px-6 py-4"
        >
            <nav class="flex flex-col gap-2">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-between rounded-[18px] px-4 py-3.5 text-base font-bold {{ request()->routeIs('dashboard') ? 'bg-white text-[#528FB9]' : 'text-white hover:bg-white/10' }} transition focus:outline-none">
                    Dashboard
                    @if(request()->routeIs('dashboard'))
                        <div class="h-2 w-2 rounded-full bg-[#fdc02a]"></div>
                    @endif
                </a>
                <a href="{{ route('quizzes.index') }}" class="flex items-center justify-between rounded-[18px] px-4 py-3.5 text-base font-bold {{ request()->routeIs('quizzes.*') ? 'bg-white text-[#528FB9]' : 'text-white hover:bg-white/10' }} transition focus:outline-none">
                    Quizzes
                    @if(request()->routeIs('quizzes.*'))
                        <div class="h-2 w-2 rounded-full bg-[#fdc02a]"></div>
                    @endif
                </a>
                
                {{-- Divider --}}
                <div class="h-px w-full bg-white/10 my-2"></div>
                <p class="px-4 text-[11px] font-bold uppercase tracking-widest text-white/50">Creator Area</p>

                <a href="{{ route('my-quizzes.index') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3.5 text-base font-bold {{ request()->routeIs('my-quizzes.index') ? 'bg-white text-[#528FB9]' : 'text-white hover:bg-white/10' }} transition focus:outline-none">
                    <img src="{{ asset('assets/images/img_myquizzes.png') }}" class="h-5 w-5 brightness-0 invert" style="{{ request()->routeIs('my-quizzes.index') ? 'filter: none;' : '' }}" alt="">
                    My Quizzes
                </a>
                <a href="{{ route('my-quizzes.create') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3.5 text-base font-bold {{ request()->routeIs('my-quizzes.create') ? 'bg-white text-[#528FB9]' : 'text-white hover:bg-white/10' }} transition focus:outline-none">
                    <img src="{{ asset('assets/images/img_create_quiz.png') }}" class="h-5 w-5 brightness-0 invert" style="{{ request()->routeIs('my-quizzes.create') ? 'filter: none;' : '' }}" alt="">
                    Create a Quiz
                </a>
            </nav>
        </div>
    </div>
</header>

{{-- Script untuk menutup dropdown <details> saat klik di luar area --}}
<script>
    document.addEventListener('click', function(event) {
        document.querySelectorAll('details.site-user-menu').forEach(function(details) {
            if (!details.contains(event.target) && details.hasAttribute('open')) {
                details.removeAttribute('open');
            }
        });
    });
</script>
