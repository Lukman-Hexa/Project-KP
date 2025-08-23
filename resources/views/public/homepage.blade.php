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

    </div>
@endsection