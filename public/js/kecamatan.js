document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('kecamatan-modal');
    const deleteModal = document.getElementById('delete-modal');
    const form = document.getElementById('kecamatan-form');
    const namaKecamatanInput = document.getElementById('nama_kecamatan');
    const kecamatanIdInput = document.getElementById('kecamatan-id');
    const modalTitle = document.getElementById('modal-title');
    const tableBody = document.querySelector('.table-container tbody');
    const closeBtn = document.querySelector('.close-btn');

    // Variabel untuk menyimpan ID kecamatan yang akan dihapus
    let kecamatanToDeleteId = null;

    // Mengambil dan menampilkan data dari API
    function fetchKecamatan() {
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.kode_kecamatan}</td>
                        <td>${item.nama_kecamatan}</td>
                        <td>
                            <button class="btn-action edit-btn" data-id="${item.id}" data-nama="${item.nama_kecamatan}"><i class="fas fa-pen"></i></button>
                            <button class="btn-action delete-btn" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    }

    // Menampilkan modal untuk menambah data
    document.getElementById('btn-add-kecamatan').onclick = function() {
        addModal.style.display = 'flex';
        modalTitle.textContent = 'Tambah Kecamatan';
        form.reset();
        kecamatanIdInput.value = '';
    }

    // Menampilkan modal untuk mengedit data
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            addModal.style.display = 'flex';
            modalTitle.textContent = 'Edit Kecamatan';
            kecamatanIdInput.value = btn.dataset.id;
            namaKecamatanInput.value = btn.dataset.nama;
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
        const id = kecamatanIdInput.value;
        const url = id ? `/api/kecamatan/${id}` : '/api/kecamatan';
        const method = id ? 'PUT' : 'POST';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ nama_kecamatan: namaKecamatanInput.value })
        })
        .then(response => response.json())
        .then(() => {
            addModal.style.display = 'none';
            fetchKecamatan(); // Muat ulang data
        });
    });

    // Menangani klik tombol hapus di tabel
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            kecamatanToDeleteId = btn.dataset.id; // Simpan ID
            deleteModal.style.display = 'flex'; // Tampilkan modal konfirmasi
        }
    });

    // Menangani tombol "Batal" di modal hapus
    document.getElementById('cancel-delete').onclick = function() {
        deleteModal.style.display = 'none';
        kecamatanToDeleteId = null;
    }

    // Menangani tombol "Hapus" di modal hapus
    document.getElementById('confirm-delete').onclick = function() {
        if (kecamatanToDeleteId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            fetch(`/api/kecamatan/${kecamatanToDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(() => {
                deleteModal.style.display = 'none';
                kecamatanToDeleteId = null;
                fetchKecamatan(); // Muat ulang data
            });
        }
    }

    // Menutup modal hapus saat mengklik di luar area
    window.onclick = function(event) {
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
            kecamatanToDeleteId = null;
        }
        if (event.target == addModal) {
            addModal.style.display = 'none';
        }
    }

    fetchKecamatan();
});