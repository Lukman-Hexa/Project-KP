@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/input_laporan.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Lapor Pengaduan</h1>
    <p class="page-description">Form untuk melakukan laporan pengaduan</p>
</div>

<div class="page-content">
    <form id="laporan-form" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-section">
            <div class="form-group">
                <label for="nama_pelapor">Nama Pelapor</label>
                <input type="text" id="nama_pelapor" name="nama_pelapor" placeholder="Nama Pelapor" required>
            </div>
            <div class="form-group">
                <label for="status_laporan">Status Laporan</label>
                <select id="status_laporan" name="status_laporan" required>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>
        
        <div class="form-section">
            <div class="form-group">
                <label for="kecamatan_id">Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id" required>
                    <option value="" disabled selected>-- Pilih Kecamatan --</option>
                    @foreach($kecamatans as $kecamatan)
                        <option value="{{ $kecamatan->id }}">{{ $kecamatan->nama_kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="kelurahan_id">Kelurahan</label>
                <select id="kelurahan_id" name="kelurahan_id" required disabled>
                    <option value="" disabled selected>-- Pilih Kelurahan --</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_masalah">Jenis Masalah Lingkungan :</label>
            <select id="jenis_masalah" name="jenis_masalah" required>
                <option value="" disabled selected>-- Pilih Jenis Masalah --</option>
                @foreach($kategori_laporans as $kategori)
                    <option value="{{ $kategori->nama_laporan }}">{{ $kategori->nama_laporan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="deskripsi_pengaduan">Deskripsi Pengaduan :</label>
            <textarea id="deskripsi_pengaduan" name="deskripsi_pengaduan" placeholder="Deskripsi Pengaduan" required></textarea>
        </div>

        <div class="form-group file-input-container">
            <button type="button" id="add-document-btn" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Tambah Lampiran
            </button>
            <div id="file-inputs">
                <div class="file-input-group">
                    <input type="file" name="dokumen[]" required>
                    <input type="text" name="nama_dokumen[]" placeholder="Masukan Nama Dokumen" required>
                    <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit">Ajukan</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/input_laporan.js') }}"></script>
@endpush