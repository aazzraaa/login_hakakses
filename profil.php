<?php
require 'auth.php';
cekLogin(); // Pastikan sudah login (semua role bisa akses)

require 'config.php';

$user_id = $_SESSION['user_id'];

// Ambil data terbaru dari database
$stmt = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Tentukan link kembali ke dashboard berdasarkan role
$link_dashboard = 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya — Matcha E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --matcha-50:  #f4f9f2;
            --matcha-100: #e6f0e2;
            --matcha-500: #5c9c48;
            --matcha-700: #3a642e;
            --matcha-800: #2e4f25;
        }
        body { background-color: var(--matcha-50); font-family: 'Segoe UI', system-ui, sans-serif; color: #2b2b2b; }
        .panel { background: #fff; border-radius: 16px; border: 1px solid var(--matcha-100); box-shadow: 0 4px 18px rgba(46, 79, 37, .05); overflow: hidden; }
        .panel-header { padding: 18px 22px; border-bottom: 1px solid var(--matcha-100); background: var(--matcha-50); }
        .panel-header h6 { margin: 0; font-weight: 700; color: var(--matcha-800); }
        .form-control { border-color: var(--matcha-100); padding: 10px 14px; }
        .form-control:focus { border-color: var(--matcha-500); box-shadow: 0 0 0 0.2rem rgba(92,156,72,.25); }
        .btn-matcha { background: var(--matcha-500); color: #fff; font-weight: 600; padding: 10px 20px; border: none; border-radius: 8px; }
        .btn-matcha:hover { background: var(--matcha-700); color: #fff; }
        .profile-avatar { width: 100px; height: 100px; background: linear-gradient(135deg, var(--matcha-500), var(--matcha-700)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 2.5rem; margin: 0 auto; box-shadow: 0 8px 20px rgba(92,156,72,.3); }
    </style>
</head>
<body class="p-4">
<div class="container" style="max-width: 900px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: var(--matcha-800);">Profil Saya</h4>
        <a href="<?= $link_dashboard ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i> Profil berhasil diperbarui!</div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <?php
        $err = $_GET['error'];
        $pesan = "Terjadi kesalahan.";
        if ($err === 'password_lama') $pesan = "Password lama salah!";
        if ($err === 'konfirmasi')    $pesan = "Konfirmasi password baru tidak cocok!";
        if ($err === 'panjang')       $pesan = "Password baru minimal 6 karakter!";
        ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($pesan) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Kolom Kiri: Kartu Identitas -->
        <div class="col-md-4">
            <div class="panel h-100 text-center p-4">
                <div class="profile-avatar mb-3"><?= strtoupper(substr($user['nama'], 0, 1)) ?></div>
                <h5 class="fw-bold mb-1"><?= htmlspecialchars($user['nama']) ?></h5>
                <p class="text-muted mb-3">@<?= htmlspecialchars($user['username']) ?></p>
                <span class="badge bg-success-subtle text-success-emphasis px-3 py-2 rounded-pill text-uppercase">
                    <?= htmlspecialchars($user['role']) ?>
                </span>
                <hr class="my-4">
                <div class="text-start small">
                    <div class="mb-2"><i class="bi bi-hash text-muted"></i> ID Pengguna: <strong><?= $user['id'] ?></strong></div>
                    <div><i class="bi bi-shield-check text-muted"></i> Status: <strong>Aktif</strong></div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Ubah Data -->
        <div class="col-md-8">
            <!-- Form Ubah Nama -->
            <div class="panel mb-4">
                <div class="panel-header"><h6><i class="bi bi-person-lines-fill me-2"></i>Ubah Nama Pengguna</h6></div>
                <div class="panel-body p-4">
                    <form action="proses_profil.php?aksi=nama" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Nama Lengkap</label>
                            <input type="text" name="nama_baru" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Username (Tidak dapat diubah)</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                        </div>
                        <button type="submit" class="btn btn-matcha">Simpan Nama</button>
                    </form>
                </div>
            </div>

            <!-- Form Ubah Password -->
            <div class="panel">
                <div class="panel-header"><h6><i class="bi bi-key-fill me-2"></i>Ubah Password</h6></div>
                <div class="panel-body p-4">
                    <form action="proses_profil.php?aksi=password" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password Lama</label>
                            <input type="password" name="pass_lama" class="form-control" placeholder="Masukkan password saat ini" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Password Baru</label>
                            <input type="password" name="pass_baru" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Konfirmasi Password Baru</label>
                            <input type="password" name="konfirmasi" class="form-control" placeholder="Ulangi password baru" minlength="6" required>
                        </div>
                        <button type="submit" class="btn btn-matcha">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>