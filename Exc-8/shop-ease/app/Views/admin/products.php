<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <h2 class="h6 fw-bold mb-3">Tambah Produk Baru</h2>
    <form action="<?= site_url('admin/produk') ?>" method="post" class="row g-3">
        <?= csrf_field() ?>
        <div class="col-md-4">
            <label class="form-label small fw-semibold">Nama Produk</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold">Kategori</label>
            <select name="category_id" class="form-select" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= esc((string) $cat['id']) ?>"><?= esc($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold">Harga (IDR)</label>
            <input type="number" class="form-control" name="price" min="0" required>
        </div>
        <div class="col-md-1">
            <label class="form-label small fw-semibold">Stok</label>
            <input type="number" class="form-control" name="stock" min="0" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Tambah</button>
        </div>
        <div class="col-12">
            <input type="text" class="form-control form-control-sm" name="description" placeholder="Deskripsi singkat produk...">
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Produk</th>
                    <th>Kategori</th>
                    <th style="width: 260px;">Harga &amp; Stok</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $prod): ?>
                    <tr>
                        <td class="ps-4 fw-semibold"><?= esc($prod['name']) ?></td>
                        <td class="small text-muted"><?= esc($prod['category_name']) ?></td>
                        <td>
                            <form action="<?= site_url('admin/produk/' . $prod['id'] . '/stok') ?>" method="post" class="d-flex gap-1">
                                <?= csrf_field() ?>
                                <input type="number" name="price" value="<?= esc((string) (int) $prod['price']) ?>" min="0" class="form-control form-control-sm" title="Harga">
                                <input type="number" name="stock" value="<?= esc((string) $prod['stock']) ?>" min="0" class="form-control form-control-sm" style="width: 80px;" title="Stok">
                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Simpan"><i class="bi bi-check-lg"></i></button>
                            </form>
                        </td>
                        <td>
                            <span class="badge <?= $prod['status'] === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                <?= esc($prod['status']) ?>
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <form action="<?= site_url('admin/produk/' . $prod['id'] . '/toggle') ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-secondary">
                                    <?= $prod['status'] === 'active' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
