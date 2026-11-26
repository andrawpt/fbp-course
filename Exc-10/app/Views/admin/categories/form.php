<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 fw-bold mb-0"><?= $category === null ? 'Tambah Kategori Baru' : 'Edit Kategori' ?></h2>
                <a href="<?= site_url('admin/categories') ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <form action="<?= esc($action) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" minlength="3" maxlength="100"
                           value="<?= esc((string) old('name', $category['name'] ?? '')) ?>" required>
                    <div class="form-text">Minimal 3 karakter, maksimal 100 karakter.</div>
                </div>

                <div class="mb-3">
                    <label for="icon" class="form-label small fw-semibold">Ikon Bootstrap (opsional)</label>
                    <input type="text" class="form-control" id="icon" name="icon" placeholder="Contoh: bi-laptop"
                           value="<?= esc((string) old('icon', $category['icon'] ?? 'bi-tag')) ?>">
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label small fw-semibold">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="4" maxlength="10000"><?= esc((string) old('description', $category['description'] ?? '')) ?></textarea>
                </div>

                <?php if ($category !== null): ?>
                    <div class="mb-3">
                        <label for="is_active" class="form-label small fw-semibold">Status Kategori</label>
                        <select name="is_active" id="is_active" class="form-select">
                            <option value="1" <?= (int) old('is_active', $category['is_active']) === 1 ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= (int) old('is_active', $category['is_active']) === 0 ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-semibold px-4">
                        <i class="bi bi-check-lg me-1"></i><?= $category === null ? 'Simpan Kategori' : 'Simpan Perubahan' ?>
                    </button>
                    <a href="<?= site_url('admin/categories') ?>" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
