<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">
        <i class="fas fa-credit-card me-2"></i>Checkout Pesanan
    </h3>
    <p class="text-muted small">Lengkapi informasi pengiriman untuk menyelesaikan pesanan Anda.</p>
</div>

<div class="row g-4">
    
    <div class="col-lg-7">
        <div class="card p-4 shadow-sm border">
            <h5 class="fw-bold text-dark mb-3">Informasi Pengiriman</h5>

            <form action="<?= site_url('checkout/process') ?>" method="post">
                <?= csrf_field() ?>

                <?php $errors = $errors ?? session()->getFlashdata('errors') ?? []; ?>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Lengkap Penerima <span class="text-danger">*</span></label>
                    <input type="text" name="nama_penerima" class="form-control <?= isset($errors['nama_penerima']) ? 'is-invalid' : '' ?>" placeholder="Contoh: Budi Santoso" value="<?= old('nama_penerima', session()->get('nama_lengkap')) ?>" required>
                    <?php if (isset($errors['nama_penerima'])) : ?>
                        <div class="invalid-feedback"><?= $errors['nama_penerima'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Nomor WhatsApp / Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="telepon" class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : '' ?>" placeholder="Contoh: 081234567890" value="<?= old('telepon') ?>" required>
                    <?php if (isset($errors['telepon'])) : ?>
                        <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Alamat Lengkap Pengiriman <span class="text-danger">*</span></label>
                    <textarea name="alamat" rows="4" class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>" placeholder="Nama jalan, nomor rumah, RT/RW, kecamatan, kota/kabupaten, kode pos" required><?= old('alamat') ?></textarea>
                    <?php if (isset($errors['alamat'])) : ?>
                        <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="p-3 bg-light rounded border mb-4 small text-muted">
                    <i class="fas fa-truck-fast me-1 text-dark"></i>
                    Metode Pembayaran: <strong>Transfer Bank / COD (Bayar di Tempat)</strong>. Konfirmasi pembayaran otomatis setelah pesanan dibuat.
                </div>

                <button type="submit" class="btn btn-dark w-100 py-3 fw-bold">
                    <i class="fas fa-lock me-2"></i> Konfirmasi & Buat Pesanan
                </button>
            </form>
        </div>
    </div>

    
    <div class="col-lg-5">
        <div class="card p-4 shadow-sm border">
            <h5 class="fw-bold text-dark mb-3">Item Pesanan (<?= count($cart) ?>)</h5>

            <div class="border-bottom pb-2 mb-3">
                <?php foreach ($cart as $item) : ?>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <div>
                            <strong class="text-dark"><?= esc($item['nama_produk']) ?></strong>
                            <span class="text-muted d-block">x <?= $item['qty'] ?></span>
                        </div>
                        <span class="fw-semibold text-dark">
                            Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.') ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-between mb-2 small text-muted">
                <span>Subtotal Produk:</span>
                <span>Rp <?= number_format($totalHarga, 0, ',', '.') ?></span>
            </div>

            <div class="d-flex justify-content-between mb-3 small text-muted">
                <span>Ongkos Kirim:</span>
                <span class="text-success fw-bold">GRATIS</span>
            </div>

            <div class="d-flex justify-content-between fs-5 fw-bold text-dark border-top pt-3">
                <span>Total Tagihan:</span>
                <span>Rp <?= number_format($totalHarga, 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

