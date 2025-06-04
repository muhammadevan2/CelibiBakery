<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class KasirOrderController extends Controller
{
    // Halaman buat pesanan baru oleh kasir
    public function index()
    {
        $menus = Menu::where('status', 'tersedia')->get();
        $pesanans = Pesanan::latest()->get();
        return view('kasir.order', compact('menus', 'pesanans'));
    }

    // Proses simpan pesanan dari kasir
    public function store(Request $request)
    {
        $request->validate([
            'pesanan' => 'required|array',
            'pesanan.*' => 'integer|min:0',
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'no_meja' => 'required|integer|min:1',
        ]);

        $pesananFilter = array_filter($request->pesanan, fn($qty) => $qty > 0);
        if (count($pesananFilter) === 0) {
            return back()->withErrors(['pesanan' => 'Minimal harus memesan 1 menu dengan jumlah lebih dari 0'])->withInput();
        }

        $menus = Menu::whereIn('id', array_keys($pesananFilter))->get();

        $totalHarga = 0;
        foreach ($menus as $menu) {
            $jumlah = $pesananFilter[$menu->id];
            $totalHarga += $menu->harga * $jumlah;
        }

        $pesanan = Pesanan::create([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp' => $request->no_hp,
            'no_meja' => $request->no_meja,
            'status_pembayaran' => 'confirm',
            'total_harga' => $totalHarga,
            'bukti_pembayaran' => null,
            'status_pesanan' => 'pending',
            'detail' => json_encode($pesananFilter),
        ]);

        return redirect()->route('kasir.pesanan.index')->with('success', 'Pesanan berhasil dibuat');
    }

    // Menampilkan daftar pesanan
    public function orders()
    {
        $pesanans = Pesanan::orderBy('created_at', 'desc')->get();
        $menus = Menu::all()->keyBy('id');
        return view('kasir.order', compact('pesanans', 'menus'));
    }

    // Update status pesanan
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status_pesanan' => 'required|string|in:pending,process,done,cancel',
        ]);

        // Jika status diubah ke cancel, langsung hapus
        if ($request->status_pesanan === 'cancel') {
            if ($pesanan->bukti_pembayaran) {
                \Storage::delete($pesanan->bukti_pembayaran);
            }

            $pesanan->delete();

            return redirect()->route('kasir.orders.index')->with('success', 'Pesanan dibatalkan dan telah dihapus.');
        }

        // Jika bukan cancel, hanya update status biasa
        $pesanan->status_pesanan = $request->status_pesanan;
        $pesanan->save();

        return redirect()->route('kasir.orders.index')->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Konfirmasi pembayaran dari kasir
    public function confirmPayment($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status_pembayaran = 'confirm';
        $pesanan->save();

        return redirect()->route('kasir.orders.index')->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    // Cetak / Download struk pesanan
    public function cetak($id, Request $request)
    {
        $pesanan = Pesanan::findOrFail($id);
        $detail = json_decode($pesanan->detail, true) ?? [];
        $menus = Menu::whereIn('id', array_keys($detail))->get()->keyBy('id');

        $pdf = Pdf::loadView('kasir.cetak', compact('pesanan', 'detail', 'menus'))
                  ->setPaper('A5');

        $mode = $request->query('mode', 'download');

        if ($mode === 'print') {
            return $pdf->stream('struk-pesanan-' . $pesanan->id . '.pdf');
        }

        return $pdf->download('struk-pesanan-' . $pesanan->id . '.pdf');
    }

    // Hapus Pesanan
    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        if ($pesanan->status_pesanan !== 'done') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status "done" yang bisa dihapus.');
        }

        // Hapus bukti pembayaran jika ada
        if ($pesanan->bukti_pembayaran) {
            Storage::delete($pesanan->bukti_pembayaran);
        }

        $pesanan->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }  
}
