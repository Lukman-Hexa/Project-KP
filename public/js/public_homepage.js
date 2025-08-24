document.addEventListener('DOMContentLoaded', function () {
    // 1. Pastikan data ada
    if (typeof window.laporansData === 'undefined' || !window.laporansData.length) {
        console.log('No laporan data available for chart');
        return;
    }

    // 2. Siapkan label & data
    const labels = window.laporansData.map(item => {
        const [year, month] = item.bulan.split('-');
        return new Date(year, month - 1, 1)
               .toLocaleString('id-ID', { month: 'long', year: 'numeric' });
    });
    const data = window.laporansData.map(item => item.jumlah_laporan);

    // 3. Cari elemen canvas
    const canvas = document.getElementById('laporanChart');
    if (!canvas) {
        console.error('Canvas element with ID "laporanChart" not found');
        return;
    }

    // 4. Hancurkan grafik lama jika masih ada
    if (window.laporanChartInstance) {
        window.laporanChartInstance.destroy();
    }

    // 5. Buat grafik baru & simpan ke global
    window.laporanChartInstance = new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Laporan',
                data: data,
                backgroundColor: '#00796b',
                borderColor: '#005f52',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Laporan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return `Jumlah Laporan: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });

    /* Opsional:
       Hancurkan grafik saat Livewire/Turbo akan meninggalkan halaman
    --------------------------------------------------------------- */
    document.addEventListener('livewire:navigating', () => {
        window.laporanChartInstance?.destroy();
    });
});