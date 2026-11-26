<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<ul class="nav nav-pills mb-4 gap-2">
    <li class="nav-item">
        <a href="<?= site_url('admin/inventory?tab=low') ?>" class="nav-link <?= $tab === 'low' ? 'active' : 'bg-white border text-dark' ?>">
            Stok Rendah (1–5) <span class="badge bg-warning text-dark ms-1"><?= esc((string) $counts['low']) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('admin/inventory?tab=out') ?>" class="nav-link <?= $tab === 'out' ? 'active' : 'bg-white border text-dark' ?>">
            Stok Habis (0) <span class="badge bg-danger ms-1"><?= esc((string) $counts['out']) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a href="<?= site_url('admin/inventory?tab=all') ?>" class="nav-link <?= $tab === 'all' ? 'active' : 'bg-white border text-dark' ?>">
            Semua Produk <span class="badge bg-secondary ms-1"><?= esc((string) $counts['all']) ?></span>
        </a>
    </li>
</ul>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Produk</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th style="width: 240px;">Ubah Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products === []): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Tidak ada produk pada tab ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($products as $prod): ?>
                        <tr>
                            <td class="ps-4 fw-semibold"><?= esc($prod['name']) ?></td>
                            <td class="small text-muted"><?= esc((string) ($prod['category_name'] ?? '—')) ?></td>
                            <td>
                                <span class="badge <?= $prod['status'] === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                    <?= $prod['status'] === 'active' ? 'Tersedia' : 'Tidak Tersedia' ?>
                                </span>
                            </td>
                            <td class="pe-4">
                                <form action="<?= site_url('admin/inventory/update/' . $prod['id']) ?>" method="post" class="d-flex gap-2">
                                    <?= csrf_field() ?>
                                    <input type="number" name="stock" value="<?= esc((string) $prod['stock']) ?>" min="0" max="1000000000" class="form-control form-control-sm" required>
                                    <button type="submit" class="btn btn-sm btn-primary fw-semibold text-nowrap">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
