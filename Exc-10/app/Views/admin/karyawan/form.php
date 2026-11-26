<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0"><?= $karyawan === null ? 'Tambah Data Karyawan' : 'Edit Data Karyawan' ?></h2>
                <a href="<?= site_url('admin/karyawan') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <form action="<?= esc($action) ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nip" class="form-label small fw-semibold">NIP (8–20 digit) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nip" name="nip" pattern="\d{8,20}" minlength="8" maxlength="20"
                               value="<?= esc((string) old('nip', $karyawan['nip'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="name" class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" minlength="3" maxlength="100"
                               value="<?= esc((string) old('name', $karyawan['name'] ?? '')) ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="position" class="form-label small fw-semibold">Jabatan <span class="text-danger">*</span></label>
                        <?php $selectedPos = old('position', $karyawan['position'] ?? ''); ?>
                        <select name="position" id="position" class="form-select" required>
                            <option value="">-- Pilih Jabatan --</option>
                            <?php foreach ($positions as $pos): ?>
                                <option value="<?= esc($pos) ?>" <?= $selectedPos === $pos ? 'selected' : '' ?>><?= esc($pos) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                        <?php $selectedStatus = old('status', $karyawan['status'] ?? 'aktif'); ?>
                        <select name="status" id="status" class="form-select" required>
                            <option value="aktif" <?= $selectedStatus === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= $selectedStatus === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" maxlength="100"
                               value="<?= esc((string) old('email', $karyawan['email'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label small fw-semibold">Telepon (10–15 digit) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="phone" name="phone" pattern="\d{10,15}" minlength="10" maxlength="15"
                               value="<?= esc((string) old('phone', $karyawan['phone'] ?? '')) ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="hire_date" class="form-label small fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="hire_date" name="hire_date"
                           value="<?= esc((string) old('hire_date', $karyawan['hire_date'] ?? date('Y-m-d'))) ?>" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check-lg me-1"></i><?= $karyawan === null ? 'Simpan Karyawan' : 'Simpan Perubahan' ?>
                    </button>
                    <a href="<?= site_url('admin/karyawan') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
