<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="h5 fw-bold mb-1">Daftar Kategori</h2>
        <p class="text-muted small mb-0">Kategori yang masih memiliki produk tidak dapat dihapus.</p>
    </div>
    <a href="<?= site_url('admin/categories/create') ?>" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-lg me-1"></i>Tambah Kategori
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Nama</th>
                    <th>Slug</th>
                    <th>Deskripsi</th>
                    <th>Produk</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories === []): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td class="ps-4 fw-semibold">
                                <i class="bi <?= esc($cat['icon']) ?> text-primary me-2"></i><?= esc($cat['name']) ?>
                            </td>
                            <td class="small text-muted"><?= esc($cat['slug']) ?></td>
                            <td class="small text-muted"><?= esc((string) ($cat['description'] ?? '—')) ?></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary"><?= esc((string) $cat['product_count']) ?> produk</span>
                            </td>
                            <td class="pe-4 text-end text-nowrap">
                                <a href="<?= site_url('admin/categories/edit/' . $cat['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <form action="<?= site_url('admin/categories/delete/' . $cat['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus kategori ini?');">
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
</div>
<?= $this->endSection() ?>
