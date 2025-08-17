document.addEventListener('DOMContentLoaded', function() {
    console.log('Halaman login sudah dimuat sepenuhnya!');

    const loginForm = document.querySelector('.login-card form');
    loginForm.addEventListener('submit', function(event) {
        // Anda bisa menambahkan validasi di sini sebelum form disubmit
        const username = document.getElementById('username').value;
        if (username.length < 3) {
            alert('Username minimal 3 karakter!');
            event.preventDefault(); // Mencegah form disubmit
        }
    });
});