<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="mb-4">
            <h1 class="h3 fw-bold mb-1">Checkout Pesanan</h1>
            <p class="text-muted small mb-0">Pilih atau isi alamat pengiriman dan konfirmasi pesanan Anda.</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc((string) session()->getFlashdata('error')) ?>
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

        <form action="<?= site_url('checkout') ?>" method="post">
            <?= csrf_field() ?>
            <div class="row g-4">
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-geo-alt text-primary me-2"></i>Alamat Pengiriman
                        </h2>

                        <?php if (! empty($addresses)): ?>
                            <div class="mb-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="address_mode" id="modeSaved" value="saved" checked>
                                    <label class="form-check-label fw-semibold" for="modeSaved">Gunakan alamat tersimpan</label>
                                </div>
                                <div class="ps-4 d-grid gap-2">
                                    <?php foreach ($addresses as $index => $addr): ?>
                                        <label class="border rounded-3 p-3 d-flex align-items-start gap-2 bg-light cursor-pointer">
                                            <input
                                                class="form-check-input mt-1"
                                                type="radio"
                                                name="address_id"
                                                value="<?= esc((string) $addr['id']) ?>"
                                                <?= $index === 0 ? 'checked' : '' ?>
                                            >
                                            <span class="small">
                                                <strong class="d-block"><?= esc($addr['recipient_name']) ?> (<?= esc($addr['phone']) ?>)</strong>
                                                <?= esc($addr['address']) ?>, <?= esc($addr['city']) ?>, <?= esc($addr['province']) ?> <?= esc($addr['postal_code']) ?>
                                            </span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="address_mode" id="modeNew" value="new">
                                <label class="form-check-label fw-semibold" for="modeNew">Tambah alamat pengiriman baru</label>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="address_mode" value="new">
                        <?php endif; ?>

                        <div id="newAddressFields" class="<?= ! empty($addresses) ? 'd-none' : '' ?> border-top pt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="recipient_name" class="form-label small fw-semibold">Nama Penerima</label>
                                    <input type="text" class="form-control" id="recipient_name" name="recipient_name" value="<?= esc((string) old('recipient_name')) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label small fw-semibold">Nomor HP</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="<?= esc((string) old('phone')) ?>">
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label small fw-semibold">Alamat Lengkap</label>
                                    <textarea class="form-control" id="address" name="address" rows="2"><?= esc((string) old('address')) ?></textarea>
                                </div>
                                <div class="col-md-5">
                                    <label for="city" class="form-label small fw-semibold">Kota / Kabupaten</label>
                                    <input type="text" class="form-control" id="city" name="city" value="<?= esc((string) old('city')) ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="province" class="form-label small fw-semibold">Provinsi</label>
                                    <input type="text" class="form-control" id="province" name="province" value="<?= esc((string) old('province')) ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="postal_code" class="form-label small fw-semibold">Kode Pos</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?= esc((string) old('postal_code')) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h2 class="h5 fw-bold mb-3">
                            <i class="bi bi-wallet2 text-primary me-2"></i>Metode Pembayaran &amp; Catatan
                        </h2>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label small fw-semibold">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" name="payment_method">
                                <option value="transfer_bank">Transfer Bank Manual (BCA / BRI / Mandiri)</option>
                                <option value="ewallet_manual">Transfer E-Wallet (QRIS / GoPay / OVO Manual)</option>
                            </select>
                        </div>
                        <div>
                            <label for="notes" class="form-label small fw-semibold">Catatan Pesanan (Opsional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Contoh: Tolong bungkus rapi"><?= esc((string) old('notes')) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h2 class="h5 fw-bold mb-3">Ringkasan Pesanan</h2>
                        <ul class="list-group list-group-flush mb-3">
                            <?php foreach ($items as $item): ?>
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-start">
                                    <div class="me-2">
                                        <div class="fw-semibold small"><?= esc($item['name']) ?></div>
                                        <div class="text-muted small"><?= esc((string) $item['quantity']) ?> &times; Rp <?= number_format((float) $item['price'], 0, ',', '.') ?></div>
                                    </div>
                                    <span class="fw-semibold small">Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="d-flex justify-content-between small mb-2">
                            <span class="text-muted">Subtotal Produk</span>
                            <span>Rp <?= number_format((float) $subtotal, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between small mb-3 pb-3 border-bottom">
                            <span class="text-muted">Ongkos Kirim (Flat)</span>
                            <span>Rp <?= number_format((float) $shippingCost, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold">Total Pembayaran</span>
                            <span class="fs-4 fw-bold text-primary">Rp <?= number_format((float) $total, 0, ',', '.') ?></span>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                <i class="bi bi-check2-circle me-2"></i>Buat Pesanan Sekarang
                            </button>
                            <a href="<?= site_url('keranjang') ?>" class="btn btn-outline-secondary">
                                Kembali ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modeSaved = document.getElementById('modeSaved');
        const modeNew = document.getElementById('modeNew');
        const newFields = document.getElementById('newAddressFields');

        if (modeSaved && modeNew && newFields) {
            modeSaved.addEventListener('change', () => newFields.classList.add('d-none'));
            modeNew.addEventListener('change', () => newFields.classList.remove('d-none'));
        }
    });
</script>
<?= $this->endSection() ?>
