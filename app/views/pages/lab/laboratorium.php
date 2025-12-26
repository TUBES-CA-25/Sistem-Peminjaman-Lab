<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICLABS - Sarana dan Prasarana Laboratorium</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
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

        /* Navbar Styles */
        .navbar {
            background-color: #ffffff;
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .navbar-brand img, .navbar-brand svg {
            width: 40px !important;
            height: 40px !important;
            max-width: 40px !important;
            max-height: 40px !important;
            object-fit: contain;
        }

        .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            padding: 0.5rem 1.5rem !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--accent-color);
        }

        /* Hero Section */
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

        /* Carousel Styles */
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

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: transparent;
            width: 24px;
            height: 24px;
        }

        .carousel-control-prev-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%230F172A' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/%3E%3C/svg%3E");
        }

        .carousel-control-next-icon {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%230F172A' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
        }

        /* Content Section */
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

        /* Accordion Styles */
        .accordion {
            margin-top: 2rem;
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

        .accordion-button::after {
            background-size: 16px;
        }

        .accordion-button .icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .accordion-button .icon i {
            font-size: 1.2rem;
            color: var(--accent-color);
        }

        .accordion-body {
            padding: 1.5rem;
            color: #64748b;
        }

        .accordion-body ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .accordion-body ul li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .accordion-body ul li:last-child {
            border-bottom: none;
        }

        .accordion-body ul li i {
            color: var(--accent-color);
        }

        /* Footer Styles */
        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: #ffffff;
            padding: 4rem 0 0;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-logo svg,
        .footer-logo img {
            width: 40px !important;
            height: 40px !important;
            max-width: 40px !important;
            max-height: 40px !important;
            object-fit: contain;
        }

        .footer-description {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .footer-contact li {
            list-style: none;
            margin-bottom: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-contact li i {
            color: var(--accent-color);
            margin-top: 4px;
        }

        .social-links {
            display: flex;
            gap: 10px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--accent-color);
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 3rem;
            padding: 1.5rem 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom p {
            margin: 0;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            margin-left: 1.5rem;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #ffffff;
        }

        /* Lab Name Animation */
        .lab-name-display {
            font-weight: 600;
            color: var(--accent-color);
            transition: opacity 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.8rem;
            }

            .carousel-item img {
                height: 300px;
            }

            .carousel-control-prev,
            .carousel-control-next {
                width: 40px;
                height: 40px;
            }

            .carousel-control-prev {
                left: 10px;
            }

            .carousel-control-next {
                right: 10px;
            }

            .footer-links {
                margin-top: 1rem;
            }

            .footer-links a {
                margin-left: 0;
                margin-right: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../../public/img/logo-iclabs.png" alt="ICLABS Logo">
                ICLABS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Laboratorium</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Sarana dan Prasarana Laboratorium</h1>
            <p class="hero-subtitle">Integrated Computer Laboratories - <span class="lab-name-display" id="currentLabName">Laboratorium Internet of Things</span></p>
            
            <div class="lab-carousel">
                <div id="labCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                    <div class="carousel-inner">
                        <!-- Slide 1: Internet of Things -->
                        <div class="carousel-item active" data-lab-name="Laboratorium Internet of Things" data-lab-description="Laboratorium Internet of Things adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200" alt="Lab IoT" class="d-block w-100">
                        </div>
                        <!-- Slide 2: Computer Networking -->
                        <div class="carousel-item" data-lab-name="Laboratorium Computer Networking" data-lab-description="Laboratorium Computer Networking adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran jaringan komputer. Laboratorium ini dilengkapi dengan 36 set komputer dan peralatan jaringan yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=1200" alt="Lab Networking" class="d-block w-100">
                        </div>
                        <!-- Slide 3: Computer Vision -->
                        <div class="carousel-item" data-lab-name="Laboratorium Computer Vision" data-lab-description="Laboratorium Computer Vision adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran pengolahan citra dan visi komputer. Laboratorium ini dilengkapi dengan 36 set komputer berkemampuan tinggi yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=1200" alt="Lab Computer Vision" class="d-block w-100">
                        </div>
                        <!-- Slide 4: Multimedia -->
                        <div class="carousel-item" data-lab-name="Laboratorium Multimedia" data-lab-description="Laboratorium Multimedia adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran multimedia dan desain grafis. Laboratorium ini dilengkapi dengan 36 set komputer dan peralatan multimedia yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1593062096033-9a26b09da705?w=1200" alt="Lab Multimedia" class="d-block w-100">
                        </div>
                        <!-- Slide 5: StartUp -->
                        <div class="carousel-item" data-lab-name="Laboratorium StartUp" data-lab-description="Laboratorium StartUp adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran kewirausahaan teknologi. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200" alt="Lab StartUp" class="d-block w-100">
                        </div>
                        <!-- Slide 6: Data Science -->
                        <div class="carousel-item" data-lab-name="Laboratorium Data Science" data-lab-description="Laboratorium Data Science adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=1200" alt="Lab Data Science" class="d-block w-100">
                        </div>
                        <!-- Slide 7: Microcontroller -->
                        <div class="carousel-item" data-lab-name="Laboratorium Microcontroller" data-lab-description="Laboratorium Microcontroller adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200" alt="Lab Microcontroller" class="d-block w-100">
                        </div>
                        <!-- Slide 8: Riset 1 -->
                        <div class="carousel-item" data-lab-name="Riset 1" data-lab-description="Riset 1 adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1200" alt="Riset 1" class="d-block w-100">
                        </div>
                        <!-- Slide 9: Riset 2 -->
                        <div class="carousel-item" data-lab-name="Riset 2" data-lab-description="Riset 2 adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.">
                            <img src="https://images.unsplash.com/photo-1462826303086-329426d1aef5?w=1200" alt="Riset 2" class="d-block w-100">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#labCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#labCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="content-section">
        <div class="container">
            <div class="lab-description">
                <h2 id="labTitle">Tentang Laboratorium Internet of Things</h2>
                <p id="labDesc">Laboratorium Internet of Things adalah fasilitas yang menyediakan layanan praktikum bagi mahasiswa untuk mendukung kegiatan pembelajaran. Laboratorium ini dilengkapi dengan 36 set komputer yang dirancang untuk mendukung proses praktikum mahasiswa secara optimal.</p>
                <p>Dengan teknologi terkini dan lingkungan yang kondusif, kami berkomitmen untuk memberikan pengalaman pembelajaran yang terbaik bagi seluruh mahasiswa.</p>
            </div>

            <!-- Accordion -->
            <div class="accordion" id="labAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hardwareCollapse">
                            <span class="icon"><i class="bi bi-display"></i></span>
                            Hardware
                        </button>
                    </h2>
                    <div id="hardwareCollapse" class="accordion-collapse collapse" data-bs-parent="#labAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> 36 Unit Komputer Desktop</li>
                                <li><i class="bi bi-check-circle-fill"></i> Processor Intel Core i7 Gen 12</li>
                                <li><i class="bi bi-check-circle-fill"></i> RAM 16GB DDR4</li>
                                <li><i class="bi bi-check-circle-fill"></i> SSD 512GB NVMe</li>
                                <li><i class="bi bi-check-circle-fill"></i> Monitor LED 24 inch Full HD</li>
                                <li><i class="bi bi-check-circle-fill"></i> Proyektor Full HD</li>
                                <li><i class="bi bi-check-circle-fill"></i> AC Split 2 PK (4 Unit)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#softwareCollapse">
                            <span class="icon"><i class="bi bi-gear-fill"></i></span>
                            Software
                        </button>
                    </h2>
                    <div id="softwareCollapse" class="accordion-collapse collapse" data-bs-parent="#labAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> Windows 11 Professional</li>
                                <li><i class="bi bi-check-circle-fill"></i> Microsoft Office 365</li>
                                <li><i class="bi bi-check-circle-fill"></i> Visual Studio Code</li>
                                <li><i class="bi bi-check-circle-fill"></i> Arduino IDE</li>
                                <li><i class="bi bi-check-circle-fill"></i> Python 3.x + Libraries</li>
                                <li><i class="bi bi-check-circle-fill"></i> XAMPP / LAMP Stack</li>
                                <li><i class="bi bi-check-circle-fill"></i> Node.js & NPM</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#fasilitasCollapse">
                            <span class="icon"><i class="bi bi-stars"></i></span>
                            Fasilitas Lain
                        </button>
                    </h2>
                    <div id="fasilitasCollapse" class="accordion-collapse collapse" data-bs-parent="#labAccordion">
                        <div class="accordion-body">
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> WiFi High Speed</li>
                                <li><i class="bi bi-check-circle-fill"></i> Ruangan Full AC</li>
                                <li><i class="bi bi-check-circle-fill"></i> Meja dan Kursi Ergonomis</li>
                                <li><i class="bi bi-check-circle-fill"></i> Whiteboard</li>
                                <li><i class="bi bi-check-circle-fill"></i> Sound System</li>
                                <li><i class="bi bi-check-circle-fill"></i> CCTV 24 Jam</li>
                                <li><i class="bi bi-check-circle-fill"></i> Fire Extinguisher</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <img src="../../public/img/logo-iclabs.png" alt="ICLABS Logo">
                        ICLABS
                    </div>
                    <p class="footer-description">
                        Mitra terpercaya dalam dunia pendidikan dan penelitian. Kami menyediakan ruang untuk inovasi dan pembelajaran tanpa batas.
                    </p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Kontak Kami</h5>
                    <ul class="footer-contact p-0">
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jl. Pendidikan No. 123, Jakarta Selatan, Indonesia</span>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <span>info@iclabs.ac.id</span>
                        </li>
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <span>+62 21 1234 5678</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Ikuti Kami</h5>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p>&copy; <?php echo date('Y'); ?> ICLABS. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end footer-links">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for Dynamic Content -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const carousel = document.getElementById('labCarousel');
            const labNameDisplay = document.getElementById('currentLabName');
            const labTitle = document.getElementById('labTitle');
            const labDesc = document.getElementById('labDesc');

            // Function to update content based on current slide
            function updateLabContent(item) {
                const labName = item.getAttribute('data-lab-name');
                const labDescription = item.getAttribute('data-lab-description');

                // Update subtitle with animation
                labNameDisplay.style.opacity = '0';
                setTimeout(() => {
                    labNameDisplay.textContent = labName;
                    labNameDisplay.style.opacity = '1';
                }, 150);

                // Update content section
                labTitle.textContent = 'Tentang ' + labName;
                labDesc.textContent = labDescription;
            }

            // Listen for carousel slide events
            carousel.addEventListener('slid.bs.carousel', function(event) {
                const activeItem = event.relatedTarget;
                updateLabContent(activeItem);
            });

            // Touch/Swipe support is built into Bootstrap 5 by default
            // Enable touch swiping for better mobile experience
            const carouselInstance = new bootstrap.Carousel(carousel, {
                interval: 5000,
                touch: true,
                wrap: true
            });
        });
    </script>
</body>
</html>
