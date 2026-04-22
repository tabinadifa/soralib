<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     *
     * @var string
     */
    protected $table = 'buku';

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul_buku',
        'kategori_id',
        'jumlah_stok',
        'deskripsi',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'bahasa',
        'isbn',
        'gambar_buku_id',
    ];

    /**
     * Atribut yang harus disembunyikan untuk serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Atribut yang harus di-cast ke tipe asli.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah_stok' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke model KategoriBuku.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    /**
     * Relasi ke model FileManager untuk gambar buku.
     */
    public function gambar()
    {
        return $this->belongsTo(FileManager::class, 'gambar_buku_id');
    }
}