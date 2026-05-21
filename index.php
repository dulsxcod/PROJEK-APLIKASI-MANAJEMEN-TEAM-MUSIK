<?php
// Memulai session untuk mengecek apakah ada pesan error dari file proses
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>BeatManager - Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <style>
        :root {
            --bg-color: #171218;
            --primary-gradient: linear-gradient(135deg, #9d50bb 0%, #6e208c 100%);
            --text-primary: #ebdfe9;
            --text-muted: #d1c2d2;
            --accent-color: #edb1ff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            height: 100vh;
            overflow: hidden;
        }

        /* Background Image Layer */
        .bg-layer {
            position: absolute;
            inset: 0;
            z-index: 0;
        }
        .bg-layer img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.3);
        }
        .bg-gradient-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent, rgba(23, 18, 24, 0.8));
        }

        /* Main Container */
        main {
            position: relative;
            z-index: 10;
            height: 100vh;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            border-left: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border-radius: 1rem;
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Input Styling Custom */
        .input-glow-group {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .input-glow-group:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 10px rgba(237, 177, 255, 0.2);
        }
        .input-glow-group input, .input-glow-group select {
            background: transparent;
            border: none;
            color: var(--text-primary);
            box-shadow: none !important;
        }
        /* Mengatur warna opsi select dropdown di mode dark */
        .input-glow-group select option {
            background-color: #251c27;
            color: var(--text-primary);
        }
        .input-glow-group input::placeholder {
            color: rgba(154, 140, 155, 0.6);
        }
        .input-glow-group .input-group-text {
            background: transparent;
            border: none;
            color: #9a8c9b;
        }

        /* Custom Checkbox */
        .custom-checkbox {
            position: relative;
            cursor: pointer;
        }
        .custom-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        .checkmark {
            width: 18px;
            height: 18px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .custom-checkbox:hover .checkmark {
            border-color: rgba(237, 177, 255, 0.5);
        }
        .custom-checkbox input:checked ~ .checkmark {
            border-color: var(--accent-color);
        }
        .custom-checkbox input:checked ~ .checkmark .material-symbols-outlined {
            display: block !important;
            font-size: 14px;
            color: var(--accent-color);
        }

        /* Gradient Button */
        .gradient-btn {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 0.85rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .gradient-btn:hover {
            filter: brightness(1.2);
            box-shadow: 0 0 20px rgba(157, 80, 187, 0.4);
            transform: translateY(-1px);
            color: white;
        }
        .gradient-btn:active {
            transform: scale(0.98);
        }

        /* Links & Utility */
        .text-muted-custom { color: var(--text-muted); }
        .text-accent { color: var(--accent-color); text-decoration: none; }
        .text-accent:hover { color: #f9d8ff; }
        .text-secondary-custom { color: #d6baff; text-decoration: none; }
        .text-secondary-custom:hover { text-decoration: underline; }

        /* Status & Decor */
        .system-status {
            position: absolute;
            bottom: 2.5rem;
            left: 2.5rem;
            opacity: 0.5;
            letter-spacing: 0.08em;
            font-size: 0.75rem;
        }
        .live-clock-section {
            position: absolute;
            top: 2.5rem;
            right: 2.5rem;
            opacity: 0.5;
            letter-spacing: 0.08em;
            font-size: 0.75rem;
        }
        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>
<body>

    <div class="bg-layer">
        <img alt="Professional Recording Studio" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBcn5nzut0j_eY5AtBnQ320P8_Yzhk3q2tQK7MiuCe-JUOCS1UEuQ7NeMNosM3HbRpGAZujFAHNFvueR2tFQlXAAZ53J53wn43uNYzK97aInAQ-xZVygh5aFDBDYhOKohI2akvm-v3zpkMjZoaQN2I08a2KMHIWLO1A0sdZxkXcClHPI7j1rgOdpbAkeHLo3Uj_RkIAmZrIQCw1QTc9DaHoukxmjlRyhe3j41bKpAfeS1rtyjX1EfBn0nEh1QN8wRX4TQvSWVsjug0"/>
        <div class="bg-gradient-overlay"></div>
    </div>

    <main class="d-flex items-center justify-content-center align-items-center px-3">
        
        <div class="glass-card w-100 p-4 p-md-5" style="max-width: 450px;">
            
            <div class="text-center mb-4">
                <h1 class="fw-bold tracking-tight mb-1" style="background: linear-gradient(to right, var(--accent-color), #d6baff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-size: 2rem;">
                    BeatManager
                </h1>
                <p class="text-muted-custom text-uppercase small fw-medium tracking-widest" style="letter-spacing: 0.15em; font-size: 0.75rem;">
                    Pro Studio Admin Portal
                </p>
            </div>

            <?php if (isset($_SESSION['error_message'])) : ?>
                <div class="alert alert-danger py-2 text-center small mb-3" style="background: rgba(220, 53, 69, 0.2); color: #ff8e98; border: 1px solid rgba(220, 53, 69, 0.4);">
                    <?= $_SESSION['error_message']; ?>
                </div>
                <?php 
                    unset($_SESSION['error_message']); 
                ?>
            <?php endif; ?>

            <form action="proses_login.php" method="POST" id="loginForm">
                
                <div class="mb-3">
                    <label class="form-label text-muted-custom small fw-medium ms-1 mb-2">Username</label>
                    <div class="input-group input-glow-group p-1 align-items-center">
                        <span class="input-group-text"><span class="material-symbols-outlined">person</span></span>
                        <input class="form-control" name="Username" placeholder="Enter your username" type="text" required/>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted-custom small fw-medium ms-1 mb-2">Password</label>
                    <div class="input-group input-glow-group p-1 align-items-center">
                        <span class="input-group-text"><span class="material-symbols-outlined">lock</span></span>
                        <input class="form-control" id="passwordInput" name="Password" placeholder="••••••••" type="password" required/>
                        <button class="btn border-0 text-muted-custom" onclick="togglePassword()" type="button">
                            <span class="material-symbols-outlined align-middle" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label text-muted-custom small fw-medium ms-1 mb-2">Login Sebagai</label>
                    <div class="input-group input-glow-group p-1 align-items-center">
                        <span class="input-group-text"><span class="material-symbols-outlined">manage_accounts</span></span>
                        <select class="form-select border-0" name="Role" required>
                            <option value="anggota">Anggota Team</option>
                            <option value="admin">Admin / Pemilik Lab</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 px-1">
                    <label class="custom-checkbox d-flex align-items-center gap-2">
                        <input type="checkbox"/>
                        <span class="checkmark">
                            <span class="material-symbols-outlined" style="display: none;">check</span>
                        </span>
                        <span class="text-muted-custom small">Remember me</span>
                    </label>
                    <a class="text-accent small" href="#">Forgot Password?</a>
                </div>

                <button class="btn gradient-btn w-100 rounded-3 mb-4" name="submit_login" type="submit">
                    Login
                </button>
            </form>

            <div class="text-center pt-3 border-top border-white border-opacity-10">
                <p class="text-muted-custom small mb-0">
                    Don't have an account? 
                    <a class="text-secondary-custom fw-bold" href="#">Contact Lab Owner</a>
                </p>
            </div>
        </div>

        <div class="system-status d-none d-lg-flex align-items-center gap-2 text-muted-custom">
            <span class="material-symbols-outlined small">graphic_eq</span>
            <span>SYSTEM STATUS: STABLE</span>
            <span class="pulse-dot"></span>
        </div>
        
        <div class="live-clock-section d-none d-lg-block text-end text-muted-custom">
            <p class="mb-0" id="clock">00:00:00 UTC</p>
            <p class="mb-0 text-white-50" style="font-size: 0.65rem;">STUDIO SESSION ACTIVE</p>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('en-US', { hour12: false, hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('clock').innerText = `${timeStr} UTC`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>