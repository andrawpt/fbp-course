<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="d-flex flex-wrap gap-2 mb-3">
    <?php
    $filters = [
        ''                     => 'Semua',
        'pending_payment'      => 'Menunggu Bayar',
        'waiting_verification' => 'Perlu Verifikasi',
        'processing'           => 'Diproses Operator',
        'shipped'              => 'Dikirim',
        'completed'            => 'Selesai',
    ];
    ?>
    <?php foreach ($filters as $val => $label): ?>
        <a
            href="<?= site_url('admin/pesanan' . ($val !== '' ? '?status=' . $val : '')) ?>"
            class="btn btn-sm <?= ($selectedStatus ?? '') === $val ? 'btn-primary' : 'btn-outline-secondary' ?>"
        >
            <?= esc($label) ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status Pesanan</th>
                    <th>Status Bayar</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pesanan untuk filter ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td class="ps-4 fw-bold"><?= esc($order['order_number']) ?></td>
                            <td class="small">
                                <div class="fw-semibold"><?= esc($order['customer_name']) ?></div>
                                <div class="text-muted"><?= esc($order['customer_email']) ?></div>
                            </td>
                            <td class="small text-muted"><?= esc($order['order_date']) ?></td>
                            <td class="fw-bold text-primary">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></td>
                            <td><span class="badge bg-info-subtle text-info-emphasis"><?= esc($order['order_status']) ?></span></td>
                            <td><span class="badge bg-warning-subtle text-warning-emphasis"><?= esc((string) ($order['payment_status'] ?? 'unpaid')) ?></span></td>
                            <td class="pe-4 text-end">
                                <a href="<?= site_url('admin/pesanan/' . $order['order_number']) ?>" class="btn btn-sm btn-primary fw-semibold">
                                    <?= $order['order_status'] === 'waiting_verification' ? 'Verifikasi Bukti' : 'Detail' ?>
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
