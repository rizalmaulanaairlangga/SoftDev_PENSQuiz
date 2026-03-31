<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="">
        {{-- pb-20 penting untuk kasih ruang bottom nav --}}

        <div class="flex gap-4 p-4">

            <!-- Sidebar -->
            <x-sidebar />

            <!-- Content -->
            <div class="flex-1 md:ml-24 min-h-screen bg-[#f5f5f5]">

                <div class="w-full px-4 sm:px-6 lg:px-6 pt-4">

                    <!-- HEADER -->
                    <x-app-header />

                    <!-- CONTENT -->
                    <div class="mx-auto w-full max-w-[1600px] mt-4">
                        {{ $slot }}
                    </div>

                </div>

            </div>

        </div>

        <!-- Bottom Navigation -->
        <div class="mb-20 md:p-0">
            <x-bottom-nav />
        </div>

    </div>

</body>
</html>
