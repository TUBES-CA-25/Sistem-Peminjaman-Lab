<?php 
    // 1. Bagian Header Tim
    include '../../components/header.php'; 
?>

<style>
    /* Seluruh CSS Ikhlas tetap dipertahankan 100% */
    :root {
        --primary-color: #0F172A;
        --secondary-color: #1e293b;
        --accent-color: #2563EB;
        --light-bg: #BFDBFE;
        --text-dark: #0F172A;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        color: var(--text-dark);
        background-color: #ffffff;
    }

    .hero-section {
        background: #ffffff;
        padding: 3rem 0 4rem;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -350px;
        right: -200px;
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(191, 219, 254, 0.6) 0%, rgba(224, 242, 254, 0.4) 30%, rgba(240, 249, 255, 0.2) 50%, transparent 70%);
        border-radius: 50%;
        z-index: 0;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 80px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 80' preserveAspectRatio='none'%3E%3Cpath fill='%23f8fbff' d='M0,40 Q360,80 720,40 T1440,40 L1440,80 L0,80 Z'/%3E%3C/svg%3E") no-repeat bottom;
        background-size: 100% 100%;
        z-index: 1;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .hero-subtitle {
        text-align: center;
        color: #64748b;
        margin-bottom: 2rem;
    }

    .lab-carousel {
        position: relative;
        z-index: 10;
    }

    .carousel-inner {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .carousel-item img {
        width: 100%;
        height: 450px;
        object-fit: cover;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 1;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .carousel-control-prev {
        left: -30px;
    }

    .carousel-control-next {
        right: -30px;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        background: #ffffff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-50%) scale(1.05);
    }

    .carousel-control-prev-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%230F172A' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
    }

    .carousel-control-next-icon {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%230F172A' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
    }

    .content-section {
        padding: 4rem 0;
        background-color: #ffffff;
    }

    .lab-description h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .lab-description p {
        color: #64748b;
        line-height: 1.8;
        font-size: 0.95rem;
    }

    .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 12px !important;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .accordion-button {
        font-weight: 600;
        color: var(--text-dark);
        background-color: #ffffff;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .accordion-button:not(.collapsed) {
        background-color: var(--light-bg);
        color: var(--primary-color);
        box-shadow: none;
    }

    .accordion-button .icon i {
        font-size: 1.2rem;
        color: var(--accent-color);
    }

    .accordion-body {
        padding: 1.5rem;
        color: #64748b;
    }

    .accordion-body ul li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .accordion-body ul li i {
        color: var(--accent-color);
    }

    .lab-name-display {
        font-weight: 600;
        color: var(--accent-color);
        transition: opacity 0.3s ease;
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 1.8rem; }
        .carousel-item img { height: 300px; }
        .carousel-control-prev, .carousel-control-next { width: 40px; height: 40px; }
    }
</style>

<section class="hero-section">
    <div class="container">
        <h1 class="hero-title">Sarana dan Prasarana Laboratorium</h1>
        <p class="hero-subtitle">Integrated Computer Laboratories - <span class="lab-name-display" id="currentLabName">Laboratorium Internet of Things</span></p>
        
        <div class="lab-carousel">
            <div id="labCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-lab-name="Laboratorium Internet of Things" data-lab-description="Laboratorium Internet of Things adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                        <img src="https://lh3.googleusercontent.com/u/0/d/1cATshoGam42Yp1FyuRHyY_fzOuTy3TMd" alt="Lab IoT" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Laboratorium Computer Networking" data-lab-description="Laboratorium Computer Networking adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran jaringan komputer. Laboratorium ini dilengkapi dengan 36 set komputer dan peralatan jaringan yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                        <img src="https://lh3.googleusercontent.com/u/0/d/1fcZHlP9VELnz02B_RgaSOLCU9TBJrU3i" alt="Lab Networking" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Laboratorium Computer Vision" data-lab-description="Laboratorium Computer Vision adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran pengolahan citra dan visi komputer.">
                        <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1200" alt="Lab Computer Vision" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Laboratorium Multimedia" data-lab-description="Laboratorium Multimedia adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran multimedia.">
                        <img src="https://images.unsplash.com/photo-1593062096033-9a26b09da705?w=1200" alt="Lab Multimedia" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Laboratorium StartUp" data-lab-description="Laboratorium StartUp mendukung kegiatan pembelajaran kewirausahaan teknologi.">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200" alt="Lab StartUp" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Laboratorium Data Science" data-lab-description="Laboratorium Data Science mendukung kegiatan pembelajaran pengolahan data.">
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200" alt="Lab Data Science" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Laboratorium Microcontroller" data-lab-description="Laboratorium Microcontroller mendukung kegiatan pembelajaran mikrokontroler.">
                        <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200" alt="Lab Micro" class="d-block w-100">
                    </div>
                    <div class="carousel-item" data-lab-name="Riset 2" data-lab-description="Fasilitas riset pendukung pembelajaran.">
                        <img src="https://images.unsplash.com/photo-1462826303086-329426d1aef5?w=1200" alt="Riset 2" class="d-block w-100">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#labCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#labCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</section>

