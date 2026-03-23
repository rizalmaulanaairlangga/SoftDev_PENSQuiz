<x-guest-layout>
<div class="min-h-screen flex flex-col md:flex-row">

    <!-- LEFT: ILLUSTRATION -->
    <div class="hidden md:flex w-1/2 bg-[#f5f5f5] items-center justify-center">
        <div class="text-center">
            <h2 class="text-4xl font-semibold mb-4">Join and start your journey.</h2>
            <p class="text-gray-600 max-w-sm">
                Thousands of students are already learning smarter with PENSQuiz.
            </p>
        </div>
    </div>

    <!-- RIGHT: FORM -->
    <div class="w-full md:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">

            <h1 class="text-2xl font-bold mb-2">PENS<span class="text-gray-400">Quiz</span></h1>
            <h2 class="text-4xl font-semibold mb-4">Create your account</h2>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <input name="full_name" placeholder="Full Name" required
                    class="w-full border rounded-xl px-4 py-3">
                
                <input name="nrp" placeholder="NRP" required
                    class="w-full border rounded-xl px-4 py-3">

                <input type="email" name="email" placeholder="Email" required
                    class="w-full border rounded-xl px-4 py-3">

                <input type="password" name="password" placeholder="Password" required
                    class="w-full border rounded-xl px-4 py-3">

                <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                    class="w-full border rounded-xl px-4 py-3">

                <button class="w-full bg-[#104876] text-white py-3 rounded-xl">
                    Register Now
                </button>

            </form>

        </div>
    </div>

</div>
</x-guest-layout>