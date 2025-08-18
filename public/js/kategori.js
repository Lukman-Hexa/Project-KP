// File: public/js/kategori.js

document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('kategori-modal');
    const deleteModal = document.getElementById('delete-modal');
    const form = document.getElementById('kategori-form');
    const namaLaporanInput = document.getElementById('nama_laporan');
    const kategoriIdInput = document.getElementById('kategori-id');
    const modalTitle = document.getElementById('modal-title');
    const tableBody = document.querySelector('.table-container tbody');
    const closeBtn = document.querySelector('.close-btn');

    // Variabel untuk menyimpan ID kategori yang akan dihapus
    let kategoriToDeleteId = null;

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
        addModal.style.display = 'flex';
        modalTitle.textContent = 'Tambah Kategori Laporan';
        form.reset();
        kategoriIdInput.value = '';
    }

    // Menampilkan modal untuk mengedit data
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            addModal.style.display = 'flex';
            modalTitle.textContent = 'Edit Kategori Laporan';
            kategoriIdInput.value = btn.dataset.id;
            namaLaporanInput.value = btn.dataset.nama;
        }
    });

    // Menutup modal
    closeBtn.onclick = function() {
        addModal.style.display = 'none';
    }
    window.onclick = function(event) {
        if (event.target == addModal) {
            addModal.style.display = 'none';
        }
    }

    // Menangani pengiriman form (tambah atau edit)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = kategoriIdInput.value;
        const url = id ? `/api/kategori-laporan/${id}` : '/api/kategori-laporan';
        const method = id ? 'PUT' : 'POST';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ nama_laporan: namaLaporanInput.value })
        })
        .then(response => response.json())
        .then(() => {
            addModal.style.display = 'none';
            fetchKategoriLaporan(); // Muat ulang data
        });
    });

    // Menangani klik tombol hapus di tabel
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            kategoriToDeleteId = btn.dataset.id; // Simpan ID
            deleteModal.style.display = 'flex'; // Tampilkan modal konfirmasi
        }
    });

    // Menangani tombol "Batal" di modal hapus
    document.getElementById('cancel-delete').onclick = function() {
        deleteModal.style.display = 'none';
        kategoriToDeleteId = null;
    }

    // Menangani tombol "Hapus" di modal hapus
    document.getElementById('confirm-delete').onclick = function() {
        if (kategoriToDeleteId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch(`/api/kategori-laporan/${kategoriToDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(() => {
                deleteModal.style.display = 'none';
                kategoriToDeleteId = null;
                fetchKategoriLaporan(); // Muat ulang data
            });
        }
    }

    // Menutup modal hapus saat mengklik di luar area
    window.onclick = function(event) {
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
            kategoriToDeleteId = null;
        }
        if (event.target == addModal) {
            addModal.style.display = 'none';
        }
    }

    fetchKategoriLaporan();
});