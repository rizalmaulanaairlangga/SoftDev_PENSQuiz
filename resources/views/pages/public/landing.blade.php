<x-guest-layout>
<div class="min-h-screen bg-[#f5f5f5]">

    <!-- Header FULL WIDTH -->
    <header class="w-full px-6 mt-6">
        <div class="bg-[#104876] rounded-2xl px-8 py-5 flex justify-between items-center w-full">

            <!-- TEXT LOGO (not image) -->
            <h1 class="text-white font-bold text-xl tracking-wide">
                PENSQuiz
            </h1>

            <a href="{{ route('login') }}"
            class="bg-white text-black font-semibold 
                    px-4 py-2 text-sm 
                    sm:px-6 sm:text-base
                    rounded-xl hover:bg-gray-100 transition">
                Start Now
            </a>

        </div>
    </header>

    <!-- MAIN CONTENT FULL WIDTH -->
    <section class="px-6 mt-8">
        <div class="bg-white rounded-2xl p-10 w-full">

            <!-- Badge (DASHED BORDER) -->
            <div class="border border-dashed border-black rounded-xl px-4 py-1 inline-block mb-6">
                Quiz Platform
            </div>

            <!-- Heading -->
            <h1 class="text-5xl md:text-6xl font-semibold mb-6 leading-tight">
                Learn More<br>Easily with PENSQuiz
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl max-w-xl mb-10">
                Create, share, and take interactive quizzes with the academic community of the Surabaya State Electronics Polytechnic.
            </p>

            <!-- Divider (DASHED) -->
            <div class="w-full border-t border-dashed border-gray-400 mb-10"></div>

            <!-- Stats -->
            <div class="flex flex-wrap gap-12 text-center sm:text-left">

                <div class="w-full sm:w-auto">
                    <p class="text-3xl font-bold">2.4K</p>
                    <p>Active Students</p>
                </div>

                <div class="w-full sm:w-auto">
                    <p class="text-3xl font-bold">100+</p>
                    <p>Quizzes Available</p>
                </div>

                <div class="w-full sm:w-auto">
                    <p class="text-3xl font-bold">20+</p>
                    <p>Courses</p>
                </div>

            </div>

        </div>
    </section>

</div>
</x-guest-layout>