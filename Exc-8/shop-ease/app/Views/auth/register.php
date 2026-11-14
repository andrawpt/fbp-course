<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="se-brand-icon mb-3">
                            <i class="bi bi-person-plus-fill"></i>
                        </span>
                        <h1 class="h4 fw-bold mb-1">Daftar Akun Customer</h1>
                        <p class="text-muted small mb-0">
                            Buat akun ShopEase untuk mulai berbelanja, mengelola keranjang, dan melacak pengiriman.
                        </p>
                    </div>

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

                    <form action="<?= site_url('register') ?>" method="post" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    value="<?= esc((string) old('name')) ?>"
                                    placeholder="Contoh: Budi Santoso"
                                    required
                                    autofocus
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small">Alamat Email <span class="text-danger">*</span></label>
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
                                >
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold small">Nomor HP (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                <input
                                    type="tel"
                                    class="form-control"
                                    id="phone"
                                    name="phone"
                                    value="<?= esc((string) old('phone')) ?>"
                                    placeholder="0812xxxxxxxx"
                                >
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password"
                                        name="password"
                                        placeholder="Minimal 8 karakter"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirm" class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock-fill"></i></span>
                                    <input
                                        type="password"
                                        class="form-control"
                                        id="password_confirm"
                                        name="password_confirm"
                                        placeholder="Ulangi password"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-check2-circle me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>

                    <div class="text-center small text-muted">
                        Sudah punya akun?
                        <a href="<?= site_url('login') ?>" class="fw-semibold text-decoration-none">Masuk di sini</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
