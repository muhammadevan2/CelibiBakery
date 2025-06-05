@extends('layouts.kasir')

@section('content')
    {{-- Container utama untuk header dengan padding responsif --}}
    <div class=" mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📦 Daftar Pesanan</h1>
    </div>

    {{-- Container utama untuk daftar pesanan --}}
    <div class="mb-6">
        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6'
                    });
                });
            </script>
        @endif

        {{-- Jika tidak ada pesanan --}}
        @if ($pesanans->isEmpty())
            <div class="text-center text-gray-500 italic mt-20">
                Belum ada pesanan.
            </div>
        @else
            {{-- Grid responsif untuk kartu pesanan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pesanans as $pesanan)
                    <div class="bg-white rounded-lg shadow p-6 border flex flex-col relative">
                        {{-- Info Pelanggan --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">Nama Pelanggan</p>
                                <p class="text-lg font-medium">{{ $pesanan->nama_pelanggan }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">No HP</p>
                                <p class="text-lg font-medium">{{ $pesanan->no_hp }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">No Meja</p>
                                <p class="text-lg font-medium">{{ $pesanan->no_meja }}</p>
                            </div>
                        </div>

                        {{-- Status dan Total --}}
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4 flex-wrap">
                            <div>
                                <p class="text-sm text-gray-500 font-semibold">Total Harga</p>
                                <p class="text-xl font-bold text-gray-800">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex flex-wrap gap-6">
                                <div>
                                    <p class="text-sm text-gray-500 font-semibold">Status Pembayaran</p>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                        {{ $pesanan->status_pembayaran === 'confirm' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                        {{ ucfirst($pesanan->status_pembayaran) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 font-semibold">Status Pesanan</p>
                                    <form action="{{ route('kasir.orders.updateStatus', $pesanan->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status_pesanan" class="w-full sm:w-auto border border-gray-300 rounded px-3 py-1 text-sm">
                                            @php
                                                $statuses = ['pending', 'process', 'done', 'cancel'];
                                            @endphp
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status }}" {{ $pesanan->status_pesanan === $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm font-semibold">
                                            Update
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Detail Pesanan --}}
                        <div class="mb-4">
                            <p class="text-sm font-semibold text-gray-500 mb-2">Detail Pesanan</p>
                            @php
                                $detail = json_decode($pesanan->detail, true) ?? [];
                                $menus = count($detail) > 0 
                                    ? \App\Models\Menu::whereIn('id', array_keys($detail))->get()->keyBy('id') 
                                    : collect();
                            @endphp

                            @if (count($detail) > 0 && $menus->isNotEmpty())
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left border border-gray-200 rounded overflow-hidden shadow-sm">
                                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider">
                                            <tr>
                                                <th class="px-4 py-3">Menu</th>
                                                <th class="px-4 py-3">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($detail as $id => $qty)
                                                @php
                                                    $menu = $menus->get($id);
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-2">
                                                        {{ $menu ? $menu->nama : '❌ Tidak ditemukan (ID: ' . $id . ')' }}
                                                    </td>
                                                    <td class="px-4 py-2">{{ $qty }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="italic text-gray-500">Tidak ada detail pesanan.</p>
                            @endif
                        </div>

                        {{-- Bukti Pembayaran & Tombol Aksi --}}
                        @if ($pesanan->bukti_pembayaran)
                            <div class="mt-4">
                                <p class="text-sm font-semibold text-gray-500 mb-2">Detail Pembayaran</p>
                                <button onclick="document.getElementById('modal-{{ $pesanan->id }}').classList.remove('hidden')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm font-semibold w-full">
                                    🧾 Lihat Bukti Pembayaran
                                </button>

                                @if ($pesanan->status_pesanan === 'done')
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-2">
                                        <!-- Tombol download -->
                                        <a href="{{ route('kasir.orders.cetak', ['id' => $pesanan->id, 'mode' => 'download']) }}" 
                                            class="bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm font-semibold text-center">
                                            Download Struk
                                        </a>

                                        <!-- Tombol print langsung -->
                                        <a href="{{ route('kasir.orders.cetak', ['id' => $pesanan->id, 'mode' => 'print']) }}" 
                                            target="_blank"
                                            class="bg-green-500 hover:bg-green-600 text-white py-2 rounded text-sm font-semibold text-center">
                                            Print Struk
                                        </a>

                                        <!-- Tombol hapus -->
                                        <form action="{{ route('kasir.orders.destroy', $pesanan->id) }}" method="POST" onsubmit="confirmDelete(event)">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="bg-red-500 hover:bg-red-600 text-white py-2 rounded text-sm font-semibold text-center w-full">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <!-- Modal Bukti Pembayaran -->
                                <div id="modal-{{ $pesanan->id }}" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
                                    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
                                        <h2 class="text-xl font-semibold mb-4">🧾 Bukti Pembayaran</h2>
                                        <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                                            class="w-full rounded border border-gray-300 shadow-sm object-contain mb-4">

                                        @if ($pesanan->status_pembayaran !== 'confirm')
                                            <form action="{{ route('kasir.orders.confirmPayment', $pesanan->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold">
                                                    ✅ Konfirmasi Pembayaran
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-green-600 font-semibold text-sm">
                                                Pembayaran telah dikonfirmasi.
                                            </div>
                                        @endif

                                        <button onclick="document.getElementById('modal-{{ $pesanan->id }}').classList.add('hidden')"
                                                class="absolute top-2 right-3 text-gray-600 hover:text-red-600 text-lg">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Script konfirmasi hapus --}}
    <script>
        function confirmDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Yakin hapus pesanan?',
                text: "Data akan hilang permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
        }
    </script>
@endsection
