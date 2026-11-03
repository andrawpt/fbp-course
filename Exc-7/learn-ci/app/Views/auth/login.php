<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'Login - SIAKAD') ?></title>

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            overflow-x: hidden;
        }

        
        @keyframes slideUpFade {
            0% {
                opacity: 0;
                transform: translateY(28px) scale(0.97);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes pulseIcon {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.06);
            }
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.06);
            padding: 2.5rem;
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-card:hover {
            box-shadow: 0 16px 36px -6px rgba(0, 0, 0, 0.09);
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            background-color: #0f172a;
            color: #ffffff;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.25rem;
            animation: pulseIcon 3s ease-in-out infinite;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .brand-logo:hover {
            transform: rotate(12deg) scale(1.12);
        }

        .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            height: auto;
            font-size: 0.95rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-control:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.12);
            transform: translateY(-2px);
        }

        .btn-submit {
            background-color: #0f172a;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 0.8rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-submit:hover {
            background-color: #1e293b;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.2);
        }

        .btn-submit:active {
            transform: translateY(0) scale(0.98);
        }

        .demo-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .demo-box:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>

<body class="min-vh-100 d-flex align-items-center justify-content-center py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                <div class="login-card">

                    
                    <div class="text-center mb-4">
                        <div class="brand-logo shadow-sm">
                            <i class="fas fa-graduation-cap fa-lg"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark mb-1">Masuk ke SIAKAD</h4>
                        <p class="text-muted small">Sistem Informasi Akademik</p>
                    </div>

                    
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-dark bg-light border text-danger small py-2 px-3 mb-3" style="border-radius: 8px;">
                            <i class="fas fa-circle-exclamation mr-1"></i>
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-dark bg-light border text-success small py-2 px-3 mb-3" style="border-radius: 8px;">
                            <i class="fas fa-circle-check mr-1"></i>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    
                    <form action="<?= site_url('login') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted mb-1">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?= old('username') ?>" required autofocus>
                        </div>

                        <div class="form-group mb-4">
                            <label class="small font-weight-bold text-muted mb-1">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="btn btn-submit btn-block">
                            Masuk
                        </button>
                    </form>

                    
                    <div class="demo-box mt-4 text-center">
                        <span class="small text-muted d-block font-weight-bold mb-1">Akun Akses Default:</span>
                        <code class="small text-dark font-weight-bold">admin</code> &bull; <code class="small text-dark font-weight-bold">admin123</code>
                    </div>

                </div>

                <div class="text-center mt-4">
                    <span class="small text-muted">&copy; <?= date('Y') ?> SIAKAD</span>
                </div>

            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
