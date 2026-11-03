<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>

<div class="p-5 mb-4 bg-white border rounded-4 text-center">
    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill small mb-3">
        Koleksi Terbaru 2026
    </span>
    <h1 class="display-6 fw-bold text-dark mb-2">Desain Minimalis, Fungsi Maksimal.</h1>
    <p class="text-muted col-md-7 mx-auto mb-4 small">
        Temukan produk pilihan berkualitas dengan estetika modern monokrom untuk menunjang gaya hidup dan produktivitas Anda.
    </p>

    
    <form action="<?= site_url('katalog') ?>" method="get" class="col-md-6 mx-auto">
        <?php if (!empty($selectedCategory)) : ?>
            <input type="hidden" name="kategori" value="<?= esc($selectedCategory) ?>">
        <?php endif; ?>
        <div class="input-group">
            <input type="text" name="cari" class="form-control border-end-0 py-2" placeholder="Cari nama produk..." value="<?= esc($keyword ?? '') ?>">
            <button class="btn btn-dark px-4" type="submit">
                <i class="fas fa-magnifying-glass"></i>
            </button>
        </div>
    </form>
</div>

<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= site_url('katalog') ?>" class="btn btn-sm <?= empty($selectedCategory ?? '') ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">
            Semua Produk
        </a>
        <?php foreach ($categories as $cat) : ?>
            <a href="<?= site_url('katalog?kategori=' . $cat['id']) ?>" class="btn btn-sm <?= (($selectedCategory ?? '') == $cat['id']) ? 'btn-dark' : 'btn-outline-dark' ?> rounded-pill px-3">
                <?= esc($cat['nama_kategori']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <span class="text-muted small">
        Menampilkan <strong><?= count($products) ?></strong> produk
    </span>
</div>

<div class="row g-4">
    <?php if (!empty($products)) : ?>
        <?php foreach ($products as $p) : ?>
            <div class="col-sm-6 col-lg-4">
                <div class="card card-product h-100 overflow-hidden d-flex flex-column">
                    
                    <div class="position-relative bg-light text-center d-flex align-items-center justify-content-center" style="height: 220px; border-bottom: 1px solid var(--border-color);">
                        <?php if (!empty($p['gambar']) && file_exists(FCPATH . 'uploads/products/' . $p['gambar'])) : ?>
                            <img src="<?= base_url('uploads/products/' . esc($p['gambar'])) ?>" alt="<?= esc($p['nama_produk']) ?>" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                        <?php else : ?>
                            <div class="text-muted py-5 text-center">
                                <i class="fas fa-box-open fa-3x mb-2 d-block opacity-50"></i>
                                <span class="small">Tanpa Gambar</span>
                            </div>
                        <?php endif; ?>

                        <?php if ($p['stok'] <= 0) : ?>
                            <span class="position-absolute top-0 end-0 m-3 badge bg-danger">Habis</span>
                        <?php endif; ?>
                    </div>

                    
                    <div class="card-body p-4 d-flex flex-column flex-grow-1">
                        <span class="badge bg-light text-muted border small mb-2 align-self-start">
                            <?= esc($p['nama_kategori'] ?? 'Umum') ?>
                        </span>

                        <h5 class="fw-bold mb-1 fs-6">
                            <a href="<?= site_url('produk/' . $p['slug']) ?>" class="text-dark text-decoration-none">
                                <?= esc($p['nama_produk']) ?>
                            </a>
                        </h5>

                        <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= esc($p['deskripsi'] ?? 'Tidak ada deskripsi singkat.') ?>
                        </p>

                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                            <div>
                                <span class="text-muted small d-block">Harga:</span>
                                <strong class="fs-5 text-dark">
                                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                </strong>
                            </div>

                            <form action="<?= site_url('cart/add/' . $p['id']) ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" class="btn btn-dark btn-sm px-3" <?= $p['stok'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="fas fa-plus me-1"></i> Beli
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="col-12 py-5 text-center text-muted">
            <i class="fas fa-store-slash fa-3x mb-3 text-secondary d-block"></i>
            <h5>Produk Tidak Ditemukan</h5>
            <p class="small">Coba ubah kata kunci pencarian atau pilih kategori lain.</p>
            <a href="<?= site_url('katalog') ?>" class="btn btn-outline-dark btn-sm">Lihat Semua Produk</a>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
