<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>

<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">
        <i class="fas fa-clock-rotate-left me-2"></i>Riwayat Pesanan Saya
    </h3>
    <p class="text-muted small">Daftar transaksi dan status pemesanan Anda.</p>
</div>

<?php if (!empty($orders)) : ?>
    <div class="card shadow-sm border p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Penerima</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach ($orders as $o) : ?>
                        <tr>
                            <td><strong class="text-dark"><?= esc($o['invoice']) ?></strong></td>
                            <td class="text-muted"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
                            <td><?= esc($o['nama_penerima']) ?></td>
                            <td class="fw-bold text-dark">Rp <?= number_format($o['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge bg-dark text-uppercase"><?= esc($o['status']) ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('checkout/success/' . $o['invoice']) ?>" class="btn btn-sm btn-outline-dark">
                                    <i class="fas fa-file-lines me-1"></i> Lihat Invoice
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php else : ?>
    <div class="card p-5 text-center bg-white border">
        <i class="fas fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
        <h5 class="fw-bold text-dark mb-1">Belum Ada Riwayat Pesanan</h5>
        <p class="text-muted small mb-3">Anda belum pernah melakukan pemesanan produk di toko kami.</p>
        <div>
            <a href="<?= site_url('katalog') ?>" class="btn btn-dark btn-sm px-4">Mulai Belanja</a>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

