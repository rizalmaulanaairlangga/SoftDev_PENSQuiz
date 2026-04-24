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

<body class="min-h-screen bg-[#eaf2f8] font-sans antialiased text-slate-900">
    <div class="flex min-h-screen flex-col">
        <x-auth-top-nav />

        <main class="flex-1 px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
            <div class="mx-auto w-full max-w-7xl">
                {{ $slot }}
            </div>
        </main>

        <x-site-footer />
    </div>
</body>
</html>
