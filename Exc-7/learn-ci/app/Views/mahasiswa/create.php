<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Tambah Mahasiswa
        </h1>
        <p class="text-muted small mb-0">Isi formulir di bawah ini untuk menambahkan data mahasiswa baru.</p>
    </div>
    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Formulir Data Baru</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('mahasiswa/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">NIM (Nomor Induk Mahasiswa) <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control <?= isset($validation) && $validation->hasError('nim') ? 'is-invalid' : '' ?>" placeholder="Contoh: 220101001" value="<?= old('nim') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('nim')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('nim') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control <?= isset($validation) && $validation->hasError('nama') ? 'is-invalid' : '' ?>" placeholder="Contoh: Ahmad Fauzi" value="<?= old('nama') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('nama')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" placeholder="nama@student.ac.id" value="<?= old('email') ?>" required>
                            <?php if (isset($validation) && $validation->hasError('email')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">Program Studi <span class="text-danger">*</span></label>
                            <select name="jurusan" class="form-control <?= isset($validation) && $validation->hasError('jurusan') ? 'is-invalid' : '' ?>" required>
                                <option value="" disabled <?= old('jurusan') ? '' : 'selected' ?>>-- Pilih Program Studi --</option>
                                <option value="Teknik Informatika" <?= old('jurusan') === 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                                <option value="Sistem Informasi" <?= old('jurusan') === 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                                <option value="Teknik Komputer" <?= old('jurusan') === 'Teknik Komputer' ? 'selected' : '' ?>>Teknik Komputer</option>
                                <option value="Teknologi Informasi" <?= old('jurusan') === 'Teknologi Informasi' ? 'selected' : '' ?>>Teknologi Informasi</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('jurusan')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('jurusan') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="genderL" name="jenis_kelamin" class="custom-control-input" value="Laki-laki" <?= old('jenis_kelamin') === 'Perempuan' ? '' : 'checked' ?>>
                            <label class="custom-control-label small" for="genderL">Laki-laki</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="genderP" name="jenis_kelamin" class="custom-control-input" value="Perempuan" <?= old('jenis_kelamin') === 'Perempuan' ? 'checked' : '' ?>>
                            <label class="custom-control-label small" for="genderP">Perempuan</label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-dark">Foto Profil Mahasiswa</label>
                        <div class="custom-file">
                            <input type="file" name="foto" class="custom-file-input <?= isset($validation) && $validation->hasError('foto') ? 'is-invalid' : '' ?>" id="fotoMahasiswa" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(this);">
                            <label class="custom-file-label" for="fotoMahasiswa">Pilih berkas foto...</label>
                            <?php if (isset($validation) && $validation->hasError('foto')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('foto') ?></div>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-muted">Format: JPG, PNG, WEBP &bull; Maksimal 2 MB.</small>
                    </div>

                    <div class="form-group text-center my-3 d-none" id="previewContainer">
                        <label class="small font-weight-bold text-muted d-block mb-2">Preview Foto:</label>
                        <img id="imgPreview" src="#" alt="Preview" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Simpan Data
                    </button>
                    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-secondary px-3 ml-2">
                        Batal
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (file) {
        const label = input.nextElementSibling;
        label.innerText = file.name;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imgPreview');
            preview.src = e.target.result;
            document.getElementById('previewContainer').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?= $this->endSection() ?>
