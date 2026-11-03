<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Dashboard
        </h1>
        <p class="text-muted small mb-0">Ringkasan aktivitas dan metrik sistem akademik.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('mahasiswa/create') ?>" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Mahasiswa
        </a>
    </div>
</div>

<div class="row">

    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 py-2 border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Mahasiswa</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= esc($totalMahasiswa ?? 0) ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 py-2 border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Pengguna Terdaftar</div>
                        <div class="h4 mb-0 font-weight-bold text-dark"><?= esc($totalUser ?? 1) ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 py-2 border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Dosen Aktif</div>
                        <div class="h4 mb-0 font-weight-bold text-dark">25</div>
                    </div>
                    <div class="col-auto">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 py-2 border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Mata Kuliah</div>
                        <div class="h4 mb-0 font-weight-bold text-dark">40</div>
                    </div>
                    <div class="col-auto">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-dark" style="width: 44px; height: 44px;">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-dark">Mahasiswa Terbaru</h6>
                <a href="<?= site_url('mahasiswa') ?>" class="btn btn-sm btn-outline-dark">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="10%">Foto</th>
                                <th width="25%">NIM</th>
                                <th>Nama Lengkap</th>
                                <th width="30%">Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentMahasiswa)) : ?>
                                <?php foreach ($recentMahasiswa as $m) : ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($m['foto']) && file_exists(FCPATH . 'uploads/mahasiswa/' . $m['foto'])) : ?>
                                                <img src="<?= base_url('uploads/mahasiswa/' . esc($m['foto'])) ?>" alt="Foto" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover; border: 1px solid #e2e8f0;">
                                            <?php else : ?>
                                                <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center border" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td><code class="text-dark font-weight-bold"><?= esc($m['nim']) ?></code></td>
                                        <td class="font-weight-500"><?= esc($m['nama']) ?></td>
                                        <td><span class="badge badge-light"><?= esc($m['jurusan']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        Belum ada data mahasiswa terdaftar.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <a href="<?= site_url('mahasiswa/create') ?>" class="btn btn-outline-dark btn-block mb-2 text-left">
                    <i class="fas fa-user-plus mr-2"></i> Tambah Mahasiswa Baru
                </a>
                <a href="<?= site_url('mahasiswa') ?>" class="btn btn-outline-dark btn-block mb-2 text-left">
                    <i class="fas fa-table-list mr-2"></i> Kelola Seluruh Data
                </a>
                <a href="<?= site_url('profile') ?>" class="btn btn-outline-dark btn-block text-left">
                    <i class="fas fa-user-gear mr-2"></i> Profil Pengguna
                </a>
            </div>
        </div>

        <div class="card border-left-primary">
            <div class="card-body">
                <div class="font-weight-bold text-dark mb-1 small text-uppercase" style="letter-spacing: 0.05em;">
                    Status Sistem
                </div>
                <p class="small text-muted mb-0">
                    Sistem terenkripsi aktif. Semua rute diproteksi oleh <strong>AuthFilter</strong> dengan manajemen sesi minimalis.
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
