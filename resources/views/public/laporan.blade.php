@extends('layouts.public')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/public_laporan.css') }}">
@endpush

@section('content')
    <div class="main-content-public">
        <div class="search-container">
            <form action="{{ route('public.laporan') }}" method="GET" class="search-form">
                <input type="text" name="search" placeholder="Cari laporan..." class="search-input" value="{{ request('search') }}">
                <button type="submit" class="search-button">Cari</button>
            </form>
        </div>
        
        <h2 class="report-list-title">Daftar Laporan</h2>
        
        @foreach($laporans as $laporan)
            <div class="report-card" onclick="this.classList.toggle('open')">
                <div class="report-summary">
                    <h3>{{ $laporan->judul_laporan }}</h3>
                    <span class="report-status status-{{ $laporan->status_laporan }}">
                        {{ ucfirst($laporan->status_laporan) }}
                    </span>
                </div>

                <div class="report-details">
                    <p style="margin-bottom: 10px;">{{ $laporan->deskripsi_pengaduan }}</p>

                    <div class="report-details-box">
                        <div class="report-detail-item">
                            <strong>Tanggal</strong>
                            <span>: {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d/m/Y') }}</span>
                        </div>
                        <div class="report-detail-item">
                            <strong>Lokasi Kejadian</strong>
                            <span>: {{ $laporan->lokasi_kejadian }}</span>
                        </div>
                        <div class="report-detail-item">
                            <strong>Kecamatan</strong>
                            <span>: {{ $laporan->kecamatan->nama_kecamatan }}</span>
                        </div>
                        <div class="report-detail-item">
                            <strong>Kelurahan</strong>
                            <span>: {{ $laporan->kelurahan->nama_kelurahan }}</span>
                        </div>
                        <div class="report-detail-item">
                            <strong>Kategori</strong>
                            <span>: {{ $laporan->jenis_masalah }}</span>
                        </div>
                    </div>

                    @if($laporan->dokumen->count() > 0)
                    <div class="report-files">
                        <p style="font-weight: bold; margin-bottom: 5px;">Nama Lampiran</p>
                        <ul class="file-list">
                            @foreach($laporan->dokumen as $dokumen)
                            <li class="file-item">
                                <span>{{ $dokumen->nama_dokumen }}</span>
                                <a href="{{ Storage::url($dokumen->path_file) }}" target="_blank" class="file-link">
                                    Lihat File
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection