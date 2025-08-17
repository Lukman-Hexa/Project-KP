<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dinas Lingkungan Hidup</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo DLH">
            <span>Dinas Lingkungan Hidup</span>
        </div>
    </div>

    <div class="container">
        <div class="login-card">
            <div class="login-header">
                <h2>Login</h2>
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <div class="input-icon-container">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" placeholder="name" required>
                    </div>
                    <!-- @error('password')
                        <div class="alert-error">{{ $message }}</div>
                    @enderror -->
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icon-container">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>
                    @error('password')
                        <div class="alert-error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <div class="forgot-password">
                <a href="#">Lupa Kata Sandi?</a>
            </div>
        </div>
    </div>
    <script  src="{{ asset('js/login.js') }}"></script>
</body>
</html>