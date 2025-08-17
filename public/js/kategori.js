// File: public/js/kategori.js

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('kategori-modal');
    const form = document.getElementById('kategori-form');
    const namaLaporanInput = document.getElementById('nama_laporan');
    const kategoriIdInput = document.getElementById('kategori-id');
    const modalTitle = document.getElementById('modal-title');
    const tableBody = document.querySelector('.table-container tbody');

    // Mengambil dan menampilkan data dari API
    function fetchKategoriLaporan() {
        fetch('/api/kategori-laporan')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.kode_kategori}</td>
                        <td>${item.nama_laporan}</td>
                        <td>
                            <button class="btn-action edit-btn" data-id="${item.id}" data-nama="${item.nama_laporan}"><i class="fas fa-pen"></i></button>
                            <button class="btn-action delete-btn" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    }

    // Menampilkan modal untuk menambah data
    document.getElementById('btn-add-kategori').onclick = function() {
        modal.style.display = 'flex';
        modalTitle.textContent = 'Tambah Kategori Laporan';
        form.reset();
        kategoriIdInput.value = '';
    }

    // Menampilkan modal untuk mengedit data
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            modal.style.display = 'flex';
            modalTitle.textContent = 'Edit Kategori Laporan';
            kategoriIdInput.value = btn.dataset.id;
            namaLaporanInput.value = btn.dataset.nama;
        }
    });

    // Menutup modal
    document.querySelector('.close-btn').onclick = function() {
        modal.style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Menangani pengiriman form (tambah atau edit)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = kategoriIdInput.value;
        const url = id ? `/api/kategori-laporan/${id}` : '/api/kategori-laporan';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ nama_laporan: namaLaporanInput.value })
        })
        .then(response => response.json())
        .then(() => {
            modal.style.display = 'none';
            fetchKategoriLaporan(); // Muat ulang data
        });
    });

    // Menangani penghapusan data
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            const id = btn.dataset.id;
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                fetch(`/api/kategori-laporan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(() => {
                    fetchKategoriLaporan(); // Muat ulang data
                });
            }
        }
    });

    // Panggil fungsi saat halaman dimuat
    fetchKategoriLaporan();
});