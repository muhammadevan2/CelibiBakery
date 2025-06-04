<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use Illuminate\Support\Facades\Storage;

class PesananController extends Controller
{
    // Tampilkan halaman menu pelanggan
    public function menu(Request $request)
    {
        $menus = Menu::where('status', 'tersedia')->get();
        $cart = $request->session()->get('cart', []);
        return view('pelanggan.menu', compact('menus', 'cart'));
    }

    // Halaman detail menu (per menu)
    public function detail($id, Request $request)
    {
        $menu = Menu::where('id', $id)->where('status', 'tersedia')->firstOrFail();
        $cart = $request->session()->get('cart', []);
        $jumlah = $cart[$menu->id] ?? 0;

        return view('pelanggan.menu_detail', compact('menu', 'cart', 'jumlah'));
    }

    // Halaman checkout konfirmasi pesanan
    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong!');
        }

        $menus = Menu::whereIn('id', array_keys($cart))->get();

        return view('pelanggan.checkout', compact('menus', 'cart'));
    }

    // Proses simpan pesanan dari checkout
    public function pesan(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string',
            'no_hp' => 'required|numeric',
            'no_meja' => 'required|numeric',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/')->with('error', 'Keranjang kosong!');
        }

        $menus = Menu::whereIn('id', array_keys($cart))->get();

        // Hitung total harga
        $totalHarga = 0;
        foreach ($menus as $menu) {
            $totalHarga += $menu->harga * $cart[$menu->id];
        }

        // Upload bukti pembayaran
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Simpan pesanan dengan kolom detail (json)
        $pesanan = Pesanan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'no_meja' => $request->no_meja,
            'bukti_pembayaran' => $path,
            'status' => 'pending',
            'status_pembayaran' => 'pending',
            'total_harga' => $totalHarga,
            'detail' => json_encode($cart),  // Ini yang bikin json detail tersimpan
        ]);

        // Simpan detail pesanan ke tabel pesanan_details (optional, tapi direkomendasikan)
        foreach ($menus as $menu) {
            PesananDetail::create([
                'pesanan_id' => $pesanan->id,
                'menu_id' => $menu->id,
                'jumlah' => $cart[$menu->id],
                'harga_satuan' => $menu->harga,
            ]);
        }

        // Bersihkan session cart
        $request->session()->forget('cart');

        return redirect('/')->with('success', 'Pesanan berhasil dibuat! Terima kasih.');
    }

    // Fungsi untuk update cart dari AJAX atau form
    public function updateCart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $menu_id = $request->menu_id;
        $jumlah = $request->jumlah;

        if ($jumlah <= 0) {
            unset($cart[$menu_id]);
        } else {
            $cart[$menu_id] = $jumlah;
        }

        $request->session()->put('cart', $cart);
        return response()->json(['success' => true]);
    }
}
