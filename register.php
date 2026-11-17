<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Matcha E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --matcha-bg: #f7f9f5;       /* Warna dasar cream matcha */
            --matcha-dark: #2b2b2b;     /* Warna teks utama */
            --matcha-btn: #222222;      /* Warna tombol gelap */
            --matcha-border: #dcdcdc;   /* Warna garis form */
            --matcha-text-muted: #7a7a7a;
        }

        body {
            background-color: var(--matcha-bg);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            height: 100vh;
            overflow: hidden; /* Mencegah scroll di desktop */
        }

        /* --- Layout Split Screen --- */
        .register-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* --- Sisi Kiri (Form) --- */
        .register-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4% 8%;
            background-color: var(--matcha-bg);
            position: relative;
            z-index: 2;
            overflow-y: auto; /* Agar bisa discroll jika layar pendek */
        }

        /* Logo Kiri Atas */
        .brand-logo {
            position: absolute;
            top: 30px;
            left: 8%;
            font-weight: 800;
            font-size: 1.2rem;
            color: #333;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .brand-logo i {
            color: #5c9c48;
        }

        /* Typography */
        .register-title {
            font-family: 'Georgia', serif;
            font-weight: 700;
            font-size: 2.2rem;
            color: var(--matcha-dark);
            margin-bottom: 0.5rem;
        }
        .register-subtitle {
            font-size: 0.95rem;
            color: var(--matcha-text-muted);
            margin-bottom: 2rem;
            font-family: 'Georgia', serif;
        }

        /* Form Inputs */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--matcha-dark);
            margin-bottom: 4px;
        }
        
        .input-group-text {
            background-color: transparent;
            border-right: none;
            color: #b0b0b0;
            padding-right: 5px;
            border-color: var(--matcha-border);
        }
        
        .form-control, .form-select {
            border-left: none;
            background-color: transparent;
            box-shadow: none !important;
            border-color: var(--matcha-border);
            padding-left: 5px;
            font-size: 0.95rem;
        }
        .form-select {
            border-left: 1px solid var(--matcha-border); /* Select butuh border penuh */
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--matcha-dark);
        }
        .form-control::placeholder {
            color: #b0b0b0;
            font-size: 0.9rem;
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--matcha-dark);
        }

        /* Tombol Register */
        .btn-register {
            background-color: var(--matcha-btn);
            color: #fff;
            border: none;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        .btn-register:hover {
            background-color: #000;
            transform: translateY(-1px);
        }

        /* Link Login */
        .login-link {
            text-align: left;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--matcha-text-muted);
        }
        .login-link a {
            color: var(--matcha-dark);
            font-weight: 600;
            text-decoration: underline;
        }
        .login-link a:hover {
            color: #5c9c48;
        }

        /* --- Sisi Kanan (Gambar Ilustrasi) --- */
        .register-right {
            flex: 1.2;
            /* Gambar pemandangan alam hijau (Matcha vibe) */
            background-image: url('https://png.pngtree.com/background/20250112/original/pngtree-aerial-view-of-trees-and-water-in-teal-green-color-picture-image_16088271.jpg');
            background-size: cover;
            background-position: center;
            position: relative;
            display: none;
        }

        /* Overlay gradasi matcha */
        .register-right::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: linear-gradient(90deg, var(--matcha-bg) 0%, rgba(247, 249, 245, 0.4) 20%, rgba(92, 156, 72, 0.15) 100%);
        }

        /* Responsive */
        @media (min-width: 992px) {
            .register-right { display: block; }
        }
        @media (max-width: 991px) {
            .register-left {
                padding: 10% 8%;
                background-image: url('https://images.unsplash.com/photo-1545048702-79362596cdc9?q=80&w=800&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
            }
            .register-left::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(247, 249, 245, 0.96); /* Overlay putih agar teks terbaca */
                z-index: -1;
            }
            body { overflow: auto; } /* Izinkan scroll di HP */
        }
    </style>
</head>
<body>

<div class="register-container">
    
    <!-- ================= KIRI: FORM REGISTER ================= -->
    <div class="register-left">
        
        <!-- Logo -->
        <div class="brand-logo">
            <i class="bi bi-cup-hot-fill"></i> Matcha.Edu
        </div>

        <!-- Judul -->
        <h1 class="register-title">Create an account.</h1>
        <p class="register-subtitle">Bergabunglah dan mulai perjalananmu bersama kami.</p>

        <!-- Notifikasi Error -->
        <?php if (isset($_GET['error'])): ?>
            <?php
            $err = $_GET['error'];
            $pesan = "Terjadi kesalahan.";
            if ($err === 'username')    $pesan = "Username sudah dipakai, coba yang lain!";
            if ($err === 'kosong')      $pesan = "Semua field wajib diisi!";
            if ($err === 'role')        $pesan = "Role tidak valid!";
            if ($err === 'panjang')     $pesan = "Password minimal 6 karakter!";
            if ($err === 'konfirmasi')  $pesan = "Konfirmasi password tidak cocok!";
            ?>
            <div class="alert alert-danger py-2" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($pesan) ?>
            </div>
        <?php endif; ?>

        <!-- Notifikasi Sukses -->
        <?php if (isset($_GET['sukses'])): ?>
            <div class="alert alert-success py-2" style="font-size: 0.85rem; background-color: #d1e7dd; color: #0f5132; border: none;">
                <i class="bi bi-check-circle-fill me-2"></i> Register berhasil! Silakan login.
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="proses_register.php" method="POST">
            
            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>
            </div>

            <!-- Username -->
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-at"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Pilih username unik" required>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" name="konfirmasi" class="form-control" placeholder="Ulangi password" minlength="6" required>
                </div>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="form-label">Daftar Sebagai</label>
                <select name="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="petugas">Petugas</option>
                    <option value="siswa">Siswa</option>
                </select>
                <small class="text-muted" style="font-size: 0.75rem;">*Role admin hanya dapat dibuat oleh sistem.</small>
            </div>

            <!-- Tombol Register -->
            <button type="submit" class="btn btn-register w-100">Sign up</button>
        </form>

        <!-- Link Login -->
        <div class="login-link">
            Already have an account? <a href="index.php">Log in</a>
        </div>

    </div>

    <!-- ================= KANAN: ILUSTRASI ================= -->
    <div class="register-right">
        <!-- Gambar diatur via CSS background -->
    </div>

</div>

</body>
</html>