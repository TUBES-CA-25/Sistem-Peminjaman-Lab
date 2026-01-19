<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | Peminjaman Lab</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --primary:#3b82f6;
    --secondary:#22d3ee;
    --dark:#020617;
}

/* ================= PAGE ================= */
body{
    margin:0;
    font-family:system-ui,-apple-system,Segoe UI,Roboto;
    background:#020617;
}

/* ================= WRAPPER ================= */
.auth-wrapper{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:40px;
    background:linear-gradient(120deg,#020617,#0f172a,#1e3a8a);
}

/* ================= CARD ================= */
.auth-box{
    max-width:1050px;
    width:100%;
    display:grid;
    grid-template-columns:1.1fr 1fr;
    background:rgba(255,255,255,.05);
    backdrop-filter:blur(26px);
    border-radius:32px;
    overflow:hidden;
    box-shadow:0 40px 80px rgba(0,0,0,.7);
}

/* ================= IMAGE SIDE ================= */
.auth-image{
    position:relative;
    overflow:hidden;
    perspective:1400px; /* MAKIN BESAR = 3D MAKIN TERASA */
}

.auth-image img.bg{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* OVERLAY GELAP */
.auth-image::after{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(
        to bottom,
        rgba(2,6,23,.70),
        rgba(2,6,23,.97)
    );
}

/* ================= LOGO 3D CENTER (SUPER STRONG) ================= */
.logo-3d{
    position:absolute;
    top:50%;
    left:50%;
    transform-style:preserve-3d;
    transform:translate(-50%,-50%) translateZ(120px);
    z-index:5;
    animation:logoFloatStrong 6s ease-in-out infinite;
}

.logo-3d img{
    width:240px;                 /* 🔥 LEBIH BESAR */
    max-width:75%;
    filter:
        drop-shadow(0 40px 80px rgba(0,0,0,.9))
        drop-shadow(0 0 45px rgba(59,130,246,.65))
        drop-shadow(0 0 80px rgba(34,211,238,.45));
    animation:logoGlowStrong 3s ease-in-out infinite;
}

/* FLOATING 3D KUAT */
@keyframes logoFloatStrong{
    0%,100%{
        transform:
            translate(-50%,-50%)
            translateZ(120px)
            rotateX(0deg)
            rotateY(0deg);
    }
    50%{
        transform:
            translate(-50%,-50%)
            translateZ(180px)
            rotateX(18deg)
            rotateY(-18deg);
    }
}

/* GLOW + DEPTH KUAT */
@keyframes logoGlowStrong{
    0%,100%{
        filter:
            drop-shadow(0 40px 80px rgba(0,0,0,.9))
            drop-shadow(0 0 35px rgba(59,130,246,.55))
            drop-shadow(0 0 70px rgba(34,211,238,.35));
    }
    50%{
        filter:
            drop-shadow(0 60px 120px rgba(0,0,0,1))
            drop-shadow(0 0 60px rgba(59,130,246,.85))
            drop-shadow(0 0 110px rgba(34,211,238,.6));
    }
}

/* ================= CAPTION ================= */
.image-caption{
    position:absolute;
    bottom:36px;
    left:36px;
    z-index:3;
    color:white;
}

.image-caption h3{
    font-weight:900;
}

.image-caption p{
    opacity:.85;
}

/* ================= FORM SIDE ================= */
.auth-form{
    padding:70px 60px;
    color:white;
}

.auth-form h2{
    font-weight:900;
}

.form-control{
    background:rgba(255,255,255,.12)!important;
    border:1px solid rgba(255,255,255,.18)!important;
    color:white!important;
    border-radius:16px;
    padding:14px 18px;
}

.form-control::placeholder{
    color:rgba(255,255,255,.4);
}

.btn-login{
    background:linear-gradient(135deg,var(--primary),var(--secondary));
    border:none;
    color:white;
    font-weight:800;
    border-radius:16px;
    padding:14px;
}

.btn-login:hover{
    transform:translateY(-2px);
    box-shadow:0 20px 40px rgba(59,130,246,.45);
}

/* ================= RESPONSIVE ================= */
@media(max-width:900px){
    .auth-box{
        grid-template-columns:1fr;
    }
    .auth-image{
        display:none;
    }
}
</style>
</head>

<body>

<div class="auth-wrapper">
    <div class="auth-box">

        <!-- IMAGE SIDE -->
        <div class="auth-image">
            <img src="<?= BASE_URL ?>/storage/Laboratorium.jpg" class="bg" alt="Laboratorium">

            <!-- LOGO 3D SUPER CENTER -->
            <div class="logo-3d">
                <img src="<?= BASE_URL ?>/storage/logo.png" alt="Logo">
            </div>

            <div class="image-caption">
                <h3>Peminjaman Laboratorium</h3>
                <p>Sistem reservasi & manajemen ruang praktikum</p>
            </div>
        </div>

        <!-- FORM SIDE -->
        <div class="auth-form">
            <h2>Masuk ke Akun</h2>
            <p class="text-white-50 mb-4">
                Silakan masuk untuk mengelola peminjaman laboratorium
            </p>

            <form method="post" action="<?= BASE_URL ?>/auth/login">
                <div class="mb-3">
                    <label class="small text-white-50">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com">
                </div>

                <div class="mb-2">
                    <label class="small text-white-50">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                </div>

                <div class="text-end mb-4">
                    <a href="<?= BASE_URL ?>/auth/forgot" class="small text-white-50">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-login w-100">Masuk</button>
            </form>

            <p class="small mt-4 text-white-50">
                Belum punya akun?
                <a href="<?= BASE_URL ?>/auth/register" class="text-white fw-bold">Daftar di sini</a>
            </p>
        </div>

    </div>
</div>

</body>
</html>
