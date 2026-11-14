<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success') || session()->getFlashdata('error')): ?>
    <div class="container pt-3">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= esc((string) session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc((string) session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Hero Banner Section -->
<section class="se-hero py-5 py-lg-6">
    <div class="container py-lg-4 position-relative" style="z-index: 1;">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-7">
                <h1 class="display-5 fw-bold mb-3 lh-sm">
                    Belanja Kebutuhan Harian Lebih Mudah, Cepat, dan Terpantau
                </h1>
                <p class="lead text-white-50 mb-4">
                    ShopEase menghadirkan katalog produk terpusat dengan informasi stok transparan, alur checkout terstruktur, verifikasi pembayaran aman, hingga pelacakan pengiriman dalam satu tempat.
                </p>
                <div class="d-flex flex-wrap gap-3 mb-4">
                    <a href="#produk-unggulan" class="btn btn-warning btn-lg fw-bold px-4 text-dark">
                        <i class="bi bi-bag me-2"></i>Belanja Sekarang
                    </a>
                    <a href="#kategori" class="btn btn-outline-light btn-lg fw-semibold px-4">
                        Lihat Kategori
                    </a>
                </div>
                <div class="row g-3 pt-2">
                    <div class="col-sm-4">
                        <div class="d-flex align-items-center gap-2 small text-white-75">
                            <i class="bi bi-check-circle-fill text-warning"></i>
                            <span>Stok Produk Terupdate</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex align-items-center gap-2 small text-white-75">
                            <i class="bi bi-shield-lock-fill text-warning"></i>
                            <span>Pembayaran Terverifikasi</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="d-flex align-items-center gap-2 small text-white-75">
                            <i class="bi bi-truck text-warning"></i>
                            <span>Lacak Status &amp; Resi</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="se-hero-card p-4 p-md-5 text-white">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-warning text-dark fw-bold px-3 py-2">Sorotan Katalog</span>
                        <span class="small text-white-50">Versi 1.0</span>
                    </div>
                    <h2 class="h4 fw-bold mb-3">Ringkasan Layanan ShopEase</h2>
                    <div class="d-grid gap-3">
                        <div class="d-flex align-items-start gap-3 bg-white bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-grid-fill fs-4 text-warning"></i>
                            <div>
                                <div class="fw-bold"><?= count($categories) ?> Kategori Aktif</div>
                                <div class="small text-white-50">Elektronik, Fashion, Aksesoris, hingga Kebutuhan Belajar</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 bg-white bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-receipt-cutoff fs-4 text-warning"></i>
                            <div>
                                <div class="fw-bold">Transaksi Terjamin</div>
                                <div class="small text-white-50">Validasi stok server-side &amp; snapshot harga otomatis</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 bg-white bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-box-seam-fill fs-4 text-warning"></i>
                            <div>
                                <div class="fw-bold">Pelacakan Pesanan</div>
                                <div class="small text-white-50">Status pembayaran &amp; pengiriman dipisah secara jelas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kategori Pilihan Section -->
<section id="kategori" class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="text-primary fw-bold text-uppercase small">Kategori Pilihan</span>
                <h2 class="h3 fw-bold mb-0 mt-1">Jelajahi Berdasarkan Kategori</h2>
            </div>
            <?php if (!empty($selectedCategory) || !empty($keyword)): ?>
                <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter
                </a>
            <?php endif; ?>
        </div>

        <div class="row g-3">
            <?php foreach ($categories as $category): ?>
                <?php $isActive = ($selectedCategory === $category['slug']); ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <a
                        href="<?= site_url('/') ?>?kategori=<?= urlencode($category['slug']) ?>#produk-unggulan"
                        class="se-category-card d-block p-3 text-center h-100 <?= $isActive ? 'active' : '' ?>"
                    >
                        <div class="se-category-icon mx-auto mb-3">
                            <i class="bi <?= esc($category['icon']) ?>"></i>
                        </div>
                        <h3 class="h6 fw-bold mb-1"><?= esc($category['name']) ?></h3>
                        <span class="small text-muted"><?= esc((string) $category['product_count']) ?> Produk</span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Produk Unggulan Section -->
<section id="produk-unggulan" class="py-5 se-section-bg">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-2">
            <div>
                <span class="text-primary fw-bold text-uppercase small">Katalog Produk</span>
                <h2 class="h3 fw-bold mb-0 mt-1">
                    <?php if (!empty($keyword)): ?>
                        Hasil Pencarian: "<?= esc($keyword) ?>"
                    <?php elseif (!empty($selectedCategory)): ?>
                        Kategori: <?= esc(ucwords(str_replace('-', ' ', $selectedCategory))) ?>
                    <?php else: ?>
                        Produk Unggulan
                    <?php endif; ?>
                </h2>
            </div>
            <span class="text-muted small">
                Menampilkan <strong><?= count($products) ?></strong> produk aktif
            </span>
        </div>

        <?php if (empty($products)): ?>
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="mb-3">
                    <i class="bi bi-search fs-1 text-muted"></i>
                </div>
                <h3 class="h5 fw-bold">Produk Tidak Ditemukan</h3>
                <p class="text-muted mb-3">
                    Maaf, tidak ada produk aktif yang sesuai dengan filter atau kata kunci Anda.
                </p>
                <div>
                    <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-primary">
                        Tampilkan Semua Produk
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <article class="se-product-card h-100 d-flex flex-column">
                            <div class="se-product-thumb">
                                <span class="badge bg-primary position-absolute top-0 start-0 m-3">
                                    <?= esc($product['badge']) ?>
                                </span>
                                <span class="badge bg-white text-dark border position-absolute top-0 end-0 m-3">
                                    Stok: <?= esc((string) $product['stock']) ?>
                                </span>
                                <i class="bi <?= esc($product['icon']) ?>" aria-hidden="true"></i>
                            </div>

                            <div class="p-3 p-md-4 d-flex flex-column flex-grow-1">
                                <div class="small text-primary fw-semibold mb-1">
                                    <?= esc($product['category_name']) ?>
                                </div>
                                <h3 class="h6 fw-bold mb-2">
                                    <?= esc($product['name']) ?>
                                </h3>
                                <p class="small text-muted mb-3 flex-grow-1">
                                    <?= esc($product['description']) ?>
                                </p>

                                <div class="se-product-price mb-3">
                                    Rp <?= number_format((float) $product['price'], 0, ',', '.') ?>
                                </div>

                                <div class="d-grid gap-2 d-sm-flex">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm flex-fill fw-semibold btn-product-detail"
                                        data-id="<?= esc((string) $product['id']) ?>"
                                        data-url="<?= site_url('produk/' . $product['slug']) ?>"
                                        data-name="<?= esc($product['name']) ?>"
                                        data-category="<?= esc($product['category_name']) ?>"
                                        data-price="Rp <?= number_format((float) $product['price'], 0, ',', '.') ?>"
                                        data-stock="<?= esc((string) $product['stock']) ?>"
                                        data-description="<?= esc($product['description']) ?>"
                                        data-icon="<?= esc($product['icon']) ?>"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productDetailModal"
                                    >
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </button>
                                    <form action="<?= site_url('keranjang/tambah') ?>" method="post" class="flex-fill d-grid">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="product_id" value="<?= esc((string) $product['id']) ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm fw-semibold" <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>>
                                            <i class="bi bi-cart-plus me-1"></i>Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Keunggulan & Alur Belanja Section -->
<section id="cara-belanja" class="py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 640px;">
            <span class="text-primary fw-bold text-uppercase small">Alur Pelanggan</span>
            <h2 class="h3 fw-bold mt-1 mb-2">Cara Belanja di ShopEase</h2>
            <p class="text-muted mb-0">
                Proses pemesanan dirancang terstruktur dari pemilihan produk hingga verifikasi pembayaran dan pengiriman.
            </p>
        </div>

        <div class="row g-4">
            <?php foreach ($steps as $step): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="se-step-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="se-step-number"><?= esc($step['number']) ?></span>
                            <i class="bi <?= esc($step['icon']) ?> fs-3 text-primary opacity-75"></i>
                        </div>
                        <h3 class="h6 fw-bold mb-2"><?= esc($step['title']) ?></h3>
                        <p class="small text-muted mb-0"><?= esc($step['desc']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Keunggulan Platform / CTA Banner -->
<section id="keunggulan" class="py-5 se-section-bg">
    <div class="container">
        <div class="card border-0 rounded-4 shadow-sm overflow-hidden bg-primary text-white">
            <div class="row align-items-center g-0">
                <div class="col-lg-8 p-4 p-md-5">
                    <span class="badge bg-warning text-dark fw-bold mb-3">Siap Mulai Berbelanja?</span>
                    <h2 class="h3 fw-bold mb-2">Daftar Akun Customer &amp; Nikmati Kemudahan Checkout</h2>
                    <p class="text-white-75 mb-0">
                        Kelola keranjang belanja, simpan alamat pengiriman, unggah bukti transfer manual, dan pantau riwayat pesanan Anda secara terpusat.
                    </p>
                </div>
                <div class="col-lg-4 p-4 p-md-5 text-lg-end">
                    <?php if (session()->get('is_logged_in')): ?>
                        <a href="#produk-unggulan" class="btn btn-light btn-lg fw-bold text-primary px-4">
                            <i class="bi bi-bag-check-fill me-2"></i>Jelajahi Katalog
                        </a>
                    <?php else: ?>
                        <a href="<?= site_url('register') ?>" class="btn btn-light btn-lg fw-bold text-primary px-4">
                            <i class="bi bi-person-plus-fill me-2"></i>Buat Akun Gratis
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail Produk (Quick View) -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <span class="badge bg-primary-subtle text-primary fw-semibold" id="modalProductCategory">Kategori</span>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body pt-3">
                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center mb-3" style="height: 160px;">
                    <i class="bi bi-box fs-1 text-primary" id="modalProductIcon"></i>
                </div>
                <h5 class="fw-bold mb-1" id="modalProductName">Nama Produk</h5>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="fs-5 fw-bold text-primary" id="modalProductPrice">Rp 0</span>
                    <span class="badge bg-success-subtle text-success" id="modalProductStock">Stok: 0</span>
                </div>
                <p class="text-muted small mb-0" id="modalProductDescription">Deskripsi produk.</p>
            </div>
            <div class="modal-footer border-top-0 pt-0 d-flex justify-content-between">
                <a href="#" id="modalProductPageLink" class="btn btn-outline-secondary btn-sm">
                    Halaman Lengkap
                </a>
                <form action="<?= site_url('keranjang/tambah') ?>" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" id="modalProductIdInput" value="">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary fw-semibold">
                        <i class="bi bi-cart-plus me-1"></i>Tambah ke Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const detailButtons = document.querySelectorAll('.btn-product-detail');
        const modalName = document.getElementById('modalProductName');
        const modalCategory = document.getElementById('modalProductCategory');
        const modalPrice = document.getElementById('modalProductPrice');
        const modalStock = document.getElementById('modalProductStock');
        const modalDesc = document.getElementById('modalProductDescription');
        const modalIcon = document.getElementById('modalProductIcon');
        const modalIdInput = document.getElementById('modalProductIdInput');
        const modalPageLink = document.getElementById('modalProductPageLink');

        detailButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                modalName.textContent = btn.dataset.name || '';
                modalCategory.textContent = btn.dataset.category || '';
                modalPrice.textContent = btn.dataset.price || '';
                modalStock.textContent = 'Stok tersedia: ' + (btn.dataset.stock || '0');
                modalDesc.textContent = btn.dataset.description || '';
                modalIcon.className = 'bi ' + (btn.dataset.icon || 'bi-box') + ' fs-1 text-primary';
                if (modalIdInput) modalIdInput.value = btn.dataset.id || '';
                if (modalPageLink) modalPageLink.href = btn.dataset.url || '#';
            });
        });
    });
</script>
<?= $this->endSection() ?>
