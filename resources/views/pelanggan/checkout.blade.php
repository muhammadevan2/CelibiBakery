@extends('layouts.app')

@section('title', 'Checkout Pesanan')
@section('header', 'Konfirmasi Pesanan')

@section('content')
<form action="/pesan" method="POST" enctype="multipart/form-data" class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    @csrf

    <table class="w-full mb-6">
        <thead>
            <tr class="border-b">
                <th class="text-left py-2">Menu</th>
                <th class="text-left py-2">Jumlah</th>
                <th class="text-left py-2">Harga Satuan</th>
                <th class="text-left py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalHarga = 0; @endphp
            @foreach($menus as $menu)
                @php
                    $jumlah = $cart[$menu->id];
                    $subtotal = $jumlah * $menu->harga;
                    $totalHarga += $subtotal;
                @endphp
                <tr class="border-b">
                    <td>{{ $menu->nama }}</td>
                    <td>{{ $jumlah }}</td>
                    <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="font-bold">
                <td colspan="3" class="text-right">Total Harga</td>
                <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="mb-4 text-center">
        <p class="mb-2 font-semibold">Scan QRIS berikut untuk melakukan pembayaran:</p>
        <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Celibi Bakery" class="mx-auto w-48 h-auto" />
    </div>

    <div class="mb-4">
        <label for="bukti_pembayaran" class="block mb-2 font-semibold">Upload Bukti Pembayaran (QRIS):</label>
        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" required class="w-full border rounded p-2">
        @error('bukti_pembayaran')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="nama_pelanggan" class="block mb-2 font-semibold">Nama:</label>
        <input type="text" name="nama_pelanggan" id="nama_pelanggan" required class="w-full border rounded p-2"
            value="{{ old('nama_pelanggan') }}">
        @error('nama_pelanggan')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="no_hp" class="block mb-2 font-semibold">Nomor Telepon:</label>
        <input type="number" name="no_hp" id="no_hp" required class="w-full border rounded p-2" value="{{ old('no_hp') }}">
        @error('no_hp')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="no_meja" class="block mb-2 font-semibold">Nomor Meja:</label>
        <input type="number" name="no_meja" id="no_meja" required class="w-full border rounded p-2" value="{{ old('no_meja') }}">
        @error('no_meja')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-between">
        <a href="{{ route('pelanggan.menu') }}" class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600">Kembali</a>
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Pesan Sekarang</button>
    </div>
</form>
@endsection
