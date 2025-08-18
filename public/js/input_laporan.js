document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('laporan-form');
    const kecamatanDropdown = document.getElementById('kecamatan_id');
    const kelurahanDropdown = document.getElementById('kelurahan_id');
    const kategoriDropdown = document.getElementById('jenis_masalah');
    const addDocumentBtn = document.getElementById('add-document-btn');
    const fileInputsContainer = document.getElementById('file-inputs');

    // Mengisi dropdown Kelurahan berdasarkan pilihan Kecamatan
    function fetchKelurahanDropdown(kecamatanId) {
        kelurahanDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Kelurahan --</option>';
        kelurahanDropdown.disabled = true;

        if (!kecamatanId) return;

        fetch(`/api/kelurahan-by-kecamatan/${kecamatanId}`) // Perbaikan di sini
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
                console.error('Error:', error);
                kelurahanDropdown.innerHTML = '<option value="" disabled selected>Gagal memuat</option>';
            });
    }

    // Menangani perubahan pada dropdown Kecamatan
    kecamatanDropdown.addEventListener('change', function() {
        const selectedKecamatanId = this.value;
        fetchKelurahanDropdown(selectedKecamatanId);
    });

    // Menambah input file baru
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

    // Menghapus input file
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

    // Menangani pengiriman form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        
        fetch('/api/laporan', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            alert('Laporan berhasil diajukan!');
            form.reset();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengajukan laporan.');
        });
    });

    fetchKecamatanDropdown();
    fetchKategoriDropdown();
});