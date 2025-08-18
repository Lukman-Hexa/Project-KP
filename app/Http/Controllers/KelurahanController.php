<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KelurahanController extends Controller
{
    /**
     * Menampilkan halaman daftar kelurahan.
     */
    public function index()
    {
        return view('kelurahan');
    }

    /**
     * Mengambil semua data kelurahan dari database.
     * Digunakan untuk API.
     */
    public function all()
    {
        $kelurahans = Kelurahan::with('kecamatan')->get();
        return response()->json($kelurahans);
    }

    /**
     * Menyimpan data kelurahan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelurahan' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        // Logika penomoran otomatis
        $lastKelurahan = Kelurahan::latest('id')->first();
        $nextId = $lastKelurahan ? $lastKelurahan->id + 1 : 1;
        $kode_kelurahan = 'KEL' . str_pad($nextId, 2, '0', STR_PAD_LEFT);

        $kelurahan = Kelurahan::create([
            'kode_kelurahan' => $kode_kelurahan,
            'nama_kelurahan' => $request->nama_kelurahan,
            'kecamatan_id' => $request->kecamatan_id,
        ]);

        return response()->json($kelurahan);
    }

    /**
     * Memperbarui data kelurahan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kelurahan' => 'required|string|max:255',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        $kelurahan = Kelurahan::findOrFail($id);
        $kelurahan->update($request->all());

        return response()->json($kelurahan);
    }

    /**
     * Menghapus data kelurahan dari database.
     */
    public function destroy($id)
    {
        $kelurahan = Kelurahan::findOrFail($id);
        $kelurahan->delete();

        // Mereset AUTO_INCREMENT jika tabel kosong
        if (Kelurahan::count() === 0) {
            Schema::disableForeignKeyConstraints();
            DB::statement('ALTER TABLE kelurahans AUTO_INCREMENT = 1;');
            Schema::enableForeignKeyConstraints();
        }

        return response()->json(null, 204);
    }
}