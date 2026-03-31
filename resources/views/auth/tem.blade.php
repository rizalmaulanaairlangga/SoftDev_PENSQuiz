{{-- ============================= --}}
{{-- LOGIN PAGE (BLADE + FIGMA-LIKE) --}}
{{-- resources/views/auth/login.blade.php --}}
{{-- ============================= --}}

<x-guest-layout>
    <div class="h-screen overflow-hidden bg-white flex flex-col lg:flex-row">

        <!-- LEFT: LOGIN FORM -->
        <section class="w-full lg:w-[42%] bg-white flex items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-0">
            <div class="w-full max-w-md lg:w-[88%]">
                <h1 class="text-[24px] sm:text-[30px] lg:text-[36px] font-bold leading-tight mb-6">
                    <span class="text-[#104876]">PENS</span><span class="text-[#adadad]">Quiz</span>
                </h1>

                <h2 class="text-[28px] sm:text-[36px] lg:text-[44px] font-semibold leading-[1.05] text-black mt-4 sm:mt-6 lg:mt-8">
                    Welcome Back!
                </h2>

                <p class="text-[14px] sm:text-[16px] lg:text-[18px] font-normal leading-[1.35] text-black mt-3 sm:mt-4 lg:mt-5">
                    Continue your studies with the PENS community.<br>
                    Log in and start today's quiz.
                </p>

                <form method="POST" action="{{ route('login') }}" class="w-full mt-6 sm:mt-8 lg:mt-10 space-y-4">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-[16px] font-normal leading-5 text-black">
                            Email Addres PENS
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="1234@ds.student.pens.ac.id"
                            required
                            autofocus
                            autocomplete="username"
                            class="w-full text-[16px] font-normal leading-5 text-black placeholder:text-[#9e9696] bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-[16px] font-normal leading-5 text-black">
                            Password
                        </label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="123457890"
                            required
                            autocomplete="current-password"
                            class="w-full text-[16px] font-normal leading-5 text-black placeholder:text-[#9e9696] bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300"
                        >
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Button -->
                    <button
                        type="submit"
                        class="w-full text-[18px] sm:text-[20px] font-semibold leading-6 text-center text-white bg-[#104876] border border-[#474242] rounded-[16px] px-8 py-3 mt-2 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] hover:bg-[#0d3a5f] hover:shadow-lg active:scale-[0.99] transition-all duration-300"
                    >
                        Login
                    </button>

                    <!-- Link -->
                    <p class="text-[15px] font-normal leading-5 text-center text-black mt-4 sm:mt-5 lg:mt-6">
                        <span>Don't have an account yet? </span>
                        <a href="{{ route('register') }}" class="font-bold hover:text-[#104876] transition-colors duration-300">
                            Sign up now
                        </a>
                    </p>
                </form>
            </div>
        </section>

        <!-- RIGHT: ILLUSTRATION / PROMO -->
        <section class="w-full lg:w-[58%] bg-[#eaeaea] lg:rounded-l-[20px] flex flex-col justify-start items-center px-4 sm:px-6 lg:px-9 py-10 sm:py-14 lg:py-24 min-h-[500px] lg:min-h-screen mt-4 lg:mt-0">
            <div class="w-full max-w-3xl flex flex-col items-center lg:items-start">
                <div class="w-full lg:w-[86%] flex flex-col gap-4 sm:gap-5 lg:gap-7 items-center lg:items-start">
                    <h2 class="text-[28px] sm:text-[36px] lg:text-[44px] font-semibold leading-tight text-black text-left">
                        Learn smarter,<br>starting here.
                    </h2>

                    <p class="text-[14px] sm:text-[16px] lg:text-[18px] font-normal leading-6 text-black text-left max-w-xl">
                        PENSQuiz helps you sharpen your understanding through interactive quizzes — anytime, anywhere.
                    </p>
                </div>

                <div class="relative w-full lg:w-[92%] h-[180px] sm:h-[240px] lg:h-[300px] mt-6 sm:mt-8 lg:mt-10">
                    <!-- Blue background card -->
                    <div class="absolute w-[66%] min-w-[220px] bg-[#104876] border border-white rounded-[16px] shadow-[0px_4px_4px_rgba(136,136,136,0.25)] top-[32%] left-[18%] sm:top-[35%] sm:left-[24%] lg:top-[28%] lg:left-[24%] px-5 sm:px-7 lg:px-10 py-8 sm:py-12 lg:py-16">
                        <p class="text-[20px] font-normal leading-6 text-center text-[#eaeaea]">
                            [Ilustrasi]
                        </p>
                    </div>

                    <!-- Top-left card -->
                    <div class="absolute w-[160px] sm:w-[180px] lg:w-[200px] bg-white border border-[#474242] rounded-[16px] shadow-[0px_4px_4px_rgba(136,136,136,0.25)] top-0 left-0 px-7 py-10 sm:px-10 sm:py-12 lg:px-14 lg:py-14 z-20">
                        <p class="text-[18px] sm:text-[20px] font-normal leading-6 text-center text-[#104876]">
                            [Ilustrasi]
                        </p>
                    </div>

                    <!-- Bottom-right card -->
                    <div class="absolute w-[160px] sm:w-[180px] lg:w-[200px] bg-white border border-[#474242] rounded-[16px] shadow-[0px_4px_4px_rgba(136,136,136,0.25)] bottom-0 right-0 px-7 py-10 sm:px-10 sm:py-12 lg:px-14 lg:py-14 z-20">
                        <p class="text-[18px] sm:text-[20px] font-normal leading-6 text-center text-[#104876]">
                            [Ilustrasi]
                        </p>
                    </div>
                </div>

                <p class="text-[16px] font-medium leading-5 text-center text-black mt-4 sm:mt-5 lg:mt-7 max-w-md lg:max-w-none">
                    Hone your knowledge, measure your abilities.<br>
                    From students, for PENS students.
                </p>
            </div>
        </section>
    </div>
