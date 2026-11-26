<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="py-5 se-section-bg">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Dashboard Customer</h1>
                <p class="text-muted small mb-0">Kelola profil akun dan pantau ringkasan aktivitas belanja Anda.</p>
            </div>
            <a href="<?= site_url('/') ?>#produk-unggulan" class="btn btn-primary btn-sm fw-semibold">
                <i class="bi bi-bag me-1"></i>Mulai Belanja
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= esc((string) session()->getFlashdata('success')) ?>
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

        <!-- Ringkasan Aktivitas -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="small text-muted">Total Pesanan</div>
                    <div class="fs-3 fw-bold text-primary"><?= esc((string) $totalOrders) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="small text-muted">Menunggu Bayar</div>
                    <div class="fs-3 fw-bold text-warning"><?= esc((string) $pendingCount) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="small text-muted">Sedang Diproses</div>
                    <div class="fs-3 fw-bold text-info"><?= esc((string) $activeCount) ?></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 p-3">
                    <div class="small text-muted">Selesai</div>
                    <div class="fs-3 fw-bold text-success"><?= esc((string) $doneCount) ?></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Profil Pengguna -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h2 class="h5 fw-bold mb-3">
                        <i class="bi bi-person-badge text-primary me-2"></i>Profil Pengguna
                    </h2>
                    <form action="<?= site_url('akun/profil') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nama Lengkap</label>
                            <input type="text" class="form-control" name="name" value="<?= esc((string) ($user['name'] ?? '')) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" class="form-control" value="<?= esc((string) ($user['email'] ?? '')) ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone" value="<?= esc((string) ($user['phone'] ?? '')) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Alamat</label>
                            <textarea class="form-control" name="address" rows="2"><?= esc((string) ($user['address'] ?? '')) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-semibold">Simpan Profil</button>
                    </form>
                </div>
            </div>

            <!-- Aktivitas Pesanan Terbaru -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h2 class="h5 fw-bold mb-0">Aktivitas Pesanan Terbaru</h2>
                        <a href="<?= site_url('pesanan') ?>" class="btn btn-sm btn-outline-primary fw-semibold">Lihat Semua</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Pesanan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentOrders)): ?>
                                    <tr><td colspan="4" class="text-center text-muted py-4">Belum ada aktivitas pesanan.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold"><?= esc($order['order_number']) ?></td>
                                            <td class="fw-semibold text-primary">Rp <?= number_format((float) $order['total'], 0, ',', '.') ?></td>
                                            <td><span class="badge bg-info-subtle text-info-emphasis"><?= esc($order['order_status']) ?></span></td>
                                            <td class="pe-4 text-end">
                                                <a href="<?= site_url('pesanan/' . $order['order_number']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
