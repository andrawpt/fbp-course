<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Detail Pesanan: <?= esc($order['invoice']) ?></h4>
        <p class="text-muted small mb-0">Rincian produk dan status pengiriman barang.</p>
    </div>
    <a href="<?= site_url('admin/orders') ?>" class="btn btn-outline-dark btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pesanan
    </a>
</div>

<div class="row g-4">
    
    <div class="col-lg-5">
        
        <div class="card p-4 shadow-sm border mb-4">
            <h6 class="fw-bold text-dark mb-3">Ubah Status Pesanan</h6>
            <form action="<?= site_url('admin/orders/update-status/' . $order['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label small text-muted">Status Saat Ini:</label>
                    <select name="status" class="form-select">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>PENDING (Menunggu Pembayaran)</option>
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>PROCESSING (Diproses / Dikemas)</option>
                        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>COMPLETED (Selesai / Terkirim)</option>
                        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>CANCELLED (Dibatalkan)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark btn-sm w-100">
                    <i class="fas fa-check me-1"></i> Perbarui Status
                </button>
            </form>
        </div>

        
        <div class="card p-4 shadow-sm border">
            <h6 class="fw-bold text-dark mb-3">Informasi Pengiriman</h6>
            <div class="small">
                <div class="mb-2">
                    <span class="text-muted d-block">Nama Penerima:</span>
                    <strong class="text-dark"><?= esc($order['nama_penerima']) ?></strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted d-block">Nomor Telepon:</span>
                    <strong class="text-dark"><?= esc($order['telepon']) ?></strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted d-block">Waktu Pesanan:</span>
                    <strong class="text-dark"><?= date('d F Y, H:i', strtotime($order['created_at'])) ?></strong>
                </div>
                <div>
                    <span class="text-muted d-block">Alamat Lengkap:</span>
                    <span class="text-dark"><?= nl2br(esc($order['alamat'])) ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7">
        <div class="card p-4 shadow-sm border">
            <h6 class="fw-bold text-dark mb-3">Daftar Barang yang Dipesan</h6>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th width="20%">Harga</th>
                            <th width="15%" class="text-center">Jumlah</th>
                            <th width="25%" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item) : ?>
                            <tr>
                                <td class="fw-semibold text-dark"><?= esc($item['nama_produk']) ?></td>
                                <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                                <td class="text-center"><?= $item['jumlah'] ?></td>
                                <td class="text-end fw-bold text-dark">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total Pembayaran:</td>
                            <td class="text-end fw-bold fs-6 text-dark">Rp <?= number_format($order['total_harga'], 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

