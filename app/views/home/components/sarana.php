<!-- Sarana dan Prasarana Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4 section-title">
                    Sarana dan Prasarana<br>Laboratorium
                </h2>
                <p class="text-muted mb-4">
                    Laboratorium kami dilengkapi dengan fasilitas modern termasuk 50 unit komputer dengan spesifikasi
                    terkini, proyektor interaktif, sistem audio yang canggih, dan kapasitas ruangan untuk 60 orang.
                    Semua peralatan dirawat secara berkala untuk memastikan kenyamanan pembelajaran Anda.
                </p>

                <div class="mb-4">
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill me-3 mt-1 icon-check"></i>
                        <div>
                            <h6 class="fw-semibold mb-1">50 Unit Workstation High-End</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill me-3 mt-1 icon-check"></i>
                        <div>
                            <h6 class="fw-semibold mb-1">Intermet Kecepatan Tinggi (100mbps)</h6>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="bi bi-check-circle-fill me-3 mt-1 icon-check"></i>
                        <div>
                            <h6 class="fw-semibold mb-1">Ruang Ber-AC & Nyaman</h6>
                        </div>
                    </div>
                </div>

                <a href="https://iclabs.fikom.umi.ac.id/laboratorium/sarana-dan-prasarana"
                    class="btn btn-outline-custom px-4 py-2 rounded-pill btn-hover-effect">
                    Lihat Selengkapnya
                </a>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <!-- Fixed Path: Relative to app/views/index.php if included there, but should check if this needs adjustment. 
                         Since home.php is included in app/views/index.php, and this is included in home.php, relative paths should ideally be from the entry point or absolute.
                         The original code had ../../public/img/Mulmed.jpg which is relative to app/views/pages/home/home.php location IF accessing directly, 
                         BUT home.php is included in app/views/index.php
                         Wait, let's check where app/views/index.php is.
                         c:\xampp\htdocs\TUBES_CA\app\views\index.php
                         c:\xampp\htdocs\TUBES_CA\public\img\Mulmed.jpg
                         From app/views/index.php, public is ../../public
                         The original code had ../../public/img/Mulmed.jpg SAME as I saw in view_file.
                         So I will keep it as is.
                     -->
                    <div class="position-relative">
                        <img src="<?= BASE_URL ?>/img/Mulmed.jpg" alt="Laboratorium Multimedia ICLABS"
                            class="img-fluid rounded-4 shadow-lg img-sarana">
                    </div>
                </div>
            </div>
        </div>
</section>