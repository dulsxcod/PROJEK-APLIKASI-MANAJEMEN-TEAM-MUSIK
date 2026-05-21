<?php
session_start();
include '../config/koneksi.php';
global $conn;

// Sekarang $_SESSION['UserID'] sudah terisi secara dinamis sesuai siapa yang login
$id_login = isset($_SESSION['UserID']) ? $_SESSION['UserID'] : 1;

// Query ini akan otomatis mengambil data ALIF (UserID: 4) jika Alif yang login
$query = mysqli_query($conn, "SELECT * FROM anggota WHERE UserID = $id_login");
$row = mysqli_fetch_assoc($query);
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

        /* Simulasi Grafik */
        .chart-container {
            height: 250px;
            position: relative;
        }

        .pulse-dot-chart {
            width: 12px;
            height: 12px;
            background: var(--accent-color);
            border-radius: 50%;
            position: absolute;
            left: 40%;
            bottom: 70%;
            box-shadow: 0 0 15px var(--accent-color);
        }

        .pulse-line {
            width: 1px;
            height: 70%;
            background: rgba(237, 177, 255, 0.3);
            position: absolute;
            left: 40.4%;
            bottom: 0;
            border-style: dashed;
        }

        .bar-chart-mock {
            height: 128px;
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .bar-item {
            flex: 1;
            background: rgba(214, 186, 255, 0.1);
            border-radius: 4px 4px 0 0;
            transition: background 0.2s;
        }

        .bar-item:hover {
            background: rgba(214, 186, 255, 0.4);
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

        .avatar-more {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--surface-variant);
            border: 2px solid var(--surface-color);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
        }

        /* Garis Waktu Aktivitas (Timeline) */
        .timeline-item {
            position: relative;
            padding-left: 20px;
        }

        .timeline-line {
            position: absolute;
            left: 27px;
            top: 40px;
            bottom: -20px;
            width: 2px;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Gaya Kustom Tabel */
        .custom-table th {
            background-color: rgba(255, 255, 255, 0.02) !important;
            color: var(--text-muted);
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .custom-table td {
            color: var(--text-primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            background: transparent !important;
        }

        .custom-table tr:hover td {
            background-color: rgba(255, 255, 255, 0.03) !important;
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

        .hover-purple:hover {
            color: var(--accent-color) !important;
            text-shadow: 0 0 8px rgba(237, 177, 255, 0.4);
        }

        .breadcrumb-item+.breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.3) !important;
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
                <a class="nav-link-custom" href="index.php">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    <span class="small fw-medium">Dashboard</span>
                </a>
                <a class="nav-link-custom" href="anggota.php">
                    <span class="material-symbols-outlined">group</span>
                    <span class="small fw-medium">Data Anggota</span>
                </a>
                <a class="nav-link-custom active" href="job.php">
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
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0 align-items-center" style="font-size: 13px; letter-spacing: 0.05em;">
                    <li class="breadcrumb-item">
                        <a href="index.php"
                            class="text-white text-decoration-none d-flex align-items-center gap-1 hover-purple">
                            <span class="material-symbols-outlined" style="font-size: 16px;">Dashboard</span> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="anggota.php"
                            class="text-white text-decoration-none d-flex align-items-center gap-1 hover-purple">
                            Jadwal/Job
                        </a>
                    </li>
                    <li class="breadcrumb-item active fw-semibold" aria-current="page"
                        style="color: var(--accent-color);">
                        Tambah Job
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
        <section class="mt-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="glass-panel p-4 p-md-5" style="border-radius: 16px;">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="p-2.5 rounded-3" style="background: rgba(237,177,255,0.1);">
                                <span class="material-symbols-outlined"
                                    style="color: var(--accent-color)">person_add</span>
                            </div>
                            <div>
                                <h2 class="h5 text-white mb-0 fw-bold">Tambah Job Baru</h2>
                                <p class="text-muted-custom small mb-0">Silakan isi formulir di bawah ini dengan
                                    lengkap.</p>
                            </div>
                        </div>

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

        // Logika sederhana saat menu samping diaktifkan (klik)
        const navItems = document.querySelectorAll('.nav-link-custom');
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                navItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            });
        });
    </script>
</body>

</html>