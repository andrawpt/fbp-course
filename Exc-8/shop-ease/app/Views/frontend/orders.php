<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Riwayat Pesanan Saya</h1>
                <p class="text-muted small mb-0">Pantau status pembayaran, pemrosesan, dan pengiriman pesanan Anda.</p>
            </div>
            <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-primary btn-sm fw-semibold">
                <i class="bi bi-bag-plus me-1"></i>Belanja Lagi
            </a>
        </div>

        <?php if (empty($orders)): ?>
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <div class="mb-3"><i class="bi bi-receipt fs-1 text-muted"></i></div>
                <h2 class="h5 fw-bold">Belum Ada Pesanan</h2>
                <p class="text-muted mb-4">Anda belum memiliki riwayat pesanan. Mulai belanja produk unggulan kami sekarang.</p>
                <div>
                    <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-primary fw-semibold px-4">Lihat Katalog</a>
                </div>
            </div>
        <?php else: ?>
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">No. Pesanan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status Pesanan</th>
                                <th>Status Pembayaran</th>
                                <th class="pe-4 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="ps-4 fw-bold"><?= esc($order['order_number']) ?></td>
                                    <td class="small text-muted"><?= esc($order['order_date']) ?></td>
                                    <td class="fw-bold text-primary">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></td>
                                    <td><span class="badge bg-info-subtle text-info-emphasis"><?= esc($order['order_status']) ?></span></td>
                                    <td><span class="badge bg-secondary-subtle text-secondary-emphasis"><?= esc((string) ($order['payment_status'] ?? 'unpaid')) ?></span></td>
                                    <td class="pe-4 text-end">
                                        <a href="<?= site_url('pesanan/' . $order['order_number']) ?>" class="btn btn-sm btn-outline-primary fw-semibold">
                                            Detail &amp; Bayar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
