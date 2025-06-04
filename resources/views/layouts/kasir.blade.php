<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - Celibi Bakery</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="flex bg-gray-100 min-h-screen">

    <!-- Sidebar -->
    <aside id="sidebar" 
           class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform -translate-x-full md:translate-x-0 md:static md:translate-x-0 transition-transform duration-300 ease-in-out z-50 flex flex-col">
        <div class="p-6 border-b flex items-center justify-between">
            <h2 class="text-2xl font-bold text-green-700">Celibi Kasir</h2>
            <!-- Tombol close di mobile -->
            <button id="sidebarClose" class="md:hidden text-green-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="p-4 space-y-2 flex-grow">
            <p class="text-xl text-black mt-1 mb-4 font-bold text-center">Halo, {{ Auth::user()->name }}</p>
            <a href="{{ route('kasir.dashboard') }}" 
               class="block px-4 py-2 rounded hover:bg-green-100 {{ request()->routeIs('kasir.dashboard') ? 'bg-green-100 font-semibold' : '' }}">
               Dashboard
            </a>
            <a href="{{ route('kasir.menu.index') }}" 
               class="block px-4 py-2 rounded hover:bg-green-100 {{ request()->routeIs('kasir.menu.*') ? 'bg-green-100 font-semibold' : '' }}">
               Manajemen Menu
            </a>
            <a href="{{ route('kasir.orders.index') }}" 
            class="block px-4 py-2 rounded hover:bg-green-100 {{ request()->routeIs('kasir.orders.*') ? 'bg-green-100 font-semibold' : '' }}">
                Pesanan Masuk
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded hover:bg-red-100 text-red-600">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col md:ml">

        <!-- Topbar -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <!-- Tombol hamburger buka sidebar di mobile -->
            <button id="sidebarToggle" class="md:hidden text-green-700 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="text-lg font-bold text-green-600">Panel Kasir</div>
            <div class="text-sm text-gray-600">Login sebagai: <span class="font-semibold">{{ Auth::user()->name }}</span></div>
        </header>

        <!-- Page Content -->
        <main class="p-6 flex-grow overflow-y-auto">
            @yield('content')
        </main>

            <!-- Push scripts section -->
            @stack('scripts')

        <!-- Footer -->
        <footer class="bg-white shadow p-4 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Celibi Bakery - Halaman Kasir
        </footer>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');

        openBtn.addEventListener('click', () => {
            sidebar.classList.remove('-translate-x-full');
        });

        closeBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    </script>
</body>

</html>
