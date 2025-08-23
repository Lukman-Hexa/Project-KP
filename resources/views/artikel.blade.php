@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/artikel.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Daftar Artikel</h1>
    <p class="page-description">Daftar Artikel Yang Telah Terdaftar</p>
</div>

<div class="page-content">
    <button id="btn-add-artikel" class="btn btn-primary">
        <i class="fas fa-plus"></i> Artikel
    </button>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No. Artikel</th>
                    <th>Judul Artikel</th>
                    <th>Deskripsi</th>
                    <th>Gambar Artikel</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data akan diisi oleh JavaScript --}}
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah/Edit --}}
<div id="artikel-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Tambah Artikel</h2>
            <span class="close-btn">&times;</span>
        </div>
        <form id="artikel-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="artikel-id" name="id">
            <div class="form-group">
                <label for="judul_artikel">Judul Artikel</label>
                <input type="text" id="judul_artikel" name="judul_artikel" required>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="gambar_artikel">Gambar Artikel</label>
                <input type="file" id="gambar_artikel" name="gambar_artikel" accept="image/*">
                <small>Kosongkan jika tidak ingin mengubah gambar.</small>
            </div>
            <button type="submit" class="btn btn-submit">Simpan</button>
        </form>
    </div>
</div>

{{-- Modal Hapus --}}
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
    <script src="{{ asset('js/artikel.js') }}"></script>
@endpush