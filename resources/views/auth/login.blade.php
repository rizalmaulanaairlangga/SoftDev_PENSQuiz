<x-guest-layout>

    <!-- LEFT: FORM -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            <h1 class="text-2xl font-bold mb-2">PENS<span class="text-gray-400">Quiz</span></h1>
            <h2 class="text-4xl font-semibold mb-4">Welcome Back!</h2>
            <p class="text-gray-600 mb-6">
                Continue your studies with the PENS community.
            </p>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm mb-1">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full border rounded-xl px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full border rounded-xl px-4 py-3">
                </div>

                <button class="w-full bg-[#104876] text-white py-3 rounded-xl">
                    Login
                </button>

                <p class="text-sm text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-semibold">Sign up</a>
                </p>
            </form>

        </div>
    </div>

    <!-- RIGHT: ILLUSTRATION -->
    <div class="hidden md:flex w-1/2 bg-[#f5f5f5] items-center justify-center">
        <div class="text-center">
            <h2 class="text-4xl font-semibold mb-4">Learn smarter,<br>starting here.</h2>
            <p class="text-gray-600 max-w-sm">
                PENSQuiz helps you sharpen your understanding through interactive quizzes.
            </p>
        </div>
    </div>

</div>
</x-guest-layout>