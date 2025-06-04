@extends('layouts.app')

@section('title', 'Detail Menu - ' . $menu->nama)

@section('header', 'Detail Menu')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-lg shadow-lg p-6">
    @if($menu->gambar)
        <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama }}" class="w-full h-64 object-cover rounded-lg mb-6">
    @endif

    <h2 class="text-3xl font-bold mb-2 text-gray-900">{{ $menu->nama }}</h2>
    <p class="text-green-600 font-semibold mb-2">{{ ucfirst($menu->kategori) }}</p>
    <p class="text-2xl font-extrabold text-gray-800 mb-4">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>

    <p class="text-gray-700 mb-6 whitespace-pre-line">{{ $menu->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

    <label class="text-gray-700 font-medium mb-2">Jumlah:</label>
    <div class="flex items-center space-x-2 mb-6">
        <button onclick="changeQty({{ $menu->id }}, -1)" class="px-4 py-2 text-lg font-bold border rounded hover:bg-gray-200">&lt;</button>
        <input
            type="number"
            id="jumlah-{{ $menu->id }}"
            value="{{ $jumlah ?? 0 }}"
            min="0"
            class="w-20 text-center border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500"
            onchange="updateCart({{ $menu->id }}, this.value)"
        >
        <button onclick="changeQty({{ $menu->id }}, 1)" class="px-4 py-2 text-lg font-bold border rounded hover:bg-gray-200">&gt;</button>
    </div>

    <button onclick="tambahPesanan()" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-green-700 transition">
        Tambah Pesanan
    </button>

    <a href="{{ route('menu') }}" class="block mt-6 text-center text-green-600 hover:underline font-semibold">Kembali ke Menu</a>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function changeQty(menuId, delta) {
        const input = document.getElementById('jumlah-' + menuId);
        let jumlah = parseInt(input.value) || 0;
        jumlah = Math.max(0, jumlah + delta);
        input.value = jumlah;
        updateCart(menuId, jumlah);
    }

    function updateCart(menuId, jumlah) {
        axios.post('/update-cart', {
            menu_id: menuId,
            jumlah: jumlah
        }).then(res => {
            if (res.data.success) {
                let cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
                if (jumlah <= 0) {
                    delete cart[menuId];
                } else {
                    cart[menuId] = jumlah;
                }
                sessionStorage.setItem('cart', JSON.stringify(cart));
                window.dispatchEvent(new Event('cartUpdated'));
            }
        }).catch(() => {
            alert('Gagal memperbarui keranjang.');
        });
    }

    function tambahPesanan() {
        const input = document.getElementById('jumlah-{{ $menu->id }}');
        const jumlah = parseInt(input.value) || 0;

        if(jumlah <= 0){
            alert('Jumlah harus lebih dari 0 untuk menambah pesanan.');
            return;
        }

        updateCart({{ $menu->id }}, jumlah);
        setTimeout(() => {
            window.location.href = '{{ route("menu") }}';
        }, 300);
    }

    window.addEventListener('storage', function(event) {
        if (event.key === 'cart') {
            syncInputsWithCart();
        }
    });

    window.addEventListener('cartUpdated', () => {
        syncInputsWithCart();
    });

    function syncInputsWithCart() {
        let cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
        const input = document.getElementById('jumlah-{{ $menu->id }}');
        if(input){
            input.value = cart['{{ $menu->id }}'] || 0;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        let serverCart = @json($cart ?? []);
        sessionStorage.setItem('cart', JSON.stringify(serverCart));
        syncInputsWithCart();
    });
</script>
@endpush
