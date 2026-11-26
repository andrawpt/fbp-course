<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Keranjang Belanja</h1>
                <p class="text-muted small mb-0">Periksa kuantitas item sebelum melanjutkan ke checkout.</p>
            </div>
            <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-outline-primary btn-sm fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Lanjut Belanja
            </a>
        </div>

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

        <?php if (empty($items)): ?>
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="mb-3"><i class="bi bi-cart-x fs-1 text-muted"></i></div>
                <h2 class="h5 fw-bold">Keranjang Belanja Kosong</h2>
                <p class="text-muted mb-4">Belum ada produk di keranjang Anda. Yuk mulai pilih produk unggulan kami!</p>
                <div>
                    <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-primary fw-semibold px-4">
                        Jelajahi Produk
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Produk</th>
                                        <th>Harga</th>
                                        <th style="width: 170px;">Kuantitas</th>
                                        <th>Subtotal</th>
                                        <th class="pe-4 text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                        <i class="bi <?= esc($item['icon']) ?> text-primary fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <a href="<?= site_url('produk/' . $item['slug']) ?>" class="fw-bold text-dark text-decoration-none">
                                                            <?= esc($item['name']) ?>
                                                        </a>
                                                        <div class="small text-muted">
                                                            <?= esc($item['category_name']) ?> &bull; Stok: <?= esc((string) $item['stock']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="small fw-semibold">
                                                Rp <?= number_format((float) $item['price'], 0, ',', '.') ?>
                                            </td>
                                            <td>
                                                <form action="<?= site_url('keranjang/update') ?>" method="post" class="d-flex gap-1">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="item_id" value="<?= esc((string) $item['item_id']) ?>">
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        value="<?= esc((string) $item['quantity']) ?>"
                                                        min="1"
                                                        max="<?= esc((string) $item['stock']) ?>"
                                                        class="form-control form-control-sm"
                                                    >
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Perbarui kuantitas">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="fw-bold text-primary">
                                                Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?>
                                            </td>
                                            <td class="pe-4 text-end">
                                                <form action="<?= site_url('keranjang/hapus') ?>" method="post" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="item_id" value="<?= esc((string) $item['item_id']) ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h2 class="h5 fw-bold mb-3">Ringkasan Belanja</h2>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Total Item</span>
                            <span><?= esc((string) $cartCount) ?> unit</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="fw-semibold">Subtotal Produk</span>
                            <span class="fw-bold text-primary fs-5">
                                Rp <?= number_format((float) $subtotal, 0, ',', '.') ?>
                            </span>
                        </div>
                        <div class="d-grid">
                            <a href="<?= site_url('checkout') ?>" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-bag-check me-2"></i>Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
