<?= $this->extend('layouts/shop') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-5 shadow-sm border text-center mb-4">
            <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                <i class="fas fa-check fa-2x"></i>
            </div>
            <h3 class="fw-bold text-dark mb-1">Pesanan Anda Berhasil Dibuat!</h3>
            <p class="text-muted small mb-4">Terima kasih atas pesanan Anda. Kami sedang memproses pengiriman produk Anda.</p>

            
            <div class="text-start bg-light p-4 rounded border mb-4">
                <div class="row g-3 small">
                    <div class="col-sm-6">
                        <span class="text-muted d-block">Nomor Invoice:</span>
                        <strong class="fs-6 text-dark"><?= esc($order['invoice']) ?></strong>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted d-block">Tanggal:</span>
                        <strong class="text-dark"><?= date('d F Y, H:i', strtotime($order['created_at'])) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block">Penerima:</span>
                        <strong class="text-dark"><?= esc($order['nama_penerima']) ?> (<?= esc($order['telepon']) ?>)</strong>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted d-block">Status:</span>
                        <span class="badge bg-dark text-uppercase"><?= esc($order['status']) ?></span>
                    </div>
                    <div class="col-12 border-top pt-2">
                        <span class="text-muted d-block">Alamat Pengiriman:</span>
                        <span class="text-dark"><?= nl2br(esc($order['alamat'])) ?></span>
                    </div>
                </div>
            </div>

            
            <div class="table-responsive text-start mb-4">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light small">
                        <tr>
                            <th>Nama Produk</th>
                            <th width="20%">Harga</th>
                            <th width="12%" class="text-center">Jumlah</th>
                            <th width="25%" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="small">
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

            
            <div class="d-flex justify-content-center gap-2">
                <button onclick="window.print();" class="btn btn-outline-dark px-4">
                    <i class="fas fa-print me-1"></i> Cetak Invoice
                </button>
                <a href="<?= site_url('katalog') ?>" class="btn btn-dark px-4">
                    <i class="fas fa-bag-shopping me-1"></i> Belanja Lagi
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

