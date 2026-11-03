<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Data Mahasiswa
        </h1>
        <p class="text-muted small mb-0">Kelola informasi seluruh mahasiswa aktif di sistem.</p>
    </div>
    <a href="<?= site_url('mahasiswa/create') ?>" class="btn btn-primary btn-sm shadow-sm">
        <i class="fas fa-plus mr-1"></i> Tambah Mahasiswa
    </a>
</div>

<div class="card mb-4">
    <div class="card-header d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-dark">Daftar Mahasiswa</h6>
        <span class="badge badge-light"><?= count($mahasiswa) ?> Mahasiswa</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="8%" class="text-center">Foto</th>
                        <th width="15%">NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th width="20%">Jurusan</th>
                        <th width="12%">Gender</th>
                        <th width="12%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mahasiswa) && is_array($mahasiswa)) : ?>
                        <?php $no = 1; foreach ($mahasiswa as $m) : ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                <td class="text-center">
                                    <?php if (!empty($m['foto']) && file_exists(FCPATH . 'uploads/mahasiswa/' . $m['foto'])) : ?>
                                        <img src="<?= base_url('uploads/mahasiswa/' . esc($m['foto'])) ?>" alt="Foto" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 1px solid #e2e8f0;">
                                    <?php else : ?>
                                        <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center border" style="width: 38px; height: 38px; font-size: 0.8rem;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><code class="text-dark font-weight-bold"><?= esc($m['nim']) ?></code></td>
                                <td class="font-weight-500 text-dark"><?= esc($m['nama']) ?></td>
                                <td class="text-muted small"><?= esc($m['email']) ?></td>
                                <td><span class="badge badge-light"><?= esc($m['jurusan']) ?></span></td>
                                <td class="small text-muted"><?= esc($m['jenis_kelamin']) ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('mahasiswa/edit/' . $m['id']) ?>" class="btn btn-outline-dark btn-sm py-1 px-2" title="Edit Data">
                                        <i class="fas fa-pen fa-xs"></i>
                                    </a>
                                    <a href="<?= site_url('mahasiswa/delete/' . $m['id']) ?>" class="btn btn-dark btn-sm py-1 px-2 ml-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data <?= esc($m['nama']) ?>?');" title="Hapus Data">
                                        <i class="fas fa-trash fa-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block text-gray-300"></i>
                                Belum ada data mahasiswa. Silakan klik tombol <strong>Tambah Mahasiswa</strong>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
