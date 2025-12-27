<?php include '../components/header.php'; ?>

<main>
<div class="auth-card text-center">
    <h3>Masuk ke Akun</h3>
    <p>Peminjaman Lab</p>
    <p>Silakan masuk untuk melanjutkan akses laboratorium</p>

    <form method="post" action="#">
        <div class="mb-3 text-start">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="nama@email.com">
        </div>

        <div class="mb-2 text-start">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" placeholder="Masukkan password Anda">
        </div>

        <div class="text-end mb-3">
            <a href="#" class="auth-link" style="font-size: 13px;">Lupa Password?</a>
        </div>

        <button type="submit" class="btn btn-auth w-100 mb-3">Masuk</button>
    </form>

    <hr class="text-white">

    <p style="font-size: 14px;">
        Belum punya akun?
        <a href="register.php" class="auth-link">Daftar di sini</a>
    </p>
</div>
</main>
<style>
  html, body {
    height: 100%;
  }

  body {
    background: linear-gradient(100deg, #020617, #172554, #1e3a8a, #1d4ed8);
    display: flex;
    flex-direction: column;
    font-family: "Segoe UI", sans-serif;
  }

  main {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
  }

  .auth-card {
    width: 100%;
    max-width: 420px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border-radius: 16px;
    padding: 30px;
    color: #fff;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.3);
  }

  .auth-card h3 {
    font-weight: 600;
  }

  .auth-card p {
    font-size: 14px;
    color: #e0e0e0;
  }

  .form-control {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: #fff;
  }

  .form-control::placeholder {
    color: #d0d0d0;
  }

  .form-control:focus {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
    box-shadow: none;
  }

  .btn-auth {
    background: #ffffff;
    color: #2c5364;
    font-weight: 600;
    border-radius: 8px;
  }

  .btn-auth:hover {
    background: #f1f1f1;
  }

  .auth-link {
    color: #a8d8ff;
    text-decoration: none;
    font-weight: 500;
  }

  .auth-link:hover {
    text-decoration: underline;
  }
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include '../components/footer.php'; ?>
