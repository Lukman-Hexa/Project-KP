<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenLaporan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'nama_dokumen',
        'path_file',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}