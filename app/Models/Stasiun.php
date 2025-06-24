<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stasiun extends Model
{
    // Wajib: nama tabel
    protected $table = 'stasiun_jabodetabek';

    // Tambahkan semua kolom yang ada di tabel, termasuk lon dan lat
    protected $fillable = [
        'namobj', 'kodkod', 'kabkot', 'provinsi', 'kelas', 'status_ope',
        'lon', 'lat', 'user_id', 'gambar', 'created_at', 'updated_at'
    ];

    // Jika tidak ada kolom 'geom', hapus dari fillable
}
