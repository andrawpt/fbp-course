<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<!-- Ringkasan Produk -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Total Produk</div>
            <div class="h4 fw-bold mb-0"><?= esc((string) $summary['total_products']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Total Kategori</div>
            <div class="h4 fw-bold mb-0"><?= esc((string) $summary['total_categories']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Produk Aktif</div>
            <div class="h4 fw-bold text-success mb-0"><?= esc((string) $summary['active_products']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Produk Tidak Aktif</div>
            <div class="h4 fw-bold text-secondary mb-0"><?= esc((string) $summary['inactive_products']) ?></div>
        </div>
    </div>
</div>

<!-- Filter & Tombol Tambah -->
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
    <form action="<?= site_url('admin/products') ?>" method="get" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-semibold mb-1">Cari Nama Produk</label>
            <input type="text" name="q" class="form-control" placeholder="Ketik nama produk..." value="<?= esc($keyword) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Kategori</label>
            <select name="category_id" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc((string) $cat['id']) ?>" <?= (int) $categoryId === (int) $cat['id'] ? 'selected' : '' ?>>
                        <?= esc($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Filter Stok</label>
            <select name="stock_filter" class="form-select">
                <option value="">Semua Stok</option>
                <option value="low" <?= $stockFilter === 'low' ? 'selected' : '' ?>>Stok Rendah (1–5)</option>
                <option value="out" <?= $stockFilter === 'out' ? 'selected' : '' ?>>Stok Habis (0)</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary fw-semibold">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            <a href="<?= site_url('admin/products/create') ?>" class="btn btn-primary fw-semibold flex-grow-1">
                <i class="bi bi-plus-lg me-1"></i>Tambah Produk
            </a>
        </div>
    </form>
</div>

<!-- Tabel Produk -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products === []): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada produk yang sesuai filter.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= esc((string) ($prod['image'] ?: '/images/placeholder.svg')) ?>"
                                         alt="<?= esc($prod['name']) ?>"
                                         class="rounded-3 border object-fit-cover"
                                         style="width: 44px; height: 44px;">
                                    <div>
                                        <div class="fw-semibold"><?= esc($prod['name']) ?></div>
                                        <div class="small text-muted"><?= esc($prod['slug']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="small text-muted"><?= esc((string) ($prod['category_name'] ?? '—')) ?></td>
                            <td class="fw-semibold">Rp<?= number_format((float) $prod['price'], 0, ',', '.') ?></td>
                            <td>
                                <?php if ((int) $prod['stock'] === 0): ?>
                                    <span class="badge bg-danger-subtle text-danger">Habis (0)</span>
                                <?php elseif ((int) $prod['stock'] <= 5): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis">Rendah (<?= esc((string) $prod['stock']) ?>)</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-dark border"><?= esc((string) $prod['stock']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $prod['status'] === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                    <?= $prod['status'] === 'active' ? 'Tersedia' : 'Tidak Tersedia' ?>
                                </span>
                            </td>
                            <td class="pe-4 text-end text-nowrap">
                                <a href="<?= site_url('admin/products/edit/' . $prod['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <form action="<?= site_url('admin/products/delete/' . $prod['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus produk ini secara permanen?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="card-footer bg-white border-top py-3 px-4">
            <?= $pager->links('default', 'default_full') ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
