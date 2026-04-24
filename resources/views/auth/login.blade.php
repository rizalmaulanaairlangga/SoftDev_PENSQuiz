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

    <main class="min-h-screen bg-[#f8f8f6] px-4 py-4 font-sans text-[#101010] sm:px-6 lg:px-8">
        <div class="mx-auto grid min-h-[calc(100vh-2rem)] max-w-[1600px] gap-6 lg:h-[calc(100vh-2rem)] lg:grid-cols-2 lg:gap-8">
            <section class="auth-slide-from-right relative overflow-hidden rounded-[28px] bg-[linear-gradient(180deg,#fafcff_0%,#edf5ff_63%,#d4e5fb_100%)] px-6 py-7 sm:px-10 sm:py-8 lg:px-14 lg:py-8 xl:px-20">
                <img
                    src="{{ asset('assets/images/img_background.png') }}"
                    alt=""
                    class="pointer-events-none absolute inset-0 h-full w-full object-cover opacity-45"
                    aria-hidden="true"
                >

                <div class="relative z-10 flex min-h-full flex-col">
                    <a href="{{ url('/') }}" class="inline-flex w-fit items-center gap-3 text-base font-semibold text-[#101010] transition hover:text-[#1d5687] focus:outline-none">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m15 18-6-6 6-6" />
                        </svg>
                        Back
                    </a>

                    <div class="mt-8 sm:mt-10 lg:mt-11">
                        <x-brand-logo href="{{ url('/') }}" ariaLabel="PENSQuiz home" class="w-[154px] sm:w-[176px]" />

                        <h1 class="mt-8 max-w-xl text-5xl font-extrabold leading-none tracking-normal text-black sm:text-6xl lg:text-[68px]">
                            Welcome back!
                        </h1>

                        <p class="mt-5 max-w-xl text-base font-medium leading-7 text-[#101010] sm:text-lg">
                            Continue your studies with the PENS community.<br class="hidden sm:block">
                            Log in and start today's quiz.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="mt-8 w-full max-w-xl space-y-4 sm:mt-10 lg:mt-11">
                        @csrf

                        <div>
                            <label for="email" class="block text-base font-medium text-[#171717]">
                                PENS Student Email Address
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
                                class="mt-2 h-[52px] w-full rounded-[18px] border border-[#6f6b69] bg-white px-5 py-4 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20 sm:h-14 sm:px-6 sm:text-lg lg:h-[58px]"
                            >
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password" class="block text-base font-medium text-[#171717]">
                                Password
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="123457890"
                                required
                                autocomplete="current-password"
                                class="mt-2 h-[52px] w-full rounded-[18px] border border-[#6f6b69] bg-white px-5 py-4 text-base font-medium text-black placeholder:text-[#a9a0a0] shadow-sm transition focus:border-transparent focus:outline-none focus:ring-4 focus:ring-[#1d5687]/20 sm:h-14 sm:px-6 sm:text-lg lg:h-[58px]"
                            >
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <button
                            type="submit"
                            class="flex h-14 w-full items-center justify-center rounded-full bg-[#1d5687] px-8 text-lg font-bold text-white shadow-[0_18px_40px_rgba(74,140,197,0.28)] transition hover:-translate-y-0.5 hover:bg-[#17466f] hover:shadow-[0_22px_48px_rgba(74,140,197,0.34)] focus:outline-none focus:ring-4 focus:ring-[#1d5687]/25 active:scale-[0.99] sm:text-xl lg:h-[58px]"
                        >
                            Login
                        </button>

                        <p class="text-center text-base font-medium text-[#111111]">
                            Don't have an account yet?
                            <a href="{{ route('register') }}" class="font-extrabold transition hover:text-[#1d5687] focus:outline-none">
                                Sign up now.
                            </a>
                        </p>
                    </form>
                </div>
            </section>

            <section class="auth-slide-from-left relative min-h-[520px] overflow-hidden rounded-[28px] bg-[linear-gradient(180deg,#8fd4f0_0%,#7dc6e9_18%,#5b98d0_52%,#1f4b88_100%)] px-6 py-10 sm:px-10 lg:min-h-0 lg:px-12 lg:py-8">
                <img
                    src="{{ asset('assets/images/img_background.png') }}"
                    alt=""
                    class="pointer-events-none absolute inset-0 h-full w-full object-cover object-center opacity-35 mix-blend-soft-light"
                    aria-hidden="true"
                >

                <div class="relative z-10 flex min-h-full flex-col justify-between gap-8">
                    <div class="flex justify-center pt-4 lg:pt-10">
                        <img
                            src="{{ asset('assets/images/img_exam_1.png') }}"
                            alt="Student taking an online quiz"
                            class="w-full max-w-[360px] object-contain drop-shadow-[0_28px_40px_rgba(12,48,86,0.22)] sm:max-w-[420px] lg:max-w-[430px] xl:max-w-[500px]"
                        >
                    </div>

                    <div class="pb-2 sm:pb-6 lg:pb-8 xl:pb-10">
                        <h2 class="max-w-3xl text-3xl font-extrabold leading-tight tracking-normal text-white sm:text-4xl lg:text-[32px] xl:text-[36px]">
                            Learn smarter, starting here.
                        </h2>
                        <p class="mt-3 max-w-2xl text-base font-medium leading-7 text-white sm:text-lg lg:text-base xl:text-lg">
                            Hone your knowledge, measure your abilities.<br class="hidden sm:block">
                            From PENS students, for PENS students.
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </main>
</x-guest-layout>
