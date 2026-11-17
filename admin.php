
<?php
require 'auth.php';
cekRole('admin');

require 'config.php';

// ==========================================
// DATA ADMIN
// ==========================================
$namaAdmin = $_SESSION['nama'] ?? 'Administrator';
$username  = $_SESSION['username'] ?? 'admin';

// ==========================================
// NAVIGASI HALAMAN
// ==========================================
$halaman = $_GET['halaman'] ?? 'dashboard';

$halamanValid = ['dashboard', 'kelola_user', 'profil'];

if (!in_array($halaman, $halamanValid, true)) {
    $halaman = 'dashboard';
}

// ==========================================
// STATISTIK PENGGUNA DARI DATABASE
// ==========================================
$stmt = $koneksi->query("
    SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) AS admin,
        SUM(CASE WHEN role = 'petugas' THEN 1 ELSE 0 END) AS petugas,
        SUM(CASE WHEN role = 'siswa' THEN 1 ELSE 0 END) AS siswa
    FROM users
");

$statistik = $stmt->fetch(PDO::FETCH_ASSOC);

$totalUser = (int) ($statistik['total'] ?? 0);
$totalAdmin = (int) ($statistik['admin'] ?? 0);
$totalPetugas = (int) ($statistik['petugas'] ?? 0);
$totalSiswa = (int) ($statistik['siswa'] ?? 0);

