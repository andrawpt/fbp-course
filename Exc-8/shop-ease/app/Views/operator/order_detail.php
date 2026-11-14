<?= $this->extend('layouts/operator') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="<?= site_url('operator') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Antrean Operator
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0">Daftar Barang untuk Dikemas</h2>
                <span class="badge bg-success-subtle text-success px-3 py-2"><?= esc($order['order_status']) ?></span>
            </div>
            <ul class="list-group list-group-flush">
                <?php foreach ($order['items'] as $item): ?>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold"><?= esc($item['product_name']) ?></span>
                        <span class="badge bg-dark rounded-pill"><?= esc((string) $item['quantity']) ?> unit</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h3 class="h6 fw-bold mb-2">Tujuan Pengiriman</h3>
            <p class="small text-muted mb-0">
                <strong><?= esc($order['recipient_name']) ?></strong> (<?= esc($order['phone']) ?>)<br>
                <?= esc($order['address']) ?>, <?= esc($order['city']) ?>, <?= esc($order['province']) ?> <?= esc($order['postal_code']) ?>
            </p>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h3 class="h6 fw-bold mb-3">
                <i class="bi bi-truck text-success me-2"></i>Input Kurir &amp; Nomor Resi
            </h3>

            <?php if ($order['order_status'] === 'processing'): ?>
                <form action="<?= site_url('operator/pesanan/' . $order['order_number'] . '/kirim') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="ship">

                    <div class="mb-3">
                        <label for="courier" class="form-label small fw-semibold">Kurir Ekspedisi</label>
                        <select class="form-select" id="courier" name="courier" required>
                            <option value="JNE">JNE Express</option>
                            <option value="J&T">J&amp;T Express</option>
                            <option value="SiCepat">SiCepat</option>
                            <option value="Pos Indonesia">Pos Indonesia</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tracking_number" class="form-label small fw-semibold">Nomor Resi</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number" placeholder="Contoh: JNE1234567890" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        <i class="bi bi-send-check me-1"></i>Kirim Pesanan
                    </button>
                </form>
            <?php elseif ($order['order_status'] === 'shipped'): ?>
                <div class="alert alert-info small mb-3">
                    Dikirim via <strong><?= esc((string) ($order['shipment']['courier'] ?? '-')) ?></strong><br>
                    No. Resi: <strong><?= esc((string) ($order['shipment']['tracking_number'] ?? '-')) ?></strong>
                </div>

                <form action="<?= site_url('operator/pesanan/' . $order['order_number'] . '/kirim') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="complete">
                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        <i class="bi bi-check2-all me-1"></i>Tandai Pesanan Selesai
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-success small mb-0">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Pesanan telah selesai dikirim (<?= esc((string) ($order['shipment']['courier'] ?? '-')) ?> &bull; <?= esc((string) ($order['shipment']['tracking_number'] ?? '-')) ?>).
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
