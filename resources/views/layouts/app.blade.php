<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Celibi Bakery')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    {{-- Tambahan CSS --}}
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">

    {{-- Header --}}
    <header class="bg-green-600 shadow-md">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-white tracking-wide">🍞 Celibi Bakery</h1>
        </div>
    </header>

    {{-- Konten utama --}}
    <main class="flex-grow container mx-auto px-6 py-8">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-center">@yield('header')</h2>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t text-center py-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} Celibi Bakery. All rights reserved.
    </footer>

    {{-- Tambahan JS --}}
    @stack('scripts')
</body>
</html>
