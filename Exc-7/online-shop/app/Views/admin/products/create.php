<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Tambah Produk Baru</h4>
        <p class="text-muted small mb-0">Lengkapi formulir untuk menambahkan produk ke katalog.</p>
    </div>
    <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-dark btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <?php $errors = session()->getFlashdata('errors') ?? []; ?>
        <div class="card p-4 shadow-sm border">
            <form action="<?= site_url('admin/products/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="nama_produk" class="form-control <?= isset($errors['nama_produk']) ? 'is-invalid' : '' ?>" placeholder="Contoh: Mechanical Keyboard Wireless" value="<?= old('nama_produk') ?>" required>
                    <?php if (isset($errors['nama_produk'])) : ?>
                        <div class="invalid-feedback"><?= $errors['nama_produk'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Kategori Produk <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>" required>
                            <option value="" disabled <?= old('category_id') ? '' : 'selected' ?>>-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>" <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category_id'])) : ?>
                            <div class="invalid-feedback"><?= $errors['category_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Harga Produk (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="harga" class="form-control <?= isset($errors['harga']) ? 'is-invalid' : '' ?>" placeholder="Contoh: 150000" value="<?= old('harga') ?>" min="1" required>
                        <?php if (isset($errors['harga'])) : ?>
                            <div class="invalid-feedback"><?= $errors['harga'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Stok Awal <span class="text-danger">*</span></label>
                    <input type="number" name="stok" class="form-control <?= isset($errors['stok']) ? 'is-invalid' : '' ?>" placeholder="Contoh: 20" value="<?= old('stok', 10) ?>" min="0" required>
                    <?php if (isset($errors['stok'])) : ?>
                        <div class="invalid-feedback"><?= $errors['stok'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Deskripsi Produk</label>
                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan spesifikasi dan keunggulan produk..."><?= old('deskripsi') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Upload Foto Produk</label>
                    <input type="file" name="gambar" class="form-control <?= isset($errors['gambar']) ? 'is-invalid' : '' ?>" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImg(this)">
                    <small class="form-text text-muted">Format: JPG, PNG, WEBP &bull; Maksimal 2 MB.</small>
                    <?php if (isset($errors['gambar'])) : ?>
                        <div class="invalid-feedback d-block"><?= $errors['gambar'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4 text-center d-none" id="previewBox">
                    <span class="small text-muted d-block mb-1">Preview Gambar:</span>
                    <img id="imgPreview" src="#" alt="Preview" class="rounded border" style="max-height: 140px; object-fit: contain;">
                </div>

                <hr class="my-4">

                <button type="submit" class="btn btn-dark px-4">
                    <i class="fas fa-save me-1"></i> Simpan Produk
                </button>
                <a href="<?= site_url('admin/products') ?>" class="btn btn-outline-dark px-3 ms-2">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imgPreview').src = e.target.result;
            document.getElementById('previewBox').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>

