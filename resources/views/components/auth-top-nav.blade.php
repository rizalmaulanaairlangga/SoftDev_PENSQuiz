@php
    $user = auth()->user();
    $userHandle = $user?->username ?: $user?->nrp ?: 'user';
    $userLabel = $user?->full_name ?: 'User';
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

<header class="px-5 pt-4 sm:px-8 lg:px-10 lg:pt-5">
    <div class="mx-auto max-w-7xl rounded-[28px] bg-[linear-gradient(145deg,#a8dbf1_0%,#79b7dc_52%,#4b87b2_100%)] shadow-[0_16px_40px_rgba(21,65,107,0.14)]">
        <div class="flex items-center justify-between gap-6 px-6 py-5 sm:px-8 lg:px-10">
            <x-brand-logo href="{{ route('dashboard') }}" ariaLabel="PENSQuiz dashboard" class="w-[148px] sm:w-[176px] lg:w-[194px]" />

            <nav class="hidden items-center gap-10 text-base font-semibold text-white md:flex" aria-label="Primary navigation">
                <a href="{{ route('dashboard') }}" class="site-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Dashboard</a>
                <a href="{{ route('quizzes.index') }}" class="site-nav-link {{ request()->routeIs('quizzes.*') ? 'is-active' : 'text-white/80' }} transition hover:text-[#fdc02a] focus:outline-none">Quizzes</a>
                <button type="button" class="site-nav-link text-white/80 transition hover:text-[#fdc02a] focus:outline-none">Courses</button>
                <details class="site-user-menu relative">
                    <summary class="site-nav-link {{ request()->routeIs('my-quizzes.*') ? 'is-active' : 'text-white/80' }} flex cursor-pointer list-none items-center gap-2 transition hover:text-[#fdc02a] focus:outline-none">
                        My Quizzes
                        <svg class="h-4 w-4 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </summary>

                    <div class="site-menu-panel absolute left-1/2 top-[calc(100%+1.15rem)] z-50 -translate-x-1/2 rounded-[24px] border border-slate-200/70 bg-white p-2 text-slate-900 shadow-[0_18px_40px_rgba(15,23,42,0.16)]">
                        <a href="{{ route('my-quizzes.index') }}" class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-sm font-semibold transition hover:bg-slate-100 focus:outline-none">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M3 5h7l2 2h9v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5Zm0 4h18" />
                            </svg>
                            My Quizzes
                        </a>
                        <a href="{{ route('my-quizzes.create') }}" class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-sm font-semibold transition hover:bg-slate-100 focus:outline-none">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M4 4h10l6 6v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Zm0 2v16h14V10h-5V6H4Zm7 3h2v3h3v2h-3v3h-2v-3H8v-2h3V9Z" />
                            </svg>
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
                        <p class="mt-1 text-sm font-semibold text-white/80">&#64;{{ $userHandle }}</p>
                    </div>
                    <svg class="h-5 w-5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                </summary>

                <div class="absolute right-0 top-[calc(100%+0.85rem)] z-50 min-w-[220px] rounded-2xl border border-slate-200/80 bg-white p-2 shadow-[0_18px_40px_rgba(15,23,42,0.16)]">
                    <div class="rounded-xl px-4 py-3">
                        <p class="text-sm font-bold text-slate-900">{{ $userLabel }}</p>
                        <p class="mt-1 text-sm font-medium text-slate-500">&#64;{{ $userHandle }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none">
                            Logout
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <path d="m16 17 5-5-5-5" />
                                <path d="M21 12H9" />
                            </svg>
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
                    <a href="{{ route('my-quizzes.index') }}" class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-sm font-semibold transition hover:bg-slate-100 focus:outline-none">
                        My Quizzes
                    </a>
                    <a href="{{ route('my-quizzes.create') }}" class="flex items-center gap-3 rounded-[18px] px-4 py-3 text-sm font-semibold transition hover:bg-slate-100 focus:outline-none">
                        Create a Quiz
                    </a>
                </div>
            </details>
        </nav>
    </div>
</header>
