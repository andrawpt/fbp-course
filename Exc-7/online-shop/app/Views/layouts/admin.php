<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Admin Panel - Online Shop') ?></title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --dark-color: #0f172a;
            --border-color: #e2e8f0;
            --bg-light: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: #0f172a;
        }

        
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #0f172a;
            color: #ffffff;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
        }

        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-column: column;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            font-weight: 500;
            padding: 0.8rem 1.25rem;
            margin: 0.2rem 1rem;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.15);
            font-weight: 600;
        }

        
        .topbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 2rem;
        }

        
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.08);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-dark {
            background-color: #0f172a;
            border-color: #0f172a;
        }

        .btn-dark:hover {
            background-color: #1e293b;
            transform: translateY(-2px);
        }

        .content-area {
            animation: fadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    
    <aside class="sidebar d-flex flex-column p-3">
        <a href="<?= site_url('admin/dashboard') ?>" class="d-flex align-items-center gap-2 text-white text-decoration-none px-3 py-3 mb-3 border-bottom border-secondary">
            <i class="fas fa-bag-shopping fs-4"></i>
            <span class="fs-5 fw-bold">ADMIN SHOP</span>
        </a>

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="<?= site_url('admin/dashboard') ?>" class="nav-link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/products') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/products') ? 'active' : '' ?>">
                    <i class="fas fa-box-open me-2"></i> Kelola Produk
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('admin/orders') ?>" class="nav-link <?= str_starts_with(uri_string(), 'admin/orders') ? 'active' : '' ?>">
                    <i class="fas fa-receipt me-2"></i> Pesanan Masuk
                </a>
            </li>
            <hr class="border-secondary my-3">
            <li class="nav-item">
                <a href="<?= site_url('/') ?>" class="nav-link text-white" target="_blank">
                    <i class="fas fa-store me-2"></i> Lihat Toko &rarr;
                </a>
            </li>
        </ul>

        <div class="border-top border-secondary pt-3 px-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <strong class="d-block text-white small"><?= esc(session()->get('nama_lengkap') ?? 'Admin') ?></strong>
                    <span class="badge bg-secondary text-white" style="font-size: 0.65rem;">ADMINISTRATOR</span>
                </div>
                <a href="<?= site_url('logout') ?>" class="text-white opacity-75" title="Logout">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </div>
    </aside>

    
    <div class="main-wrapper d-flex flex-column">
        
        <header class="topbar d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-dark mb-0"><?= esc($title ?? 'Panel Admin') ?></h5>
            <div class="d-flex align-items-center gap-2">
                <a href="<?= site_url('/') ?>" class="btn btn-sm btn-outline-dark">
                    <i class="fas fa-eye me-1"></i> Mode Pembeli
                </a>
                <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-dark">
                    Keluar
                </a>
            </div>
        </header>

        
        <main class="p-4 flex-grow-1 content-area">
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-dark bg-white border border-dark alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-circle-check text-dark me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-dark bg-white border border-danger text-danger alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 10px;">
                    <i class="fas fa-circle-exclamation me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <footer class="p-3 text-center text-muted small bg-white border-top">
            &copy; <?= date('Y') ?> Online Shop Admin &bull; Minimalist Clean Interface
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

