<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Detail Pesanan <?= esc($order['order_number']) ?></h1>
                <p class="text-muted small mb-0">Tanggal order: <?= esc($order['order_date']) ?></p>
            </div>
            <a href="<?= site_url('pesanan') ?>" class="btn btn-outline-secondary btn-sm fw-semibold">
                <i class="bi bi-arrow-left me-1"></i>Daftar Pesanan
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= esc((string) session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc((string) session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        <?php endif; ?>
        <?php $errors = session()->getFlashdata('errors') ?? []; ?>
        <?php if (! empty($errors)): ?>
            <div class="alert alert-danger small" role="alert">
                <ul class="mb-0 ps-3">
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc((string) $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h2 class="h5 fw-bold mb-0">Rincian Produk (Snapshot Harga)</h2>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info-subtle text-info-emphasis px-3 py-2">
                                Pesanan: <?= esc($order['order_status']) ?>
                            </span>
                            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-2">
                                Pembayaran: <?= esc((string) ($order['payment']['payment_status'] ?? 'unpaid')) ?>
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga Satuan</th>
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

                    <div class="border-top mt-3 pt-3 d-grid gap-1 small">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Subtotal</span>
                            <span>Rp <?= number_format((float) $order['subtotal'], 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span>Rp <?= number_format((float) $order['shipping_cost'], 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between fs-5 fw-bold text-primary pt-2 border-top mt-1">
                            <span>Total</span>
                            <span>Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h2 class="h5 fw-bold mb-3">Alamat &amp; Pengiriman</h2>
                    <div class="row g-3 small">
                        <div class="col-md-6">
                            <div class="fw-bold mb-1"><?= esc($order['recipient_name']) ?> (<?= esc($order['phone']) ?>)</div>
                            <div class="text-muted">
                                <?= esc($order['address']) ?>, <?= esc($order['city']) ?>, <?= esc($order['province']) ?> <?= esc($order['postal_code']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fw-bold mb-1">Status Pengiriman</div>
                            <div class="text-muted">
                                Status: <span class="badge bg-secondary-subtle text-secondary-emphasis"><?= esc((string) ($order['shipment']['shipment_status'] ?? 'pending')) ?></span><br>
                                Kurir: <?= esc((string) ($order['shipment']['courier'] ?? 'Belum ditentukan')) ?><br>
                                No. Resi: <?= esc((string) ($order['shipment']['tracking_number'] ?? '-')) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h2 class="h5 fw-bold mb-3">
                        <i class="bi bi-credit-card text-primary me-2"></i>Instruksi &amp; Bukti Bayar
                    </h2>
                    <div class="bg-light rounded-3 p-3 small mb-3">
                        <div class="fw-bold mb-1">Transfer ke Rekening ShopEase:</div>
                        <div>Bank BCA: <strong>1234-567-890</strong> a.n. ShopEase Store</div>
                        <div>Total Transfer: <strong class="text-primary">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></strong></div>
                    </div>

                    <?php if (! empty($order['payment']['proof_file'])): ?>
                        <div class="alert alert-info small mb-3">
                            <i class="bi bi-file-earmark-check me-1"></i>
                            Bukti pembayaran sudah diunggah.
                            <a href="<?= base_url($order['payment']['proof_file']) ?>" target="_blank" class="alert-link d-block mt-1">Lihat Bukti Pembayaran</a>
                        </div>
                    <?php endif; ?>

                    <?php if (in_array($order['order_status'], ['pending_payment', 'waiting_verification'], true)): ?>
                        <form action="<?= site_url('pesanan/' . $order['order_number'] . '/bayar') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label for="proof_file" class="form-label small fw-semibold">Unggah Bukti Pembayaran (JPG/PNG/PDF, maks 2MB)</label>
                                <input class="form-control form-control-sm" type="file" id="proof_file" name="proof_file" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="bi bi-upload me-2"></i>Kirim Bukti Pembayaran
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
