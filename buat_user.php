<?php
require 'config.php';

// Hanya membuat akun admin
$data = [
    ['Administrator', 'admin', 'admin123', 'admin']
];

$sql = "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

foreach ($data as $u) {
    // Cek apakah username sudah ada
    $cek = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
    $cek->execute([$u[1]]);
    if ($cek->fetch()) {
        echo "Username '{$u[1]}' sudah ada, dilewati.<br>";
        continue;
    }

    $passwordHash = password_hash($u[2], PASSWORD_DEFAULT);
    $stmt->execute([$u[0], $u[1], $passwordHash, $u[3]]);
    echo "Akun admin berhasil dibuat.<br>";
}

echo "<br>Selesai. <strong>Hapus file ini sekarang!</strong>";
?>