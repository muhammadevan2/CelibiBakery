<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            [
                'nama' => 'Roti Tawar Coklat',
                'harga' => 12000,
                'kategori' => 'makanan',
                'deskripsi' => 'Roti tawar dengan filling coklat lembut.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Roti+Coklat',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Donat Original',
                'harga' => 8000,
                'kategori' => 'makanan',
                'deskripsi' => 'Donat empuk dengan taburan gula halus.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Donat+Original',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Kue Lapis',
                'harga' => 15000,
                'kategori' => 'makanan',
                'deskripsi' => 'Kue lapis legit tradisional.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Kue+Lapis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Kopi Hitam',
                'harga' => 10000,
                'kategori' => 'minuman',
                'deskripsi' => 'Kopi hitam pahit tanpa gula.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Kopi+Hitam',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Jus Jeruk',
                'harga' => 12000,
                'kategori' => 'minuman',
                'deskripsi' => 'Jus jeruk segar tanpa tambahan gula.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Jus+Jeruk',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Roti Keju',
                'harga' => 13000,
                'kategori' => 'makanan',
                'deskripsi' => 'Roti dengan taburan keju parut.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Roti+Keju',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Es Teh Manis',
                'harga' => 7000,
                'kategori' => 'minuman',
                'deskripsi' => 'Teh dingin dengan gula cair.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Es+Teh+Manis',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Brownies Coklat',
                'harga' => 18000,
                'kategori' => 'makanan',
                'deskripsi' => 'Brownies coklat moist dan legit.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Brownies+Coklat',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Milkshake Vanilla',
                'harga' => 15000,
                'kategori' => 'minuman',
                'deskripsi' => 'Milkshake dengan rasa vanilla lezat.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Milkshake+Vanilla',
                'status' => 'tersedia',
            ],
            [
                'nama' => 'Croissant',
                'harga' => 16000,
                'kategori' => 'makanan',
                'deskripsi' => 'Croissant renyah dan buttery.',
                'gambar' => 'https://via.placeholder.com/300x200?text=Croissant',
                'status' => 'tersedia',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
