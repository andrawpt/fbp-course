<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Tentang Sistem
        </h1>
        <p class="text-muted small mb-0">Rincian arsitektur dan spesifikasi aplikasi SIAKAD.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Arsitektur & Spesifikasi</h6>
            </div>
            <div class="card-body p-4">
                <p class="text-dark">
                    <strong>SIAKAD CI4</strong> dibangun dengan arsitektur <strong>Model-View-Controller (MVC)</strong> dan mengusung antarmuka modern yang bersih, profesional, dan fokus pada efisiensi kerja.
                </p>

                <div class="my-4">
                    <div class="d-flex mb-3">
                        <div class="mr-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px;">
                                <i class="fas fa-shield-halved fa-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-1">Keamanan & Kontrol Akses</h6>
                            <p class="text-muted small mb-0">Autentikasi terpusat dengan filter rute, enkripsi Bcrypt, dan validasi sesi otomatis.</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="mr-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px;">
                                <i class="fas fa-database fa-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-1">Manajemen Data Lengkap (CRUD)</h6>
                            <p class="text-muted small mb-0">Pengelolaan data mahasiswa lengkap dengan validasi integritas data di tingkat framework.</p>
                        </div>
                    </div>

                    <div class="d-flex">
                        <div class="mr-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 36px; height: 36px;">
                                <i class="fas fa-file-arrow-up fa-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-1">Penanganan Berkas (File Upload)</h6>
                            <p class="text-muted small mb-0">Upload foto profil dengan validasi tipe berkas, pembatasan ukuran, dan pembersihan file lama otomatis.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge badge-light mr-2 py-2 px-3 border"><i class="fab fa-php mr-1"></i> PHP 8.2</span>
                    <span class="badge badge-light mr-2 py-2 px-3 border"><i class="fas fa-fire mr-1"></i> CodeIgniter 4</span>
                    <span class="badge badge-light mr-2 py-2 px-3 border"><i class="fab fa-bootstrap mr-1"></i> Minimalist UI</span>
                    <span class="badge badge-light py-2 px-3 border"><i class="fas fa-database mr-1"></i> MySQL / SQLite</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4 text-center p-4">
            <div class="card-body p-0">
                <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 72px; height: 72px;">
                    <i class="fas fa-user-tie fa-2x"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">Andraw Pratama</h5>
                <p class="text-muted small mb-3">Exercise 8 &bull; Fullstack Backend</p>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-outline-dark btn-sm btn-block">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
