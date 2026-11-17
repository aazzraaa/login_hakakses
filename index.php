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
    <title>Login — Matcha E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --matcha-bg: #f7f9f5;       /* Warna dasar cream matcha */
            --matcha-dark: #2b2b2b;     /* Warna teks utama */
            --matcha-btn: #222222;      /* Warna tombol gelap seperti gambar */
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
        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* --- Sisi Kiri (Form) --- */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 5% 10%;
            background-color: var(--matcha-bg);
            position: relative;
            z-index: 2;
        }

        /* Logo Kiri Atas */
        .brand-logo {
            position: absolute;
            top: 40px;
            left: 10%;
            font-weight: 800;
            font-size: 1.2rem;
            color: #333;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .brand-logo i {
            color: var(--matcha-500, #5c9c48); /* Fallback jika variabel matcha tidak ada */
        }

        /* Typography */
        .login-title {
            font-family: 'Georgia', serif; /* Font serif elegan seperti di gambar */
            font-weight: 700;
            font-size: 2.5rem;
            color: var(--matcha-dark);
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            font-size: 0.95rem;
            color: var(--matcha-text-muted);
            margin-bottom: 2.5rem;
            font-family: 'Georgia', serif;
        }

        /* Form Inputs */
        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--matcha-dark);
        }
        
        .input-group-text {
            background-color: transparent;
            border-right: none;
            color: #b0b0b0;
            padding-right: 5px;
        }
        
        .form-control {
            border-left: none;
            background-color: transparent;
            box-shadow: none !important;
            border-color: var(--matcha-border);
            padding-left: 5px;
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: var(--matcha-dark);
        }
        .form-control::placeholder {
            color: #b0b0b0;
            font-size: 0.9rem;
        }
        .input-group:focus-within .input-group-text {
            border-color: var(--matcha-dark);
        }

        /* Tombol Login */
        .btn-login {
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
        .btn-login:hover {
            background-color: #000;
            transform: translateY(-1px);
        }

        /* Link Register */
        .register-link {
            text-align: left;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: var(--matcha-text-muted);
        }
        .register-link a {
            color: var(--matcha-dark);
            font-weight: 600;
            text-decoration: underline;
        }
        .register-link a:hover {
            color: #5c9c48; /* Matcha */
        }

        /* --- Sisi Kanan (Gambar Ilustrasi) --- */
        .login-right {
            flex: 1.2; /* Gambar sedikit lebih lebar */
            background-image: url('https://png.pngtree.com/background/20250112/original/pngtree-aerial-view-of-trees-and-water-in-teal-green-color-picture-image_16088271.jpg'); /* Gambar pemandangan hijau/danau */
            background-size: cover;
            background-position: center;
            position: relative;
            display: none; /* Sembunyikan di layar kecil */
        }

        /* Overlay gradasi matcha agar gambar menyatu dengan kiri */
        .login-right::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(90deg, var(--matcha-bg) 0%, rgba(247, 249, 245, 0.4) 20%, rgba(92, 156, 72, 0.15) 100%);
        }

        /* Responsive untuk layar kecil (Tablet/HP) */
        @media (min-width: 992px) {
            .login-right { display: block; }
        }
        @media (max-width: 991px) {
            .login-left {
                padding: 10%;
                background-image: url('https://images.unsplash.com/photo-1545048702-79362596cdc9?q=80&w=800&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
            }
            .login-left::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(247, 249, 245, 0.95); /* Overlay putih agar teks terbaca */
                z-index: -1;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    
    <!-- ================= KIRI: FORM LOGIN ================= -->
    <div class="login-left">
        
        <!-- Logo Kiri Atas -->
        <div class="brand-logo">
            <i class="bi bi-cup-hot-fill"></i> Matcha.Edu
        </div>

        <!-- Judul -->
        <h1 class="login-title">Welcome back!</h1>
        <p class="login-subtitle">Tempat di mana ketenangan bertemu dengan pengetahuan.</p>

        <!-- Notifikasi Error -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger py-2" style="font-size: 0.85rem;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Username atau password salah!
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="proses_login.php" method="POST">
            
            <!-- Input Username -->
            <div class="mb-4">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required autofocus>
                </div>
            </div>

            <!-- Input Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Remember & Forgot Password -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label" for="remember" style="font-size: 0.85rem; color: var(--matcha-text-muted);">
                        Remember me
                    </label>
                </div>
                <a href="#" style="font-size: 0.85rem; color: var(--matcha-text-muted); text-decoration: none;">Forgot password?</a>
            </div>

            <!-- Tombol Login -->
            <button type="submit" class="btn btn-login w-100">Log in</button>
        </form>

        <!-- Link Register -->
        <div class="register-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </div>

    </div>

    <!-- ================= KANAN: ILUSTRASI ================= -->
    <div class="login-right">
        <!-- Gambar diatur via CSS background -->
    </div>

</div>

</body>
</html>