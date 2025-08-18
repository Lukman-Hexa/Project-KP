<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\DokumenLaporan;
use App\Models\KategoriLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanController extends Controller
{
    public function create()
    {
        $kecamatans = Kecamatan::all();
        $kategori_laporans = KategoriLaporan::all();
        
        return view('input_laporan', compact('kecamatans', 'kategori_laporans'));
    }

    /**
     * Mengambil data kelurahan berdasarkan ID kecamatan.
     */
    public function getKelurahanByKecamatan($kecamatanId)
    {
        $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)->get();
        return response()->json($kelurahans);
    }

    /**
     * Menyimpan laporan pengaduan baru dan dokumen terkait.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required|string|max:255',
            'status_laporan' => 'required|string',
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'kelurahan_id' => 'required|exists:kelurahans,id',
            'jenis_masalah' => 'required|string|max:255',
            'deskripsi_pengaduan' => 'required|string',
            'dokumen' => 'required|array',
            'dokumen.*' => 'mimes:pdf|max:6144', // Setiap file harus berukuran maksimal 2MB
            'nama_dokumen' => 'required|array',
            'nama_dokumen.*' => 'required|string|max:255',
        ]);

        // Logika penomoran otomatis
        $lastLaporan = Laporan::latest('id')->first();
        $nextId = $lastLaporan ? $lastLaporan->id + 1 : 1;
        $kode_laporan = 'LPR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

        // Menyimpan data laporan utama
        $laporan = Laporan::create([
            'nama_pelapor' => $request->nama_pelapor,
            'kode_laporan' => $kode_laporan,
            'status_laporan' => $request->status_laporan,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'jenis_masalah' => $request->jenis_masalah,
            'deskripsi_pengaduan' => $request->deskripsi_pengaduan,
        ]);

        // Menyimpan dokumen
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $key => $file) {
                if ($file->isValid()) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    // Diubah agar file tersimpan di folder 'public/dokumen'
                    $filePath = Storage::putFileAs('public/dokumen', $file, $fileName);
                    
                    DokumenLaporan::create([
                        'laporan_id' => $laporan->id,
                        'nama_dokumen' => $request->nama_dokumen[$key],
                        'path_file' => $filePath,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Laporan berhasil diajukan!'], 201);
    }
}