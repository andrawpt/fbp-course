<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Profil Pengguna
        </h1>
        <p class="text-muted small mb-0">Rincian identitas dan hak akses akun Anda.</p>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4 mb-4">
        <div class="card text-center p-4">
            <div class="card-body p-0">
                <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-user fa-2x"></i>
                </div>
                <h5 class="font-weight-bold text-dark mb-1">
                    <?= esc(session()->get('nama_lengkap') ?? 'Administrator') ?>
                </h5>
                <span class="badge badge-light border text-uppercase mb-3 px-3 py-1">
                    <?= esc(session()->get('role') ?? 'admin') ?>
                </span>

                <hr class="my-3">

                <div class="text-left small text-muted">
                    <div class="mb-2"><strong class="text-dark">Username:</strong> <code><?= esc(session()->get('username') ?? 'admin') ?></code></div>
                    <div class="mb-2"><strong class="text-dark">User ID:</strong> #<?= esc(session()->get('userId') ?? 1) ?></div>
                    <div><strong class="text-dark">Status:</strong> Aktif (Sesi Terotentikasi)</div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Rincian Informasi Akun</h6>
            </div>
            <div class="card-body p-4">
                <div class="form-group row border-bottom pb-3 mb-3">
                    <label class="col-sm-3 col-form-label font-weight-bold text-dark">Nama Lengkap</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext font-weight-500 text-dark" value="<?= esc(session()->get('nama_lengkap') ?? 'Administrator Akademik') ?>">
                    </div>
                </div>

                <div class="form-group row border-bottom pb-3 mb-3">
                    <label class="col-sm-3 col-form-label font-weight-bold text-dark">Username</label>
                    <div class="col-sm-9">
                        <input type="text" readonly class="form-control-plaintext font-weight-bold text-dark" value="<?= esc(session()->get('username') ?? 'admin') ?>">
                    </div>
                </div>

                <div class="form-group row border-bottom pb-3 mb-3">
                    <label class="col-sm-3 col-form-label font-weight-bold text-dark">Hak Akses</label>
                    <div class="col-sm-9">
                        <span class="badge badge-dark text-uppercase"><?= esc(session()->get('role') ?? 'admin') ?></span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-primary btn-sm">
                        Kelola Mahasiswa
                    </a>
                    <a href="#" data-toggle="modal" data-target="#logoutModal" class="btn btn-outline-dark btn-sm ml-2">
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
