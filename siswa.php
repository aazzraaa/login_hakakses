<?php
require 'auth.php';
cekRole('siswa');

$namaSiswa = $_SESSION['nama'];
$username  = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Siswa — Matcha E-School</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --matcha-50:  #f4f9f2;
            --matcha-100: #e6f0e2;
            --matcha-200: #cfe3c8;
            --matcha-300: #a8cd9a;
            --matcha-400: #7fb56b;
            --matcha-500: #5c9c48;
            --matcha-600: #4a7f39;
            --matcha-700: #3a642e;
            --matcha-800: #2e4f25;
        }
        * { -webkit-font-smoothing: antialiased; }
        body {
            background-color: var(--matcha-50);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #2b2b2b;
        }

        /* ==== SIDEBAR ==== */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: linear-gradient(180deg, var(--matcha-700) 0%, var(--matcha-800) 100%);
            position: fixed;
            top: 0; left: 0;
            padding: 22px 16px;
            box-shadow: 4px 0 24px rgba(46, 79, 37, 0.15);
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        .sidebar .brand {
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
            padding: 0 8px;
        }
        .sidebar .brand .logo {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--matcha-300), var(--matcha-500));
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,.2);
            color: #fff;
            font-size: 1.1rem;
        }
        .sidebar .nav-label {
            color: rgba(255,255,255,.45);
            font-size: .72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 10px;
            margin: 16px 0 8px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 3px;
            font-size: .92rem;
            display: flex;
            align-items: center;
            gap: 11px;
            transition: all .2s ease;
        }
        .sidebar .nav-link i { font-size: 1.05rem; }
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.08);
            color: #fff;
            transform: translateX(3px);
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, var(--matcha-400), var(--matcha-500));
            color: #fff;
            box-shadow: 0 4px 14px rgba(127, 181, 107, .4);
        }
        .sidebar .logout-btn {
            margin-top: auto;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            color: #fff;
            border-radius: 10px;
            padding: 10px;
            font-size: .9rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: all .2s;
        }
        .sidebar .logout-btn:hover {
            background: #fff;
            color: var(--matcha-700);
        }

        /* ==== MAIN AREA ==== */
        .main-wrap {
            margin-left: 250px;
            padding: 28px 32px;
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
        .topbar .subtitle {
            color: #7a7a7a;
            font-size: .88rem;
            margin-top: 2px;
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            padding: 7px 14px 7px 7px;
            border-radius: 40px;
            box-shadow: 0 2px 10px rgba(46, 79, 37, .08);
        }
        .user-chip .avatar {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--matcha-400), var(--matcha-600));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
        }
        .user-chip .info { line-height: 1.2; }
        .user-chip .info .name { font-size: .85rem; font-weight: 600; color: #333; }
        .user-chip .info .role {
            font-size: .72rem;
            color: var(--matcha-600);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ==== STAT CARDS ==== */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--matcha-100);
            box-shadow: 0 4px 18px rgba(46, 79, 37, .05);
            transition: all .25s ease;
            display: flex;
            align-items: center;
            gap: 14px;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 26px rgba(46, 79, 37, .12);
        }
        .stat-card .icon-box {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }
        .stat-card .label {
            font-size: .8rem;
            color: #7a7a7a;
            font-weight: 500;
            margin-bottom: 2px;
        }
        .stat-card .value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--matcha-800);
            line-height: 1.1;
        }
        .ib-green { background: #e6f0e2; color: var(--matcha-600); }
        .ib-blue  { background: #e3eefb; color: #2c6fb5; }
        .ib-orange{ background: #fdf0e1; color: #d17a22; }
        .ib-teal  { background: #e0f2f1; color: #1a8f83; }

        /* ==== PANEL CARDS ==== */
        .panel {
            background: #fff;
            border-radius: 16px;
            border: 1px solid var(--matcha-100);
            box-shadow: 0 4px 18px rgba(46, 79, 37, .05);
            overflow: hidden;
            height: 100%;
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
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .panel-header h6 i { color: var(--matcha-500); }
        .panel-body { padding: 18px 22px; }

        /* ==== TABLE ==== */
        .table-modern { margin: 0; font-size: .88rem; }
        .table-modern thead th {
            background: var(--matcha-50);
            color: var(--matcha-700);
            font-weight: 600;
            font-size: .76rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            border: none;
            padding: 12px 14px;
        }
        .table-modern tbody td {
            padding: 13px 14px;
            border-color: var(--matcha-100);
            vertical-align: middle;
        }
        .table-modern tbody tr:hover { background: var(--matcha-50); }
        .time-badge {
            background: var(--matcha-100);
            color: var(--matcha-700);
            padding: 4px 10px;
            border-radius: 8px;
            font-size: .78rem;
            font-weight: 600;
        }
        .score-badge {
            background: var(--matcha-100);
            color: var(--matcha-700);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: .78rem;
            font-weight: 700;
        }

        /* ==== ANNOUNCEMENT ==== */
        .announce-item {
            display: flex;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px dashed var(--matcha-100);
        }
        .announce-item:last-child { border-bottom: none; padding-bottom: 0; }
        .announce-icon {
            width: 40px; height: 40px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }
        .announce-title { font-weight: 600; font-size: .9rem; color: #2b2b2b; }
        .announce-desc { font-size: .82rem; color: #777; line-height: 1.45; margin-top: 2px; }
        .announce-time { font-size: .72rem; color: #aaa; margin-top: 4px; }

        /* ==== PROFILE CARD ==== */
        .profile-avatar-lg {
            width: 82px; height: 82px;
            background: linear-gradient(135deg, var(--matcha-400), var(--matcha-600));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 2rem;
            margin: 0 auto;
            box-shadow: 0 8px 20px rgba(92, 156, 72, .35);
        }
        .profile-list .list-group-item {
            border-color: var(--matcha-100);
            padding: 11px 0;
            font-size: .86rem;
        }
        .profile-list .list-group-item .label { color: #888; }
        .profile-list .list-group-item .val { font-weight: 600; color: var(--matcha-800); }

        /* ==== RESPONSIVE ==== */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); transition: transform .3s; }
            .sidebar.show { transform: translateX(0); }
            .main-wrap { margin-left: 0; padding: 20px; }
            .mobile-toggle { display: inline-flex !important; }
        }
        .mobile-toggle {
            display: none;
            background: var(--matcha-600);
            color: #fff;
            border: none;
            border-radius: 10px;
            width: 42px; height: 42px;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-right: 12px;
        }
    </style>
</head>
<body>

<!-- ================= SIDEBAR ================= -->
<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="logo"><i class="bi bi-mortarboard-fill"></i></div>
        <span>Matcha E-School</span>
    </div>

    <div class="nav-label">Menu Siswa</div>
    <a href="#" class="nav-link active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="#" class="nav-link"><i class="bi bi-person-vcard-fill"></i> Data Pribadi</a>
    <a href="#" class="nav-link"><i class="bi bi-calendar-week-fill"></i> Jadwal Pelajaran</a>
    <a href="#" class="nav-link"><i class="bi bi-journal-check"></i> Nilai</a>

    <div class="nav-label">Layanan</div>
    <a href="#" class="nav-link"><i class="bi bi-cash-coin"></i> Pembayaran SPP</a>
    <a href="#" class="nav-link"><i class="bi bi-megaphone-fill"></i> Pengumuman</a>
    <a href="#" class="nav-link"><i class="bi bi-question-circle-fill"></i> Bantuan</a>

    <a href="logout.php" class="logout-btn">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</aside>

<!-- ================= MAIN ================= -->
<div class="main-wrap">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <h1>Dashboard Siswa</h1>
                <div class="subtitle">Selamat datang kembali, <strong><?= htmlspecialchars($namaSiswa) ?></strong> 👋</div>
            </div>
        </div>
        <div class="user-chip">
            <div class="avatar"><?= strtoupper(substr($namaSiswa, 0, 1)) ?></div>
            <div class="info">
                <div class="name"><?= htmlspecialchars($namaSiswa) ?></div>
                <div class="role">Siswa</div>
            </div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-box ib-green"><i class="bi bi-journal-text"></i></div>
                <div>
                    <div class="label">Mata Pelajaran</div>
                    <div class="value">10</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-box ib-blue"><i class="bi bi-award-fill"></i></div>
                <div>
                    <div class="label">Rata-rata Nilai</div>
                    <div class="value">87.5</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-box ib-orange"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <div class="label">Kehadiran</div>
                    <div class="value">96%</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="icon-box ib-teal"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="label">Status SPP</div>
                    <div class="value" style="color: var(--matcha-600);">Lunas</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <!-- JADWAL HARI INI -->
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-header">
                    <h6><i class="bi bi-calendar-week-fill"></i> Jadwal Hari Ini</h6>
                    <small class="text-muted"><?= date('d M Y') ?></small>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Jam</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Ruang</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="time-badge">07.00 - 08.30</span></td>
                                    <td class="fw-semibold">Matematika</td>
                                    <td>Bu Sari</td>
                                    <td>R-101</td>
                                </tr>
                                <tr>
                                    <td><span class="time-badge">08.30 - 10.00</span></td>
                                    <td class="fw-semibold">Bahasa Indonesia</td>
                                    <td>Pak Budi</td>
                                    <td>R-102</td>
                                </tr>
                                <tr>
                                    <td><span class="time-badge">10.15 - 11.45</span></td>
                                    <td class="fw-semibold">Bahasa Inggris</td>
                                    <td>Miss Dewi</td>
                                    <td>Lab Bahasa</td>
                                </tr>
                                <tr>
                                    <td><span class="time-badge">13.00 - 14.30</span></td>
                                    <td class="fw-semibold">Pemrograman Web</td>
                                    <td>Pak Andi</td>
                                    <td>Lab Komputer</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- PENGUMUMAN -->
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <h6><i class="bi bi-megaphone-fill"></i> Pengumuman Terbaru</h6>
                    <a href="#" class="small text-decoration-none" style="color: var(--matcha-600); font-weight:600;">Lihat semua</a>
                </div>
                <div class="panel-body pt-3">
                    <div class="announce-item">
                        <div class="announce-icon ib-blue"><i class="bi bi-info-circle-fill"></i></div>
                        <div>
                            <div class="announce-title">Ujian Tengah Semester</div>
                            <div class="announce-desc">UTS dilaksanakan mulai 25 November 2024. Persiapkan dirimu!</div>
                            <div class="announce-time">2 hari lalu</div>
                        </div>
                    </div>
                    <div class="announce-item">
                        <div class="announce-icon ib-green"><i class="bi bi-check2-circle"></i></div>
                        <div>
                            <div class="announce-title">Pembayaran SPP</div>
                            <div class="announce-desc">Terima kasih, SPP bulan ini sudah lunas.</div>
                            <div class="announce-time">5 hari lalu</div>
                        </div>
                    </div>
                    <div class="announce-item">
                        <div class="announce-icon ib-orange"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div>
                            <div class="announce-title">Ekstrakurikuler</div>
                            <div class="announce-desc">Pendaftaran ekskul dibuka sampai akhir bulan ini.</div>
                            <div class="announce-time">1 minggu lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NILAI TERBARU -->
        <div class="col-lg-7">
            <div class="panel">
                <div class="panel-header">
                    <h6><i class="bi bi-journal-check"></i> Nilai Terbaru</h6>
                    <a href="#" class="small text-decoration-none" style="color: var(--matcha-600); font-weight:600;">Lihat semua</a>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Tugas</th>
                                    <th>UTS</th>
                                    <th>UAS</th>
                                    <th>Rata-rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-semibold">Matematika</td>
                                    <td>85</td>
                                    <td>88</td>
                                    <td>90</td>
                                    <td><span class="score-badge">87.7</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Bahasa Indonesia</td>
                                    <td>90</td>
                                    <td>85</td>
                                    <td>88</td>
                                    <td><span class="score-badge">87.7</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Bahasa Inggris</td>
                                    <td>80</td>
                                    <td>82</td>
                                    <td>85</td>
                                    <td><span class="score-badge">82.3</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Pemrograman Web</td>
                                    <td>95</td>
                                    <td>92</td>
                                    <td>94</td>
                                    <td><span class="score-badge">93.7</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- DATA PRIBADI -->
        <div class="col-lg-5">
            <div class="panel">
                <div class="panel-header">
                    <h6><i class="bi bi-person-vcard-fill"></i> Data Pribadi</h6>
                </div>
                <div class="panel-body">
                    <div class="text-center mb-3">
                        <div class="profile-avatar-lg"><?= strtoupper(substr($namaSiswa, 0, 1)) ?></div>
                        <h6 class="mt-3 mb-0 fw-bold"><?= htmlspecialchars($namaSiswa) ?></h6>
                        <small class="text-muted">@<?= htmlspecialchars($username) ?></small>
                    </div>
                    <ul class="list-group list-group-flush profile-list">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="label"><i class="bi bi-hash me-1"></i>NIS</span>
                            <span class="val">2024001</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="label"><i class="bi bi-door-open me-1"></i>Kelas</span>
                            <span class="val">XII RPL 1</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="label"><i class="bi bi-book me-1"></i>Jurusan</span>
                            <span class="val">Rekayasa Perangkat Lunak</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="label"><i class="bi bi-person-badge me-1"></i>Wali Kelas</span>
                            <span class="val">Bu Rina</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="text-center small mt-4" style="color:#9aa79a;">
        &copy; <?= date('Y') ?> Matcha E-School — Dashboard Siswa
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>