<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Daftar Akun - Online Shop') ?></title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            padding: 2.5rem;
        }

        .btn-dark {
            background-color: #0f172a;
            border-color: #0f172a;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
        }

        .btn-dark:hover {
            background-color: #1e293b;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
        }

        .form-control:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
        }
    </style>
</head>

<body class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="auth-card">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 52px; height: 52px;">
                            <i class="fas fa-user-plus fs-4"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Daftar Akun Baru</h4>
                        <p class="text-muted small">Buat akun untuk mulai berbelanja.</p>
                    </div>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger small py-2 px-3 mb-3 rounded-3">
                            <ul class="mb-0 ps-3">
                                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                                    <li><?= esc($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('register') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Rian Pratama" value="<?= old('nama_lengkap') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username_kamu" value="<?= old('username') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="<?= old('email') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirm" class="form-control" placeholder="Ketik ulang password" required>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mb-3">
                            Daftar Akun
                        </button>
                    </form>

                    <div class="text-center small text-muted">
                        Sudah punya akun? <a href="<?= site_url('login') ?>" class="text-dark fw-bold text-decoration-none">Masuk di sini</a>
                    </div>
                </div>

                <div class="text-center mt-3 small">
                    <a href="<?= site_url('/') ?>" class="text-muted text-decoration-none">&larr; Kembali ke Katalog Toko</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

