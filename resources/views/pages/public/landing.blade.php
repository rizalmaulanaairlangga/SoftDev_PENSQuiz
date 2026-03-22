{{-- resources/views/pages/public/landing.blade.php --}}
<x-guest-layout>
<div class="bg-[#f5f5f5] min-h-screen">

    <!-- Header Section -->
    <header class="w-full max-w-[92%] mx-auto mt-6">
        <div class="bg-[#104876] rounded-xl p-6 flex justify-between items-center">

            <!-- Logo -->
            <div class="w-[136px]">
                <img src="{{ asset('assets/images/img_header_logo.png') }}" alt="PENSQuiz Logo" />
            </div>

            <!-- Start Now Button -->
            <a href="{{ route('login') }}"
               class="bg-white text-black font-bold px-6 py-2 rounded-xl hover:bg-gray-100 transition">
                Start Now
            </a>

        </div>
    </header>

    <!-- Main Content Section -->
    <section class="flex justify-center mt-10">
        <div class="bg-white rounded-xl p-10 w-full max-w-6xl">

            <!-- Badge -->
            <div class="border border-black rounded-xl px-3 py-1 inline-block mb-6">
                Quiz Platform
            </div>

            <!-- Heading -->
            <h1 class="text-5xl font-semibold mb-6">
                Learn More<br>Easily with PENSQuiz
            </h1>

            <!-- Description -->
            <p class="text-lg w-1/2 mb-10">
                Create, share, and take interactive quizzes with the academic community of the Surabaya State Electronics Polytechnic.
            </p>

            <!-- Divider -->
            <div class="w-full h-px bg-black mb-8"></div>

            <!-- Stats -->
            <div class="flex gap-12">

                <div>
                    <p class="text-3xl font-bold">2.4K</p>
                    <p>Active Students</p>
                </div>

                <div>
                    <p class="text-3xl font-bold">100+</p>
                    <p>Quizzes Available</p>
                </div>

                <div>
                    <p class="text-3xl font-bold">20+</p>
                    <p>Courses</p>
                </div>

            </div>

        </div>
    </section>

</div>
</x-guest-layout>
