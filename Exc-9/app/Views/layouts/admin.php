<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin Panel – ShopEase') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="d-flex min-vh-100">
        <!-- Admin Sidebar -->
        <aside class="bg-dark text-white d-flex flex-column p-3" style="width: 260px; flex-shrink: 0;">
            <a href="<?= site_url('admin') ?>" class="d-flex align-items-center gap-2 text-white text-decoration-none mb-4 px-2 pt-1">
                <span class="se-brand-icon"><i class="bi bi-shield-check"></i></span>
                <div>
                    <div class="fw-bold lh-1">ShopEase</div>
                    <small class="text-info" style="font-size: 0.72rem;">ADMINISTRATOR</small>
                </div>
            </a>

            <ul class="nav nav-pills flex-column gap-1 mb-auto">
                <li class="nav-item">
                    <a href="<?= site_url('admin') ?>" class="nav-link text-white <?= uri_string() === 'admin' ? 'active bg-primary' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/pesanan') ?>" class="nav-link text-white <?= str_starts_with(uri_string(), 'admin/pesanan') ? 'active bg-primary' : '' ?>">
                        <i class="bi bi-receipt-cutoff me-2"></i>Pesanan &amp; Verifikasi
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/produk') ?>" class="nav-link text-white <?= str_starts_with(uri_string(), 'admin/produk') ? 'active bg-primary' : '' ?>">
                        <i class="bi bi-box-seam me-2"></i>Kelola Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= site_url('admin/kategori') ?>" class="nav-link text-white <?= str_starts_with(uri_string(), 'admin/kategori') ? 'active bg-primary' : '' ?>">
                        <i class="bi bi-tags me-2"></i>Kelola Kategori
                    </a>
                </li>
                <li class="nav-item mt-3 pt-3 border-top border-secondary">
                    <a href="<?= site_url('/') ?>" class="nav-link text-white-50">
                        <i class="bi bi-shop me-2"></i>Lihat Toko Publik
                    </a>
                </li>
            </ul>

            <div class="border-top border-secondary pt-3 mt-3 px-2">
                <div class="small fw-semibold"><?= esc((string) session()->get('name')) ?></div>
                <div class="small text-white-50 mb-2"><?= esc((string) session()->get('email')) ?></div>
                <form action="<?= site_url('logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 fw-semibold">
                        <i class="bi bi-box-arrow-right me-1"></i>Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-grow-1 d-flex flex-column">
            <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                <h1 class="h5 fw-bold mb-0"><?= esc($title ?? 'Admin Panel') ?></h1>
                <span class="badge bg-primary-subtle text-primary px-3 py-2">Role: Administrator</span>
            </header>

            <main class="p-4 flex-grow-1">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?= esc((string) session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc((string) session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
