<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
  <div class="container">

    <a class="navbar-brand fw-bold" href="<?= BASE_URL; ?>">
      ICLABS
    </a>

    <!-- Menu di sebelah kanan -->
    <div class="ms-auto">
      <a href="<?= BASE_URL; ?>/external/profile" class="btn btn-sm btn-outline-primary me-2">
          <i class="bi bi-person-circle me-1"></i> Profil Saya
      </a>
      <a href="<?= BASE_URL; ?>auth/logout" class="btn btn-danger rounded-pill px-4 fw-bold" style="font-size: 0.9rem;">
        Sign Out
      </a>
    </div>
  </div>
</nav>