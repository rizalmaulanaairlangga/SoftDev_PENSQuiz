<div class="fixed bottom-0 left-0 w-full bg-[#104876] flex justify-around items-center py-3 md:hidden z-50">

    <!-- HOME -->
    <a href="{{ route('dashboard') }}"
        class="p-4 rounded-full transition
        {{ request()->routeIs('dashboard') ? 'bg-white/90' : 'hover:bg-white/20 active:bg-white' }}">

        <img src="{{ asset('assets/dashboard/icons/img_home_page.png') }}"
                class="w-6 h-6 {{ request()->routeIs('dashboard') ? '' : 'brightness-0 invert' }}">
    </a>

    <!-- discover quiz -->
    <a href="{{ route('quizzes.index') }}"
        class="p-4 rounded-full transition
        {{ request()->routeIs('quizzes.index') ? 'bg-white/90' : 'hover:bg-white/20 active:bg-white' }}">

        <img src="{{ asset('assets/dashboard/icons/img_four_squares.png') }}"
                class="w-6 h-6 {{ request()->routeIs('quizzes.index') ? '' : 'brightness-0 invert' }}">
    </a>

    {{-- COURSES --}}
    <a href="#"
       class="p-3 rounded-full hover:bg-white/20 active:bg-white transition-all">
        <img src="{{ asset('assets/dashboard/icons/img_books.png') }}"
            class="w-6 h-6 brightness-0 invert" alt="">
    </a>

    <!-- create -->
    <a href="{{ route('my-quizzes.index') }}"
        class="p-4 rounded-full transition
        {{ request()->routeIs('my-quizzes.index') ? 'bg-white/90' : 'hover:bg-white/20 active:bg-white' }}">

        <img src="{{ asset('assets/dashboard/icons/img_create_order.png') }}"
                class="w-6 h-6 {{ request()->routeIs('my-quizzes.index') ? '' : 'brightness-0 invert' }}">
    </a>


</div>