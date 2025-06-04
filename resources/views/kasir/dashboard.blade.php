@extends('layouts.kasir')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Kasir</h1>
        <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}. Silakan pilih aksi di bawah ini.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <a href="{{ route('kasir.menu.index') }}" class="block bg-white border rounded-lg shadow hover:shadow-md p-6 transition duration-300">
            <div class="flex items-center space-x-4">
                <div class="text-3xl">🍞</div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Kelola Menu</h2>
                    <p class="text-sm text-gray-500">Tambah, edit, atau hapus menu makanan & minuman.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('kasir.orders.index') }}" class="block bg-white border rounded-lg shadow hover:shadow-md p-6 transition duration-300">
            <div class="flex items-center space-x-4">
                <div class="text-3xl">📦</div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Kelola Pesanan</h2>
                    <p class="text-sm text-gray-500">Lihat dan proses pesanan pelanggan yang masuk.</p>
                </div>
            </div>
        </a>
    </div>
@endsection
