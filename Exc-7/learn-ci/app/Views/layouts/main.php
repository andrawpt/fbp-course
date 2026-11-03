<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Latihan CodeIgniter 4') ?></title>

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100 bg-light">

    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom sticky-top" style="background-color: #0f172a !important; border-color: #1e293b !important;">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= site_url('dashboard') ?>">
                <i class="bi bi-mortarboard fs-4"></i> SIAKAD
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() === 'dashboard' ? 'active fw-semibold' : '' ?>" href="<?= site_url('dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() === 'about' ? 'active fw-semibold' : '' ?>" href="<?= site_url('about') ?>">
                            <i class="bi bi-info-circle me-1"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() === 'profile' ? 'active fw-semibold' : '' ?>" href="<?= site_url('profile') ?>">
                            <i class="bi bi-person me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() === 'contact' ? 'active fw-semibold' : '' ?>" href="<?= site_url('contact') ?>">
                            <i class="bi bi-envelope me-1"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    
    <main class="flex-shrink-0 py-4">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    
    <footer class="footer mt-auto py-3 bg-white border-top text-center text-muted">
        <div class="container">
            <span class="small">&copy; <?= date('Y') ?> Latihan CodeIgniter 4 &bull; Dibuat dengan Bootstrap 5</span>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
