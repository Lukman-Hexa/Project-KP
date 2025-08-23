document.addEventListener('DOMContentLoaded', function() {
    const addModal = document.getElementById('artikel-modal');
    const deleteModal = document.getElementById('delete-modal');
    const form = document.getElementById('artikel-form');
    const judulArtikelInput = document.getElementById('judul_artikel');
    const deskripsiInput = document.getElementById('deskripsi');
    const gambarArtikelInput = document.getElementById('gambar_artikel');
    const artikelIdInput = document.getElementById('artikel-id');
    const modalTitle = document.getElementById('modal-title');
    const tableBody = document.querySelector('.table-container tbody');
    const closeBtn = document.querySelector('.close-btn');

    let artikelToDeleteId = null;

    function fetchArtikel() {
        // Perbaiki URL API di sini
        fetch('/api/artikel-api')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('.table-container tbody');
                tableBody.innerHTML = '';
                data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${item.judul_artikel}</td>
                        <td>${item.deskripsi.substring(0, 50)}...</td>
                        <td>
                            <img src="${window.location.origin}/storage/${item.gambar_artikel.replace('public/', '')}" alt="Gambar Artikel" width="100">
                        </td>
                        <td>
                            <button class="btn-action edit-btn" data-id="${item.id}" data-judul="${item.judul_artikel}" data-deskripsi="${item.deskripsi}"><i class="fas fa-pen"></i></button>
                            <button class="btn-action delete-btn" data-id="${item.id}"><i class="fas fa-trash"></i></button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });
    }

    document.getElementById('btn-add-artikel').onclick = function() {
        addModal.style.display = 'flex';
        modalTitle.textContent = 'Tambah Artikel';
        form.reset();
        artikelIdInput.value = '';
        gambarArtikelInput.required = true;
    }

    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            const btn = e.target.closest('.edit-btn');
            const id = btn.dataset.id;
            
            fetch(`/api/artikel/${id}`)
                .then(response => response.json())
                .then(data => {
                    addModal.style.display = 'flex';
                    modalTitle.textContent = 'Edit Artikel';
                    artikelIdInput.value = data.id;
                    judulArtikelInput.value = data.judul_artikel;
                    deskripsiInput.value = data.deskripsi;
                    gambarArtikelInput.required = false; // Tidak wajib saat edit
                });
        }
    });

    closeBtn.onclick = function() {
        addModal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == addModal) {
            addModal.style.display = 'none';
        }
    }

   form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = artikelIdInput.value;
        // Perbaiki URL API di sini
        const url = id ? `/api/artikel-api/${id}` : '/api/artikel-api';
        const method = 'POST';
        const formData = new FormData(form);

        if (id) {
            formData.append('_method', 'PUT');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(() => {
            addModal.style.display = 'none';
            fetchArtikel();
        })
        .catch(error => console.error('Error:', error));
    });

    tableBody.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const btn = e.target.closest('.delete-btn');
            artikelToDeleteId = btn.dataset.id;
            deleteModal.style.display = 'flex';
        }
    });

    document.getElementById('confirm-delete').onclick = function() {
        if (artikelToDeleteId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            // Perbaiki URL API di sini
            fetch(`/api/artikel-api/${artikelToDeleteId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(() => {
                deleteModal.style.display = 'none';
                artikelToDeleteId = null;
                fetchArtikel();
            });
        }
    }
    
    fetchArtikel();
});