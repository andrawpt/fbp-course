<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= esc((string) session()->getFlashdata('success')) ?>
                <a href="<?= site_url('keranjang') ?>" class="alert-link ms-2">Lihat Keranjang</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc((string) session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small">
                <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item">
                    <a href="<?= site_url('/') ?>?kategori=<?= urlencode($product['category_slug']) ?>#produk-unggulan">
                        <?= esc($product['category_name']) ?>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?= esc($product['name']) ?></li>
            </ol>
        </nav>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="row g-0">
                <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-5" style="min-height: 320px;">
                    <div class="text-center">
                        <i class="bi <?= esc($product['icon']) ?> text-primary" style="font-size: 7rem;"></i>
                        <div class="mt-3">
                            <span class="badge bg-primary"><?= esc($product['badge']) ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 p-4 p-md-5">
                    <div class="text-primary fw-semibold small mb-1"><?= esc($product['category_name']) ?></div>
                    <h1 class="h3 fw-bold mb-2"><?= esc($product['name']) ?></h1>
                    <div class="fs-3 fw-bold text-primary mb-3">
                        Rp <?= number_format((float) $product['price'], 0, ',', '.') ?>
                    </div>

                    <div class="mb-3">
                        <?php if ((int) $product['stock'] > 0): ?>
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Stok tersedia: <?= esc((string) $product['stock']) ?> unit
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger px-3 py-2">Stok habis</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-muted mb-4"><?= esc($product['description']) ?></p>

                    <form action="<?= site_url('keranjang/tambah') ?>" method="post" class="row g-3 align-items-end">
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= esc((string) $product['id']) ?>">

                        <div class="col-sm-4">
                            <label for="quantity" class="form-label small fw-semibold">Kuantitas</label>
                            <input
                                type="number"
                                class="form-control"
                                id="quantity"
                                name="quantity"
                                value="1"
                                min="1"
                                max="<?= esc((string) max(1, (int) $product['stock'])) ?>"
                                <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>
                            >
                        </div>

                        <div class="col-sm-8 d-grid d-sm-flex gap-2">
                            <button
                                type="submit"
                                class="btn btn-primary btn-lg fw-semibold flex-fill"
                                <?= (int) $product['stock'] < 1 ? 'disabled' : '' ?>
                            >
                                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                            </button>
                            <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-outline-secondary btn-lg">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
