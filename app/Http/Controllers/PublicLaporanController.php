<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class PublicLaporanController extends Controller
{
    /**
     * Menampilkan daftar laporan untuk tampilan publik.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Laporan::with(['kecamatan', 'kelurahan', 'dokumen']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul_laporan', 'like', "%{$searchTerm}%")
                  ->orWhere('jenis_masalah', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal', 'like', "%{$searchTerm}%");
            });
        }

        $laporans = $query->get();
        return view('public.laporan', compact('laporans'));
    }

    // Metode baru untuk API daftar laporan
    public function getPublicLaporans(Request $request)
    {
        $query = Laporan::with(['kecamatan', 'kelurahan', 'dokumen']);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('judul_laporan', 'like', "%{$searchTerm}%")
                  ->orWhere('jenis_masalah', 'like', "%{$searchTerm}%")
                  ->orWhere('tanggal', 'like', "%{$searchTerm}%");
            });
        }

        $laporans = $query->get();
        return response()->json($laporans);
    }
}