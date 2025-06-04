<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // Tampilkan halaman kelola menu
    public function index()
    {
        $menus = Menu::all();
        return view('kasir.menu.index', compact('menus'));
    }

    // Simpan menu baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|in:makanan,minuman',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $data['status'] = 'tersedia';

        Menu::create($data);

        return redirect()->route('kasir.menu.index')->with('add_success', 'Menu berhasil ditambahkan.');
    }

    // Update status tersedia/habis
    public function updateStatus(Menu $menu)
    {
        $menu->status = $menu->status === 'tersedia' ? 'habis' : 'tersedia';
        $menu->save();

        return redirect()->route('kasir.menu.index')->with('status_updated', 'Status menu berhasil diubah.');
    }

    // Tidak dipakai karena edit pakai modal
    public function edit(Menu $menu)
    {
        return view('kasir.menu.edit', compact('menu'));
    }

    // Update menu
    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|in:makanan,minuman',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|max:2048',
            'status' => 'required|in:tersedia,habis',
        ]);

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('menus', 'public');
        }

        $menu->update($data);

        return redirect()->route('kasir.menu.index')->with('edit_success', 'Menu berhasil diperbarui.');
    }

    // Hapus menu
    public function destroy(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()->route('kasir.menu.index')->with('delete_success', 'Menu berhasil dihapus.');
    }
}
