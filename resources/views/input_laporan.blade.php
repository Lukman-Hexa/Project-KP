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
                <label for="nama_pelapor">Nama Pelapor <span style="color: red;">*</span></label>
                <input type="text" id="nama_pelapor" name="nama_pelapor" placeholder="Nama Pelapor" required maxlength="255">
            </div>
            <div class="form-group">
                <label for="status_laporan">Status Laporan <span style="color: red;">*</span></label>
                <select id="status_laporan" name="status_laporan" required>
                    <option value="" disabled selected>-- Pilih Status --</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>
        
        <div class="form-section">
            <div class="form-group">
                <label for="kecamatan_id">Kecamatan <span style="color: red;">*</span></label>
                <select id="kecamatan_id" name="kecamatan_id" required>
                    <option value="" disabled selected>-- Pilih Kecamatan --</option>
                </select>
            </div>
            <div class="form-group">
                <label for="kelurahan_id">Kelurahan <span style="color: red;">*</span></label>
                <select id="kelurahan_id" name="kelurahan_id" required disabled>
                    <option value="" disabled selected>-- Pilih Kelurahan --</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="jenis_masalah">Jenis Masalah Lingkungan <span style="color: red;">*</span></label>
            <select id="jenis_masalah" name="jenis_masalah" required>
                <option value="" disabled selected>-- Pilih Jenis Masalah --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="deskripsi_pengaduan">Deskripsi Pengaduan <span style="color: red;">*</span></label>
            <textarea id="deskripsi_pengaduan" name="deskripsi_pengaduan" placeholder="Deskripsi Pengaduan" required rows="5"></textarea>
        </div>

        <div class="form-group file-input-container">
            <label>Lampiran Dokumen <span style="color: red;">*</span> <small>(Format: PDF, Max: 6MB per file)</small></label>
            <button type="button" id="add-document-btn" class="btn btn-secondary">
                <i class="fas fa-plus"></i> Tambah Lampiran
            </button>
            <div id="file-inputs">
                <div class="file-input-group">
                    <input type="file" name="dokumen[]" accept=".pdf" required>
                    <input type="text" name="nama_dokumen[]" placeholder="Masukan Nama Dokumen" required maxlength="255">
                    <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit">Ajukan</button>
        </div>
    </form>
</div>

<!-- Loading overlay (optional) -->
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px;">
        <p>Sedang mengunggah laporan...</p>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/input_laporan.js') }}"></script>
@endpush