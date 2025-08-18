@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Kategori Laporan</h1>
    <p class="page-description">Daftar Kategori Laporan</p>
</div>

<div class="page-content">
    <button id="btn-add-kategori" class="btn btn-primary">
        <i class="fas fa-plus"></i>Kategori Laporan
    </button>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No. Kategori Laporan</th>
                    <th>Nama Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kategori_laporans as $kategori)
                <tr>
                    <td>{{ $kategori->kode_kategori }}</td>
                    <td>{{ $kategori->nama_laporan }}</td>
                    <td>
                        <button class="btn-action edit-btn" data-id="{{ $kategori->id }}" data-nama="{{ $kategori->nama_laporan }}"><i class="fas fa-pen"></i></button>
                        <button class="btn-action delete-btn" data-id="{{ $kategori->id }}"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="kategori-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Tambah Kategori Laporan</h2>
            <span class="close-btn">&times;</span>
        </div>
        <form id="kategori-form">
            <input type="hidden" id="kategori-id" name="id">
            <div class="form-group">
                <label for="nama_laporan">Nama Kategori Laporan</label>
                <input type="text" id="nama_laporan" name="nama_laporan" required>
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
<script src="{{ asset('js/kategori.js') }}"></script>
@endpush