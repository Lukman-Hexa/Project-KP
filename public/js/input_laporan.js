document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('laporan-form');
    const kecamatanDropdown = document.getElementById('kecamatan_id');
    const kelurahanDropdown = document.getElementById('kelurahan_id');
    const kategoriDropdown = document.getElementById('jenis_masalah');
    const addDocumentBtn = document.getElementById('add-document-btn');
    const fileInputsContainer = document.getElementById('file-inputs');

    function fetchKecamatanDropdown() {
        fetch('/api/kecamatan')
            .then(response => response.json())
            .then(data => {
                kecamatanDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Kecamatan --</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nama_kecamatan;
                    kecamatanDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching kecamatan:', error));
    }

    function fetchKategoriDropdown() {
        fetch('/api/kategori-laporan')
            .then(response => response.json())
            .then(data => {
                kategoriDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Jenis Masalah --</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.nama_laporan;
                    option.textContent = item.nama_laporan;
                    kategoriDropdown.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching kategori laporan:', error));
    }

    function fetchKelurahanDropdown(kecamatanId) {
        kelurahanDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Kelurahan --</option>';
        kelurahanDropdown.disabled = true;

        if (!kecamatanId) return;

        fetch(`/api/kelurahan-by-kecamatan/${kecamatanId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(kelurahan => {
                    const option = document.createElement('option');
                    option.value = kelurahan.id;
                    option.textContent = kelurahan.nama_kelurahan;
                    kelurahanDropdown.appendChild(option);
                });
                kelurahanDropdown.disabled = false;
            })
            .catch(error => {
                console.error('Error fetching kelurahan:', error);
                kelurahanDropdown.innerHTML = '<option value="" disabled selected>Gagal memuat</option>';
            });
    }

    kecamatanDropdown.addEventListener('change', function() {
        const selectedKecamatanId = this.value;
        fetchKelurahanDropdown(selectedKecamatanId);
    });

    addDocumentBtn.addEventListener('click', function() {
        const fileInputGroup = document.createElement('div');
        fileInputGroup.className = 'file-input-group';
        fileInputGroup.innerHTML = `
            <input type="file" name="dokumen[]" required>
            <input type="text" name="nama_dokumen[]" placeholder="Masukan Nama Dokumen" required>
            <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
        `;
        fileInputsContainer.appendChild(fileInputGroup);
    });

    fileInputsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.btn-remove')) {
            const fileInputGroup = e.target.closest('.file-input-group');
            if (fileInputsContainer.childElementCount > 1) {
                fileInputGroup.remove();
            } else {
                alert('Minimal satu dokumen harus diunggah.');
            }
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('/api/laporan', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest' // Tambahkan header ini
            }
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                return response.text().then(text => {
                    console.error('Server returned non-JSON response:', text);
                    throw new Error('Terjadi kesalahan pada server. Silakan coba lagi.');
                });
            }
        })
        .then(data => {
            alert('Laporan berhasil diajukan!');
            form.reset();
            fetchKecamatanDropdown();
            fetchKategoriDropdown();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengajukan laporan.');
        });
    });
    
    fetchKecamatanDropdown();
    fetchKategoriDropdown();
});