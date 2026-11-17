
<?php
require 'auth.php';
cekRole('admin');

require 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: kelola_user.php?error=id");
    exit;
}

$stmt = $koneksi->prepare("SELECT id, nama, username, role FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: kelola_user.php?error=notfound");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pengguna</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f3f8f1;
            font-family: 'Segoe UI', sans-serif;
        }

        .edit-card {
            max-width: 650px;
            margin: 35px auto;
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(46,79,37,.08);
        }

        .title {
            color: #3a642e;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: #3a642e;
        }

        .btn-matcha {
            background: #5c9c48;
            color: white;
        }

        .btn-matcha:hover {
            background: #3a642e;
            color: white;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="edit-card">

        <div class="mb-4">
            <h3 class="title">
                <i class="bi bi-pencil-square me-2"></i>
                Edit Pengguna
            </h3>

            <p class="text-muted mb-0">
                Ubah informasi akun pengguna.
            </p>
        </div>

        <form action="proses_edit_user.php" method="POST">

            <input type="hidden" name="id"
                   value="<?= (int) $user['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>

                <input type="text"
                       name="nama"
                       class="form-control"
                       value="<?= htmlspecialchars($user['nama']) ?>"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>

                <input type="text"
                       name="username"
                       class="form-control"
                       value="<?= htmlspecialchars($user['username']) ?>"
                       required>
            </div>

            <div class="mb-4">
                <label class="form-label">Role Pengguna</label>

                <select name="role" class="form-select"
                    <?= $user['id'] == $_SESSION['user_id'] ? 'disabled' : '' ?>>

                    <option value="admin"
                        <?= $user['role'] === 'admin' ? 'selected' : '' ?>>
                        Admin
                    </option>

                    <option value="petugas"
                        <?= $user['role'] === 'petugas' ? 'selected' : '' ?>>
                        Petugas
                    </option>

                    <option value="siswa"
                        <?= $user['role'] === 'siswa' ? 'selected' : '' ?>>
                        Siswa
                    </option>

                </select>

                <?php if ($user['id'] == $_SESSION['user_id']): ?>
                    <small class="text-muted">
                        Role akun admin yang sedang digunakan tidak dapat diubah.
                    </small>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">

                <button type="submit" class="btn btn-matcha">
                    <i class="bi bi-save me-1"></i>
                    Simpan Perubahan
                </button>

                <a href="admin.php?halaman=kelola_user"
                   class="btn btn-outline-secondary">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
