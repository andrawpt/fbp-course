<footer class="se-footer pt-5 pb-4 mt-auto">
    <div class="container">
        <div class="row g-4 pb-4 border-bottom border-secondary border-opacity-25">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="se-brand-icon">
                        <i class="bi bi-bag-check-fill"></i>
                    </span>
                    <span class="fs-5 fw-bold text-white">ShopEase</span>
                </div>
                <p class="small mb-3">
                    Aplikasi toko online berbasis web untuk pengelolaan katalog terpusat, keranjang belanja, checkout, verifikasi pembayaran manual, dan pemantauan pengiriman.
                </p>
                <div class="d-flex gap-2">
                    <span class="badge bg-secondary bg-opacity-25 text-light fw-normal">IDR (Rupiah)</span>
                    <span class="badge bg-secondary bg-opacity-25 text-light fw-normal">CodeIgniter 4</span>
                    <span class="badge bg-secondary bg-opacity-25 text-light fw-normal">Bootstrap 5</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3">Navigasi</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="<?= site_url('/') ?>">Beranda</a></li>
                    <li><a href="#kategori">Kategori</a></li>
                    <li><a href="#produk-unggulan">Produk Unggulan</a></li>
                    <li><a href="#cara-belanja">Cara Belanja</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 col-6">
                <h6 class="text-white fw-bold mb-3">Layanan Pelanggan</h6>
                <ul class="list-unstyled small d-grid gap-2">
                    <li><a href="#cara-belanja">Panduan Pembayaran Manual</a></li>
                    <li><a href="#cara-belanja">Verifikasi &amp; Status Pesanan</a></li>
                    <li><a href="#cara-belanja">Informasi Pengiriman &amp; Resi</a></li>
                    <li><a href="#keunggulan">Keamanan Transaksi</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Informasi Toko</h6>
                <ul class="list-unstyled small d-grid gap-2 mb-0">
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-envelope text-primary"></i>
                        <span>support@shopease.local</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock text-primary"></i>
                        <span>Senin – Sabtu, 08.00 – 17.00 WIB</span>
                    </li>
                    <li class="d-flex align-items-center gap-2">
                        <i class="bi bi-shield-check text-primary"></i>
                        <span>Verifikasi Pembayaran Oleh Admin</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 gap-2 small">
            <p class="mb-0 text-secondary">
                &copy; <?= date('Y') ?> ShopEase – Online Store. All rights reserved.
            </p>
            <p class="mb-0 text-secondary">
                Studi Kasus Mata Kuliah Web Framework &bull; CodeIgniter 4 MVC
            </p>
        </div>
    </div>
</footer>
