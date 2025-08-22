<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_laporan',
        'kode_laporan',
        'status_laporan',
        'lokasi_kejadian',
        'tanggal',
        'kecamatan_id',
        'kelurahan_id',
        'jenis_masalah',
        'deskripsi_pengaduan',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenLaporan::class);
    }
}