<?php 
    include '../../components/header.php'; 
?>

<div class="container-fluid text-white d-flex align-items-center justify-content-center" 
     style="background: linear-gradient(135deg, #4c75ceff 0%, #004d81ff 100%); 
            height: 320px; 
            margin-top: -20px; 
            position: relative; 
            overflow: hidden;">
    
    <div style="position: absolute; left: -50px; top: 20%; opacity: 0.15; z-index: 0;">
        <svg width="200" height="200" viewBox="0 0 100 100" fill="#BFDBFE">
            <path d="M25 5 L75 5 L100 50 L75 95 L25 95 L0 50 Z" />
        </svg>
    </div>
    <div style="position: absolute; left: 50px; top: 50%; opacity: 0.1; z-index: 0;">
        <svg width="120" height="120" viewBox="0 0 100 100" fill="#BFDBFE">
            <path d="M25 5 L75 5 L100 50 L75 95 L25 95 L0 50 Z" />
        </svg>
    </div>

    <div style="position: absolute; right: -30px; top: 10%; opacity: 0.2; z-index: 0;">
        <svg width="250" height="250" viewBox="0 0 100 100" fill="#BFDBFE">
            <path d="M25 5 L75 5 L100 50 L75 95 L25 95 L0 50 Z" />
        </svg>
    </div>
    <div style="position: absolute; right: 100px; bottom: 10%; opacity: 0.1; z-index: 0;">
        <svg width="150" height="150" viewBox="0 0 100 100" fill="#BFDBFE">
            <path d="M25 5 L75 5 L100 50 L75 95 L25 95 L0 50 Z" />
        </svg>
    </div>

    <h1 class="fw-bold" style="font-size: 3.5rem; letter-spacing: 2px; z-index: 1; text-shadow: 0px 4px 10px rgba(0,0,0,0.2);">
        Kontak Laboratorium
    </h1>
</div>

<div class="container my-5 py-4">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #0F172A; font-size: 2.5rem;">Kontak</h2>
    </div>

    <div class="row g-5">
        <div class="col-lg-5">
            <div class="mb-5">
                <h4 class="fw-bold mb-3" style="color: #1E293B;">Alamat</h4>
                <div class="text-secondary" style="line-height: 1.8; font-size: 1.05rem;">
                    <p class="mb-0 fw-bold text-dark">Fakultas Ilmu Komputer UMI</p>
                    <p class="mb-0">Jl. Inspeksi Kanal No.2, Panaikang</p>
                    <p class="mb-0">Kec. Panakkukang, Kota Makassar</p>
                    <p>Sulawesi Selatan 90231</p>
                </div>
            </div>

            <div>
                <h4 class="fw-bold mb-3" style="color: #1E293B;">Kontak</h4>
                <div class="text-secondary" style="font-size: 1.05rem;">
                    <p class="mb-2">Email: <span class="text-dark">fikom.iclabs@umi.ac.id</span></p>
                    <p class="mb-2">Telepon: <span class="text-dark">+62 821-4533-7413</span></p>
                    <p>Instagram: <a href="https://www.instagram.com/labfikomumi" target="_blank" class="text-primary text-decoration-none">@labfikomumi</a></p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="rounded-4 overflow-hidden border shadow-sm">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.818290458622!2d119.44635217587654!3d-5.136129394841103!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2db9fd3365008369%3A0x7af76b8baf265f2b!2sFakultas%20Ilmu%20Komputer%20UMI!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" 
                    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>

<?php 
    include '../../components/footer.php'; 
?>