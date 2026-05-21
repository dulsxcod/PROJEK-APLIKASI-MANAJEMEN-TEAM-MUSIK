<?php
session_start();
include '../config/koneksi.php';
global $conn;

// Sekarang $_SESSION['UserID'] sudah terisi secara dinamis sesuai siapa yang login
$id_login = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 1;

// Query ini akan otomatis mengambil data ALIF (UserID: 4) jika Alif yang login
$query = mysqli_query($conn, "SELECT * FROM anggota WHERE UserID = $id_login");
$row = mysqli_fetch_assoc($query);


// Query 2: Menghitung total semua musisi di tabel anggota
$query_total_anggota = mysqli_query($conn, "SELECT COUNT(*) AS total_anggota FROM anggota");
$data_total_anggota = mysqli_fetch_assoc($query_total_anggota);
$total_anggota = $data_total_anggota['total_anggota'];


$query_total_job = mysqli_query($conn, "SELECT COUNT(*) AS total_job FROM job");
$data_total_job = mysqli_fetch_assoc($query_total_job);
$total_job = $data_total_job['total_job'];
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>BeatManager | Admin Studio Pro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <style>
        :root {
            --bg-color: #171218;
            --surface-color: #241e25;
            --surface-variant: #39333a;
            --primary-gradient: linear-gradient(135deg, #9d50bb 0%, #6e208c 100%);
            --accent-color: #edb1ff;
            --text-primary: #ebdfe9;
            --text-muted: #d1c2d2;
            --secondary-color: #d6baff;
            --tertiary-color: #d0cb59;
            --sidebar-width: 288px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            overflow-x: hidden;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }

        /* Panel Efek Kaca (Glassmorphism) */
        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            border-left: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-panel:hover {
            transform: translateY(-4px);
        }

        .glow-primary-card {
            background: linear-gradient(135deg, rgba(157, 80, 187, 0.1) 0%, transparent 100%);
            border-color: rgba(237, 177, 255, 0.2);
            box-shadow: 0 0 30px 0 rgba(237, 177, 255, 0.05);
        }

        /* TAMBAHAN: Efek Hover Kustom untuk Breadcrumbs */
        .hover-purple:hover {
            color: var(--accent-color) !important;
            text-shadow: 0 0 8px rgba(237, 177, 255, 0.4);
        }

        /* Scrollbar Kustom */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(237, 177, 255, 0.2);
            border-radius: 10px;
        }

        /* Navigasi Samping (Sidebar) */
        aside {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: rgba(23, 18, 24, 0.4);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 20px 0 40px rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }

        .brand-title {
            background: linear-gradient(to right, var(--accent-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            color: var(--text-primary);
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-link-custom.active {
            color: var(--accent-color);
            background: rgba(157, 80, 187, 0.15);
            border-left: 4px solid var(--accent-color);
            border-radius: 0 0.5rem 0.5rem 0;
        }

        /* Area Konten Utama */
        main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding-top: 100px;
        }

        /* Navigasi Atas (Top Navbar) */
        header {
            position: fixed;
            top: 0;
            right: 0;
            width: calc(100% - var(--sidebar-width));
            height: 80px;
            background: rgba(23, 18, 24, 0.4);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1040;
        }

        .search-box {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding-left: 45px;
            color: var(--text-primary);
        }

        .search-box:focus {
            background: rgba(0, 0, 0, 0.6);
            border-color: rgba(237, 177, 255, 0.5);
            box-shadow: 0 0 10px rgba(237, 177, 255, 0.2);
            color: var(--text-primary);
        }

        /* Tombol & Lencana */
        .gradient-btn {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .gradient-btn:hover {
            filter: brightness(1.1);
            box-shadow: 0 0 15px rgba(157, 80, 187, 0.4);
            color: white;
        }

        .outline-btn-rounded {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--accent-color);
            border-radius: 50px;
            padding: 6px 20px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .outline-btn-rounded:hover {
            border-color: var(--accent-color);
            background: rgba(237, 177, 255, 0.05);
            color: var(--accent-color);
        }

        /* Tumpukan Avatar */
        .avatar-stack img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--surface-color);
            margin-right: -12px;
            object-fit: cover;
        }

        /* Penyesuaian Responsif */
        @media (max-width: 991.98px) {
            aside {
                display: none;
            }

            header {
                width: 100%;
            }

            main {
                margin-left: 0;
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    </style>
</head>

<body>

    <aside class="d-flex flex-column justify-content-between py-4">
        <div>
            <div class="px-4 mb-4">
                <h1 class="h3 fw-bold brand-title mb-0">ADMIN Team</h1>
                <small class="text-white border opacity-50 text-uppercase tracking-widest"
                    style="font-size: 10px;">Admin Studio Pro</small>
            </div>

            <a href="index.php" class="text-decoration-none">
                <div class="px-3 mb-4">
                    <div class="d-flex align-items-center gap-3 p-3 glass-panel"
                        style="border-radius: 12px; transform: none;">
                        <img alt="Avatar Admin" class="rounded-circle object-fit-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCckUUmf2pbR34bOL0Ux5qx8dLAbJRNb85WVUwjNuAqbtdliy2ZTmqHnjSWxaeNODa9ueOiXQLONOyE_rRaYGvWoNDHRCWG3ejw1H4xJR-u5SSGZRZ_MITX1_GEuklqIjII4VUXL8rtHwEm665H2dssSxO90a7LCLaxLCb3Wnsk_nYhm6p4o1nElSk6gXk7OgIBLlp1drhqMKPMniywgNh489CDbLNkz4yv1lRo5tG3iPtI6s0d5SgPkwPyGY5zmoUcV2NfkkAyLC0"
                            style="width:40px; height:40px; border: 1px solid rgba(237,177,255,0.3);" />
                        <div class="overflow-hidden">
                            <p class="mb-0 fw-semibold text-white text-truncate small">
                                <?= isset($row['NamaLengkap']) ? $row['NamaLengkap'] : 'Admin'; ?>
                            </p>
                            <span class="text-white text-uppercase tracking-wider" style="font-size: 9px;">Kepala
                                Operasional</span>
                        </div>
                    </div>
                </div>
            </a>

            <nav class="px-2 d-flex flex-column gap-1 custom-scrollbar overflow-y-auto"
                style="max-height: calc(100vh - 320px);">
                <a class="nav-link-custom active" href="index.php">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    <span class="small fw-medium">Dashboard</span>
                </a>
                <a class="nav-link-custom" href="anggota.php">
                    <span class="material-symbols-outlined">group</span>
                    <span class="small fw-medium">Data Anggota</span>
                </a>
                <a class="nav-link-custom" href="job.php">
                    <span class="material-symbols-outlined">event</span>
                    <span class="small fw-medium">Jadwal/Job</span>
                </a>
                <a class="nav-link-custom" href="chat.php">
                    <span class="material-symbols-outlined">forum</span>
                    <span class="small fw-medium">Chat Group</span>
                </a>
            </nav>
        </div>

        <div class="px-3">
            <a href="../index.php" class="btn gradient-btn w-100 py-2.5 mb-3 rounded-3 shadow-sm text-sm">
                Logout
            </a>
            <div class="pt-3 border-top border-white border-opacity-10 d-flex flex-column gap-1">
                <a class="nav-link-custom py-1 text-sm" href="#">
                    <span class="material-symbols-outlined" style="font-size: 18px;">settings</span>
                    <span style="font-size: 13px;">Pengaturan</span>
                </a>
                <a class="nav-link-custom py-1 text-sm" href="#">
                    <span class="material-symbols-outlined" style="font-size: 18px;">help</span>
                    <span style="font-size: 13px;">Bantuan</span>
                </a>
            </div>
        </div>
    </aside>

    <header class="d-flex align-items-center justify-content-between px-4">
        <div class="d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 align-items-center" style="font-size: 13px; letter-spacing: 0.05em;">
                    <li class="breadcrumb-item active fw-semibold d-flex align-items-center gap-1" aria-current="page"
                        style="color: var(--accent-color); text-shadow: 0 0 8px rgba(237, 177, 255, 0.3);">
                        <span class="material-symbols-outlined" style="font-size: 16px;">dashboard</span> Dashboard
                    </li>
                </ol>
            </nav>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="position-relative d-none d-md-block" style="width: 280px;">
                <span class="material-symbols-outlined position-absolute opacity-50"
                    style="left: 16px; top: 50%; transform: translateY(-50%); font-size: 18px;">search</span>
                <input class="form-control search-box w-100 py-1.5" placeholder="Cari musisi, lagu..." type="text"
                    style="font-size: 13px;" />
            </div>
        </div>
    </header>

    <main class="px-4 pb-5">
        <section class="row g-4 mt-1">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3"
                            style="background: rgba(214,186,255,0.15); color: var(--secondary-color);">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Total
                            Anggota</p>
                        <h3 class="h2 fw-bold mb-0"><?= $total_anggota; ?> Anggota</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3"
                            style="background: rgba(214,186,255,0.15); color: var(--secondary-color);">
                            <span class="material-symbols-outlined">event</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Total Job
                        </p>
                        <h3 class="h2 fw-bold mb-0"><?= $total_job; ?> Job</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3"
                            style="background: rgba(208,203,89,0.15); color: var(--tertiary-color);">
                            <span class="material-symbols-outlined">event_available</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Agenda
                            Studio</p>
                        <h3 class="h2 fw-bold mb-0">8 Event</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3"
                            style="background: rgba(237,177,255,0.15); color: var(--accent-color);">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Estimasi
                            Kas</p>
                        <h3 class="h2 fw-bold mb-0" style="color: var(--accent-color);">$482k</h3>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Efek kustom sorotan cahaya mengikuti pointer mouse pada background
        const body = document.querySelector('body');
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.pointerEvents = 'none';
        overlay.style.zIndex = '1';
        overlay.style.background = 'radial-gradient(circle 800px at var(--x) var(--y), rgba(237, 177, 255, 0.03), transparent 80%)';
        body.appendChild(overlay);

        window.addEventListener('mousemove', (e) => {
            overlay.style.setProperty('--x', e.clientX + 'px');
            overlay.style.setProperty('--y', e.clientY + 'px');
        });
    </script>
</body>

</html>