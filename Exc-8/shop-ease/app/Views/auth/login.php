<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="se-brand-icon mb-3">
                            <i class="bi bi-person-lock"></i>
                        </span>
                        <h1 class="h4 fw-bold mb-1">Masuk ke ShopEase</h1>
                        <p class="text-muted small mb-0">
                            Masuk untuk mengelola keranjang, checkout, dan memantau pesanan Anda.
                        </p>
                    </div>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show small" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i><?= esc((string) session()->getFlashdata('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc((string) session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                        </div>
                    <?php endif; ?>

                    <?php $errors = session()->getFlashdata('errors') ?? []; ?>
                    <?php if (! empty($errors)): ?>
                        <div class="alert alert-danger small" role="alert">
                            <ul class="mb-0 ps-3">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= esc((string) $error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('login') ?>" method="post" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    value="<?= esc((string) old('email')) ?>"
                                    placeholder="nama@email.com"
                                    required
                                    autofocus
                                >
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold small">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Tampilkan atau sembunyikan password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </button>
                        </div>
                    </form>

                    <div class="text-center small text-muted">
                        Belum punya akun?
                        <a href="<?= site_url('register') ?>" class="fw-semibold text-decoration-none">Daftar sekarang</a>
                    </div>
                </div>

                <!-- Informasi Akun Demo (Seeder) -->
                <div class="card border-0 bg-white shadow-sm rounded-4 p-3 mt-3">
                    <div class="small fw-bold text-primary mb-2">
                        <i class="bi bi-info-circle me-1"></i>Akun Demo (Seeder)
                    </div>
                    <div class="small text-muted d-grid gap-1">
                        <div><strong>Customer:</strong> <code>customer@shopease.local</code> / <code>Customer123!</code></div>
                        <div><strong>Admin:</strong> <code>admin@shopease.local</code> / <code>Admin123!</code></div>
                        <div><strong>Operator:</strong> <code>operator@shopease.local</code> / <code>Operator123!</code></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                toggleBtn.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
            });
        }
    });
</script>
<?= $this->endSection() ?>
