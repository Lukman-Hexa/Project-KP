@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/data_laporan.css') }}">
@endpush

@section('content')
<div class="page-header">
    <h1 class="page-title">Data Laporan</h1>
    <p class="page-description">Daftar Laporan Pengaduan yang Masuk</p>
</div>

<div class="page-content">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No. Laporan</th>
                    <th>Nama Pelapor</th>
                    <th>Lokasi Kejadian</th>
                    <th>Jenis Masalah</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Status Laporan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporans as $laporan)
                    <tr>
                        <td>{{ $laporan->kode_laporan }}</td>
                        <td>{{ $laporan->nama_pelapor }}</td>
                        <td>{{ $laporan->kelurahan->nama_kelurahan }}, {{ $laporan->kecamatan->nama_kecamatan }}</td>
                        <td>{{ $laporan->jenis_masalah }}</td>
                        <td>{{ $laporan->deskripsi_pengaduan }}</td>
                        <td>
                            @foreach ($laporan->dokumen as $dokumen)
                                <a href="{{ Storage::url($dokumen->path_file) }}" target="_blank">{{ $dokumen->nama_dokumen }}</a><br>
                            @endforeach
                        </td>
                        <td>{{ $laporan->status_laporan }}</td>
                        <td>
                            <button class="btn-action edit-btn" data-id="{{ $laporan->id }}"><i class="fas fa-pen"></i></button>
                            <button class="btn-action delete-btn" data-id="{{ $laporan->id }}"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modal-title">Edit Laporan</h2>
            <span class="close-btn">&times;</span>
        </div>
        <form id="edit-laporan-form">
            <input type="hidden" id="laporan-id" name="id">
            <div class="form-group">
                <label for="edit_nama_pelapor">Nama Pelapor</label>
                <input type="text" id="edit_nama_pelapor" name="nama_pelapor" required>
            </div>
            <div class="form-group">
                <label for="edit_status_laporan">Status Laporan</label>
                <select id="edit_status_laporan" name="status_laporan" required>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-submit">Simpan</button>
        </form>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="modal">
    <div class="modal-content-delete">
        <h2 class="modal-title-delete">Konfirmasi Hapus</h2>
        <p>Apakah Anda yakin ingin menghapus data laporan ini?</p>
        <div class="modal-buttons">
            <button id="cancel-delete" class="btn btn-secondary">Batal</button>
            <button id="confirm-delete" class="btn btn-danger">Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/data_laporan.js') }}"></script>
@endpush