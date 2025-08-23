@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@section('content')
    <div class="dashboard-content">
        <img src="{{ asset('images/logo.png') }}" alt="Logo DLH" class="main-logo">
        <h1 class="main-title">
            Dinas <span class="text-blue">Lingk</span><span class="text-yellow">ungan</span> Hidup
        </h1>
    </div>
@endsection