<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="h6 fw-bold mb-3">Tambah Kategori Baru</h2>
            <form action="<?= site_url('admin/kategori') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold">Nama Kategori</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="icon" class="form-label small fw-semibold">Ikon Bootstrap (opsional)</label>
                    <input type="text" class="form-control" id="icon" name="icon" placeholder="Contoh: bi-laptop">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-semibold">Simpan Kategori</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                            <tr>
                                <td class="ps-4 fw-semibold">
                                    <i class="bi <?= esc($cat['icon']) ?> text-primary me-2"></i><?= esc($cat['name']) ?>
                                </td>
                                <td class="small text-muted"><?= esc($cat['slug']) ?></td>
                                <td>
                                    <?php if ((int) $cat['is_active'] === 1): ?>
                                        <span class="badge bg-success-subtle text-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <form action="<?= site_url('admin/kategori/' . $cat['id'] . '/toggle') ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <?= (int) $cat['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
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
</div>
<?= $this->endSection() ?>
