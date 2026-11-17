<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$nama       = trim($_POST['nama'] ?? '');
$username   = trim($_POST['username'] ?? '');
$password   = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';
$role       = $_POST['role'] ?? '';

// Validasi kosong
if ($nama === '' || $username === '' || $password === '' || $konfirmasi === '' || $role === '') {
    header("Location: register.php?error=kosong");
    exit;
}

// Role hanya boleh petugas / siswa (admin TIDAK boleh)
if (!in_array($role, ['petugas', 'siswa'], true)) {
    header("Location: register.php?error=role");
    exit;
}

// Password minimal 6 karakter
if (strlen($password) < 6) {
    header("Location: register.php?error=panjang");
    exit;
}

// Konfirmasi password harus sama
if ($password !== $konfirmasi) {
    header("Location: register.php?error=konfirmasi");
    exit;
}

// Cek username sudah dipakai atau belum
$cek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
$cek->execute([$username]);
if ($cek->fetch()) {
    header("Location: register.php?error=username");
    exit;
}

// Simpan dengan password hash
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql  = "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);
$stmt->execute([$nama, $username, $hash, $role]);

header("Location: register.php?sukses=1");
exit;
?>