@extends('layouts.kasir')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Edit Menu</h2>

    <form action="{{ route('kasir.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="nama" class="block mb-2 font-medium text-gray-700">Nama</label>
            <input id="nama" type="text" name="nama" value="{{ old('nama', $menu->nama) }}" 
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div>
            <label for="harga" class="block mb-2 font-medium text-gray-700">Harga (Rp)</label>
            <input id="harga" type="number" name="harga" value="{{ old('harga', $menu->harga) }}" 
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div>
            <label for="kategori" class="block mb-2 font-medium text-gray-700">Kategori</label>
            <select id="kategori" name="kategori" 
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                <option value="makanan" {{ old('kategori', $menu->kategori) == 'makanan' ? 'selected' : '' }}>Makanan</option>
                <option value="minuman" {{ old('kategori', $menu->kategori) == 'minuman' ? 'selected' : '' }}>Minuman</option>
            </select>
        </div>

        <div>
            <label for="status" class="block mb-2 font-medium text-gray-700">Status</label>
            <select id="status" name="status" 
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                <option value="tersedia" {{ old('status', $menu->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="habis" {{ old('status', $menu->status) == 'habis' ? 'selected' : '' }}>Habis</option>
            </select>
        </div>

        <div>
            <label for="deskripsi" class="block mb-2 font-medium text-gray-700">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" 
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>

        <div>
            <label class="block mb-2 font-medium text-gray-700">Gambar</label>
            @if($menu->gambar)
                <img src="{{ asset('storage/' . $menu->gambar) }}" alt="Gambar Menu" 
                    class="mb-4 h-32 w-32 object-cover rounded-md border border-gray-300">
            @endif
            <input type="file" name="gambar" accept="image/*" 
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <button type="submit" 
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-md transition-colors duration-200">
            Update Menu
        </button>
    </form>
</div>
@endsection
