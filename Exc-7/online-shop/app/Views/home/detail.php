<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>

<nav class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="<?= site_url('/') ?>" class="text-muted text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('katalog?kategori=' . $product['category_id']) ?>" class="text-muted text-decoration-none"><?= esc($product['nama_kategori']) ?></a></li>
        <li class="breadcrumb-item active text-dark font-weight-bold"><?= esc($product['nama_produk']) ?></li>
    </ol>
</nav>

<div class="row g-5 mb-5">
    
    <div class="col-lg-6">
        <div class="card p-4 bg-white text-center d-flex align-items-center justify-content-center" style="min-height: 380px;">
            <?php if (!empty($product['gambar']) && file_exists(FCPATH . 'uploads/products/' . $product['gambar'])) : ?>
                <img src="<?= base_url('uploads/products/' . esc($product['gambar'])) ?>" alt="<?= esc($product['nama_produk']) ?>" class="img-fluid rounded" style="max-height: 350px; object-fit: contain;">
            <?php else : ?>
                <div class="py-5 text-muted">
                    <i class="fas fa-box-open fa-5x mb-3 d-block opacity-50"></i>
                    <span>Gambar Produk Belum Tersedia</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="col-lg-6">
        <span class="badge bg-light text-muted border px-3 py-2 rounded-pill small mb-2">
            <?= esc($product['nama_kategori'] ?? 'Kategori Umum') ?>
        </span>

        <h2 class="fw-bold text-dark mb-2"><?= esc($product['nama_produk']) ?></h2>

        <div class="d-flex align-items-center gap-3 mb-4">
            <h3 class="fw-bold text-dark mb-0">
                Rp <?= number_format($product['harga'], 0, ',', '.') ?>
            </h3>
            <span class="badge <?= $product['stok'] > 0 ? 'bg-dark' : 'bg-danger' ?> py-2 px-3">
                <?= $product['stok'] > 0 ? 'Stok Tersedia: ' . $product['stok'] : 'Stok Habis' ?>
            </span>
        </div>

        <div class="border-top border-bottom py-3 mb-4">
            <h6 class="fw-bold text-dark mb-2">Deskripsi Produk:</h6>
            <p class="text-muted small mb-0 lh-lg">
                <?= nl2br(esc($product['deskripsi'] ?? 'Belum ada deskripsi untuk produk ini.')) ?>
            </p>
        </div>

        
        <form action="<?= site_url('cart/add/' . $product['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-3 align-items-center mb-4">
                <div class="col-auto">
                    <label class="col-form-label small fw-bold">Jumlah:</label>
                </div>
                <div class="col-auto">
                    <input type="number" name="qty" class="form-control text-center" value="1" min="1" max="<?= $product['stok'] ?>" style="width: 85px;" <?= $product['stok'] <= 0 ? 'disabled' : '' ?>>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-dark w-100 py-2" <?= $product['stok'] <= 0 ? 'disabled' : '' ?>>
                        <i class="fas fa-cart-plus me-2"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </form>

        <a href="<?= site_url('katalog') ?>" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Katalog
        </a>
    </div>
</div>

<?php if (!empty($relatedProducts)) : ?>
    <hr class="my-5">
    <h4 class="fw-bold text-dark mb-4">Produk Terkait Lainnya</h4>
    <div class="row g-4">
        <?php foreach ($relatedProducts as $rp) : ?>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-product h-100 p-3">
                    <h6 class="fw-bold fs-6 mb-2">
                        <a href="<?= site_url('produk/' . $rp['slug']) ?>" class="text-dark text-decoration-none">
                            <?= esc($rp['nama_produk']) ?>
                        </a>
                    </h6>
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <strong class="text-dark">Rp <?= number_format($rp['harga'], 0, ',', '.') ?></strong>
                        <a href="<?= site_url('produk/' . $rp['slug']) ?>" class="btn btn-sm btn-outline-dark">Lihat</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

