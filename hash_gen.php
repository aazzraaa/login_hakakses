<?php
// GANTI dengan password admin yang kamu inginkan
$password = "admin123";

$hash = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Password: " . htmlspecialchars($password) . "</h3>";
echo "<h3>Hash: </h3>";
echo "<textarea rows='3' cols='80'>" . $hash . "</textarea>";
echo "<p>Copy hash di atas, lalu paste ke kolom <code>password</code> di tabel users.</p>";
?>