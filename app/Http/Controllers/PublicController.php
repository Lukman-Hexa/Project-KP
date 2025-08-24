<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PublicController extends Controller
{
    public function index()
    {
        try {
            // Mengambil data jumlah laporan per bulan dari database
            $laporansBulanan = Laporan::select(
                    DB::raw('count(id) as jumlah_laporan'),
                    DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan')
                )
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->get();
                
            // Debug: pastikan data ada
            if ($laporansBulanan->isEmpty()) {
                // Jika tidak ada data, buat data dummy untuk testing
                $laporansBulanan = collect([
                    (object) ['bulan' => date('Y-m'), 'jumlah_laporan' => 0]
                ]);
            }
                
            // Mengambil 3 laporan terbaru
            $laporanTerbaru = Laporan::with(['kecamatan', 'kelurahan'])
                                    ->latest()
                                    ->take(3)
                                    ->get();

            // Menghitung total laporan
            $totalLaporan = Laporan::count();
            $totalLaporanSelesai = Laporan::where('status_laporan', 'selesai')->count();
                
            return view('public.homepage', compact('laporansBulanan', 'laporanTerbaru', 'totalLaporan', 'totalLaporanSelesai'));
            
        } catch (\Exception $e) {
            // Handle error dan log
            Log::error('Error in PublicController@index: ' . $e->getMessage());
            
            // Return dengan data kosong
            return view('public.homepage', [
                'laporansBulanan' => collect(),
                'laporanTerbaru' => collect(),
                'totalLaporan' => 0,
                'totalLaporanSelesai' => 0
            ]);
        }
    }

    // Metode baru untuk API data homepage
    public function getPublicData()
    {
        try {
            $laporansBulanan = Laporan::select(
                    DB::raw('count(id) as jumlah_laporan'),
                    DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan')
                )
                ->groupBy('bulan')
                ->orderBy('bulan', 'asc')
                ->get();
                
            $laporanTerbaru = Laporan::with(['kecamatan', 'kelurahan'])
                                    ->latest()
                                    ->take(3)
                                    ->get();

            $totalLaporan = Laporan::count();
            $totalLaporanSelesai = Laporan::where('status_laporan', 'selesai')->count();
                
            return response()->json([
                'laporansBulanan' => $laporansBulanan,
                'laporanTerbaru' => $laporanTerbaru,
                'totalLaporan' => $totalLaporan,
                'totalLaporanSelesai' => $totalLaporanSelesai
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fetching public homepage data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch public data'], 500);
        }
    }
}