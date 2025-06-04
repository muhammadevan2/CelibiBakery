<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $fillable = [
    'nama_pelanggan',
    'no_hp',
    'no_meja',
    'bukti_pembayaran',
    'status',
    'status_pembayaran',
    'total_harga',
    'detail', // pastikan ini ada
];


    public function details()
    {
        return $this->hasMany(PesananDetail::class);
    }
}
