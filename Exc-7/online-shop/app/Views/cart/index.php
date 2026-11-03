<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">
        <i class="fas fa-cart-shopping me-2"></i>Keranjang Belanja
    </h3>
    <p class="text-muted small">Tinjau produk yang telah Anda pilih sebelum melanjutkan ke checkout.</p>
</div>

<?php if (!empty($cart)) : ?>
    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card p-3 shadow-sm border">
                <form action="<?= site_url('cart/update') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th width="15%">Jumlah</th>
                                    <th>Subtotal</th>
                                    <th width="8%" class="text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $id => $item) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if (!empty($item['gambar']) && file_exists(FCPATH . 'uploads/products/' . $item['gambar'])) : ?>
                                                    <img src="<?= base_url('uploads/products/' . esc($item['gambar'])) ?>" alt="Item" class="rounded border" style="width: 50px; height: 50px; object-fit: contain;">
                                                <?php else : ?>
                                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px; font-size: 0.8rem;">
                                                        <i class="fas fa-box"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div>
                                                    <a href="<?= site_url('produk/' . $item['slug']) ?>" class="fw-semibold text-dark text-decoration-none">
                                                        <?= esc($item['nama_produk']) ?>
                                                    </a>
                                                    <small class="text-muted d-block">Stok: <?= $item['stok'] ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                        <td>
                                            <input type="number" name="qty[<?= $id ?>]" class="form-control text-center" value="<?= $item['qty'] ?>" min="1" max="<?= $item['stok'] ?>">
                                        </td>
                                        <td class="fw-bold text-dark">
                                            Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= site_url('cart/remove/' . $id) ?>" class="btn btn-sm btn-outline-dark" title="Hapus">
                                                <i class="fas fa-trash fa-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <a href="<?= site_url('cart/clear') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Kosongkan semua item di keranjang?');">
                            <i class="fas fa-trash-can me-1"></i> Kosongkan Keranjang
                        </a>
                        <button type="submit" class="btn btn-sm btn-outline-dark">
                            <i class="fas fa-rotate me-1"></i> Perbarui Keranjang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="col-lg-4">
            <div class="card p-4 shadow-sm border">
                <h5 class="fw-bold text-dark mb-3">Ringkasan Pesanan</h5>

                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>Total Item:</span>
                    <span><?= array_sum(array_column($cart, 'qty')) ?> barang</span>
                </div>

                <div class="d-flex justify-content-between mb-3 fs-5 fw-bold text-dark border-top pt-3">
                    <span>Total Belanja:</span>
                    <span>Rp <?= number_format($totalHarga, 0, ',', '.') ?></span>
                </div>

                <div class="d-grid gap-2">
                    <a href="<?= site_url('checkout') ?>" class="btn btn-dark py-2 fw-semibold">
                        Lanjut ke Pembayaran <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                    <a href="<?= site_url('katalog') ?>" class="btn btn-outline-dark py-2 small">
                        Lanjut Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="card p-5 text-center bg-white border">
        <i class="fas fa-cart-shopping fa-3x text-muted mb-3 opacity-50"></i>
        <h4 class="fw-bold text-dark mb-1">Keranjang Belanja Masih Kosong</h4>
        <p class="text-muted small mb-4">Anda belum menambahkan produk apa pun ke keranjang belanja.</p>
        <div>
            <a href="<?= site_url('katalog') ?>" class="btn btn-dark px-4 py-2">
                <i class="fas fa-bag-shopping me-1"></i> Mulai Belanja Sekarang
            </a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