// ==========================================
// DATA PENGGUNA TERBARU
// ==========================================
$stmt = $koneksi->query("
    SELECT id, nama, username, role
    FROM users
    ORDER BY id DESC
    LIMIT 5
");

$penggunaTerbaru = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================================
// JUDUL HALAMAN
// ==========================================
$judulHalaman = [
    'dashboard' => 'Dashboard Admin',
    'kelola_user' => 'Kelola Pengguna',
    'profil' => 'Profil Saya'
];

$judul = $judulHalaman[$halaman];

// ==========================================
// URL KONTEN
// ==========================================
$urlKonten = '';

if ($halaman === 'kelola_user') {
    $urlKonten = 'kelola_user.php';
} elseif ($halaman === 'profil') {
    $urlKonten = 'profil.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= htmlspecialchars($judul) ?> — Matcha Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --matcha-50: #f3f8f1;
            --matcha-100: #e6f0e2;
            --matcha-200: #cfe3c8;
            --matcha-300: #a8cd9a;
            --matcha-400: #7fb56b;
            --matcha-500: #5c9c48;
            --matcha-600: #4a7f39;
            --matcha-700: #3a642e;
            --matcha-800: #2e4f25;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--matcha-50);
            font-family: 'Segoe UI', system-ui, sans-serif;
            color: #2b2b2b;
        }

        /* SIDEBAR */

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 22px 16px;
            background: linear-gradient(
                180deg,
                var(--matcha-700),
                var(--matcha-800)
            );
            box-shadow: 4px 0 24px rgba(46,79,37,.15);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .brand {
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            padding: 0 8px;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(
                135deg,
                var(--matcha-300),
                var(--matcha-500)
            );
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .nav-label {
            color: rgba(255,255,255,.45);
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 10px;
            margin: 18px 0 9px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: .92rem;
            text-decoration: none;
            transition: .2s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.09);
            color: white;
            transform: translateX(3px);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(
                135deg,
                var(--matcha-400),
                var(--matcha-500)
            );
            color: white;
            box-shadow: 0 4px 14px rgba(127,181,107,.35);
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
        }

        .logout-btn {
            margin-top: auto;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            color: white;
            padding: 11px;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: .2s;
        }

        .logout-btn:hover {
            background: white;
            color: var(--matcha-700);
        }

        /* MAIN */

        .main-wrap {
            margin-left: 260px;
            padding: 28px 32px;
            min-height: 100vh;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }

        .topbar h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--matcha-800);
            margin: 0;
        }

        .subtitle {
            color: #888;
            font-size: .88rem;
            margin-top: 4px;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            padding: 7px 14px 7px 7px;
            border-radius: 40px;
            box-shadow: 0 2px 10px rgba(46,79,37,.08);
        }

        .avatar {
            width: 38px;
            height: 38px;
            background: linear-gradient(
                135deg,
                var(--matcha-400),
                var(--matcha-600)
            );
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
        }

        .user-name {
            font-size: .85rem;
            font-weight: 600;
        }

        .user-role {
            color: var(--matcha-600);
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* STATISTICS */

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 16px;
            border: 1px solid var(--matcha-100);
            box-shadow: 0 4px 18px rgba(46,79,37,.05);
            transition: .25s;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 26px rgba(46,79,37,.12);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 14px;
        }

        .ib-green {
            background: #e6f0e2;
            color: var(--matcha-600);
        }

        .ib-blue {
            background: #e3eefb;
            color: #2c6fb5;
        }

        .ib-orange {
            background: #fdf0e1;
            color: #d17a22;
        }

        .ib-purple {
            background: #ece5fb;
            color: #7b52c9;
        }

        .stat-label {
            color: #888;
            font-size: .83rem;
        }

        .stat-value {
            color: var(--matcha-800);
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 3px;
        }

        /* PANEL */

        .panel {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--matcha-100);
            box-shadow: 0 4px 18px rgba(46,79,37,.05);
            overflow: hidden;
        }

        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--matcha-100);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .panel-header h6 {
            margin: 0;
            font-weight: 700;
            color: var(--matcha-800);
        }

        .panel-body {
            padding: 20px 22px;
        }

        /* TABLE */

        .table-modern {
            margin: 0;
            font-size: .88rem;
        }

        .table-modern thead th {
            background: var(--matcha-50);
            color: var(--matcha-700);
            border: none;
            padding: 13px;
            font-size: .76rem;
            text-transform: uppercase;
        }

        .table-modern tbody td {
            padding: 13px;
            border-color: var(--matcha-100);
            vertical-align: middle;
        }

        .avatar-sm {
            width: 34px;
            height: 34px;
            background: var(--matcha-500);
            border-radius: 50%;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin-right: 9px;
        }

        .role-badge {
            padding: 5px 11px;
            border-radius: 20px;
            font-size: .73rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .role-admin {
            background: #e6f0e2;
            color: var(--matcha-700);
        }

        .role-petugas {
            background: #e3eefb;
            color: #2c6fb5;
        }

        .role-siswa {
            background: #fdf0e1;
            color: #d17a22;
        }

        /* CONTENT FRAME */

        .content-frame {
            width: 100%;
            height: calc(100vh - 145px);
            min-height: 650px;
            border: none;
            border-radius: 15px;
            background: var(--matcha-50);
        }

        .mobile-toggle {
            display: none;
            width: 42px;
            height: 42px;
            background: var(--matcha-600);
            color: white;
            border: none;
            border-radius: 10px;
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .overlay {
            display: none;
        }

        /* RESPONSIVE */

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrap {
                margin-left: 0;
                padding: 20px;
            }

            .mobile-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .overlay.show {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,.35);
                z-index: 999;
            }

            .content-frame {
                height: calc(100vh - 125px);
                min-height: 650px;
            }
        }

        @media (max-width: 576px) {
            .user-chip .info {
                display: none;
            }

            .topbar h1 {
                font-size: 1.15rem;
            }

            .main-wrap {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<!-- OVERLAY MOBILE -->
<div class="overlay" id="overlay"
     onclick="tutupSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">

    <div class="brand">
        <div class="brand-logo">
            <i class="bi bi-cup-hot-fill"></i>
        </div>
        <span>Matcha Panel</span>
    </div>

    <div class="nav-label">Menu Utama</div>

    <a href="admin.php?halaman=dashboard"
       class="nav-link <?= $halaman === 'dashboard' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2-fill"></i>
        Dashboard
    </a>

    <a href="admin.php?halaman=kelola_user"
       class="nav-link <?= $halaman === 'kelola_user' ? 'active' : '' ?>">
        <i class="bi bi-people-fill"></i>
        Kelola Pengguna
    </a>

    <div class="nav-label">Akun</div>

    <a href="admin.php?halaman=profil"
       class="nav-link <?= $halaman === 'profil' ? 'active' : '' ?>">
        <i class="bi bi-person-circle"></i>
        Profil Saya
    </a>

    <a href="logout.php" class="logout-btn">
        <i class="bi bi-box-arrow-right me-1"></i>
        Logout
    </a>

</aside>

<!-- MAIN -->
<main class="main-wrap">

    <!-- TOPBAR -->
    <div class="topbar">

        <div class="d-flex align-items-center">

            <button class="mobile-toggle"
                    onclick="bukaSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <div>
                <h1><?= htmlspecialchars($judul) ?></h1>

                <div class="subtitle">
                    Selamat datang, <?= htmlspecialchars($namaAdmin) ?>!
                </div>
            </div>

        </div>

        <div class="user-chip">

            <div class="avatar">
                <?= strtoupper(substr($namaAdmin, 0, 1)) ?>
            </div>

            <div class="info">
                <div class="user-name">
                    <?= htmlspecialchars($namaAdmin) ?>
                </div>

                <div class="user-role">
                    Administrator
                </div>
            </div>

        </div>

    </div>

    <?php if ($halaman === 'dashboard'): ?>

        <!-- STATISTIK -->
        <div class="row g-3 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box ib-green">
                        <i class="bi bi-people-fill"></i>
                    </div>

                    <div class="stat-label">Total Pengguna</div>

                    <div class="stat-value">
                        <?= $totalUser ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box ib-blue">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>

                    <div class="stat-label">Administrator</div>

                    <div class="stat-value">
                        <?= $totalAdmin ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box ib-orange">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>

                    <div class="stat-label">Petugas</div>

                    <div class="stat-value">
                        <?= $totalPetugas ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="icon-box ib-purple">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div class="stat-label">Siswa</div>

                    <div class="stat-value">
                        <?= $totalSiswa ?>
                    </div>
                </div>
            </div>

        </div>

        <!-- TABEL PENGGUNA -->
        <div class="panel">

            <div class="panel-header">

                <h6>
                    <i class="bi bi-people-fill me-2"></i>
                    Pengguna Terbaru
                </h6>

                <a href="admin.php?halaman=kelola_user"
                   class="btn btn-sm"
                   style="background:#5c9c48;color:white;">
                    Lihat Semua
                    <i class="bi bi-arrow-right ms-1"></i>
                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-modern align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pengguna</th>
                            <th>Username</th>
                            <th>Role</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (count($penggunaTerbaru) > 0): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($penggunaTerbaru as $u): ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td>
                                    <span class="avatar-sm">
                                        <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                    </span>

                                    <?= htmlspecialchars($u['nama']) ?>
                                </td>

                                <td>
                                    @<?= htmlspecialchars($u['username']) ?>
                                </td>

                                <td>
                                    <span class="role-badge role-<?= htmlspecialchars($u['role']) ?>">
                                        <?= htmlspecialchars($u['role']) ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada pengguna.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

        <!-- AKSI CEPAT -->
        <div class="panel mt-4">

            <div class="panel-body d-flex flex-wrap align-items-center justify-content-between gap-3">

                <div>
                    <h6 class="fw-bold mb-1"
                        style="color:var(--matcha-800)">
                        Aksi Cepat
                    </h6>

                    <small class="text-muted">
                        Akses pengelolaan aplikasi
                    </small>
                </div>

                <div class="d-flex flex-wrap gap-2">

                    <a href="admin.php?halaman=kelola_user"
                       class="btn btn-success">
                        <i class="bi bi-people-fill me-1"></i>
                        Kelola Pengguna
                    </a>

                    <a href="admin.php?halaman=profil"
                       class="btn btn-outline-success">
                        <i class="bi bi-person-circle me-1"></i>
                        Profil Saya
                    </a>

                </div>

            </div>

        </div>

    <?php else: ?>

        <!-- KONTEN HALAMAN LAIN -->
        <iframe
            id="contentFrame"
            class="content-frame"
            src="<?= htmlspecialchars($urlKonten, ENT_QUOTES, 'UTF-8') ?>"
            title="<?= htmlspecialchars($judul) ?>">
        </iframe>

    <?php endif; ?>

    <footer class="text-center small mt-4"
            style="color:#9aa79a">
        &copy; <?= date('Y') ?> Matcha Panel — Dashboard Administrator
    </footer>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function bukaSidebar() {
        document.getElementById('sidebar').classList.add('show');
        document.getElementById('overlay').classList.add('show');
    }

    function tutupSidebar() {
        document.getElementById('sidebar').classList.remove('show');
        document.getElementById('overlay').classList.remove('show');
    }

    // Menangani link kembali dari halaman di dalam iframe.
    // Contoh: tombol kembali pada kelola_user.php menuju admin.php.
    const frame = document.getElementById('contentFrame');

    if (frame) {
        frame.addEventListener('load', function () {
            try {
                const frameDoc = frame.contentDocument;

                frameDoc.addEventListener('click', function (event) {
                    const link = event.target.closest('a[href]');

                    if (!link) return;

                    const tujuan = new URL(link.href, window.location.href);

                    if (
                        tujuan.origin === window.location.origin &&
                        tujuan.pathname.endsWith('/admin.php')
                    ) {
                        event.preventDefault();
                        window.location.href = tujuan.href;
                    }
                }, true);

            } catch (error) {
                console.warn('Navigasi iframe tidak dapat diproses.');
            }
        });
    }
</script>

</body>
</html>
