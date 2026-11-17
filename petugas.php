<?php
require 'auth.php';
cekRole('petugas');

$namaPetugas = $_SESSION['nama'];
$username    = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Petugas — Matcha E-School</title>
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

        /* ==== MAIN ==== */
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
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 26px rgba(46, 79, 37, .12);
        }
        .stat-card .top-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }
        .stat-card .icon-box {
            width: 48px; height: 48px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }
        .stat-card .label {
            font-size: .82rem;
            color: #7a7a7a;
            font-weight: 500;
            margin-bottom: 4px;
        }
        .stat-card .value {
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--matcha-800);
            line-height: 1;
        }
        .stat-card .trend {
            font-size: .76rem;
            font-weight: 600;
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .trend.up { color: var(--matcha-600); }
        .trend.down { color: #c0392b; }
        .ib-green { background: #e6f0e2; color: var(--matcha-600); }
        .ib-blue  { background: #e3eefb; color: #2c6fb5; }
        .ib-orange{ background: #fdf0e1; color: #d17a22; }
        .ib-teal  { background: #e0f2f1; color: #1a8f83; }

        /* ==== PANEL ==== */
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
        .avatar-sm {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--matcha-300), var(--matcha-500));
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .75rem;
            margin-right: 9px;
        }
        .status-badge {
            padding: 4px 11px;
            border-radius: 20px;
            font-size: .74rem;
            font-weight: 600;
        }
        .st-wait { background: #fff3cd; color: #664d03; }
        .st-ok   { background: #d1e7dd; color: #0f5132; }
        .st-no   { background: #f8d7da; color: #842029; }
        .st-proc { background: #cfe2ff; color: #084298; }

        /* ==== QUICK ACTION TILES ==== */
        .action-tile {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #fff;
            border: 1px solid var(--matcha-100);
            border-radius: 14px;
            padding: 16px 18px;
            text-decoration: none;
            color: #2b2b2b;
            transition: all .2s ease;
            height: 100%;
        }
        .action-tile:hover {
            border-color: var(--matcha-400);
            background: var(--matcha-50);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(46, 79, 37, .1);
            color: var(--matcha-800);
        }
        .action-tile .at-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .action-tile .at-title { font-weight: 700; font-size: .92rem; }
        .action-tile .at-desc { font-size: .78rem; color: #888; margin-top: 1px; }

        /* ==== ACTIVITY ==== */
        .activity-item {
            display: flex;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px dashed var(--matcha-100);
        }
        .activity-item:last-child { border-bottom: none; padding-bottom: 0; }
        .activity-icon {
            width: 38px; height: 38px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .activity-text { font-size: .86rem; color: #444; line-height: 1.4; }
        .activity-time { font-size: .72rem; color: #999; margin-top: 2px; }

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
        <div class="logo"><i class="bi bi-clipboard2-check-fill"></i></div>
        <span>Matcha Petugas</span>
    </div>

    <div class="nav-label">Operasional</div>
    <a href="#" class="nav-link active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
    <a href="#" class="nav-link"><i class="bi bi-clipboard2-check-fill"></i> Verifikasi Data</a>
    <a href="#" class="nav-link"><i class="bi bi-journal-plus"></i> Input Data</a>
    <a href="#" class="nav-link"><i class="bi bi-cash-coin"></i> Pembayaran SPP</a>

    <div class="nav-label">Laporan</div>
    <a href="#" class="nav-link"><i class="bi bi-file-earmark-bar-graph-fill"></i> Rekap Harian</a>
    <a href="#" class="nav-link"><i class="bi bi-printer-fill"></i> Cetak Bukti</a>
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
                <h1>Dashboard Petugas</h1>
                <div class="subtitle">Halo, <strong><?= htmlspecialchars($namaPetugas) ?></strong> — siap melayani hari ini? 💪</div>
            </div>
        </div>
        <div class="user-chip">
            <div class="avatar"><?= strtoupper(substr($namaPetugas, 0, 1)) ?></div>
            <div class="info">
                <div class="name"><?= htmlspecialchars($namaPetugas) ?></div>
                <div class="role">Petugas</div>
            </div>
        </div>
    </div>

    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="top-row">
                    <div class="icon-box ib-orange"><i class="bi bi-hourglass-split"></i></div>
                    <span class="trend up"><i class="bi bi-arrow-up-right"></i> +6</span>
                </div>
                <div class="label">Menunggu Verifikasi</div>
                <div class="value">18</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="top-row">
                    <div class="icon-box ib-green"><i class="bi bi-check2-circle"></i></div>
                    <span class="trend up"><i class="bi bi-arrow-up-right"></i> +12</span>
                </div>
                <div class="label">Terverifikasi Hari Ini</div>
                <div class="value">42</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="top-row">
                    <div class="icon-box ib-teal"><i class="bi bi-cash-stack"></i></div>
                    <span class="trend up"><i class="bi bi-arrow-up-right"></i> +8</span>
                </div>
                <div class="label">SPP Diterima</div>
                <div class="value">35</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card">
                <div class="top-row">
                    <div class="icon-box ib-blue"><i class="bi bi-people-fill"></i></div>
                    <span class="trend down"><i class="bi bi-arrow-down-right"></i> -2</span>
                </div>
                <div class="label">Antrian Aktif</div>
                <div class="value">7</div>
            </div>
        </div>
    </div>

    <!-- QUICK ACTION TILES -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="#" class="action-tile">
                <div class="at-icon ib-orange"><i class="bi bi-clipboard2-check-fill"></i></div>
                <div>
                    <div class="at-title">Verifikasi Data</div>
                    <div class="at-desc">18 antrian menunggu</div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="#" class="action-tile">
                <div class="at-icon ib-green"><i class="bi bi-journal-plus"></i></div>
                <div>
                    <div class="at-title">Input Data Siswa</div>
                    <div class="at-desc">Tambah data baru</div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="#" class="action-tile">
                <div class="at-icon ib-teal"><i class="bi bi-cash-coin"></i></div>
                <div>
                    <div class="at-title">Terima SPP</div>
                    <div class="at-desc">Catat pembayaran</div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="#" class="action-tile">
                <div class="at-icon ib-blue"><i class="bi bi-printer-fill"></i></div>
                <div>
                    <div class="at-title">Cetak Bukti</div>
                    <div class="at-desc">Kwitansi / surat</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-4">

        <!-- ANTRIAN VERIFIKASI -->
        <div class="col-lg-8">
            <div class="panel">
                <div class="panel-header">
                    <h6><i class="bi bi-clipboard2-check-fill"></i> Antrian Verifikasi</h6>
                    <a href="#" class="small text-decoration-none" style="color: var(--matcha-600); font-weight:600;">Lihat semua</a>
                </div>
                <div class="panel-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern align-middle">
                            <thead>
                                <tr>
                                    <th>Pemohon</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span class="avatar-sm">R</span>
                                        Rina Wulandari
                                    </td>
                                    <td>Pendaftaran Siswa</td>
                                    <td>17 Nov 2026</td>
                                    <td><span class="status-badge st-wait">Menunggu</span></td>
                                    <td>
                                        <button class="btn btn-sm" style="background: var(--matcha-500); color:#fff; border-radius:7px;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-x-lg text-danger"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="avatar-sm" style="background: linear-gradient(135deg,#7fb8e8,#2c6fb5);">A</span>
                                        Andi Saputra
                                    </td>
                                    <td>Perubahan Data</td>
                                    <td>17 Nov 2026</td>
                                    <td><span class="status-badge st-proc">Diproses</span></td>
                                    <td>
                                        <button class="btn btn-sm" style="background: var(--matcha-500); color:#fff; border-radius:7px;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-x-lg text-danger"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="avatar-sm" style="background: linear-gradient(135deg,#f5b46a,#d17a22);">D</span>
                                        Dina Maulida
                                    </td>
                                    <td>Pembayaran SPP</td>
                                    <td>16 Nov 2026</td>
                                    <td><span class="status-badge st-ok">Selesai</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light" disabled><i class="bi bi-check2 text-muted"></i></button>
                                        <button class="btn btn-sm btn-light" disabled><i class="bi bi-x-lg text-muted"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="avatar-sm" style="background: linear-gradient(135deg,#c39bd3,#7b52c9);">F</span>
                                        Fajar Ramadhan
                                    </td>
                                    <td>Pendaftaran Siswa</td>
                                    <td>16 Nov 2026</td>
                                    <td><span class="status-badge st-no">Ditolak</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-light" disabled><i class="bi bi-check2 text-muted"></i></button>
                                        <button class="btn btn-sm btn-light" disabled><i class="bi bi-x-lg text-muted"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <span class="avatar-sm">S</span>
                                        Sinta Aulia
                                    </td>
                                    <td>Perubahan Data</td>
                                    <td>15 Nov 2026</td>
                                    <td><span class="status-badge st-wait">Menunggu</span></td>
                                    <td>
                                        <button class="btn btn-sm" style="background: var(--matcha-500); color:#fff; border-radius:7px;">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light"><i class="bi bi-x-lg text-danger"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- AKTIVITAS -->
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-header">
                    <h6><i class="bi bi-clock-history"></i> Aktivitas Saya</h6>
                </div>
                <div class="panel-body pt-2">
                    <div class="activity-item">
                        <div class="activity-icon ib-green"><i class="bi bi-check2-circle"></i></div>
                        <div>
                            <div class="activity-text">Memverifikasi <strong>Andi Saputra</strong></div>
                            <div class="activity-time">5 menit lalu</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon ib-teal"><i class="bi bi-cash-coin"></i></div>
                        <div>
                            <div class="activity-text">Menerima SPP <strong>Dina Maulida</strong></div>
                            <div class="activity-time">22 menit lalu</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon ib-orange"><i class="bi bi-x-lg"></i></div>
                        <div>
                            <div class="activity-text">Menolak pendaftaran <strong>Fajar</strong></div>
                            <div class="activity-time">1 jam lalu</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon ib-blue"><i class="bi bi-printer-fill"></i></div>
                        <div>
                            <div class="activity-text">Cetak 3 kwitansi SPP</div>
                            <div class="activity-time">2 jam lalu</div>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon ib-green"><i class="bi bi-journal-plus"></i></div>
                        <div>
                            <div class="activity-text">Input data siswa baru <strong>Sinta</strong></div>
                            <div class="activity-time">3 jam lalu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <footer class="text-center small mt-4" style="color:#9aa79a;">
        &copy; <?= date('Y') ?> Matcha E-School — Dashboard Petugas
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>