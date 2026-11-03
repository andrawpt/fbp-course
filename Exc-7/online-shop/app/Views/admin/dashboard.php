<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card p-3" style="border-left: 4px solid #0f172a !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Pendapatan</span>
                    <h4 class="fw-bold text-dark mt-1 mb-0">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h4>
                </div>
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 48px; height: 48px;">
                    <i class="fas fa-money-bill-wave fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3" style="border-left: 4px solid #0f172a !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Pesanan Masuk</span>
                    <h4 class="fw-bold text-dark mt-1 mb-0"><?= $totalOrders ?></h4>
                </div>
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 48px; height: 48px;">
                    <i class="fas fa-receipt fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3" style="border-left: 4px solid #0f172a !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Produk</span>
                    <h4 class="fw-bold text-dark mt-1 mb-0"><?= $totalProducts ?></h4>
                </div>
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 48px; height: 48px;">
                    <i class="fas fa-boxes-stacked fa-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card p-3" style="border-left: 4px solid #0f172a !important;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Pelanggan</span>
                    <h4 class="fw-bold text-dark mt-1 mb-0"><?= $totalUsers ?></h4>
                </div>
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 48px; height: 48px;">
                    <i class="fas fa-users fa-lg"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold text-dark mb-0">Pesanan Masuk Terbaru</h6>
                <a href="<?= site_url('admin/orders') ?>" class="btn btn-sm btn-outline-dark">Lihat Semua</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice</th>
                            <th>Penerima</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentOrders)) : ?>
                            <?php foreach ($recentOrders as $ro) : ?>
                                <tr>
                                    <td><strong><?= esc($ro['invoice']) ?></strong></td>
                                    <td><?= esc($ro['nama_penerima']) ?></td>
                                    <td class="fw-bold">Rp <?= number_format($ro['total_harga'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="badge bg-dark text-uppercase"><?= esc($ro['status']) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= site_url('admin/orders/detail/' . $ro['id']) ?>" class="btn btn-sm btn-outline-dark py-1 px-2">
                                            Rincian
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3">Aksi Cepat</h6>
            <div class="d-grid gap-2">
                <a href="<?= site_url('admin/products/create') ?>" class="btn btn-dark text-start py-2">
                    <i class="fas fa-plus me-2"></i> Tambah Produk Baru
                </a>
                <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-dark text-start py-2">
                    <i class="fas fa-list me-2"></i> Kelola Daftar Produk
                </a>
                <a href="<?= site_url('admin/orders') ?>" class="btn btn-outline-dark text-start py-2">
                    <i class="fas fa-truck-ramp-box me-2"></i> Proses Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

