<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Masuk - Online Shop') ?></title>

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
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .btn-dark {
            background-color: #0f172a;
            border-color: #0f172a;
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .btn-dark:hover {
            background-color: #1e293b;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
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
                        <div class="rounded-circle bg-dark text-white d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 56px; height: 56px;">
                            <i class="fas fa-bag-shopping fs-4"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Masuk ke Akun</h4>
                        <p class="text-muted small">Kelola belanja dan pesanan Anda dengan mudah.</p>
                    </div>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-dark bg-light border text-danger small py-2 px-3 mb-3 rounded-3">
                            <i class="fas fa-circle-exclamation me-1"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-dark bg-light border text-success small py-2 px-3 mb-3 rounded-3">
                            <i class="fas fa-circle-check me-1"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label small fw-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?= old('username') ?>" required autofocus>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 mb-3">
                            Masuk
                        </button>
                    </form>

                    
                    <div class="bg-light p-3 rounded-3 border text-center small text-muted mb-3">
                        <span class="d-block fw-bold text-dark mb-1">Akun Demo:</span>
                        Admin: <code>admin</code> / <code>admin123</code><br>
                        Customer: <code>budi</code> / <code>user123</code>
                    </div>

                    <div class="text-center small text-muted">
                        Belum punya akun? <a href="<?= site_url('register') ?>" class="text-dark fw-bold text-decoration-none">Daftar sekarang</a>
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

