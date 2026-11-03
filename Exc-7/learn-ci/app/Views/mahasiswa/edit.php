<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Edit Data Mahasiswa
        </h1>
        <p class="text-muted small mb-0">Perbarui profil dan rincian data mahasiswa.</p>
    </div>
    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Data Mahasiswa: <strong><?= esc($mahasiswa['nama']) ?></strong></h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= site_url('mahasiswa/update/' . $mahasiswa['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">NIM (Nomor Induk Mahasiswa) <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control <?= isset($validation) && $validation->hasError('nim') ? 'is-invalid' : '' ?>" value="<?= old('nim', $mahasiswa['nim']) ?>" required>
                            <?php if (isset($validation) && $validation->hasError('nim')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('nim') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control <?= isset($validation) && $validation->hasError('nama') ? 'is-invalid' : '' ?>" value="<?= old('nama', $mahasiswa['nama']) ?>" required>
                            <?php if (isset($validation) && $validation->hasError('nama')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" value="<?= old('email', $mahasiswa['email']) ?>" required>
                            <?php if (isset($validation) && $validation->hasError('email')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label class="small font-weight-bold text-dark">Program Studi <span class="text-danger">*</span></label>
                            <select name="jurusan" class="form-control <?= isset($validation) && $validation->hasError('jurusan') ? 'is-invalid' : '' ?>" required>
                                <?php $selectedJurusan = old('jurusan', $mahasiswa['jurusan']); ?>
                                <option value="Teknik Informatika" <?= $selectedJurusan === 'Teknik Informatika' ? 'selected' : '' ?>>Teknik Informatika</option>
                                <option value="Sistem Informasi" <?= $selectedJurusan === 'Sistem Informasi' ? 'selected' : '' ?>>Sistem Informasi</option>
                                <option value="Teknik Komputer" <?= $selectedJurusan === 'Teknik Komputer' ? 'selected' : '' ?>>Teknik Komputer</option>
                                <option value="Teknologi Informasi" <?= $selectedJurusan === 'Teknologi Informasi' ? 'selected' : '' ?>>Teknologi Informasi</option>
                            </select>
                            <?php if (isset($validation) && $validation->hasError('jurusan')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('jurusan') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                        <?php $selectedGender = old('jenis_kelamin', $mahasiswa['jenis_kelamin']); ?>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="genderL" name="jenis_kelamin" class="custom-control-input" value="Laki-laki" <?= $selectedGender === 'Laki-laki' ? 'checked' : '' ?>>
                            <label class="custom-control-label small" for="genderL">Laki-laki</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="genderP" name="jenis_kelamin" class="custom-control-input" value="Perempuan" <?= $selectedGender === 'Perempuan' ? 'checked' : '' ?>>
                            <label class="custom-control-label small" for="genderP">Perempuan</label>
                        </div>
                    </div>

                    
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark d-block">Foto Profil Saat Ini:</label>
                        <?php if (!empty($mahasiswa['foto']) && file_exists(FCPATH . 'uploads/mahasiswa/' . $mahasiswa['foto'])) : ?>
                            <img src="<?= base_url('uploads/mahasiswa/' . esc($mahasiswa['foto'])) ?>" alt="Foto" class="rounded-circle border mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                        <?php else : ?>
                            <div class="rounded-circle bg-light border text-muted d-inline-flex align-items-center justify-content-center mb-2" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-lg"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-dark">Ganti Foto Profil (Opsional)</label>
                        <div class="custom-file">
                            <input type="file" name="foto" class="custom-file-input <?= isset($validation) && $validation->hasError('foto') ? 'is-invalid' : '' ?>" id="fotoMahasiswa" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewImage(this);">
                            <label class="custom-file-label" for="fotoMahasiswa">Pilih foto baru...</label>
                            <?php if (isset($validation) && $validation->hasError('foto')) : ?>
                                <div class="invalid-feedback"><?= $validation->getError('foto') ?></div>
                            <?php endif; ?>
                        </div>
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto profil.</small>
                    </div>

                    <div class="form-group text-center my-3 d-none" id="previewContainer">
                        <label class="small font-weight-bold text-muted d-block mb-2">Preview Foto Baru:</label>
                        <img id="imgPreview" src="#" alt="Preview Foto Baru" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <hr class="my-4">

                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Perbarui Data
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
