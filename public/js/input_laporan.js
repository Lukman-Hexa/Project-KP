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

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validasi form sebelum submit
        const namaSelapor = document.getElementById('nama_pelapor').value.trim();
        const statusLaporan = document.getElementById('status_laporan').value;
        const kecamatanId = document.getElementById('kecamatan_id').value;
        const kelurahanId = document.getElementById('kelurahan_id').value;
        const jenisMasalah = document.getElementById('jenis_masalah').value;
        const deskripsiPengaduan = document.getElementById('deskripsi_pengaduan').value.trim();
        
        // Validasi file upload
        const fileInputs = document.querySelectorAll('input[name="dokumen[]"]');
        const namaFiles = document.querySelectorAll('input[name="nama_dokumen[]"]');
        
        if (!namaSelapor) {
            alert('Nama pelapor harus diisi!');
            return;
        }
        
        if (!statusLaporan) {
            alert('Status laporan harus dipilih!');
            return;
        }
        
        if (!kecamatanId) {
            alert('Kecamatan harus dipilih!');
            return;
        }
        
        if (!kelurahanId) {
            alert('Kelurahan harus dipilih!');
            return;
        }
        
        if (!jenisMasalah) {
            alert('Jenis masalah harus dipilih!');
            return;
        }
        
        if (!deskripsiPengaduan) {
            alert('Deskripsi pengaduan harus diisi!');
            return;
        }
        
        // Validasi file
        for (let i = 0; i < fileInputs.length; i++) {
            if (!fileInputs[i].files[0]) {
                alert('Semua file dokumen harus diunggah!');
                return;
            }
            
            if (!namaFiles[i].value.trim()) {
                alert('Nama dokumen harus diisi!');
                return;
            }
            
            // Validasi ukuran file (max 6MB)
            if (fileInputs[i].files[0].size > 6 * 1024 * 1024) {
                alert('Ukuran file tidak boleh lebih dari 6MB!');
                return;
            }
            
            // Validasi format file
            if (!fileInputs[i].files[0].name.toLowerCase().endsWith('.pdf')) {
                alert('Format file harus PDF!');
                return;
            }
        }
        
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Tampilkan loading
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
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (!response.ok) {
                // Log response untuk debugging
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
            alert('Laporan berhasil diajukan!');
            form.reset();
            
            // Reset dropdowns
            kelurahanDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Kelurahan --</option>';
            kelurahanDropdown.disabled = true;
            
            // Reset file inputs container to original state
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
            // Reset button
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        });
    });
    
    // Initialize dropdowns
    fetchKecamatanDropdown();
    fetchKategoriDropdown();
});