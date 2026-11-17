<?php
require 'auth.php';
cekRole('admin'); // ⛔ Mencegah petugas & siswa masuk

require 'config.php';

// Ambil semua user dari database
$stmt = $koneksi->query("SELECT * FROM users ORDER BY role ASC, nama ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pengguna — Admin</title>
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
        .table-modern { margin: 0; font-size: .9rem; }
        .table-modern thead th { background: var(--matcha-50); color: var(--matcha-700); font-weight: 600; font-size: .78rem; text-transform: uppercase; border: none; padding: 14px; }
        .table-modern tbody td { padding: 14px; border-color: var(--matcha-100); vertical-align: middle; }
        .table-modern tbody tr:hover { background: var(--matcha-50); }
        .avatar-sm { width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--matcha-500), var(--matcha-700)); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: .8rem; margin-right: 10px; }
        .role-badge { padding: 5px 12px; border-radius: 20px; font-size: .75rem; font-weight: 600; text-transform: capitalize; }
        .role-admin   { background: #e6f0e2; color: var(--matcha-700); }
        .role-petugas { background: #e3eefb; color: #2c6fb5; }
        .role-siswa   { background: #fdf0e1; color: #d17a22; }
    </style>
</head>
<body class="p-4">
<div class="container-fluid" style="max-width: 1100px;">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0" style="color: var(--matcha-800);">Kelola Pengguna</h4>
            <small class="text-muted">Daftar seluruh akun yang terdaftar di sistem</small>
        </div>
        <a href="admin.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    <?php if (isset($_GET['sukses'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i> Pengguna berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'self'): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Anda tidak dapat menghapus akun Anda sendiri!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="panel">
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pengguna</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $u): ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <span class="avatar-sm"><?= strtoupper(substr($u['nama'], 0, 1)) ?></span>
                            <span class="fw-semibold"><?= htmlspecialchars($u['nama']) ?></span>
                        </td>
                        <td>@<?= htmlspecialchars($u['username']) ?></td>
                        <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
                        
<td class="text-center">

    <!-- TOMBOL EDIT -->
    <a href="edit_user.php?id=<?= (int) $u['id'] ?>"
       class="btn btn-sm btn-warning"
       title="Edit Pengguna">
        <i class="bi bi-pencil-square"></i>
    </a>

    <!-- TOMBOL HAPUS -->
    <?php if ($u['id'] == $_SESSION['user_id']): ?>

        <button class="btn btn-sm btn-light" disabled title="Ini akun Anda">
            <i class="bi bi-lock text-muted"></i>
        </button>

    <?php else: ?>

        <a href="hapus_user.php?id=<?= (int) $u['id'] ?>"
           class="btn btn-sm btn-light text-danger"
           onclick="return confirm('Yakin ingin menghapus user <?= htmlspecialchars($u['nama'], ENT_QUOTES, 'UTF-8') ?>?');">
            <i class="bi bi-trash"></i>
        </a>

    <?php endif; ?>

</td>

                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>