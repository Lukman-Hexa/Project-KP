<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - Dinas Lingkungan Hidup</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="user-profile-info">
                    <div class="profile-icon-container">
                        <div class="profile-icon">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="user-info">
                        {{-- Memeriksa apakah pengguna sudah login --}}
                        @auth
                            <p class="username">{{ Auth::user()->name }}</p>
                            <p class="status">
                                <span class="status-dot online"></span> Online
                            </p>
                        @else
                            <p class="username">Guest</p>
                            <p class="status">
                                <span class="status-dot offline"></span> Offline
                            </p>
                        @endauth
                    </div>
                </div>
            </div>
            <nav class="sidebar-nav">
                <ul class="main-menu">
                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <!-- <li><a href="#"><i class="fas fa-file-alt"></i> Input Laporan</a></li> -->
                    <li class="{{ request()->routeIs('input-laporan') ? 'active' : '' }}">
                        <a href="{{ route('input-laporan') }}"><i class="fas fa-file-alt"></i> Input Laporan</a>
                    </li>
                    <li><a href="#"><i class="fas fa-file-invoice"></i> Data Laporan</a></li>
                    <li class="{{ request()->routeIs('kategori-laporan') ? 'active' : '' }}">
                        <a href="{{ route('kategori-laporan') }}"><i class="fas fa-list-alt"></i> Kategori Laporan</a>
                    </li>
                    <li class="{{ request()->routeIs('kecamatan') ? 'active' : '' }}">
                        <a href="{{ route('kecamatan') }}"><i class="fas fa-city"></i> Kecamatan</a>
                    </li>
                    
                    <li class="{{ request()->routeIs('kelurahan') ? 'active' : '' }}">
                        <a href="{{ route('kelurahan') }}"><i class="fas fa-map-marker-alt"></i> Kelurahan</a>
                    </li>
                </ul>
            </nav>
            <div class="sidebar-footer">
                <a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i> Log Out</a>
            </div>
        </aside>

        <main class="main-content">
            <header class="main-header">
                <div class="header-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo DLH">
                    <span>Dinas Lingkungan Hidup</span>
                </div>
            </header>
            
            <div class="content">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>
</html>