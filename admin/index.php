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

// Query untuk menghitung total saldo kas
$query_saldo = mysqli_query($conn, "SELECT SUM(Pemasukan) - SUM(Pengeluaran) AS total_saldo FROM kas");
$data_saldo = mysqli_fetch_assoc($query_saldo);
$total_saldo = $data_saldo['total_saldo'] ?? 0; // Jika data kosong, default ke 0
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
            transform: translateY(-2px);
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

        /* Tombol & Form Dark Theme */
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

        .form-control-dark {
            background: rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: var(--text-primary) !important;
        }
        .form-control-dark:focus {
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 0.25rem rgba(237, 177, 255, 0.1) !important;
        }
        .form-control-dark::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Custom Tabs for Quick Input */
        .nav-pills-custom .nav-link {
            color: var(--text-muted);
            border-radius: 50px;
            padding: 8px 16px;
            font-size: 13px;
        }
        .nav-pills-custom .nav-link.active {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 10px rgba(157, 80, 187, 0.3);
        }

        /* Penyesuaian Responsif */
        @media (max-width: 991.98px) {
            aside { display: none; }
            header { width: 100%; }
            main {
                margin-left: 0;
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="d-flex flex-column justify-content-between py-4">
        <div>
            <div class="px-4 mb-4">
                <h1 class="h3 fw-bold brand-title mb-0">KIMOCHI Team</h1>
                <small class="text-white border opacity-50 text-uppercase tracking-widest" style="font-size: 10px;">Admin Studio Pro</small>
            </div>

            <a href="index.php" class="text-decoration-none">
                <div class="px-3 mb-4">
                    <div class="d-flex align-items-center gap-3 p-3 glass-panel" style="border-radius: 12px; transform: none;">
                        <img alt="Avatar Admin" class="rounded-circle object-fit-cover"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCckUUmf2pbR34bOL0Ux5qx8dLAbJRNb85WVUwjNuAqbtdliy2ZTmqHnjSWxaeNODa9ueOiXQLONOyE_rRaYGvWoNDHRCWG3ejw1H4xJR-u5SSGZRZ_MITX1_GEuklqIjII4VUXL8rtHwEm665H2dssSxO90a7LCLaxLCb3Wnsk_nYhm6p4o1nElSk6gXk7OgIBLlp1drhqMKPMniywgNh489CDbLNkz4yv1lRo5tG3iPtI6s0d5SgPkwPyGY5zmoUcV2NfkkAyLC0"
                            style="width:40px; height:40px; border: 1px solid rgba(237,177,255,0.3);" />
                        <div class="overflow-hidden">
                            <p class="mb-0 fw-semibold text-white text-truncate small">
                                <?= isset($row['NamaLengkap']) ? $row['NamaLengkap'] : 'Admin'; ?>
                            </p>
                            <span class="text-white text-uppercase tracking-wider" style="font-size: 9px;">Kepala Operasional</span>
                        </div>
                    </div>
                </div>
            </a>

            <nav class="px-2 d-flex flex-column gap-1 custom-scrollbar overflow-y-auto" style="max-height: calc(100vh - 320px);">
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
                <a class="nav-link-custom" href="kas.php">
                    <span class="material-symbols-outlined">money</span>
                    <span class="small fw-medium">Data Kas</span>
                </a>
            </nav>
        </div>

        <div class="px-3">
            <a href="../index.php" class="btn gradient-btn w-100 py-2.5 mb-3 rounded-3 shadow-sm text-sm">Logout</a>
        </div>
    </aside>

    <!-- HEADER -->
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
                <span class="material-symbols-outlined position-absolute opacity-50" style="left: 16px; top: 50%; transform: translateY(-50%); font-size: 18px;">search</span>
                <input class="form-control search-box w-100 py-1.5" placeholder="Cari musisi, lagu..." type="text" style="font-size: 13px;" />
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="px-4 pb-5">
        
        <!-- SECTION 1: STATISTIK (Seperti Sebelumnya) -->
        <section class="row g-4 mt-1">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3" style="background: rgba(214,186,255,0.15); color: var(--secondary-color);">
                            <span class="material-symbols-outlined">group</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Total Anggota</p>
                        <h3 class="h2 fw-bold mb-0"><?= $total_anggota; ?> Anggota</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3" style="background: rgba(214,186,255,0.15); color: var(--secondary-color);">
                            <span class="material-symbols-outlined">event</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Total Job</p>
                        <h3 class="h2 fw-bold mb-0"><?= $total_job; ?> Job</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3" style="background: rgba(208,203,89,0.15); color: var(--tertiary-color);">
                            <span class="material-symbols-outlined">event_available</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Agenda Studio</p>
                        <h3 class="h2 fw-bold mb-0">8 Event</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="glass-panel p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="p-2.5 rounded-3" style="background: rgba(237,177,255,0.15); color: var(--accent-color);">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-white small mb-1 tracking-wide text-uppercase" style="font-size: 10px;">Estimasi Kas</p>
                        <h3 class="h2 fw-bold mb-0" style="color: var(--accent-color);"><?= "Rp. " . number_format($total_saldo, 0, ',', '.'); ?> -,</h3>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION 2: INTERAKTIF (Feed Facebook & Quick Add Data) -->
        <section class="row g-4 mt-2">
            
            <!-- KOLOM KIRI: Postingan ala Facebook -->
            <div class="col-12 col-lg-7 col-xl-8">
                
                <!-- Kotak Buat Postingan -->
                <div class="glass-panel p-4 mb-4">
                    <form action="proses_post.php" method="POST">
                        <div class="d-flex gap-3 mb-3">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCckUUmf2pbR34bOL0Ux5qx8dLAbJRNb85WVUwjNuAqbtdliy2ZTmqHnjSWxaeNODa9ueOiXQLONOyE_rRaYGvWoNDHRCWG3ejw1H4xJR-u5SSGZRZ_MITX1_GEuklqIjII4VUXL8rtHwEm665H2dssSxO90a7LCLaxLCb3Wnsk_nYhm6p4o1nElSk6gXk7OgIBLlp1drhqMKPMniywgNh489CDbLNkz4yv1lRo5tG3iPtI6s0d5SgPkwPyGY5zmoUcV2NfkkAyLC0" 
                                 class="rounded-circle object-fit-cover" width="45" height="45" alt="Avatar">
                            <textarea name="isi_postingan" class="form-control form-control-dark" rows="3" 
                                      placeholder="Ada pengumuman atau info update apa hari ini, <?= isset($row['NamaLengkap']) ? explode(' ', $row['NamaLengkap'])[0] : 'Admin'; ?>?" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-light text-white border-0 d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined text-success" style="font-size: 20px;">image</span> Foto/Video
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-light text-white border-0 d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined text-info" style="font-size: 20px;">music_note</span> Audio
                                </button>
                            </div>
                            <button type="submit" name="submit_post" class="btn gradient-btn px-4 rounded-pill">Posting</button>
                        </div>
                    </form>
                </div>

                <!-- Feed Timeline (Contoh Postingan) -->
                <h6 class="text-white fw-bold mb-3 ms-1">Aktivitas Terkini</h6>
                
                <div class="glass-panel p-4 mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex gap-3 align-items-center">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCckUUmf2pbR34bOL0Ux5qx8dLAbJRNb85WVUwjNuAqbtdliy2ZTmqHnjSWxaeNODa9ueOiXQLONOyE_rRaYGvWoNDHRCWG3ejw1H4xJR-u5SSGZRZ_MITX1_GEuklqIjII4VUXL8rtHwEm665H2dssSxO90a7LCLaxLCb3Wnsk_nYhm6p4o1nElSk6gXk7OgIBLlp1drhqMKPMniywgNh489CDbLNkz4yv1lRo5tG3iPtI6s0d5SgPkwPyGY5zmoUcV2NfkkAyLC0" 
                                 class="rounded-circle" width="40" height="40" alt="Avatar">
                            <div>
                                <h6 class="mb-0 fw-bold text-white"><?= isset($row['NamaLengkap']) ? $row['NamaLengkap'] : 'Admin'; ?></h6>
                                <small class="text-white" style="font-size: 11px;">Baru saja • Divisi Operasional</small>
                            </div>
                        </div>
                        <button class="btn btn-sm text-white border-0"><span class="material-symbols-outlined">more_horiz</span></button>
                    </div>
                    
                    <p class="text-white-50 mb-3" style="font-size: 14px; line-height: 1.6;">
                        Halo tim! Jangan lupa besok kita ada loading alat jam 14.00 WIB untuk acara Wedding di Gedung Serbaguna. Pastikan kabel dan jack aman semua ya. Kas bulan ini juga tolong diselesaikan bagi yang belum. Semangat! 🎸🔥
                    </p>

                    <div class="d-flex gap-3 pt-3 border-top border-secondary border-opacity-25">
                        <button class="btn btn-sm btn-link text-decoration-none text-white d-flex align-items-center gap-2 p-0 hover-purple">
                            <span class="material-symbols-outlined" style="font-size: 18px;">thumb_up</span> Suka (4)
                        </button>
                        <button class="btn btn-sm btn-link text-decoration-none text-white d-flex align-items-center gap-2 p-0 hover-purple">
                            <span class="material-symbols-outlined" style="font-size: 18px;">chat_bubble</span> Komentar (1)
                        </button>
                    </div>
                </div>

            </div>

            <!-- KOLOM KANAN: Quick Input (Data Job, Kas, Anggota) -->
            <div class="col-12 col-lg-5 col-xl-4">
                <div class="glass-panel p-4">
                    <h6 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-warning">bolt</span> Input Cepat
                    </h6>
                    
                    <!-- Tabs Menu -->
                    <ul class="nav nav-pills nav-pills-custom mb-4 gap-2" id="quickAddTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="job-tab" data-bs-toggle="pill" data-bs-target="#job" type="button" role="tab">Job Baru</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="kas-tab" data-bs-toggle="pill" data-bs-target="#kas" type="button" role="tab">Kas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="anggota-tab" data-bs-toggle="pill" data-bs-target="#anggota" type="button" role="tab">Anggota</button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="quickAddTabContent">
                        
                        <!-- Form Data Job -->
                        <div class="tab-pane fade show active" id="job" role="tabpanel">
                            <form action="proses_tambah_job.php" method="POST">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Nama Tuan Rumah</label>
                                    <input type="text" name="NamaTuanRumah"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan nama tuan rumah" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Tanggal</label>
                                    <input type="date" name="Tanggal"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan nama lengkap" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Alamat</label>
                                    <input type="text" name="Alamat"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan alamat lengkap" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Nama Group</label>
                                    <input type="text" name="NamaGroup"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: PUTRA NAFITA CAHYA" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Seragam</label>
                                    <input type="text" name="Seragam"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: Batik" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div
                                    class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-white border-opacity-10">
                                    <a href="job.php"
                                        class="btn btn-sm d-inline-flex align-items-center gap-2 border border-white border-opacity-10 text-white p-2.5 px-4 rounded-3"
                                        style="background: rgba(255,255,255,0.02);">
                                        Batal
                                    </a>
                                    <button type="submit"
                                        class="btn btn-sm d-inline-flex align-items-center gap-2 text-white p-2.5 px-4 rounded-3"
                                        style="background: var(--primary-gradient);">
                                        <span class="material-symbols-outlined text-sm">save</span> Simpan Job
                                    </button>
                                </div>
                            </div>
                        </form>
                        </div>

                        <!-- Form Data Kas -->
                        <div class="tab-pane fade" id="kas" role="tabpanel">
                            <form action="proses_tambah_kas.php" method="POST">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Tanggal</label>
                                    <input type="date" name="Tanggal"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan nama tuan rumah" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Pemasukan</label>
                                    <input type="number" name="Pemasukan"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukan Nominal Uang Masuk" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Pengeluaran</label>
                                    <input type="number" name="Pengeluaran"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukan Nominal Uang Keluar" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Keterangan</label>
                                    <input type="text" name="Keterangan"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: Membeli Piano" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div
                                    class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-white border-opacity-10">
                                    <a href="kas.php"
                                        class="btn btn-sm d-inline-flex align-items-center gap-2 border border-white border-opacity-10 text-white p-2.5 px-4 rounded-3"
                                        style="background: rgba(255,255,255,0.02);">
                                        Batal
                                    </a>
                                    <button type="submit"
                                        class="btn btn-sm d-inline-flex align-items-center gap-2 text-white p-2.5 px-4 rounded-3"
                                        style="background: var(--primary-gradient);">
                                        <span class="material-symbols-outlined text-sm">save</span> Simpan Kas
                                    </button>
                                </div>
                            </div>
                        </form>
                        </div>

                        <!-- Form Data Anggota -->
                        <div class="tab-pane fade" id="anggota" role="tabpanel">
                            <form action="proses_tambah_anggota.php" method="POST">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label text-white-50 small fw-medium">Username</label>
                                    <input type="text" name="Username"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan username" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label text-white-50 small fw-medium">Password</label>
                                    <input type="password" name="Password"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan password" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Nama Lengkap</label>
                                    <input type="text" name="NamaLengkap"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Masukkan nama lengkap" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Tempat Lahir</label>
                                    <input type="text" name="TempatLahir"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: Sukabumi" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Tanggal Lahir</label>
                                    <input type="date" name="TanggalLahir"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: Sukabumi" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Jenis Kelamin</label>
                                    <select class="form-select border-0" name="JenisKelamin">
                                        <option value="Laki-laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Nomor HP</label>
                                    <input type="number" name="NoHP"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: 0123456789" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Role</label>
                                    <select class="form-select border-0" name="Role">
                                        <option value="admin">Admin</option>
                                        <option value="anggota">Anggota</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-white-50 small fw-medium">Bagian</label>
                                    <input type="text" name="Bagian"
                                        class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-3 p-2.5 small"
                                        placeholder="Contoh: Gendang" required style="transition: all 0.2s;"
                                        onfocus="this.style.borderColor='var(--accent-color)'; this.style.boxShadow='0 0 0 0.25rem rgba(237,177,255,0.1)';"
                                        onblur="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                </div>

                                <div
                                    class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-white border-opacity-10">
                                    <a href="anggota.php"
                                        class="btn btn-sm d-inline-flex align-items-center gap-2 border border-white border-opacity-10 text-white p-2.5 px-4 rounded-3"
                                        style="background: rgba(255,255,255,0.02);">
                                        Batal
                                    </a>
                                    <button type="submit"
                                        class="btn btn-sm d-inline-flex align-items-center gap-2 text-white p-2.5 px-4 rounded-3"
                                        style="background: var(--primary-gradient);">
                                        <span class="material-symbols-outlined text-sm">save</span> Simpan Anggota
                                    </button>
                                </div>
                            </div>
                        </form>
                        </div>

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