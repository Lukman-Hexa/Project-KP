document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('laporan-form');
    const kecamatanDropdown = document.getElementById('kecamatan_id');
    const kelurahanDropdown = document.getElementById('kelurahan_id');
    const kategoriDropdown = document.getElementById('jenis_masalah');
    const addDocumentBtn = document.getElementById('add-document-btn');
    const fileInputsContainer = document.getElementById('file-inputs');
    const konfirmasiModal = document.getElementById('konfirmasi-modal');
    const suksesModal = document.getElementById('sukses-modal');
    const cancelKonfirmasiBtn = document.getElementById('cancel-konfirmasi');
    const confirmKonfirmasiBtn = document.getElementById('confirm-konfirmasi');
    const okSuksesBtn = document.getElementById('ok-sukses');
    const loadingOverlay = document.getElementById('loading-overlay');

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
            <input type="file" name="dokumen[]" accept=".pdf" required>
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

    // Menangani klik tombol submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const judulLaporan = document.getElementById('judul_laporan').value.trim();
        const statusLaporan = document.getElementById('status_laporan').value;
        const lokasiKejadian = document.getElementById('lokasi_kejadian').value;
        const tanggal = document.getElementById('tanggal').value;
        const kecamatanId = document.getElementById('kecamatan_id').value;
        const kelurahanId = document.getElementById('kelurahan_id').value;
        const jenisMasalah = document.getElementById('jenis_masalah').value;
        const deskripsiPengaduan = document.getElementById('deskripsi_pengaduan').value.trim();
        const fileInputs = document.querySelectorAll('input[name="dokumen[]"]');
        const namaFiles = document.querySelectorAll('input[name="nama_dokumen[]"]');
        
        if (!judulLaporan || !statusLaporan || !lokasiKejadian || !tanggal || !kecamatanId || !kelurahanId || !jenisMasalah || !deskripsiPengaduan) {
            alert('Semua kolom bertanda (*) harus diisi.');
            return;
        }

        for (let i = 0; i < fileInputs.length; i++) {
            if (!fileInputs[i].files[0] || !namaFiles[i].value.trim()) {
                alert('Semua dokumen harus diunggah dan memiliki nama!');
                return;
            }
            if (fileInputs[i].files[0].size > 6 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 6MB!');
                return;
            }
            if (!fileInputs[i].files[0].name.toLowerCase().endsWith('.pdf')) {
                alert('Format file harus PDF!');
                return;
            }
        }

        konfirmasiModal.style.display = 'flex';
    });
    
    // Menangani tombol "Batal" di modal konfirmasi
    cancelKonfirmasiBtn.addEventListener('click', function() {
        konfirmasiModal.style.display = 'none';
    });

    // Menangani tombol "Ya, Ajukan" di modal konfirmasi
    confirmKonfirmasiBtn.addEventListener('click', function() {
        konfirmasiModal.style.display = 'none';
        
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        loadingOverlay.style.display = 'flex';
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Sedang mengirim...';
        submitBtn.disabled = true;

        fetch('/api/laporan', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;

            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Error response:', text);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                });
            }
            
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.includes("application/json")) {
                return response.json();
            } else {
                return response.text().then(text => {
                    console.error('Server returned non-JSON response:', text);
                    throw new Error('Server mengembalikan response yang tidak valid');
                });
            }
        })
        .then(data => {
            console.log('Success:', data);
            
            // Tampilkan modal sukses
            suksesModal.style.display = 'flex';

            form.reset();
            
            kelurahanDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Kelurahan --</option>';
            kelurahanDropdown.disabled = true;
            
            fileInputsContainer.innerHTML = `
                <div class="file-input-group">
                    <input type="file" name="dokumen[]" accept=".pdf" required>
                    <input type="text" name="nama_dokumen[]" placeholder="Masukan Nama Dokumen" required>
                    <button type="button" class="btn-remove"><i class="fas fa-times"></i></button>
                </div>
            `;
            
            fetchKecamatanDropdown();
            fetchKategoriDropdown();
        })
        .catch(error => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        })
        .finally(() => {
            loadingOverlay.style.display = 'none';
        });
    });
    
    // Menangani tombol "OK" di modal sukses
    okSuksesBtn.addEventListener('click', function() {
        suksesModal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target == konfirmasiModal) {
            konfirmasiModal.style.display = 'none';
        }
        if (event.target == suksesModal) {
            suksesModal.style.display = 'none';
        }
    }
    
    fetchKecamatanDropdown();
    fetchKategoriDropdown();
});