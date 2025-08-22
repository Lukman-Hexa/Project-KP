document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('edit-modal');
    const deleteModal = document.getElementById('delete-modal');
    const editForm = document.getElementById('edit-laporan-form');
    const tableBody = document.querySelector('.table-container tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const closeEditModalBtn = editModal.querySelector('.close-btn');

    let laporanToDeleteId = null;

    function fetchKecamatanDropdown(selectedKecamatanId = null) {
        return fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                const dropdown = document.getElementById('edit_kecamatan_id');
                dropdown.innerHTML = '<option value="" disabled>Pilih Kecamatan</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nama_kecamatan;
                    if (item.id == selectedKecamatanId) {
                        option.selected = true;
                    }
                    dropdown.appendChild(option);
                });
            });
    }

    function fetchKelurahanDropdown(kecamatanId, selectedKelurahanId = null) {
        const dropdown = document.getElementById('edit_kelurahan_id');
        dropdown.innerHTML = '<option value="" disabled selected>Pilih Kelurahan</option>';
        dropdown.disabled = true;

        if (!kecamatanId) return;

        fetch(`/api/kelurahan-by-kecamatan/${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(kelurahan => {
                    const option = document.createElement('option');
                    option.value = kelurahan.id;
                    option.textContent = kelurahan.nama_kelurahan;
                    if (kelurahan.id == selectedKelurahanId) {
                        option.selected = true;
                    }
                    dropdown.appendChild(option);
                });
                dropdown.disabled = false;
            });
    }
    
    function fetchKategoriDropdown(selectedJenisMasalah = null) {
        return fetch('/api/kategori-laporan')
            .then(response => response.json())
            .then(data => {
                const dropdown = document.getElementById('edit_jenis_masalah');
                dropdown.innerHTML = '<option value="" disabled>Pilih Jenis Masalah</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.nama_laporan;
                    option.textContent = item.nama_laporan;
                    if (item.nama_laporan === selectedJenisMasalah) {
                        option.selected = true;
                    }
                    dropdown.appendChild(option);
                });
            });
    }

    // Menangani klik tombol edit
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            const id = btn.dataset.id;
            
            fetch(`/api/laporan/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('laporan-id').value = data.id;
                    document.getElementById('edit_judul_laporan').value = data.judul_laporan;
                    document.getElementById('edit_status_laporan').value = data.status_laporan;
                    document.getElementById('edit_lokasi_kejadian').value = data.lokasi_kejadian;
                    document.getElementById('edit_tanggal').value = data.tanggal;
                    document.getElementById('edit_deskripsi_pengaduan').value = data.deskripsi_pengaduan;

                    // Mengisi dropdown dinamis
                    fetchKecamatanDropdown(data.kecamatan_id).then(() => {
                        fetchKelurahanDropdown(data.kecamatan_id, data.kelurahan_id);
                    });
                    fetchKategoriDropdown(data.jenis_masalah);
                    
                    editModal.style.display = 'flex';
                });
        }
    });

    // Menangani perubahan dropdown kecamatan
    document.getElementById('edit_kecamatan_id').addEventListener('change', function() {
        fetchKelurahanDropdown(this.value);
    });

    // Menangani pengiriman form edit
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('laporan-id').value;
        const formData = new FormData(editForm);
        const jsonData = {};
        formData.forEach((value, key) => {
            jsonData[key] = value;
        });

        fetch(`/api/laporan/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            editModal.style.display = 'none';
            window.location.reload();
        })
        .catch(error => console.error('Error:', error));
    });

    // Menangani klik tombol tutup modal edit
    closeEditModalBtn.onclick = function() {
        editModal.style.display = 'none';
    }

    // Menangani klik tombol hapus di tabel
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            laporanToDeleteId = btn.dataset.id;
            deleteModal.style.display = 'flex';
        }
    });

    // Menangani tombol "Batal" di modal hapus
    document.getElementById('cancel-delete').onclick = function() {
        deleteModal.style.display = 'none';
        laporanToDeleteId = null;
    }

    // Menangani tombol "Hapus" di modal hapus
    document.getElementById('confirm-delete').onclick = function() {
        if (laporanToDeleteId) {
            fetch(`/api/laporan/${laporanToDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(() => {
                deleteModal.style.display = 'none';
                laporanToDeleteId = null;
                window.location.reload();
            })
            .catch(error => console.error('Error:', error));
        }
    }

    // Menutup modal saat mengklik di luar area
    window.onclick = function(event) {
        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
        if (event.target == deleteModal) {
            deleteModal.style.display = 'none';
            laporanToDeleteId = null;
        }
    }
});