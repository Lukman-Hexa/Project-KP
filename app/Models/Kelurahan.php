<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_kelurahan',
        'nama_kelurahan',
        'kecamatan_id',
    ];

    /**
     * Relasi ke model Kecamatan.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }
}
