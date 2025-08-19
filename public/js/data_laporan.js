document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('edit-modal');
    const deleteModal = document.getElementById('delete-modal');
    const editForm = document.getElementById('edit-laporan-form');
    const tableBody = document.querySelector('.table-container tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let laporanToDeleteId = null;

    // Menangani klik tombol edit
    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            const id = btn.dataset.id;
            
            fetch(`/api/laporan/${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('laporan-id').value = data.id;
                    document.getElementById('edit_nama_pelapor').value = data.nama_pelapor;
                    document.getElementById('edit_status_laporan').value = data.status_laporan;
                    editModal.style.display = 'flex';
                });
        }
    });

    // Menangani pengiriman form edit
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('laporan-id').value;
        const nama_pelapor = document.getElementById('edit_nama_pelapor').value;
        const status_laporan = document.getElementById('edit_status_laporan').value;

        fetch(`/api/laporan/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ nama_pelapor, status_laporan })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            editModal.style.display = 'none';
            window.location.reload(); // Muat ulang halaman untuk melihat perubahan
        })
        .catch(error => console.error('Error:', error));
    });

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
                window.location.reload(); // Muat ulang halaman untuk melihat perubahan
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