@extends('layouts.public')

@section('content')
    <div style="padding: 15px;">
        <div class="carousel-container">
            <div class="carousel-track">
                <!-- <img src="{{ asset('images/logo.png') }}" alt="Logo DLH"> -->
                <img src="{{ asset('images/dlh1.png') }}" alt="Gambar 1" class="carousel-image">
                <img src="{{ asset('images/dlh2.jpeg') }}" alt="Gambar 2" class="carousel-image">
                <img src="{{ asset('images/dlh3.jpeg') }}" alt="Gambar 3" class="carousel-image">
            </div>
            <div class="carousel-nav-dots"></div>
        </div>

        <div class="article-card">
            <div class="article-image-placeholder">
                <p style="text-align: center; line-height: 100px;">IMAGE</p>
            </div>
            <div class="article-content">
                <h3>Judul Artikel</h3>
                <p>Deskripsi singkat artikel ini...</p>
            </div>
        </div>

        <div class="article-card">
            <div class="article-image-placeholder">
                <p style="text-align: center; line-height: 100px;">IMAGE</p>
            </div>
            <div class="article-content">
                <h3>Judul Artikel</h3>
                <p>Deskripsi singkat artikel ini...</p>
            </div>
        </div>
    </div>
@endsection