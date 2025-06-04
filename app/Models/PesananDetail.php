<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesananDetail extends Model
{
    protected $fillable = ['pesanan_id', 'menu_id', 'jumlah', 'harga_satuan'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
