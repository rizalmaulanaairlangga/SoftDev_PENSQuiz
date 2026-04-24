<x-guest-layout>
    <style>
        .auth-slide-from-left,
        .auth-slide-from-right {
            animation-duration: 760ms;
            animation-fill-mode: both;
            animation-timing-function: cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform, opacity;
        }

        .auth-slide-from-left {
            animation-name: authSlideFromLeft;
        }

        .auth-slide-from-right {
            animation-name: authSlideFromRight;
        }

        @keyframes authSlideFromLeft {
            from {
                opacity: 0;
                transform: translateX(-5rem) scale(0.985);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes authSlideFromRight {
            from {
                opacity: 0;
                transform: translateX(5rem) scale(0.985);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .auth-slide-from-left,
            .auth-slide-from-right {
                animation: none;
            }
        }
    </style>

    <main class="min-h-screen bg-[#f8f8f6] px-4 py-3 font-sans text-[#101010] sm:px-6 lg:px-8">
        <div class="mx-auto grid min-h-[calc(100vh-1.5rem)] max-w-[1600px] gap-5 lg:h-[calc(100vh-1.5rem)] lg:grid-cols-2 lg:gap-7">
            <section class="auth-slide-from-right relative min-h-[520px] overflow-hidden rounded-[28px] bg-[linear-gradient(180deg,#8fd4f0_0%,#7dc6e9_18%,#5b98d0_52%,#1f4b88_100%)] px-6 py-8 sm:px-10 lg:min-h-0 lg:px-12 lg:py-7">
                <img
                    src="{{ asset('assets/images/img_background.png') }}"
                    alt=""
                    class="pointer-events-none absolute inset-0 h-full w-full object-cover object-center opacity-35 mix-blend-soft-light"
                    aria-hidden="true"
                >

                <div class="relative z-10 flex min-h-full flex-col justify-between gap-7">
                    <div class="flex justify-center pt-4 lg:pt-8">
                        <img
                            src="{{ asset('assets/images/img_exam_1.png') }}"
                            alt="Student preparing an online quiz"
                            class="w-full max-w-[360px] -scale-x-100 object-contain drop-shadow-[0_28px_40px_rgba(12,48,86,0.22)] sm:max-w-[420px] lg:max-w-[430px] xl:max-w-[500px]"
                        >
                    </div>

                    <div class="pb-2 sm:pb-5 lg:pb-7 xl:pb-8">
                        <h2 class="max-w-3xl text-3xl font-extrabold leading-tight tracking-normal text-white sm:text-4xl lg:text-[32px] xl:text-[36px]">
                            Join and start your journey.
                        </h2>
                        <p class="mt-3 max-w-2xl text-base font-medium leading-7 text-white sm:text-lg lg:text-base xl:text-lg">
                            Thousands of PENS students have been learning<br class="hidden sm:block">
                            smarter with PENSQuiz. Now it's your turn.
                        </p>
                    </div>
                </div>
            </section>

            <section class="auth-slide-from-left relative overflow-hidden rounded-[28px] bg-[linear-gradient(180deg,#fafcff_0%,#edf5ff_63%,#d4e5fb_100%)] px-6 py-6 sm:px-10 sm:py-7 lg:px-12 lg:py-6 xl:px-16">
                <img
                    src="{{ asset('assets/images/img_background.png') }}"
                    alt=""
                    class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-45"
                    aria-hidden="true"
                >

                <div class="relative z-10 mx-auto flex min-h-full max-w-3xl flex-col">
                    <a href="{{ url('/') }}" class="inline-flex w-fit items-center gap-3 text-base font-semibold text-[#101010] transition hover:text-[#1d5687] focus:outline-none">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        Back
                    </a>

                    <div class="mt-5 sm:mt-6">
                        <x-brand-logo href="{{ url('/') }}" ariaLabel="PENSQuiz home" class="w-[154px] sm:w-[176px]" />

                        <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-normal text-black sm:text-[44px]">
                            Create an account
                        </h1>

                        <p class="mt-3 max-w-2xl text-base font-medium leading-6 text-[#101010] sm:text-[17px]">
                            Register now and start your learning journey at PENSQuiz.<br class="hidden sm:block">
                            Free for all PENS students.
                        </p>
                    </div>

                <form method="POST" action="{{ route('register') }}" class="mt-6 w-full space-y-3 sm:mt-7">
                    @csrf

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-[#171717]">
                                Full Name
                            </label>
                            <input
                                id="full_name"
                                type="text"
                                name="full_name"
                                value="{{ old('full_name') }}"
                                placeholder="John Doe"
                                required
                                autocomplete="name"
                                class="mt-2 h-12 w-full rounded-[16px] border border-[#6f6b69] bg-white px-5 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20"
                            >
                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                        </div>

                        <div>
                            <label for="nrp" class="block text-sm font-medium text-[#171717]">
                                NRP
                            </label>
                            <input
                                id="nrp"
                                type="text"
                                name="nrp"
                                value="{{ old('nrp') }}"
                                placeholder="1234567890"
                                required
                                autocomplete="username"
                                class="mt-2 h-12 w-full rounded-[16px] border border-[#6f6b69] bg-white px-5 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20"
                            >
                            <x-input-error :messages="$errors->get('nrp')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-[#171717]">
                            PENS Student Email Address
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="1234@ds.student.pens.ac.id"
                            required
                            autocomplete="email"
                            class="mt-2 h-12 w-full rounded-[16px] border border-[#6f6b69] bg-white px-5 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20"
                        >
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label for="password" class="block text-sm font-medium text-[#171717]">
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="123457890"
                                required
                                autocomplete="new-password"
                                class="mt-2 h-12 w-full rounded-[16px] border border-[#6f6b69] bg-white px-5 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-[#171717]">
                                Confirm Password
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="123457890"
                                required
                                autocomplete="new-password"
                                class="mt-2 h-12 w-full rounded-[16px] border border-[#6f6b69] bg-white px-5 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20"
                            >
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-1">
                        <input
                            type="checkbox"
                            id="agree"
                            class="h-7 w-7 rounded-[8px] border border-[#6f6b69] bg-white text-[#1d5687] focus:ring-4 focus:ring-[#1d5687]/20"
                        >
                        <label for="agree" class="cursor-pointer text-sm font-medium leading-5 text-black">
                            I agree to the Terms &amp; Conditions and Privacy Policy of PENSQuiz.
                        </label>
                    </div>

                    <button
                        id="registerBtn"
                        type="submit"
                        class="flex h-12 w-full items-center justify-center rounded-full bg-[#1d5687] px-8 text-base font-bold text-white shadow-[0_18px_40px_rgba(74,140,197,0.28)] transition hover:-translate-y-0.5 hover:bg-[#17466f] hover:shadow-[0_22px_48px_rgba(74,140,197,0.34)] focus:outline-none focus:ring-4 focus:ring-[#1d5687]/25 active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50 sm:text-lg"
                        disabled
                    >
                        Register
                    </button>

                    <p class="text-center text-sm font-medium text-[#111111] sm:text-base">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-extrabold transition hover:text-[#1d5687] focus:outline-none">
                            Login now.
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
                    button.disabled = !this.checked;
                });
            }
        </script>
    </main>
</x-guest-layout>
