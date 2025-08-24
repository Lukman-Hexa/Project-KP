document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.carousel-track');

    // Perbaikan: Tambahkan pemeriksaan di sini
    if (track) {
        const images = document.querySelectorAll('.carousel-image');
        const dotsContainer = document.querySelector('.carousel-nav-dots');
        let currentIndex = 0;
        const totalImages = images.length;
        const intervalTime = 3000; // Waktu dalam ms (3 detik)

        // Perbaikan: Hapus semua titik yang ada sebelum membuat yang baru
        if (dotsContainer) {
            dotsContainer.innerHTML = '';
        }

        // Buat dot navigasi
        images.forEach((_, index) => {
            const dot = document.createElement('span');
            dot.classList.add('nav-dot');
            if (index === 0) {
                dot.classList.add('active');
            }
            if (dotsContainer) {
                dotsContainer.appendChild(dot);
            }
        });

        const dots = document.querySelectorAll('.nav-dot');

        const updateCarousel = () => {
            const offset = -currentIndex * 100;
            track.style.transform = `translateX(${offset}%)`;

            dots.forEach(dot => dot.classList.remove('active'));
            if (dots[currentIndex]) {
                dots[currentIndex].classList.add('active');
            }
        };

        const nextSlide = () => {
            currentIndex = (currentIndex + 1) % totalImages;
            updateCarousel();
        };

        // Atur pergeseran otomatis
        setInterval(nextSlide, intervalTime);
    }
});