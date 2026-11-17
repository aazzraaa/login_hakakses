<?php
session_start();
require 'config.php';

// Pastikan sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: profil.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$aksi    = $_GET['aksi'] ?? '';

// ==========================================
// AKSI 1: UBAH NAMA
// ==========================================
if ($aksi === 'nama') {
    $nama_baru = trim($_POST['nama_baru'] ?? '');

    if ($nama_baru === '') {
        header("Location: profil.php?error=kosong");
        exit;
    }

    $stmt = $koneksi->prepare("UPDATE users SET nama = ? WHERE id = ?");
    $stmt->execute([$nama_baru, $user_id]);

    // Update session agar langsung berubah di dashboard
    $_SESSION['nama'] = $nama_baru;

    header("Location: profil.php?sukses=1");
    exit;
}

// ==========================================
// AKSI 2: UBAH PASSWORD
// ==========================================
if ($aksi === 'password') {
    $pass_lama   = $_POST['pass_lama'] ?? '';
    $pass_baru   = $_POST['pass_baru'] ?? '';
    $konfirmasi  = $_POST['konfirmasi'] ?? '';

    // 1. Ambil password lama dari DB
    $stmt = $koneksi->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Verifikasi password lama
    if (!password_verify($pass_lama, $user['password'])) {
        header("Location: profil.php?error=password_lama");
        exit;
    }

    // 3. Validasi password baru
    if (strlen($pass_baru) < 6) {
        header("Location: profil.php?error=panjang");
        exit;
    }

    if ($pass_baru !== $konfirmasi) {
        header("Location: profil.php?error=konfirmasi");
        exit;
    }

    // 4. Hash password baru & simpan
    $hash_baru = password_hash($pass_baru, PASSWORD_DEFAULT);
    $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hash_baru, $user_id]);

    header("Location: profil.php?sukses=1");
    exit;
}

// Jika aksi tidak dikenali
header("Location: profil.php");
exit;
?>