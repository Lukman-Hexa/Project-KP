// File: public/js/kelurahan.js

document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('kelurahan-modal');
    const deleteModal = document.getElementById('delete-modal');
    const form = document.getElementById('kelurahan-form');
    const namaKelurahanInput = document.getElementById('nama_kelurahan');
    const kecamatanIdInput = document.getElementById('kecamatan_id');
    const kelurahanIdInput = document.getElementById('kelurahan-id');
    const modalTitle = document.getElementById('modal-title');
    const tableBody = document.querySelector('.table-container tbody');
    const closeBtn = document.querySelector('.close-btn');

    // Variabel untuk menyimpan ID kelurahan yang akan dihapus
    let kelurahanToDeleteId = null;

    // Mengambil dan menampilkan data dari API
    function fetchKelurahan() {
        fetch('/api/kelurahan')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.kode_kelurahan}</td>
                        <td>${item.nama_kelurahan}</td>
                        <td>${item.kecamatan.nama_kecamatan}</td>
                        <td>
                            <button class="btn-action edit-btn" data-id="${item.id}" data-nama="${item.nama_kelurahan}" data-kecamatan-id="${item.kecamatan_id}"><i class="fas fa-pen"></i></button>
                            <button class="btn-action delete-btn" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    }

    // Mengambil dan mengisi dropdown kecamatan
    function fetchKecamatanDropdown() {
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                kecamatanIdInput.innerHTML = '<option value="" disabled selected>Pilih Kecamatan</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nama_kecamatan;
                    kecamatanIdInput.appendChild(option);
                });
            });
    }

    // Menampilkan modal untuk menambah data
    document.getElementById('btn-add-kelurahan').onclick = function() {
        addModal.style.display = 'flex';
        modalTitle.textContent = 'Tambah Kelurahan';
        form.reset();
        kelurahanIdInput.value = '';
        fetchKecamatanDropdown();
    }

    // Menampilkan modal untuk mengedit data
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            addModal.style.display = 'flex';
            modalTitle.textContent = 'Edit Kelurahan';
            kelurahanIdInput.value = btn.dataset.id;
            namaKelurahanInput.value = btn.dataset.nama;
            fetchKecamatanDropdown().then(() => {
                kecamatanIdInput.value = btn.dataset.kecamatanId;
            });
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
        const id = kelurahanIdInput.value;
        const url = id ? `/api/kelurahan/${id}` : '/api/kelurahan';
        const method = id ? 'PUT' : 'POST';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const data = {
            nama_kelurahan: namaKelurahanInput.value,
            kecamatan_id: kecamatanIdInput.value
        };

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(() => {
            addModal.style.display = 'none';
            fetchKelurahan(); // Muat ulang data
        });
    });

    // Menangani klik tombol hapus di tabel
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            kelurahanToDeleteId = btn.dataset.id;
            deleteModal.style.display = 'flex';
        }
    });

    // Menangani tombol "Batal" di modal hapus
    document.getElementById('cancel-delete').onclick = function() {
        deleteModal.style.display = 'none';
        kelurahanToDeleteId = null;
    }

    // Menangani tombol "Hapus" di modal hapus
    document.getElementById('confirm-delete').onclick = function() {
        if (kelurahanToDeleteId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch(`/api/kelurahan/${kelurahanToDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(() => {
                deleteModal.style.display = 'none';
                kelurahanToDeleteId = null;
                fetchKelurahan();
            });
        }
    }

    // Menutup modal hapus saat mengklik di luar area
    window.onclick = function(event) {
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
            kelurahanToDeleteId = null;
        }
        if (event.target == addModal) {
            addModal.style.display = 'none';
        }
    }

    fetchKelurahan();
});