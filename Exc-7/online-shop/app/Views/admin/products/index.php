<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Daftar Produk Toko</h4>
        <p class="text-muted small mb-0">Kelola katalog produk, stok, dan harga barang.</p>
    </div>
    <a href="<?= site_url('admin/products/create') ?>" class="btn btn-dark btn-sm">
        <i class="fas fa-plus me-1"></i> Tambah Produk
    </a>
</div>

<div class="card p-3 shadow-sm border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center">#</th>
                    <th width="8%" class="text-center">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th width="10%">Stok</th>
                    <th width="15%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)) : ?>
                    <?php $no = 1; foreach ($products as $p) : ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?= $no++ ?></td>
                            <td class="text-center">
                                <?php if (!empty($p['gambar']) && file_exists(FCPATH . 'uploads/products/' . $p['gambar'])) : ?>
                                    <img src="<?= base_url('uploads/products/' . esc($p['gambar'])) ?>" alt="Item" class="rounded border" style="width: 44px; height: 44px; object-fit: contain;">
                                <?php else : ?>
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong class="text-dark d-block"><?= esc($p['nama_produk']) ?></strong>
                                <small class="text-muted"><?= esc($p['slug']) ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border"><?= esc($p['nama_kategori'] ?? 'Umum') ?></span></td>
                            <td class="fw-bold text-dark">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge <?= $p['stok'] > 0 ? 'bg-dark' : 'bg-danger' ?>">
                                    <?= $p['stok'] ?> unit
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('admin/products/edit/' . $p['id']) ?>" class="btn btn-sm btn-outline-dark py-1 px-2" title="Edit">
                                    <i class="fas fa-pen fa-xs"></i>
                                </a>
                                <a href="<?= site_url('admin/products/delete/' . $p['id']) ?>" class="btn btn-sm btn-dark py-1 px-2 ms-1" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" title="Hapus">
                                    <i class="fas fa-trash fa-xs"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada produk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

