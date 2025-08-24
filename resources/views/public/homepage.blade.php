@extends('layouts.public')

@push('styles')
<style>
    /* Gaya untuk Statistik Ringkas */
    .stats-container {
        display: flex;
        justify-content: space-around;
        gap: 15px;
        margin: 15px;
    }

    .stat-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-align: center;
        flex-grow: 1;
    }

    .stat-card h3 {
        margin-top: 0;
        color: #00796b;
        font-size: 16px;
    }

    .stat-card p {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    /* Gaya untuk Laporan Terbaru */
    .recent-reports-container {
        padding: 15px;
        background-color: #fff;
        margin: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .report-card-mini {
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
    }

    .report-summary-mini {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
    }

    .report-summary-mini h4 {
        margin: 0;
        font-size: 14px;
        color: #333;
    }

    .report-info-mini {
        font-size: 12px;
        color: #666;
    }

    .report-info-mini i {
        margin-right: 5px;
    }

    .btn-full-reports {
        display: block;
        width: 100%;
        text-align: center;
        background-color: #0087c5;
        color: #fff;
        padding: 10px;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 15px;
        transition: background-color 0.3s ease;
    }

    .btn-full-reports:hover {
        background-color: #0076a5;
    }

    /* Gaya untuk status laporan */
    .report-status {
        padding: 4px 8px;
        border-radius: 12px;
        color: #fff;
        font-weight: bold;
        font-size: 10px;
    }

    .status-proses {
        background-color: #0079c1;
    }

    .status-selesai {
        background-color: #28a745;
    }
</style>
@endpush

@section('content')
    <div style="padding: 15px;">
        {{-- Carousel yang sudah ada --}}
        <div class="carousel-container">
            <div class="carousel-track">
                <img src="{{ asset('images/dlh.jpeg') }}" alt="Gambar 1" class="carousel-image">
                <img src="{{ asset('images/dlh2.jpeg') }}" alt="Gambar 2" class="carousel-image">
                <img src="{{ asset('images/dlh3.jpeg') }}" alt="Gambar 3" class="carousel-image">
                <img src="{{ asset('images/dlh4.jpeg') }}" alt="Gambar 4" class="carousel-image">
                <img src="{{ asset('images/dlh5.jpeg') }}" alt="Gambar 5" class="carousel-image">
                <img src="{{ asset('images/dlh6.jpeg') }}" alt="Gambar 6" class="carousel-image">
            </div>
            <div class="carousel-nav-dots"></div>
        </div>

        {{-- Bagian Statistik Ringkas --}}
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Laporan</h3>
                <p>{{ $totalLaporan }}</p>
            </div>
            <div class="stat-card">
                <h3>Laporan Selesai</h3>
                <p>{{ $totalLaporanSelesai }}</p>
            </div>
        </div>

        {{-- Container untuk grafik --}}
        <div class="chart-container" style="padding: 15px; background-color: #fff; margin: 15px; border-radius: 8px;">
            <h2 style="text-align: center; color: #00796b;">Jumlah Laporan Bulanan</h2>
            <canvas id="laporanChart"></canvas>
        </div>

        {{-- Bagian Laporan Terbaru --}}
        <div class="recent-reports-container">
            <h2 style="color: #00796b;">Laporan Terbaru</h2>
            @foreach($laporanTerbaru as $laporan)
            <div class="report-card-mini">
                <div class="report-summary-mini">
                    <h4>{{ $laporan->judul_laporan }}</h4>
                    <span class="report-status status-{{ $laporan->status_laporan }}">
                        {{ ucfirst($laporan->status_laporan) }}
                    </span>
                </div>
                <div class="report-info-mini">
                    <p><i class="fas fa-map-marker-alt"></i> {{ $laporan->kecamatan->nama_kecamatan }}, {{ $laporan->kelurahan->nama_kelurahan }}</p>
                    <p><i class="fas fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach
            <a href="{{ route('public.laporan') }}" class="btn-full-reports">Lihat Semua Laporan</a>
        </div>
        
    </div>
@endsection


@push('scripts')
    {{-- Chart.js harus dimuat lebih dulu --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Definisikan data TANPA @json agar editor tidak salah baca --}}
    <script>
        window.laporansData = {!! json_encode($laporansBulanan ?? []) !!};
        console.log('Laporan Data:', window.laporansData);
    </script>

    {{-- Baru kemudian file JS public --}}
    <script src="{{ asset('js/public_homepage.js') }}"></script>
    <script src="{{ asset('js/public_scripts.js') }}"></script>
@endpush
