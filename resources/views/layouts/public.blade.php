<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinas Lingkungan Hidup</title>
    <link rel="stylesheet" href="{{ asset('css/public_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="public-layout">
        <header class="public-header">
            <div class="public-header-content">
                <img src="{{ asset('images/logo.png') }}" alt="Logo DLH">
                <span>Dinas Lingkungan Hidup</span>
            </div>
        </header>

        <main class="main-content-public">
            @yield('content')
        </main>

        <nav class="bottom-nav">
            <a href="{{ url('/') }}" class="nav-item @if(Request::is('/')) active @endif">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
            <a href="https://wa.me/6283126157245" target="_blank" class="nav-item whatsapp">
                <i class="fab fa-whatsapp"></i>
                <span>Hubungi</span>
            </a>
            <a href="{{ route('public.laporan') }}" class="nav-item @if(Request::is('laporan')) active @endif">
                <i class="fas fa-file-alt"></i>
                <span>Lapor</span>
            </a>
        </nav>
    </div>
    @stack('scripts')
    <script src="{{ asset('js/public_scripts.js') }}"></script>
</body>
</html>