<aside class="hidden md:flex flex-col justify-between
            fixed top-0 left-0 h-screen w-24
            bg-[#104876] py-6 z-50">
    
    <!-- TOP MENU -->
    <div class="flex flex-col items-center gap-8">

        <!-- HOME -->
        <a href="{{ route('dashboard') }}"
           class="p-4 rounded-full transition
           {{ request()->routeIs('dashboard') ? 'bg-white/90' : 'hover:bg-white/20 active:bg-white' }}">

            <img src="{{ asset('assets/dashboard/icons/img_home_page.png') }}"
                 class="w-8 h-8 {{ request()->routeIs('dashboard') ? '' : 'brightness-0 invert' }}">
        </a>

        <!-- discover quiz -->
        <a href="{{ route('quizzes.index') }}"
           class="p-4 rounded-full transition
           {{ request()->routeIs('quizzes.index') ? 'bg-white/90' : 'hover:bg-white/20 active:bg-white' }}">

            <img src="{{ asset('assets/dashboard/icons/img_four_squares.png') }}"
                 class="w-8 h-8 {{ request()->routeIs('quizzes.index') ? '' : 'brightness-0 invert' }}">
        </a>

        <a href="#" class="p-4 rounded-full hover:bg-white/20">
            <img src="{{ asset('assets/dashboard/icons/img_books.png') }}"
                 class="w-8 h-8 invert">
        </a>

        <!-- my quizzes -->
        <a href="{{ route('my-quizzes.index') }}"
           class="p-4 rounded-full transition
           {{ request()->routeIs('my-quizzes.index') ? 'bg-white/90' : 'hover:bg-white/20 active:bg-white' }}">

            <img src="{{ asset('assets/dashboard/icons/img_create_order.png') }}"
                 class="w-8 h-8 {{ request()->routeIs('my-quizzes.index') ? '' : 'brightness-0 invert' }}">
        </a>

    </div>

    <!-- LOGOUT (BOTTOM FIXED) -->
    <div class="flex justify-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="p-4 rounded-full hover:bg-red-500/20 transition">
                <img src="{{ asset('assets/dashboard/icons/img_logout.png') }}"
                     class="w-8 h-8 invert">
            </button>
        </form>
    </div>

</aside>