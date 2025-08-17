// File: public/js/kategori/script.js

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('kategori-modal');
    const btn = document.getElementById('btn-add-kategori');
    const span = document.getElementsByClassName('close-btn')[0];

    // Saat tombol Tambah diklik, tampilkan modal
    btn.onclick = function() {
        modal.style.display = 'flex';
    }

    // Saat tombol (x) diklik, sembunyikan modal
    span.onclick = function() {
        modal.style.display = 'none';
    }

    // Saat pengguna mengklik di luar modal, sembunyikan juga
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});