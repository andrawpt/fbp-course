<?php
helper('url');
$cart = session()->get('cart') ?? [];
$cartCount = array_sum(array_column($cart, 'qty'));
?>
<!DOCTYPE html>
<html lang="id" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Online Shop Minimalist') ?></title>

    
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
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-light);
            color: #0f172a;
        }

        
        .navbar-shop {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--dark-color) !important;
        }

        
        .btn-dark {
            background-color: var(--dark-color);
            border-color: var(--dark-color);
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-dark:hover {
            background-color: #1e293b;
            border-color: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
        }

        .btn-outline-dark {
            border-color: #cbd5e1;
            border-radius: 8px;
            color: var(--dark-color);
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .btn-outline-dark:hover {
            background-color: var(--dark-color);
            border-color: var(--dark-color);
            color: #ffffff;
            transform: translateY(-2px);
        }

        
        .card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: #ffffff;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card-product:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -6px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        
        @keyframes fadeInSlide {
            0% {
                opacity: 0;
                transform: translateY(14px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main-content {
            animation: fadeInSlide 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .badge-cart {
            font-size: 0.72rem;
            padding: 0.25em 0.55em;
            border-radius: 9999px;
        }

        footer {
            background-color: #ffffff;
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>

<body class="d-flex flex-column h-100">

    
    <nav class="navbar navbar-expand-lg navbar-shop sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
                <i class="fas fa-bag-shopping fs-4"></i>
                <span>ONLINE<strong>SHOP</strong></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarShop">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarShop">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link <?= uri_string() === '' || uri_string() === 'katalog' ? 'fw-bold text-dark' : 'text-muted' ?>" href="<?= site_url('/') ?>">
                            Katalog Produk
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    
                    <a href="<?= site_url('cart') ?>" class="btn btn-outline-dark position-relative py-2 px-3">
                        <i class="fas fa-cart-shopping me-1"></i> Keranjang
                        <?php if ($cartCount > 0) : ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge bg-dark badge-cart">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    
                    <?php if (session()->get('isLoggedIn')) : ?>
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle py-2 px-3" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= esc(session()->get('nama_lengkap')) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border: 1px solid var(--border-color) !important; border-radius: 10px;">
                                <?php if (session()->get('role') === 'admin') : ?>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= site_url('admin/dashboard') ?>">
                                            <i class="fas fa-gauge-high me-2 text-muted"></i> Panel Admin
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item py-2" href="<?= site_url('riwayat-pesanan') ?>">
                                        <i class="fas fa-clock-rotate-left me-2 text-muted"></i> Riwayat Pesanan
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="<?= site_url('logout') ?>">
                                        <i class="fas fa-arrow-right-from-bracket me-2"></i> Keluar
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?= site_url('login') ?>" class="btn btn-dark py-2 px-3">
                            <i class="fas fa-arrow-right-to-bracket me-1"></i> Masuk
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    
    <main class="flex-shrink-0 py-4 main-content">
        <div class="container">
            
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
        </div>
    </main>

    
    <footer class="footer mt-auto py-4 text-center text-muted">
        <div class="container small">
            <span>&copy; <?= date('Y') ?> <strong>ONLINE SHOP</strong> &bull; Minimalist Clean Interface</span>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

