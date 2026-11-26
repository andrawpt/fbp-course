<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="alert alert-info border-0 shadow-sm rounded-4 mb-4">
    <i class="bi bi-info-circle-fill me-2"></i>
    <strong>Catatan:</strong> Data karyawan disimpan pada tabel <code>karyawan</code> yang terpisah dari akun login (<code>users</code>). Menambahkan data karyawan tidak otomatis membuat akun login.
</div>

<div class="card border-0 shadow-sm rounded-4 p-3 mb-4">
    <form action="<?= site_url('admin/karyawan') ?>" method="get" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small fw-semibold mb-1">Cari Nama atau NIP</label>
            <input type="text" name="q" class="form-control" placeholder="Ketik nama atau NIP..." value="<?= esc($keyword) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-semibold mb-1">Filter Jabatan</label>
            <select name="position" class="form-select">
                <option value="">Semua Jabatan</option>
                <?php foreach ($positions as $pos): ?>
                    <option value="<?= esc($pos) ?>" <?= $position === $pos ? 'selected' : '' ?>><?= esc($pos) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-outline-primary fw-semibold">
                <i class="bi bi-search me-1"></i>Cari
            </button>
            <a href="<?= site_url('admin/karyawan/create') ?>" class="btn btn-primary fw-semibold flex-grow-1">
                <i class="bi bi-plus-lg me-1"></i>Tambah
            </a>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">NIP</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Email &amp; Telepon</th>
                    <th>Tgl Masuk</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($karyawanList === []): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data karyawan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($karyawanList as $kar): ?>
                        <tr>
                            <td class="ps-4 fw-semibold"><?= esc($kar['nip']) ?></td>
                            <td><?= esc($kar['name']) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= esc($kar['position']) ?></span></td>
                            <td class="small">
                                <div><?= esc($kar['email']) ?></div>
                                <div class="text-muted"><?= esc($kar['phone']) ?></div>
                            </td>
                            <td class="small text-muted"><?= esc($kar['hire_date']) ?></td>
                            <td>
                                <span class="badge <?= $kar['status'] === 'aktif' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' ?>">
                                    <?= esc(ucfirst($kar['status'])) ?>
                                </span>
                            </td>
                            <td class="pe-4 text-end text-nowrap">
                                <a href="<?= site_url('admin/karyawan/edit/' . $kar['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <form action="<?= site_url('admin/karyawan/delete/' . $kar['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus data karyawan ini?');">
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
