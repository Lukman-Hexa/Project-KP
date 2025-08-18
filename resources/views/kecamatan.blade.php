@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kecamatan.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Daftar Kecamatan</h1>
    <p class="page-description">Daftar Kecamatan Yang Telah Terdaftar</p>
</div>

<div class="page-content">
    <button id="btn-add-kecamatan" class="btn btn-primary">
        <i class="fas fa-plus"></i> + Kecamatan
    </button>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No. Kecamatan</th>
                    <th>Nama Kecamatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>
</div>

<div id="kecamatan-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Tambah Kecamatan</h2>
            <span class="close-btn">&times;</span>
        </div>
        <form id="kecamatan-form">
            <input type="hidden" id="kecamatan-id" name="id">
            <div class="form-group">
                <label for="nama_kecamatan">Nama Kecamatan</label>
                <input type="text" id="nama_kecamatan" name="nama_kecamatan" required>
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
    <script src="{{ asset('js/kecamatan.js') }}"></script>
@endpush