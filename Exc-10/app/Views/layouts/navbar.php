<header class="se-navbar sticky-top">
    <nav class="navbar navbar-expand-lg py-3" aria-label="Navigasi utama ShopEase">
        <div class="container">
            <a class="navbar-brand se-brand d-flex align-items-center gap-2" href="<?= site_url('/') ?>">
                <span class="se-brand-icon">
                    <i class="bi bi-bag-check-fill"></i>
                </span>
                <span>ShopEase</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarShopEase" aria-controls="navbarShopEase" aria-expanded="false" aria-label="Buka menu navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarShopEase">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-lg-1">
                    <li class="nav-item">
                        <a class="nav-link fw-semibold active" href="<?= site_url('/') ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#kategori">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#produk-unggulan">Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="#cara-belanja">Cara Belanja</a>
                    </li>
                </ul>

                <form class="d-flex me-lg-3 my-2 my-lg-0" role="search" action="<?= site_url('/') ?>#produk-unggulan" method="get">
                    <div class="input-group">
                        <input
                            type="search"
                            name="q"
                            class="form-control se-search-input"
                            placeholder="Cari produk atau kategori..."
                            aria-label="Cari produk"
                            value="<?= esc($keyword ?? '') ?>"
                        >
                        <?php if (!empty($selectedCategory)): ?>
                            <input type="hidden" name="kategori" value="<?= esc($selectedCategory) ?>">
                        <?php endif; ?>
                        <button class="btn btn-primary se-search-btn" type="submit" aria-label="Cari">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                    <a
                        href="<?= site_url('keranjang') ?>"
                        class="btn btn-outline-secondary position-relative"
                        aria-label="Keranjang belanja"
                    >
                        <i class="bi bi-cart3"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" id="cartBadgeCount">
                            <?= esc((string) ($cartCount ?? 0)) ?>
                        </span>
                    </a>

                    <?php if (session()->get('isLoggedIn')): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle fw-semibold d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                <span><?= esc((string) session()->get('name')) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li class="px-3 py-2 small text-muted border-bottom">
                                    <div class="fw-semibold text-dark"><?= esc((string) session()->get('email')) ?></div>
                                    <span class="badge bg-primary-subtle text-primary text-uppercase mt-1">
                                        <?= esc((string) session()->get('role')) ?>
                                    </span>
                                </li>
                                <li>
                                    <?php if (session()->get('role') === 'admin'): ?>
                                        <a class="dropdown-item py-2 fw-semibold" href="<?= site_url('admin') ?>">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i>Panel Admin
                                        </a>
                                    <?php elseif (session()->get('role') === 'operator'): ?>
                                        <a class="dropdown-item py-2 fw-semibold" href="<?= site_url('operator') ?>">
                                            <i class="bi bi-truck me-2 text-success"></i>Panel Operator
                                        </a>
                                    <?php else: ?>
                                        <a class="dropdown-item py-2" href="<?= site_url('akun') ?>">
                                            <i class="bi bi-person-badge me-2 text-primary"></i>Dashboard &amp; Profil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= site_url('keranjang') ?>">
                                            <i class="bi bi-cart3 me-2 text-primary"></i>Keranjang Belanja
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="<?= site_url('pesanan') ?>">
                                            <i class="bi bi-receipt me-2 text-primary"></i>Riwayat Pesanan
                                        </a>
                                    <?php endif; ?>
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="<?= site_url('logout') ?>" method="post" class="m-0">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="dropdown-item text-danger fw-semibold py-2">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= site_url('login') ?>" class="btn btn-outline-primary fw-semibold">
                            Masuk
                        </a>
                        <a href="<?= site_url('register') ?>" class="btn btn-primary fw-semibold">
                            Daftar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>