</x-guest-layout>


{{-- ============================= --}}
{{-- REGISTER PAGE (RESPONSIVE + AGREEMENT) --}}
{{-- resources/views/auth/register.blade.php --}}
{{-- ============================= --}}

<x-guest-layout>
    <div class="h-screen overflow-hidden bg-white flex flex-col lg:flex-row">

        <!-- LEFT: ILLUSTRATION -->
        <section class="hidden lg:flex lg:w-1/2 bg-[#eaeaea] rounded-r-[20px] items-center justify-center px-8 py-24">
            <div class="w-full max-w-3xl flex flex-col items-center text-center">
                <h2 class="text-[28px] sm:text-[36px] lg:text-[44px] font-semibold leading-tight text-black">
                    Join and start your journey.
                </h2>
                <p class="text-[14px] sm:text-[16px] lg:text-[18px] font-normal leading-6 text-black mt-6 max-w-xl">
                    Thousands of students have been learning smarter with PENSQuiz. Now it's your turn.
                </p>

                <div class="relative w-full h-[418px] mt-14">
                    <div class="absolute w-[66%] min-w-[220px] bg-[#104876] border border-white rounded-[16px] shadow-[0px_4px_4px_rgba(136,136,136,0.25)] top-[28%] left-[18%] px-7 py-28">
                        <p class="text-[20px] font-normal leading-6 text-center text-[#eaeaea]">[Ilustrasi]</p>
                    </div>
                    <div class="absolute w-[200px] bg-white border border-[#474242] rounded-[16px] shadow-[0px_4px_4px_rgba(136,136,136,0.25)] top-0 left-0 px-14 py-14 z-20">
                        <p class="text-[18px] font-normal leading-6 text-center text-[#104876]">[Ilustrasi]</p>
                    </div>
                    <div class="absolute w-[200px] bg-white border border-[#474242] rounded-[16px] shadow-[0px_4px_4px_rgba(136,136,136,0.25)] bottom-0 right-0 px-14 py-14 z-20">
                        <p class="text-[18px] font-normal leading-6 text-center text-[#104876]">[Ilustrasi]</p>
                    </div>
                </div>

                <p class="text-[16px] font-medium leading-5 text-center text-black mt-7 max-w-md">
                    Hone your knowledge, measure your abilities.<br>
                    From students, for PENS students.
                </p>
            </div>
        </section>

        <!-- RIGHT: FORM -->
        <section class="w-full lg:w-1/2 flex items-center justify-center px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-0">
            <div class="w-full max-w-md lg:w-[88%]">
                <h1 class="text-[24px] sm:text-[30px] lg:text-[36px] font-bold leading-tight mb-6">
                    <span class="text-[#104876]">PENS</span><span class="text-[#adadad]">Quiz</span>
                </h1>

                <h2 class="text-[28px] sm:text-[36px] lg:text-[44px] font-semibold leading-[1.05] text-black">
                    Create your account
                </h2>

                <p class="text-[14px] sm:text-[16px] lg:text-[18px] font-normal leading-6 text-black mt-3 sm:mt-4 lg:mt-5">
                    Register now and start your learning journey at PENSQuiz.<br>
                    Free for all PENS students.
                </p>

                <form method="POST" action="{{ route('register') }}" class="w-full mt-6 sm:mt-8 lg:mt-10 space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="full_name" class="block text-[16px] font-normal leading-5 text-black">Full Name</label>
                            <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required autocomplete="name"
                                class="w-full text-[16px] font-normal leading-5 text-black bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300">
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <label for="nrp" class="block text-[16px] font-normal leading-5 text-black">NRP</label>
                            <input id="nrp" type="text" name="nrp" value="{{ old('nrp') }}" required autocomplete="username"
                                class="w-full text-[16px] font-normal leading-5 text-black bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300">
                            <x-input-error :messages="$errors->get('nrp')" class="mt-2" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-[16px] font-normal leading-5 text-black">Email Address PENS</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="1234@ds.student.pens.ac.id" required
                            class="w-full text-[16px] font-normal leading-5 text-black placeholder:text-[#9e9696] bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-[16px] font-normal leading-5 text-black">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                            class="w-full text-[16px] font-normal leading-5 text-black placeholder:text-[#9e9696] bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-[16px] font-normal leading-5 text-black">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="w-full text-[16px] font-normal leading-5 text-black placeholder:text-[#9e9696] bg-white border border-[#474242] rounded-[16px] px-5 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] focus:outline-none focus:ring-2 focus:ring-[#104876] focus:border-transparent transition-all duration-300">
                    </div>

                    <div class="flex items-start gap-3 pt-1">
                        <input type="checkbox" id="agree" class="mt-1 h-6 w-6 rounded border-[#474242] text-[#104876] focus:ring-[#104876]">
                        <label for="agree" class="text-[15px] leading-5 text-black cursor-pointer">
                            I agree to the Terms &amp; Conditions and Privacy Policy of PENSQuiz.
                        </label>
                    </div>

                    <button
                        id="registerBtn"
                        type="submit"
                        class="w-full text-[18px] sm:text-[20px] font-semibold leading-6 text-center text-white bg-[#104876] border border-[#474242] rounded-[16px] px-8 py-3 shadow-[0px_4px_4px_rgba(136,136,136,0.25)] hover:bg-[#0d3a5f] hover:shadow-lg active:scale-[0.99] transition-all duration-300 opacity-50 cursor-not-allowed"
                        disabled
                    >
                        Register Now
                    </button>

                    <p class="text-[15px] font-normal leading-5 text-center text-black mt-4 sm:mt-5 lg:mt-6">
                        <span>Already have an account? </span>
                        <a href="{{ route('login') }}" class="font-bold hover:text-[#104876] transition-colors duration-300">
                            Login
                        </a>
                    </p>
                </form>
            </div>
        </section>
    </div>

    <script>
        const checkbox = document.getElementById('agree');
        const button = document.getElementById('registerBtn');

        if (checkbox && button) {
            checkbox.addEventListener('change', function () {
                const enabled = this.checked;
                button.disabled = !enabled;
                button.classList.toggle('opacity-50', !enabled);
                button.classList.toggle('cursor-not-allowed', !enabled);
            });
        }
    </script>
</x-guest-layout>
