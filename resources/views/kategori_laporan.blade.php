@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Kategori Laporan</h1>
    <p class="page-description">Daftar Laporan</p>
</div>

<div class="page-content">
    <button id="btn-add-kategori" class="btn btn-primary">
        <i class="fas fa-plus"></i> + Kategori Laporan
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
                <tr>
                    <td>KLP01</td>
                    <td>Pencemaran Air</td>
                    <td>
                        <button class="btn-action edit-btn"><i class="fas fa-pen"></i></button>
                        <button class="btn-action delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>KLP02</td>
                    <td>Pencemaran Udara</td>
                    <td>
                        <button class="btn-action edit-btn"><i class="fas fa-pen"></i></button>
                        <button class="btn-action delete-btn"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="kategori-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2 id="modal-title">Tambah Kategori Laporan</h2>
        <form id="kategori-form">
            <div class="form-group">
                <label for="nama_laporan">Nama Kategori Laporan</label>
                <input type="text" id="nama_laporan" name="nama_laporan" required>
            </div>
            <button type="submit" class="btn btn-submit">Simpan</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/kategori/script.js') }}"></script>
@endpush