<section class="content-section">
    <div class="container">
        <div class="lab-description">
            <h2 id="labTitle">Tentang Laboratorium Internet of Things</h2>
            <p id="labDesc">Laboratorium Internet of Things adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran.</p>
            <p>Dengan teknologi terkini dan lingkungan yang kondusif, kami berkomitmen untuk memberikan pengalaman terbaik.</p>
        </div>

        <div class="accordion" id="labAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hardwareCollapse">
                        <span class="icon"><i class="bi bi-display"></i></span>Hardware
                    </button>
                </h2>
                <div id="hardwareCollapse" class="accordion-collapse collapse" data-bs-parent="#labAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> 36 Unit Komputer Desktop</li>
                            <li><i class="bi bi-check-circle-fill"></i> Processor Intel Core i7 Gen 12</li>
                            <li><i class="bi bi-check-circle-fill"></i> RAM 16GB DDR4</li>
                            <li><i class="bi bi-check-circle-fill"></i> SSD 512GB NVMe</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#softwareCollapse">
                        <span class="icon"><i class="bi bi-gear-fill"></i></span>Software
                    </button>
                </h2>
                <div id="softwareCollapse" class="accordion-collapse collapse" data-bs-parent="#labAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> Windows 11 Pro</li>
                            <li><i class="bi bi-check-circle-fill"></i> Visual Studio Code</li>
                            <li><i class="bi bi-check-circle-fill"></i> Arduino IDE</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fasilitasCollapse">
                        <span class="icon"><i class="bi bi-stars"></i></span>Fasilitas Lain
                    </button>
                </h2>
                <div id="fasilitasCollapse" class="accordion-collapse collapse" data-bs-parent="#labAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li><i class="bi bi-check-circle-fill"></i> WiFi High Speed</li>
                            <li><i class="bi bi-check-circle-fill"></i> Ruangan Full AC</li>
                            <li><i class="bi bi-check-circle-fill"></i> Meja dan Kursi Ergonomis</li>
                            <li><i class="bi bi-check-circle-fill"></i> Whiteboard & Sound System</li>
                            <li><i class="bi bi-check-circle-fill"></i> CCTV 24 Jam & Fire Extinguisher</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('labCarousel');
        const labNameDisplay = document.getElementById('currentLabName');
        const labTitle = document.getElementById('labTitle');
        const labDesc = document.getElementById('labDesc');

        carousel.addEventListener('slid.bs.carousel', function(event) {
            const activeItem = event.relatedTarget;
            const labName = activeItem.getAttribute('data-lab-name');
            const labDescription = activeItem.getAttribute('data-lab-description');
            labNameDisplay.textContent = labName;
            labTitle.textContent = 'Tentang ' + labName;
            labDesc.textContent = labDescription;
        });
    });
</script>

<?php 
    // 2. Bagian penutup diganti dengan footer tim
    include '../../components/footer.php'; 
?>