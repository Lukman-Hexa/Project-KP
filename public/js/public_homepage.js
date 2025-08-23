document.addEventListener('DOMContentLoaded', function() {
    // Pastikan variabel global 'window.laporansData' ada sebelum mencoba mengaksesnya
    if (typeof window.laporansData !== 'undefined' && window.laporansData.length > 0) {
        const chartData = window.laporansData;
        
        const labels = chartData.map(item => {
            const [year, month] = item.bulan.split('-');
            // Format tanggal agar lebih mudah dibaca
            return new Date(year, month - 1, 1).toLocaleString('id-ID', { month: 'long', year: 'numeric' });
        });
        
        const data = chartData.map(item => item.jumlah_laporan);
        
        const ctx = document.getElementById('laporanChart');
        if (ctx) {
            const laporanChart = new Chart(ctx.getContext('2d'), {
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
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Jumlah Laporan: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            console.error('Canvas element with ID "laporanChart" not found');
        }
    } else {
        console.log('No laporan data available for chart');
    }
});