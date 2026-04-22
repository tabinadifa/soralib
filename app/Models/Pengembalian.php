<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'peminjaman_id',
        'tanggal_pengembalian',
        'kondisi_buku',
        'status',
        'denda',
        'metode_pembayaran',
        'file_bukti_pembayaran_id',
        'catatan',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function fileBuktiPembayaran()
    {
        return $this->belongsTo(FileManager::class, 'file_bukti_pembayaran_id');
    }
}
