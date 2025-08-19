document.addEventListener('DOMContentLoaded', function() {
    console.log('Halaman login sudah dimuat sepenuhnya!');

    const loginForm = document.querySelector('.login-card form');
    loginForm.addEventListener('submit', function(event) {
        // Perbaiki ID dari 'username' menjadi 'name' sesuai dengan form
        const nameInput = document.getElementById('name');
        if (nameInput.value.length < 3) {
            alert('Username minimal 3 karakter!');
            event.preventDefault(); // Mencegah form disubmit
        }
    });
});