@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kelurahan.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Daftar Kelurahan</h1>
    <p class="page-description">Daftar Kelurahan Yang Telah Terdaftar</p>
</div>

<div class="page-content">
    <button id="btn-add-kelurahan" class="btn btn-primary">
        <i class="fas fa-plus"></i>Kelurahan
    </button>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No. Kelurahan</th>
                    <th>Nama Kelurahan</th>
                    <th>Nama Kecamatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>
</div>

<div id="kelurahan-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Tambah Kelurahan</h2>
            <span class="close-btn">&times;</span>
        </div>
        <form id="kelurahan-form">
            <input type="hidden" id="kelurahan-id" name="id">
            <div class="form-group">
                <label for="nama_kelurahan">Nama Kelurahan</label>
                <input type="text" id="nama_kelurahan" name="nama_kelurahan" required>
            </div>
            <div class="form-group">
                <label for="kecamatan_id">Pilih Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id" required>
                    </select>
            </div>
            <button type="submit" class="btn btn-submit">Simpan</button>
        </form>
    </div>
</div>

<div id="delete-modal" class="modal">
    <div class="modal-content-delete">
        <h2 class="modal-title-delete">Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="modal-buttons">
            <button id="cancel-delete" class="btn btn-secondary">Batal</button>
            <button id="confirm-delete" class="btn btn-danger">Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/kelurahan.js') }}"></script>
@endpush