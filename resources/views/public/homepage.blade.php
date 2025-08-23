@extends('layouts.public')

@section('content')
    <div style="padding: 15px;">
        <div class="carousel-container">
            <div class="carousel-track">
                <!-- <img src="{{ asset('images/logo.png') }}" alt="Logo DLH"> -->
                <img src="{{ asset('images/nim.jpg') }}" alt="Gambar 1" class="carousel-image">
                <img src="{{ asset('images/nim2.png') }}" alt="Gambar 2" class="carousel-image">
                <img src="{{ asset('images/nim3.jpg') }}" alt="Gambar 3" class="carousel-image">
            </div>
            <div class="carousel-nav-dots"></div>
        </div>

       @foreach($artikels as $artikel)
            <div class="article-card">
                <div class="article-image-placeholder">
                    <img src="{{ asset($artikel->gambar_artikel) }}" alt="{{ $artikel->judul_artikel }}" style="width:100px;height:100px;object-fit:cover;">
                </div>
                <div class="article-content">
                    <h3>{{ $artikel->judul_artikel }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($artikel->deskripsi, 100, '...') }}</p>
                </div>
            </div>
        @endforeach
    </div>
@endsection