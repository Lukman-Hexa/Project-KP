<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class KecamatanController extends Controller
{
    /**
     * Menampilkan halaman daftar kecamatan.
     */
    public function index()
    {
        $kecamatan = Kecamatan::all();
        return view('kecamatan', compact('kecamatan'));
    }

    public function all()
    {
        $kecamatans = Kecamatan::all();
        return response()->json($kecamatans);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
        ]);

        // Logika penomoran otomatis
        $lastKecamatan = Kecamatan::latest('id')->first();
        $nextId = $lastKecamatan ? $lastKecamatan->id + 1 : 1;
        $kode_kecamatan = 'KEC' . str_pad($nextId, 2, '0', STR_PAD_LEFT);

        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => $kode_kecamatan,
            'nama_kecamatan' => $request->nama_kecamatan,
        ]);

        return response()->json($kecamatan);
    }

    /**
     * Memperbarui data kecamatan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:255',
        ]);

        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->update($request->all());

        return response()->json($kecamatan);
    }

    /**
     * Menghapus data kecamatan dari database.
     */
    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        // Mereset AUTO_INCREMENT jika tabel kosong
        if (Kecamatan::count() === 0) {
            Schema::disableForeignKeyConstraints();
            DB::statement('ALTER TABLE kecamatans AUTO_INCREMENT = 1;');
            Schema::enableForeignKeyConstraints();
        }

        return response()->json(null, 204);
    }
}