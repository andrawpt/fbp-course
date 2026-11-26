<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Perlu Disiapkan (Processing)</div>
            <div class="fs-3 fw-bold text-warning"><?= esc((string) $countProcessing) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Dalam Pengiriman (Shipped)</div>
            <div class="fs-3 fw-bold text-primary"><?= esc((string) $countShipped) ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-3">
            <div class="small text-muted">Selesai (Completed)</div>
            <div class="fs-3 fw-bold text-success"><?= esc((string) $countCompleted) ?></div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white py-3">
        <h2 class="h6 fw-bold mb-0">Daftar Pesanan Siap Diproses &amp; Dikirim</h2>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">No. Pesanan</th>
                    <th>Penerima</th>
                    <th>Status Pesanan</th>
                    <th>Kurir &amp; Resi</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pesanan yang perlu diproses operator.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= esc($order['order_number']) ?></td>
                            <td class="small">
                                <div class="fw-semibold"><?= esc($order['customer_name']) ?></div>
                                <div class="text-muted"><?= esc($order['order_date']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success"><?= esc($order['order_status']) ?></span>
                            </td>
                            <td class="small text-muted">
                                <?php if (! empty($order['courier'])): ?>
                                    <strong><?= esc($order['courier']) ?></strong> &bull; <?= esc((string) $order['tracking_number']) ?>
                                <?php else: ?>
                                    Belum ada resi
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="<?= site_url('operator/pesanan/' . $order['order_number']) ?>" class="btn btn-sm btn-success fw-semibold">
                                    Proses Pengiriman
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
