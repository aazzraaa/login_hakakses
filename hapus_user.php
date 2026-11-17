<?php
require 'auth.php';
cekRole('admin'); // ⛔ Hanya admin

require 'config.php';

$id = $_GET['id'] ?? 0;

// Mencegah admin menghapus dirinya sendiri
if ($id == $_SESSION['user_id']) {
    header("Location: kelola_user.php?error=self");
    exit;
}

// Hapus dari database
$stmt = $koneksi->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header("Location: kelola_user.php?sukses=1");
exit;
?>