<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLaporan extends Model
{
    use HasFactory;
    
    protected $table = 'kategori_laporans';
    
    protected $fillable = [
        'kode_kategori',
        'nama_laporan',
    ];
}