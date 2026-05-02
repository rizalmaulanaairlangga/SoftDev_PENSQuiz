@php
    $user = auth()->user();
    $userHandle = $user?->email ? explode('@', $user->email)[0] : 'user';
    $userLabel = $user?->fullName ?: 'User';
    $userInitial = strtoupper(substr($userLabel, 0, 1));
@endphp

<style>
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

<header class="sticky top-0 z-50 px-5 pt-4 sm:px-8 lg:px-10 lg:pt-5 pb-2">
    <div class="mx-auto max-w-7xl rounded-[28px] bg-[linear-gradient(145deg,#a8dbf1_0%,#79b7dc_52%,#4b87b2_100%)] shadow-[0_16px_40px_rgba(21,65,107,0.14)]">
        <div class="flex items-center justify-between gap-6 px-6 py-5 sm:px-8 lg:px-10">
            <x-brand-logo href="{{ route('dashboard') }}" ariaLabel="PENSQuiz dashboard" class="w-[148px] sm:w-[176px] lg:w-[194px]" />

            <nav class="hidden items-center gap-10 text-base font-semibold text-white md:flex" aria-label="Primary navigation">
                <a href="{{ route('dashboard') }}" class="site-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Dashboard</a>
                <a href="{{ route('quizzes.index') }}" class="site-nav-link {{ request()->routeIs('quizzes.*') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Quizzes</a>
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

            <details class="site-user-menu relative shrink-0">
                <summary class="flex cursor-pointer list-none items-center gap-3 rounded-full px-1 py-1 text-white transition hover:bg-white/10 focus:outline-none">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#fdc02a] text-lg font-extrabold text-[#17426a] ring-2 ring-white/20">
                        {{ $userInitial }}
                    </div>
                    <div class="hidden text-left md:block">
                        <p class="text-base font-bold leading-none">{{ $userLabel }}</p>
                        <p class="mt-1 text-sm font-semibold text-white/80">&#64;{{ auth()->user()->username }}</p>
                    </div>
                    <svg class="h-5 w-5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </summary>

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

        <nav class="flex items-center gap-6 overflow-x-auto px-6 pb-5 text-sm font-semibold text-white md:hidden sm:px-8">
            <a href="{{ route('dashboard') }}" class="site-nav-link shrink-0 {{ request()->routeIs('dashboard') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Dashboard</a>
            <a href="{{ route('quizzes.index') }}" class="site-nav-link shrink-0 {{ request()->routeIs('quizzes.*') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Quizzes</a>
            <button type="button" class="site-nav-link shrink-0 text-white/80 transition hover:text-[#fdc02a] focus:outline-none">Courses</button>
            <details class="site-user-menu relative shrink-0">
                <summary class="site-nav-link {{ request()->routeIs('my-quizzes.*') ? 'is-active' : 'text-white/80' }} flex cursor-pointer list-none items-center gap-2 transition hover:text-[#fdc02a] focus:outline-none">
                    My Quizzes
                    <svg class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </summary>
                <div class="site-menu-panel absolute left-0 top-[calc(100%+0.85rem)] z-50 rounded-[24px] border border-slate-200/70 bg-white p-2 text-slate-900 shadow-[0_18px_40px_rgba(15,23,42,0.16)]">
                    <a href="{{ route('my-quizzes.index') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3 text-sm font-bold transition hover:bg-[#eef8fc] hover:text-[#528FB9] focus:outline-none">
                        <img src="{{ asset('assets/images/img_myquizzes.png') }}" class="h-5 w-5 shrink-0 object-contain" alt="My Quizzes">
                        My Quizzes
                    </a>
                    <a href="{{ route('my-quizzes.create') }}" class="flex items-center gap-4 rounded-[18px] px-4 py-3 text-sm font-bold transition hover:bg-[#eef8fc] hover:text-[#528FB9] focus:outline-none">
                        <img src="{{ asset('assets/images/img_create_quiz.png') }}" class="h-5 w-5 shrink-0 object-contain" alt="Create a Quiz">
                        Create a Quiz
                    </a>
                </div>
            </details>
        </nav>
    </div>
</header>
