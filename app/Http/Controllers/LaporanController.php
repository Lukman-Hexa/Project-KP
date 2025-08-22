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
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    // ... method `create` dan `store` yang sudah ada ...
    
    /**
     * Menampilkan halaman daftar laporan.
     */
    public function index()
    {
        $laporans = Laporan::with(['kecamatan', 'kelurahan', 'dokumen'])->get();
        return view('data_laporan', compact('laporans'));
    }

    /**
     * Mengambil satu laporan berdasarkan ID.
     * Digunakan untuk API saat edit.
     */
    public function show($id)
    {
        $laporan = Laporan::with(['kecamatan', 'kelurahan', 'dokumen'])->findOrFail($id);
        return response()->json($laporan);
    }
    
    /**
     * Memperbarui data laporan yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_laporan' => 'required|string|max:255',
            'status_laporan' => 'required|string|in:proses,selesai',
            // Tambahkan validasi untuk semua bidang baru
            'lokasi_kejadian' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'kecamatan_id' => 'required|integer|exists:kecamatans,id',
            'kelurahan_id' => 'required|integer|exists:kelurahans,id',
            'jenis_masalah' => 'required|string|max:255',
            'deskripsi_pengaduan' => 'required|string',
        ]);
        
        $laporan = Laporan::findOrFail($id);
        // Perbarui semua data laporan
        $laporan->update($request->all());
        
        return response()->json($laporan);
    }
    
    /**
     * Menghapus data laporan dari database.
     */
    public function destroy($id)
    {
        $laporan = Laporan::findOrFail($id);
        
        // Hapus file-file terkait sebelum menghapus data laporan
        foreach ($laporan->dokumen as $dokumen) {
            Storage::delete($dokumen->path_file);
        }
        
        $laporan->delete();
        
        return response()->json(null, 204);
    }
    
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
        try {
            $kelurahans = Kelurahan::where('kecamatan_id', $kecamatanId)->get();
            return response()->json($kelurahans);
        } catch (\Exception $e) {
            Log::error('Error fetching kelurahan: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch kelurahan'], 500);
        }
    }

    /**
     * Menyimpan laporan pengaduan baru dan dokumen terkait.
     */
    public function store(Request $request)
    {
        try {
            // Log request untuk debugging
            Log::info('Laporan store request:', [
                'judul_laporan' => $request->judul_laporan,
                'status_laporan' => $request->status_laporan,
                'lokasi_kejadian' => $request->judul_laporan,
                'tanggal' => $request->tanggal,
                'kecamatan_id' => $request->kecamatan_id,
                'kelurahan_id' => $request->kelurahan_id,
                'jenis_masalah' => $request->jenis_masalah,
                'files_count' => $request->hasFile('dokumen') ? count($request->file('dokumen')) : 0,
            ]);

            // Validasi data
            $validated = $request->validate([
                'judul_laporan' => 'required|string|max:255',
                'status_laporan' => 'required|string|in:proses,selesai',
                'lokasi_kejadian' => 'required|string|max:255',
                'tanggal' => 'required|date',
                'kecamatan_id' => 'required|integer|exists:kecamatans,id',
                'kelurahan_id' => 'required|integer|exists:kelurahans,id',
                'jenis_masalah' => 'required|string|max:255',
                'deskripsi_pengaduan' => 'required|string',
                'dokumen' => 'required|array|min:1',
                'dokumen.*' => 'required|file|mimes:pdf|max:6144', // max 6MB
                'nama_dokumen' => 'required|array|min:1',
                'nama_dokumen.*' => 'required|string|max:255',
            ], [
                'judul_laporan.required' => 'Judul Laporan harus diisi',
                'status_laporan.required' => 'Status laporan harus dipilih',
                'status_laporan.in' => 'Status laporan tidak valid',
                'lokasi_kejadian' => 'Lokasi jadian harus diisi',
                'tanggal' => 'Tanggal harus diisi',
                'kecamatan_id.required' => 'Kecamatan harus dipilih',
                'kecamatan_id.exists' => 'Kecamatan tidak valid',
                'kelurahan_id.required' => 'Kelurahan harus dipilih',
                'kelurahan_id.exists' => 'Kelurahan tidak valid',
                'jenis_masalah.required' => 'Jenis masalah harus dipilih',
                'deskripsi_pengaduan.required' => 'Deskripsi pengaduan harus diisi',
                'dokumen.required' => 'Minimal satu dokumen harus diunggah',
                'dokumen.*.required' => 'File dokumen harus diunggah',
                'dokumen.*.mimes' => 'Format file harus PDF',
                'dokumen.*.max' => 'Ukuran file maksimal 6MB',
                'nama_dokumen.required' => 'Nama dokumen harus diisi',
                'nama_dokumen.*.required' => 'Setiap dokumen harus memiliki nama',
            ]);

            // Validasi tambahan: pastikan jumlah dokumen sama dengan nama dokumen
            if (count($request->file('dokumen')) !== count($request->nama_dokumen)) {
                return response()->json([
                    'message' => 'Jumlah dokumen dan nama dokumen harus sama',
                    'errors' => ['dokumen' => ['Jumlah dokumen dan nama dokumen tidak sesuai']]
                ], 422);
            }

            // Mulai database transaction
            DB::beginTransaction();

            // Logika penomoran otomatis
            $lastLaporan = Laporan::latest('id')->first();
            $nextId = $lastLaporan ? $lastLaporan->id + 1 : 1;
            $kode_laporan = 'LPR' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

            // Menyimpan data laporan utama
            $laporan = Laporan::create([
                'judul_laporan' => $validated['judul_laporan'],
                'kode_laporan' => $kode_laporan,
                'status_laporan' => $validated['status_laporan'],
                'lokasi_kejadian' => $validated['lokasi_kejadian'],
                'tanggal' => $validated['tanggal'],
                'kecamatan_id' => $validated['kecamatan_id'],
                'kelurahan_id' => $validated['kelurahan_id'],
                'jenis_masalah' => $validated['jenis_masalah'],
                'deskripsi_pengaduan' => $validated['deskripsi_pengaduan'],
            ]);

            Log::info('Laporan created with ID: ' . $laporan->id);

            // Menyimpan dokumen
            $dokumentMissing = true;
            if ($request->hasFile('dokumen')) {
                $files = $request->file('dokumen');
                $namaDokumen = $request->nama_dokumen;
                
                foreach ($files as $key => $file) {
                    if ($file && $file->isValid()) {
                        $dokumentMissing = false;
                        
                        // Generate unique filename
                        $fileName = time() . '_' . $key . '_' . $file->getClientOriginalName();
                        
                        // Simpan file ke storage/app/public/dokumen
                        $filePath = $file->storeAs('public/dokumen', $fileName);
                        
                        Log::info('File saved: ' . $filePath);
                        
                        // Simpan info dokumen ke database
                        DokumenLaporan::create([
                            'laporan_id' => $laporan->id,
                            'nama_dokumen' => $namaDokumen[$key] ?? 'Dokumen ' . ($key + 1),
                            'path_file' => $filePath,
                        ]);
                        
                        Log::info('Document record created for file: ' . $fileName);
                    }
                }
            }
            
            if ($dokumentMissing) {
                throw new \Exception('Tidak ada dokumen valid yang diunggah');
            }

            // Commit transaction
            DB::commit();

            Log::info('Laporan successfully stored with ID: ' . $laporan->id);

            return response()->json([
                'message' => 'Laporan berhasil diajukan!',
                'data' => [
                    'id' => $laporan->id,
                    'kode_laporan' => $laporan->kode_laporan
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation error in laporan store: ', $e->errors());
            
            return response()->json([
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing laporan: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}