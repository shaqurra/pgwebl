<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalurKereta extends Model
{
    protected $table = 'jalur_kereta_jabodetabek';
    // Jika tidak ada timestamps (created_at, updated_at) di tabel:
    public $timestamps = false;
}
