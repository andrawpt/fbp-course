<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Operator Dashboard - ShopEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-success">
    <div class="container">
        <span class="navbar-brand fw-bold">ShopEase Operator Panel</span>
        <form action="<?= site_url('logout') ?>" method="POST" class="m-0">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
        </form>
    </div>
</nav>
<div class="container py-5">
    <div class="card border-0 shadow-sm p-4">
        <h1 class="h4 fw-bold">Dashboard Operator Toko</h1>
        <p class="text-muted mb-0">
            Login sebagai: <strong><?= esc((string) session()->get('name')) ?></strong> (Role: <?= esc((string) session()->get('role')) ?>)
        </p>
    </div>
</div>
</body>
</html>
