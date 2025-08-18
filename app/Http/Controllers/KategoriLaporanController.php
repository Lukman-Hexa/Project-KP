<?php

namespace App\Http\Controllers;

use App\Models\KategoriLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KategoriLaporanController extends Controller
{
    // Menampilkan halaman Kategori Laporan dengan data
    public function index()
    {
        $kategori_laporans = KategoriLaporan::all();
        return view('kategori_laporan', compact('kategori_laporans'));
    }

    // API untuk mendapatkan semua data kategori laporan
    public function all()
    {
        $kategori_laporans = KategoriLaporan::all();
        return response()->json($kategori_laporans);
    }

    // API untuk menambah data
    public function store(Request $request)
    {
        $request->validate([
            'nama_laporan' => 'required|string|max:255',
        ]);

        // Periksa apakah tabel kosong, jika ya, reset auto_increment
        if (KategoriLaporan::count() === 0) {
            Schema::disableForeignKeyConstraints();
            DB::statement('ALTER TABLE kategori_laporans AUTO_INCREMENT = 1;');
            Schema::enableForeignKeyConstraints();
        }

        // Logika penomoran otomatis
        $lastKategori = KategoriLaporan::latest('id')->first();
        $nextId = $lastKategori ? $lastKategori->id + 1 : 1;
        $kode_kategori = 'KLP' . str_pad($nextId, 2, '0', STR_PAD_LEFT);

        $kategori_laporan = KategoriLaporan::create([
            'kode_kategori' => $kode_kategori,
            'nama_laporan' => $request->nama_laporan,
        ]);

        return response()->json($kategori_laporan);
    }

    // API untuk memperbarui data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_laporan' => 'required|string|max:255',
        ]);

        $kategori_laporan = KategoriLaporan::findOrFail($id);
        $kategori_laporan->update($request->all());

        return response()->json($kategori_laporan);
    }

    // API untuk menghapus data
    public function destroy($id)
    {
        $kategori_laporan = KategoriLaporan::findOrFail($id);
        $kategori_laporan->delete();

        return response()->json(null, 204);
    }
}