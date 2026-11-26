<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-<?= $product !== null ? '7' : '8 mx-auto' ?>">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0"><?= $product === null ? 'Tambah Produk Baru' : 'Edit Produk' ?></h2>
                <a href="<?= site_url('admin/products') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <form action="<?= esc($action) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" minlength="3" maxlength="150"
                           value="<?= esc((string) old('name', $product['name'] ?? '')) ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label small fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php $selectedCat = (int) old('category_id', $product['category_id'] ?? 0); ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= esc((string) $cat['id']) ?>" <?= $selectedCat === (int) $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                        <?php $currentStatus = old('status', ($product['status'] ?? 'active') === 'active' ? 'tersedia' : 'tidak_tersedia'); ?>
                        <select name="status" id="status" class="form-select" required>
                            <option value="tersedia" <?= in_array($currentStatus, ['tersedia', 'active'], true) ? 'selected' : '' ?>>Tersedia</option>
                            <option value="tidak_tersedia" <?= in_array($currentStatus, ['tidak_tersedia', 'inactive'], true) ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                        <div class="form-text">Jika stok 0, status otomatis disimpan Tidak Tersedia.</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label small fw-semibold">Harga (IDR) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" min="0" max="1000000000"
                               value="<?= esc((string) old('price', isset($product['price']) ? (int) $product['price'] : 0)) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="stock" class="form-label small fw-semibold">Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock" name="stock" min="0" max="1000000000"
                               value="<?= esc((string) old('stock', $product['stock'] ?? 0)) ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="10000"><?= esc((string) old('description', $product['description'] ?? '')) ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="images" class="form-label small fw-semibold">Unggah Foto Produk (JPG, PNG, WEBP, maks 2MB, maks 5 foto)</label>
                    <input type="file" class="form-control" id="images" name="images[]" accept=".jpg,.jpeg,.png,.webp" multiple>
                    <div class="form-text">Bisa memilih beberapa foto sekaligus.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check-lg me-1"></i><?= $product === null ? 'Simpan Produk' : 'Simpan Perubahan' ?>
                    </button>
                    <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <?php if ($product !== null): ?>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h3 class="h6 fw-bold mb-3">Galeri Foto Produk (<?= count($images) ?>/5)</h3>
                <?php if ($images === []): ?>
                    <div class="text-center py-4 bg-light rounded-3">
                        <img src="<?= esc((string) ($product['image'] ?: '/images/placeholder.svg')) ?>" alt="Placeholder" class="mb-2 rounded-3" style="max-height: 120px;">
                        <p class="small text-muted mb-0">Belum ada foto galeri. Unggah foto melalui form di samping.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($images as $img): ?>
                            <div class="col-6">
                                <div class="border rounded-3 p-2 bg-light h-100 d-flex flex-column">
                                    <img src="<?= esc($img['image_path']) ?>" alt="Foto Produk" class="rounded-2 w-100 object-fit-cover mb-2" style="height: 120px;">
                                    <?php if ((int) $img['is_primary'] === 1): ?>
                                        <span class="badge bg-primary mb-2">Foto Utama</span>
                                    <?php else: ?>
                                        <form action="<?= site_url('admin/products/' . $product['id'] . '/images/' . $img['id'] . '/primary') ?>" method="post" class="mb-1">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-primary w-100">Jadikan Utama</button>
                                        </form>
                                    <?php endif; ?>
                                    <form action="<?= site_url('admin/products/' . $product['id'] . '/images/' . $img['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Hapus foto ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 mt-auto">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
