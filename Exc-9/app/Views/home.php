<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ShopEase - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= site_url('/') ?>">ShopEase</a>
        <div class="d-flex align-items-center gap-2">
            <?php if (session()->get('isLoggedIn')): ?>
                <span class="text-white small me-2">
                    Halo, <strong><?= esc((string) session()->get('name')) ?></strong> (<?= esc((string) session()->get('role')) ?>)
                </span>
                <form action="<?= site_url('logout') ?>" method="POST" class="m-0">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            <?php else: ?>
                <a href="<?= site_url('login') ?>" class="btn btn-outline-light btn-sm">Login</a>
                <a href="<?= site_url('register') ?>" class="btn btn-light btn-sm text-primary fw-semibold">Registrasi</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="card border-0 shadow-sm p-4">
        <h1 class="h3 fw-bold mb-2">Selamat Datang di ShopEase</h1>
        <p class="text-muted mb-0">
            <?php if (session()->get('isLoggedIn')): ?>
                Anda berhasil login sebagai <strong><?= esc((string) session()->get('role')) ?></strong> (<?= esc((string) session()->get('email')) ?>).
            <?php else: ?>
                Silakan login atau registrasi untuk mengakses fitur ShopEase.
            <?php endif; ?>
        </p>
    </div>
</div>
</body>
</html>
