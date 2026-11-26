<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="mb-3">
    <a href="<?= site_url('admin/pesanan') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Pesanan
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h5 fw-bold mb-0"><?= esc($order['order_number']) ?></h2>
                    <small class="text-muted">Customer: <?= esc($order['customer_name']) ?> (<?= esc($order['customer_email']) ?>)</small>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-info-subtle text-info-emphasis px-3 py-2">Order: <?= esc($order['order_status']) ?></span>
                    <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2">Bayar: <?= esc((string) ($order['payment']['payment_status'] ?? 'unpaid')) ?></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                            <tr>
                                <td class="fw-semibold"><?= esc($item['product_name']) ?></td>
                                <td>Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></td>
                                <td><?= esc((string) $item['quantity']) ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="border-top mt-3 pt-3 d-flex justify-content-between fw-bold fs-5 text-primary">
                <span>Total Tagihan</span>
                <span>Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h3 class="h6 fw-bold mb-2">Alamat Pengiriman</h3>
            <p class="small text-muted mb-0">
                <strong><?= esc($order['recipient_name']) ?></strong> (<?= esc($order['phone']) ?>)<br>
                <?= esc($order['address']) ?>, <?= esc($order['city']) ?>, <?= esc($order['province']) ?> <?= esc($order['postal_code']) ?>
            </p>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h3 class="h6 fw-bold mb-3">
                <i class="bi bi-shield-check text-primary me-2"></i>Verifikasi Pembayaran
            </h3>

            <?php if (! empty($order['payment']['proof_file'])): ?>
                <div class="mb-3">
                    <div class="small text-muted mb-2">Bukti Transfer Customer:</div>
                    <a href="<?= base_url($order['payment']['proof_file']) ?>" target="_blank" class="btn btn-sm btn-outline-primary w-100 mb-2">
                        <i class="bi bi-file-earmark-image me-1"></i>Buka File Bukti Pembayaran
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary small">
                    Customer belum mengunggah bukti pembayaran.
                </div>
            <?php endif; ?>

            <?php if (($order['payment']['payment_status'] ?? '') === 'submitted'): ?>
                <div class="d-grid gap-2">
                    <form action="<?= site_url('admin/pesanan/' . $order['order_number'] . '/verifikasi') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="decision" value="verify">
                        <button type="submit" class="btn btn-success w-100 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i>Setujui &amp; Teruskan ke Operator
                        </button>
                    </form>

                    <form action="<?= site_url('admin/pesanan/' . $order['order_number'] . '/verifikasi') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="decision" value="reject">
                        <button type="submit" class="btn btn-outline-danger w-100 fw-semibold">
                            <i class="bi bi-x-circle me-1"></i>Tolak Bukti Pembayaran
                        </button>
                    </form>
                </div>
            <?php elseif (($order['payment']['payment_status'] ?? '') === 'verified'): ?>
                <div class="alert alert-success small mb-0">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Pembayaran sudah diverifikasi pada <?= esc((string) $order['payment']['verified_at']) ?>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
