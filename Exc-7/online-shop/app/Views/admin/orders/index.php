<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Manajemen Pesanan Masuk</h4>
        <p class="text-muted small mb-0">Pantau seluruh transaksi dan perbarui status pengiriman.</p>
    </div>
</div>

<div class="card p-3 shadow-sm border">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 small">
            <thead class="table-light">
                <tr>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Nama Penerima</th>
                    <th>Telepon</th>
                    <th>Total Belanja</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)) : ?>
                    <?php foreach ($orders as $o) : ?>
                        <tr>
                            <td><strong class="text-dark"><?= esc($o['invoice']) ?></strong></td>
                            <td class="text-muted"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
                            <td class="fw-semibold text-dark"><?= esc($o['nama_penerima']) ?></td>
                            <td><?= esc($o['telepon']) ?></td>
                            <td class="fw-bold text-dark">Rp <?= number_format($o['total_harga'], 0, ',', '.') ?></td>
                            <td>
                                <?php
                                $badgeColor = match($o['status']) {
                                    'completed'  => 'bg-success',
                                    'processing' => 'bg-info',
                                    'cancelled'  => 'bg-danger',
                                    default      => 'bg-dark',
                                };
                                ?>
                                <span class="badge <?= $badgeColor ?> text-uppercase"><?= esc($o['status']) ?></span>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('admin/orders/detail/' . $o['id']) ?>" class="btn btn-sm btn-outline-dark py-1 px-3">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

