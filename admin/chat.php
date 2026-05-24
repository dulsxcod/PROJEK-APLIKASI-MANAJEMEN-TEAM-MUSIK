<?php
// 1. Jalankan session di baris paling atas agar tahu siapa yang login
session_start();

// Proteksi halaman: Jika belum login, tendang ke halaman utama/login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location:../index.php");
    exit();
}

include '../config/koneksi.php';
global $conn;

// 2. Ambil UserID asli dari session akun yang sedang login saat ini
$id_login = $_SESSION['UserID'];

// Query mengambil data profil untuk sidebar/header sesuai akun yang login
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
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

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

        main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding-top: 100px;
        }

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

        @media (max-width: 991.98px) {
            aside { display: none; }
            header { width: 100%; }
            main { margin-left: 0; padding-left: 15px; padding-right: 15px; }
        }

        .chat-area {
            height: calc(100vh - 240px);
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .msg-bubble {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 1rem;
            margin-bottom: 12px;
            position: relative;
            font-size: 14px;
            line-height: 1.5;
        }

        .msg-received {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.1);
            align-self: flex-start;
            border-top-left-radius: 0;
            color: var(--text-primary);
        }

        .msg-sent {
            background: linear-gradient(135deg, rgba(157, 80, 187, 0.3) 0%, rgba(110, 32, 140, 0.3) 100%);
            border: 1px solid rgba(237, 177, 255, 0.2);
            align-self: flex-end;
            border-top-right-radius: 0;
            color: #fff;
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
                <h1 class="h3 fw-bold brand-title mb-0">KIMOCHI Team</h1>
                <small class="text-white border opacity-50 text-uppercase tracking-widest"
                    style="font-size: 10px;">Admin Studio Pro</small>
            </div>

            <a href="index.php" class="text-decoration-none">
                <div class="px-3 mb-4">
                    <div class="d-flex align-items-center gap-3 p-3 glass-panel" style="border-radius: 12px; transform: none;">
                        <img alt="Avatar Admin" class="rounded-circle object-fit-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCckUUmf2pbR34bOL0Ux5qx8dLAbJRNb85WVUwjNuAqbtdliy2ZTmqHnjSWxaeNODa9ueOiXQLONOyE_rRaYGvWoNDHRCWG3ejw1H4xJR-u5SSGZRZ_MITX1_GEuklqIjII4VUXL8rtHwEm665H2dssSxO90a7LCLaxLCb3Wnsk_nYhm6p4o1nElSk6gXk7OgIBLlp1drhqMKPMniywgNh489CDbLNkz4yv1lRo5tG3iPtI6s0d5SgPkwPyGY5zmoUcV2NfkkAyLC0" style="width:40px; height:40px; border: 1px solid rgba(237,177,255,0.3);" />
                        <div class="overflow-hidden">
                            <p class="mb-0 fw-semibold text-white text-truncate small">
                                <?= isset($row['NamaLengkap']) ? htmlspecialchars($row['NamaLengkap']) : 'Admin'; ?></p>
                            <span class="text-white text-uppercase tracking-wider" style="font-size: 9px;">Kepala Operasional</span>
                        </div>
                    </div>
                </div>
            </a>

            <nav class="px-2 d-flex flex-column gap-1 custom-scrollbar overflow-y-auto" style="max-height: calc(100vh - 320px);">
                <a class="nav-link-custom" href="index.php">
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
                <a class="nav-link-custom active" href="chat.php">
                    <span class="material-symbols-outlined">forum</span>
                    <span class="small fw-medium">Chat Group</span>
                </a>
                <a class="nav-link-custom" href="kas.php">
                    <span class="material-symbols-outlined">money</span>
                    <span class="small fw-medium">Data Kas</span>
                </a>
                <a class="nav-link-custom" href="postingan.php">
                    <span class="material-symbols-outlined">event_available</span>
                    <span class="small fw-medium">Data Postingan</span>
                </a>
            </nav>
        </div>

        <div class="px-3">
            <a href="../index.php" class="btn gradient-btn w-100 py-2.5 mb-3 rounded-3 shadow-sm text-sm">
                Logout
            </a>
        </div>
    </aside>

    <header class="d-flex align-items-center justify-content-between px-4">
        <div class="d-flex align-items-center gap-2">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb m-0 align-items-center" style="font-size: 13px; letter-spacing: 0.05em;">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white text-decoration-none d-flex align-items-center gap-1 hover-purple"><span class="material-symbols-outlined" style="font-size: 16px;">dashboard</span> Dashboard</a></li>
                    <li class="breadcrumb-item active fw-semibold" aria-current="page" style="color: var(--accent-color);">Chat Group</li>
                </ol>
            </nav>
        </div>
    </header>

    <main class="px-4 pb-5">
        <div class="container-fluid mt-3">
            <div class="glass-panel d-flex flex-column" style="height: calc(100vh - 140px); overflow: hidden; border-radius: 16px;">

                <div class="p-3 border-bottom border-white border-opacity-10 d-flex align-items-center justify-content-between" style="background: rgba(0,0,0,0.2);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-2.5 rounded-circle d-flex align-items-center justify-content-center" style="background: rgba(237,177,255,0.1); width: 45px; height: 45px;">
                            <span class="material-symbols-outlined" style="color: var(--accent-color)">groups</span>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white fw-bold small">TEAM KIMOCHI</h5>
                            <span class="text-opacity-50 text-white" style="font-size: 11px;">The real of music </span>
                        </div>
                    </div>
                </div>

                <div class="chat-area d-flex flex-column custom-scrollbar flex-grow-1" id="chatContainer">
                    <div class="text-center text-white opacity-25 my-auto">Menghubungkan ke room chat...</div>
                </div>

                <div class="p-3 border-top border-white border-opacity-10" style="background: rgba(0,0,0,0.2);">
                    <form action="proses_kirim_chat.php" id="form-kirim-chat" class="d-flex gap-2 align-items-center" method="POST">
                        <input type="text" name="pesan" class="form-control bg-white bg-opacity-5 border-white border-opacity-10 text-black rounded-5 p-2.5 px-4 small flex-grow-1" placeholder="Ketik pesan koordinasi di sini..." required autocomplete="off" style="font-size: 14px;">
                        <button type="submit" class="btn gradient-btn rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; min-width: 44px; padding: 0;">
                            <span class="material-symbols-outlined" style="font-size: 20px;">send</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const chatContainer = document.getElementById('chatContainer');
        let urutanTerbawah = true;

        // Cek posisi scroll user
        chatContainer.addEventListener('scroll', () => {
            if (chatContainer.scrollTop + chatContainer.clientHeight >= chatContainer.scrollHeight - 60) {
                urutanTerbawah = true;
            } else {
                urutanTerbawah = false;
            }
        });

        // 1. FUNGSI AMBIL CHAT REALTIME (AJAX FETCH)
        function loadChatRealtime() {
            fetch('ambil_chat.php')
                .then(response => response.text())
                .then(htmlData => {
                    chatContainer.innerHTML = htmlData;
                    if (urutanTerbawah) {
                        chatContainer.scrollTop = chatContainer.scrollHeight;
                    }
                })
                .catch(error => console.error('Gagal sinkronisasi data:', error));
        }

        // Jalankan otomatis loop setiap 1 detik
        loadChatRealtime();
        setInterval(loadChatRealtime, 1000);

        // 2. FUNGSI KIRIM CHAT TANPA REFRESH HALAMAN
        document.getElementById('form-kirim-chat').addEventListener('submit', function(e) {
            e.preventDefault(); 
            const inputPesan = this.querySelector('input[name="pesan"]');
            if(inputPesan.value.trim() === "") return;

            const formData = new FormData(this);
            formData.append('kirim_pesan', true);

            fetch('proses_kirim_chat.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if(res.status === 'success') {
                    inputPesan.value = ''; // Kosongkan form ketikan
                    urutanTerbawah = true;
                    loadChatRealtime();    // Langsung update chat detik itu juga
                } else {
                    alert('Gagal mengirim pesan: ' + res.message);
                }
            });
        });

        // Efek Sorotan Mouse Background
        const body = document.querySelector('body');
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed'; overlay.style.top = '0'; overlay.style.left = '0';
        overlay.style.width = '100vw'; overlay.style.height = '100vh'; overlay.style.pointerEvents = 'none'; overlay.style.zIndex = '1';
        overlay.style.background = 'radial-gradient(circle 800px at var(--x) var(--y), rgba(237, 177, 255, 0.03), transparent 80%)';
        body.appendChild(overlay);

        window.addEventListener('mousemove', (e) => {
            overlay.style.setProperty('--x', e.clientX + 'px');
            overlay.style.setProperty('--y', e.clientY + 'px');
        });
    </script>
</body>
</html>