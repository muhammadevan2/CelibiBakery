@extends('layouts.kasir')

@section('title', 'Kelola Menu')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🍞 Kelola Menu</h1>
    </div>
<!-- Modal Tambah Menu -->
<div id="menuModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-500">&times;</button>
        <h3 class="text-lg font-semibold mb-4">➕ Tambah Menu Baru</h3>
        <form action="{{ route('kasir.menu.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nama" placeholder="Nama Menu" class="p-2 border rounded" required>
                <input type="number" name="harga" placeholder="Harga (Rp)" class="p-2 border rounded" required>
                <select name="kategori" class="p-2 border rounded" required>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                </select>
                <input type="file" name="gambar" accept="image/*" class="p-2 border rounded">
            </div>
            <textarea name="deskripsi" placeholder="Deskripsi (opsional)" class="w-full mt-2 p-2 border rounded"></textarea>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Simpan Menu
            </button>
        </form>
    </div>
</div>

<!-- Modal Edit Menu -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6 relative">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-500">&times;</button>
        <h3 class="text-lg font-semibold mb-4">✏️ Edit Menu</h3>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nama" id="editNama" class="p-2 border rounded" required>
                <input type="number" name="harga" id="editHarga" class="p-2 border rounded" required>
                <select name="kategori" id="editKategori" class="p-2 border rounded" required>
                    <option value="makanan">Makanan</option>
                    <option value="minuman">Minuman</option>
                </select>
                <select name="status" id="editStatus" class="p-2 border rounded" required>
                    <option value="tersedia">Tersedia</option>
                    <option value="habis">Habis</option>
                </select>
                <input type="file" name="gambar" accept="image/*" class="p-2 border rounded">
            </div>
            <textarea name="deskripsi" id="editDeskripsi" class="w-full mt-2 p-2 border rounded" placeholder="Deskripsi"></textarea>
            <div class="mt-2">
                <img id="previewGambar" src="" class="w-24 h-24 object-cover rounded border" alt="Gambar Menu">
            </div>
            <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<!-- Pencarian dan Tambah -->
<div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
    <input type="text" id="searchInput" placeholder="Cari menu..." class="p-2 border rounded w-full md:w-1/2">
    <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        + Tambah Menu
    </button>
</div>

<!-- Tabel Menu -->
<div class="bg-white p-6 rounded shadow overflow-x-auto">
    <h3 class="text-lg font-semibold mb-4">📋 Daftar Menu</h3>
    <table class="w-full table-auto text-sm" id="menuTable">
        <thead>
            <tr class="text-left border-b font-semibold text-gray-700">
                <th class="p-2">Gambar</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Kategori</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-2">
                    @if($menu->gambar)
                        <img src="{{ asset('storage/' . $menu->gambar) }}" class="h-14 w-14 object-cover rounded">
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="p-2 nama-menu">{{ $menu->nama }}</td>
                <td class="p-2 capitalize">{{ $menu->kategori }}</td>
                <td class="p-2">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                <td class="p-2">
                    <span class="text-white text-xs px-2 py-1 rounded {{ $menu->status === 'tersedia' ? 'bg-green-500' : 'bg-gray-500' }}">
                        {{ $menu->status }}
                    </span>
                </td>
                <td class="p-2">
                    <div class="flex flex-wrap gap-1">
                        <form action="{{ route('kasir.menu.updateStatus', $menu->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs px-3 py-1 rounded {{ $menu->status === 'tersedia' ? 'bg-yellow-400 hover:bg-yellow-500 text-black' : 'bg-green-500 hover:bg-green-600 text-white' }}">
                                {{ $menu->status === 'tersedia' ? 'Tandai Habis' : 'Tandai Tersedia' }}
                            </button>
                        </form>
                        <button
                            type="button"
                            class="text-xs px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded edit-button"
                            data-id="{{ $menu->id }}"
                            data-nama="{{ $menu->nama }}"
                            data-harga="{{ $menu->harga }}"
                            data-kategori="{{ $menu->kategori }}"
                            data-deskripsi="{{ $menu->deskripsi }}"
                            data-status="{{ $menu->status }}"
                            data-url="{{ route('kasir.menu.update', $menu->id) }}"
                            data-gambar="{{ $menu->gambar ? asset('storage/' . $menu->gambar) : '' }}"
                        >
                            Edit
                        </button>
                        <form action="{{ route('kasir.menu.destroy', $menu->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('add_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('add_success') }}',
        timer: 2000,
        showConfirmButton: false
    });
});
</script>
@endif

@if(session('edit_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('edit_success') }}',
        timer: 2000,
        showConfirmButton: false
    });
});
</script>
@endif

@if(session('delete_success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('delete_success') }}',
        timer: 2000,
        showConfirmButton: false
    });
});
</script>
@endif

@if(session('status_updated'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '{{ session('status_updated') }}',
        timer: 2000,
        showConfirmButton: false
    });
});
</script>
@endif

<script>
function openModal() {
    document.getElementById('menuModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('menuModal').classList.add('hidden');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
document.querySelector('#editForm input[name="gambar"]').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('previewGambar').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});
document.getElementById('searchInput').addEventListener('input', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('#menuTable tbody tr').forEach(row => {
        const nama = row.querySelector('.nama-menu').textContent.toLowerCase();
        row.style.display = nama.includes(keyword) ? '' : 'none';
    });
});
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin menghapus menu ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
document.querySelectorAll('.edit-button').forEach(button => {
    button.addEventListener('click', () => {
        const form = document.getElementById('editForm');
        form.action = button.dataset.url;
        document.getElementById('editNama').value = button.dataset.nama;
        document.getElementById('editHarga').value = button.dataset.harga;
        document.getElementById('editKategori').value = button.dataset.kategori;
        document.getElementById('editStatus').value = button.dataset.status;
        document.getElementById('editDeskripsi').value = button.dataset.deskripsi;
        document.getElementById('previewGambar').src = button.dataset.gambar || '';
        document.getElementById('editModal').classList.remove('hidden');
    });
});
</script>
@endpush