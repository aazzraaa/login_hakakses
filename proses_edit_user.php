
<?php
require 'auth.php';
cekRole('admin');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin.php?halaman=kelola_user");
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$role = $_POST['role'] ?? '';

if (!$id || $id < 1 || $nama === '' || $username === '') {
    header("Location: kelola_user.php?error=invalid");
    exit;
}

if (!in_array($role, ['admin', 'petugas', 'siswa'], true)) {
    header("Location: kelola_user.php?error=role");
    exit;
}

// Pastikan pengguna tersedia
$stmt = $koneksi->prepare("SELECT id, role FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: kelola_user.php?error=notfound");
    exit;
}

// Admin tidak boleh mengubah role akun sendiri
if ($id == $_SESSION['user_id']) {
    $role = $user['role'];
}

// Periksa apakah username sudah digunakan pengguna lain
$stmt = $koneksi->prepare("
    SELECT id FROM users
    WHERE username = ? AND id != ?
");

$stmt->execute([$username, $id]);

if ($stmt->fetch()) {
    header("Location: edit_user.php?id=$id&error=username");
    exit;
}

// Simpan perubahan
$stmt = $koneksi->prepare("
    UPDATE users
    SET nama = ?, username = ?, role = ?
    WHERE id = ?
");

$stmt->execute([$nama, $username, $role, $id]);

// Perbarui session jika admin mengedit akun sendiri
if ($id == $_SESSION['user_id']) {
    $_SESSION['nama'] = $nama;
    $_SESSION['username'] = $username;
}

header("Location: kelola_user.php?sukses=edit");
exit;
?>
