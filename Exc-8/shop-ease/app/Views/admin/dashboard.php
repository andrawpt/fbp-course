<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="small text-muted">Produk Aktif</div>
            <div class="fs-3 fw-bold text-primary"><?= esc((string) $totalProducts) ?></div>
            <div class="small text-muted">Siap ditampilkan di katalog</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="small text-muted">Total Pelanggan</div>
            <div class="fs-3 fw-bold text-dark"><?= esc((string) $totalCustomers) ?></div>
            <div class="small text-muted">Akun customer terdaftar</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="small text-muted">Menunggu Verifikasi</div>
            <div class="fs-3 fw-bold text-warning"><?= esc((string) $waitingVerify) ?></div>
            <div class="small">
                <a href="<?= site_url('admin/pesanan?status=waiting_verification') ?>" class="text-decoration-none">Periksa sekarang &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">
            <div class="small text-muted">Penjualan Terverifikasi</div>
            <div class="fs-4 fw-bold text-success">Rp <?= number_format($totalSales, 0, ',', '.') ?></div>
            <div class="small text-muted">Dari <?= esc((string) $totalOrders) ?> total pesanan</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h2 class="h6 fw-bold mb-0">Pesanan Terbaru</h2>
        <a href="<?= site_url('admin/pesanan') ?>" class="btn btn-sm btn-outline-primary fw-semibold">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status Pesanan</th>
                    <th>Status Pembayaran</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan masuk.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= esc($order['order_number']) ?></td>
                            <td class="small">
                                <div class="fw-semibold"><?= esc($order['customer_name']) ?></div>
                                <div class="text-muted"><?= esc($order['customer_email']) ?></div>
                            </td>
                            <td class="fw-bold text-primary">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></td>
                            <td><span class="badge bg-info-subtle text-info-emphasis"><?= esc($order['order_status']) ?></span></td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis"><?= esc((string) ($order['payment_status'] ?? 'unpaid')) ?></span></td>
                            <td class="pe-4 text-end">
                                <a href="<?= site_url('admin/pesanan/' . $order['order_number']) ?>" class="btn btn-sm btn-primary fw-semibold">
                                    <?= $order['order_status'] === 'waiting_verification' ? 'Verifikasi' : 'Detail' ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
