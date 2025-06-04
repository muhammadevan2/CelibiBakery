@extends('layouts.app')

@section('title', 'Menu Celibi Bakery')

@section('header', 'Menu Pilihan Kami')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
    @foreach($menus as $menu)
    <div class="menu-card bg-white rounded-lg shadow-lg flex flex-col hover:shadow-2xl transition-shadow duration-300"
        data-menu-id="{{ $menu->id }}" data-harga="{{ $menu->harga }}">

        <a href="{{ route('pelanggan.menu.detail', $menu->id) }}">
            @if($menu->gambar)
                <img src="{{ asset('storage/' . $menu->gambar) }}" alt="{{ $menu->nama }}"
                    class="h-48 w-full object-cover rounded-t-lg">
            @else
                <div class="h-48 bg-gray-200 rounded-t-lg flex items-center justify-center text-gray-400 font-semibold text-lg">
                    No Image
                </div>
            @endif
            <div class="p-5">
                <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $menu->nama }}</h3>
                <p class="text-sm text-green-600 font-medium mb-3">{{ ucfirst($menu->kategori) }}</p>
                <p class="text-lg font-bold text-gray-800 mb-2">Rp {{ number_format($menu->harga,0,',','.') }}</p>
            </div>
        </a>

        <div class="px-5 pb-5 mt-auto">
            <label class="text-gray-700 font-medium mb-2 block">Jumlah:</label>
            <div class="flex items-center space-x-2">
                <button class="qty-minus px-3 py-1 text-lg font-bold border rounded hover:bg-gray-200">&lt;</button>
                <input type="number"
                    class="jumlah-input w-16 text-center border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-green-500"
                    min="0" value="{{ session('cart')[$menu->id] ?? 0 }}">
                <button class="qty-plus px-3 py-1 text-lg font-bold border rounded hover:bg-gray-200">&gt;</button>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Checkout Bar -->
<div id="checkoutBar"
    class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-5 flex justify-between items-center max-w-screen-xl mx-auto px-6 transform transition-transform duration-500 z-50">
    <div>
        <span class="text-gray-700 font-semibold">Total Harga:</span>
        <span id="totalHarga" class="font-extrabold text-2xl text-green-700 ml-2">Rp 0</span>
    </div>
    <a href="{{ route('pelanggan.checkout') }}"
        class="bg-green-600 text-white font-bold px-8 py-3 rounded-lg shadow-lg hover:bg-green-700 transition">
        Checkout
    </a>
</div>
@endsection

@push('styles')
<style>
    .checkout-hidden {
        transform: translateY(100%) !important;
    }

    .checkout-visible {
        transform: translateY(0) !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const serverCart = @json(session('cart', []));
        sessionStorage.setItem('cart', JSON.stringify(serverCart));
        syncInputsWithCart();

        const checkoutBar = document.getElementById('checkoutBar');
        checkoutBar.classList.add('checkout-hidden');

        document.querySelectorAll('.menu-card').forEach(card => {
            const menuId = card.dataset.menuId;
            const harga = parseInt(card.dataset.harga);
            const input = card.querySelector('.jumlah-input');
            const plusBtn = card.querySelector('.qty-plus');
            const minusBtn = card.querySelector('.qty-minus');

            plusBtn.addEventListener('click', () => {
                input.value = parseInt(input.value || 0) + 1;
                updateCart(menuId, input.value);
            });

            minusBtn.addEventListener('click', () => {
                input.value = Math.max(0, parseInt(input.value || 0) - 1);
                updateCart(menuId, input.value);
            });

            input.addEventListener('change', () => {
                input.value = Math.max(0, parseInt(input.value || 0));
                updateCart(menuId, input.value);
            });
        });

        window.addEventListener('storage', event => {
            if (event.key === 'cart') syncInputsWithCart();
        });

        window.addEventListener('cartUpdated', () => {
            syncInputsWithCart();
        });
    });

    function updateCart(menuId, jumlah) {
        axios.post('/update-cart', { menu_id: menuId, jumlah: jumlah })
            .then(res => {
                if (res.data.success) {
                    let cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
                    jumlah = parseInt(jumlah);
                    if (jumlah <= 0) {
                        delete cart[menuId];
                    } else {
                        cart[menuId] = jumlah;
                    }
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    updateTotalHarga();
                    window.dispatchEvent(new Event('cartUpdated'));
                }
            }).catch(() => alert('Gagal memperbarui keranjang.'));
    }

    function syncInputsWithCart() {
        let cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
        document.querySelectorAll('.menu-card').forEach(card => {
            const menuId = card.dataset.menuId;
            const input = card.querySelector('.jumlah-input');
            input.value = cart[menuId] || 0;
        });
        updateTotalHarga();
    }

    function updateTotalHarga() {
        let total = 0;
        let jumlahItem = 0;
        document.querySelectorAll('.menu-card').forEach(card => {
            const harga = parseInt(card.dataset.harga);
            const input = card.querySelector('.jumlah-input');
            const jumlah = parseInt(input.value) || 0;
            total += jumlah * harga;
            jumlahItem += jumlah;
        });

        document.getElementById('totalHarga').innerText = "Rp " + total.toLocaleString('id-ID');

        const checkoutBar = document.getElementById('checkoutBar');
        if (jumlahItem > 0) {
            checkoutBar.classList.remove('checkout-hidden');
            checkoutBar.classList.add('checkout-visible');
        } else {
            checkoutBar.classList.remove('checkout-visible');
            checkoutBar.classList.add('checkout-hidden');
        }
    }
</script>
@endpush