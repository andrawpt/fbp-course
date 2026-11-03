<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= esc($title ?? 'SIAKAD - Sistem Informasi Akademik') ?></title>

    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" type="text/css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    
    <style>
        :root {
            --dark-main: #111827;
            --dark-card: #1f2937;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
            background-color: var(--light-bg) !important;
            color: #111827 !important;
        }

        
        .sidebar, .bg-gradient-primary {
            background: #0f172a !important;
            background-color: #0f172a !important;
            background-image: none !important;
            border-right: 1px solid #1e293b;
        }

        .sidebar-brand {
            padding: 1.5rem 1rem !important;
        }

        .sidebar-brand-text {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #ffffff;
        }

        .sidebar-brand-icon i {
            color: #ffffff;
        }

        .sidebar hr.sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.08) !important;
            margin: 0.75rem 1rem !important;
        }

        .sidebar .sidebar-heading {
            color: #64748b !important;
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 0 1.25rem !important;
        }

        .sidebar .nav-item .nav-link {
            color: #94a3b8 !important;
            padding: 0.75rem 1rem !important;
            margin: 0.15rem 0.75rem !important;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.15s ease-in-out;
        }

        .sidebar .nav-item .nav-link i {
            color: #94a3b8 !important;
            margin-right: 0.65rem;
            font-size: 0.95rem;
        }

        .sidebar .nav-item .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.06);
        }

        .sidebar .nav-item .nav-link:hover i {
            color: #ffffff !important;
        }

        .sidebar .nav-item.active .nav-link {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            font-weight: 600;
        }

        .sidebar .nav-item.active .nav-link i {
            color: #ffffff !important;
        }

        
        .topbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--border-color) !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.02) !important;
            height: 4.25rem !important;
        }

        .topbar .nav-link {
            height: 4.25rem;
        }

        
        .btn {
            border-radius: 8px !important;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.45rem 1rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
        }

        .btn:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-primary {
            background-color: #0f172a !important;
            border-color: #0f172a !important;
            color: #ffffff !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #1e293b !important;
            border-color: #1e293b !important;
            color: #ffffff !important;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.2);
        }

        .btn-outline-primary, .btn-outline-dark {
            color: #0f172a !important;
            border-color: #e2e8f0 !important;
            background-color: #ffffff;
        }

        .btn-outline-primary:hover, .btn-outline-dark:hover {
            background-color: #0f172a !important;
            border-color: #0f172a !important;
            color: #ffffff !important;
        }

        .btn-secondary {
            background-color: #f1f5f9 !important;
            border-color: #e2e8f0 !important;
            color: #334155 !important;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0 !important;
            color: #0f172a !important;
        }

        .btn-warning {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }

        .btn-warning:hover {
            background-color: #e2e8f0 !important;
            border-color: #94a3b8 !important;
            color: #000000 !important;
        }

        .btn-danger {
            background-color: #18181b !important;
            border-color: #18181b !important;
            color: #ffffff !important;
        }

        .btn-danger:hover {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
        }

        
        .card {
            border: 1px solid var(--border-color) !important;
            border-radius: 12px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02) !important;
            background-color: #ffffff;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px -4px rgba(15, 23, 42, 0.08) !important;
        }

        .card-header {
            border-bottom: 1px solid var(--border-color) !important;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            background-color: #ffffff !important;
            padding: 1rem 1.25rem !important;
        }

        
        .border-left-primary, .border-left-success, .border-left-info, .border-left-warning {
            border-left: 4px solid #0f172a !important;
        }

        
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .badge-primary, .badge-dark {
            background-color: #0f172a !important;
            color: #ffffff !important;
        }

        .badge-info, .badge-secondary, .badge-light {
            background-color: #f1f5f9 !important;
            color: #334155 !important;
            border: 1px solid #e2e8f0;
        }

        
        .table thead th {
            border-top: none;
            border-bottom: 1px solid var(--border-color);
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
        }

        .table tbody tr {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc !important;
        }

        .table td {
            vertical-align: middle;
            border-top: 1px solid var(--border-color);
            color: #1e293b;
        }

        
        .form-control, .form-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.55rem 0.85rem;
            font-size: 0.9rem;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-control:focus, .form-select:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
            transform: translateY(-1px);
        }

        
        @keyframes pageFadeInUp {
            0% {
                opacity: 0;
                transform: translateY(18px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container-fluid {
            animation: pageFadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        
        @keyframes dropdownSlide {
            0% {
                opacity: 0;
                transform: translateY(10px) scale(0.98);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .dropdown-menu {
            animation: dropdownSlide 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        
        .sidebar-brand:hover .sidebar-brand-icon i {
            transform: rotate(15deg) scale(1.15);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-control:focus, .form-select:focus {
            border-color: #0f172a;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.1);
        }

        
        footer.sticky-footer {
            background-color: #ffffff !important;
            border-top: 1px solid var(--border-color);
            padding: 1.5rem 0;
            color: var(--text-muted);
        }
    </style>
</head>

<body id="page-top">

    
    <div id="wrapper">

        
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= site_url('dashboard') ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-graduation-cap fa-lg"></i>
                </div>
                <div class="sidebar-brand-text mx-3">SIAKAD</div>
            </a>

            
            <hr class="sidebar-divider my-0">

            
            <li class="nav-item <?= uri_string() === 'dashboard' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('dashboard') ?>">
                    <i class="fas fa-fw fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            
            <hr class="sidebar-divider">

            
            <div class="sidebar-heading">
                Akademik
            </div>

            
            <li class="nav-item <?= str_starts_with(uri_string(), 'mahasiswa') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('mahasiswa') ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Mahasiswa</span>
                </a>
            </li>

            
            <hr class="sidebar-divider">

            
            <div class="sidebar-heading">
                Menu
            </div>

            
            <li class="nav-item <?= uri_string() === 'profile' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('profile') ?>">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Profil Saya</span>
                </a>
            </li>

            
            <li class="nav-item <?= uri_string() === 'about' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('about') ?>">
                    <i class="fas fa-fw fa-info-circle"></i>
                    <span>Tentang Sistem</span>
                </a>
            </li>

            
            <li class="nav-item <?= uri_string() === 'contact' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('contact') ?>">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span>Bantuan</span>
                </a>
            </li>

            
            <hr class="sidebar-divider d-none d-md-block">

            
            <div class="text-center d-none d-md-inline my-2">
                <button class="rounded-circle border-0" id="sidebarToggle" style="background-color: rgba(255,255,255,0.1);"></button>
            </div>

        </ul>
        

        
        <div id="content-wrapper" class="d-flex flex-column">

            
            <div id="content">

                
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top">

                    
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-dark">
                        <i class="fa fa-bars"></i>
                    </button>

                    
                    <div class="d-none d-sm-block">
                        <span class="font-weight-bold text-dark" style="letter-spacing: -0.01em;">
                            Sistem Informasi Akademik
                        </span>
                        <span class="text-muted small ml-2 border-left pl-2">Panel Admin</span>
                    </div>

                    
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-dark small font-weight-bold">
                                    <?= esc(session()->get('nama_lengkap') ?? 'Administrator') ?>
                                    <span class="badge badge-light ml-1 border text-uppercase" style="font-size: 0.65rem;"><?= esc(session()->get('role') ?? 'admin') ?></span>
                                </span>
                                <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </a>
                            
                            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 animated--fade-in mt-2" aria-labelledby="userDropdown" style="border-radius: 10px; border: 1px solid #e5e7eb !important;">
                                <a class="dropdown-item py-2 text-dark" href="<?= site_url('profile') ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-muted"></i>
                                    Profil Saya
                                </a>
                                <a class="dropdown-item py-2 text-dark" href="<?= site_url('about') ?>">
                                    <i class="fas fa-info fa-sm fa-fw mr-2 text-muted"></i>
                                    Tentang Sistem
                                </a>
                                <div class="dropdown-divider my-1"></div>
                                <a class="dropdown-item py-2 text-danger" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
                                    Keluar
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                

                
                <div class="container-fluid px-4">

                    
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-dark bg-white border border-dark text-dark alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-dark bg-white border border-danger text-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    
                    <?= $this->renderSection('content') ?>

                </div>
                

            </div>
            

            
            <footer class="sticky-footer mt-5">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto small">
                        <span>&copy; <?= date('Y') ?> <strong>SIAKAD</strong></span>
                    </div>
                </div>
            </footer>
            

        </div>
        

    </div>
    

    
    <a class="scroll-to-top rounded" href="#page-top" style="background-color: #0f172a;">
        <i class="fas fa-angle-up text-white"></i>
    </a>

    
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-sm" style="border-radius: 12px;">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title font-weight-bold text-dark" id="exampleModalLabel">Konfirmasi Keluar</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body py-4 text-muted">
                    Apakah Anda yakin ingin mengakhiri sesi kerja Anda dan keluar dari sistem?
                </div>
                <div class="modal-footer border-top bg-light" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary btn-sm" href="<?= site_url('logout') ?>">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/startbootstrap-sb-admin-2@4.1.4/js/sb-admin-2.min.js"></script>

</body>

</html>
