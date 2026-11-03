<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <div>
        <h1 class="h4 mb-1 text-dark font-weight-bold">
            Pusat Bantuan
        </h1>
        <p class="text-muted small mb-0">Hubungi pengelola sistem untuk bantuan teknis dan pertanyaan.</p>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-5 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Informasi Layanan</h6>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark small">Gedung Akademik</div>
                        <small class="text-muted">Jl. Kampus Merdeka No. 12, Indonesia</small>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark small">Email Bantuan</div>
                        <small class="text-muted">support@siakad.ac.id</small>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <div class="font-weight-bold text-dark small">Telepon / Helpdesk</div>
                        <small class="text-muted">(021) 555-0199 (Senin-Jumat)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-7 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-dark">Kirim Tiket Bantuan</h6>
            </div>
            <div class="card-body p-4">
                <form onsubmit="event.preventDefault(); alert('Tiket bantuan berhasil dikirim.');">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark">Topik Kendala</label>
                        <select class="form-control" required>
                            <option value="">-- Pilih Topik Kendala --</option>
                            <option value="login">Kendala Login / Hak Akses</option>
                            <option value="upload">Kendala Upload Foto Mahasiswa</option>
                            <option value="data">Perubahan Data Mahasiswa</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-dark">Deskripsi</label>
                        <textarea class="form-control" rows="4" placeholder="Tuliskan kendala Anda secara singkat..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">
                        Kirim Tiket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
