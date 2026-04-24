<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem TA') ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 280px;
            --bg: #eef4fb;
            --navy-1: #06122e;
            --navy-2: #0b1738;
            --blue: #2563eb;
            --blue-dark: #1d4ed8;
            --red: #ef4444;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --card: #ffffff;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: Inter, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        body {
            overflow: hidden;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .app-shell {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--navy-1), var(--navy-2));
            color: #fff;
            padding: 24px 20px;
            border-radius: 0 24px 24px 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
            box-shadow: 10px 0 35px rgba(15, 23, 42, 0.18);
        }

        .sidebar::-webkit-scrollbar {
            width: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 22px;
            margin-bottom: 22px;
            border-bottom: 1px solid rgba(255,255,255,0.09);
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            border-radius: 22px;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #07142f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 900;
            flex-shrink: 0;
            box-shadow: 0 14px 30px rgba(37,99,235,.28);
        }

        .brand-title {
            margin: 0;
            font-size: 21px;
            font-weight: 900;
            line-height: 1.2;
        }

        .brand-subtitle {
            margin-top: 6px;
            font-size: 13px;
            color: rgba(255,255,255,.76);
            letter-spacing: .03em;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 9px;
            padding-bottom: 20px;
        }

        .nav-link {
            min-height: 52px;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 18px;
            color: rgba(255,255,255,.84);
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            border: 1px solid transparent;
            transition: .22s ease;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.06);
            color: #fff;
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(59,130,246,.30), rgba(37,99,235,.18));
            border-color: rgba(147,197,253,.18);
            color: #fff;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.05);
        }

        .nav-dot {
            width: 11px;
            height: 11px;
            border-radius: 999px;
            background: rgba(203,213,225,.72);
            flex-shrink: 0;
        }

        .nav-link.active .nav-dot {
            background: #bfdbfe;
            box-shadow: 0 0 0 4px rgba(191,219,254,.12);
        }

        .nav-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nav-badge {
            margin-left: auto;
            min-width: 22px;
            height: 22px;
            padding: 0 7px;
            border-radius: 999px;
            background: var(--red);
            color: #fff;
            font-size: 11px;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-user {
            margin-top: 18px;
            padding: 15px 16px;
            border-radius: 18px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.08);
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 900;
            line-height: 1.4;
            color: #fff;
        }

        .sidebar-user-role {
            margin-top: 4px;
            font-size: 12px;
            color: rgba(255,255,255,.76);
            line-height: 1.5;
        }

        .content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 26px 28px;
        }

        .topbar {
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(226,232,240,.9);
            border-radius: 30px;
            padding: 28px 32px;
            box-shadow: 0 18px 40px rgba(15,23,42,.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 26px;
        }

        .topbar-left h1 {
            margin: 0;
            font-size: 34px;
            font-weight: 900;
            letter-spacing: -.03em;
            color: var(--text);
        }

        .topbar-left p {
            margin: 10px 0 0;
            font-size: 15px;
            color: var(--muted);
            line-height: 1.6;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .user-chip {
            max-width: 260px;
            padding: 13px 18px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #dbe3ef;
            color: #64748b;
            font-size: 14px;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn {
            border: none;
            outline: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 18px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 900;
            cursor: pointer;
            transition: .22s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            color: #fff;
            box-shadow: 0 12px 28px rgba(37,99,235,.22);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            box-shadow: 0 12px 28px rgba(239,68,68,.18);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .alert {
            padding: 15px 18px;
            border-radius: 18px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.6;
        }

        .alert-success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .mobile-toggle {
            display: none;
            position: fixed;
            top: 14px;
            left: 14px;
            z-index: 1200;
            width: 44px;
            height: 44px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--blue), var(--blue-dark));
            color: #fff;
            font-size: 22px;
            font-weight: 900;
            box-shadow: 0 12px 28px rgba(37,99,235,.26);
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 980px) {
            body {
                overflow: auto;
            }

            .app-shell {
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }

            .mobile-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                width: min(86vw, 330px);
                transform: translateX(-105%);
                transition: transform .28s ease;
                border-radius: 0 24px 24px 0;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15,23,42,.45);
                z-index: 900;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0;
                width: 100%;
                height: auto;
                min-height: 100vh;
                overflow: visible;
                padding: 76px 16px 24px;
            }

            .topbar {
                border-radius: 24px;
                padding: 24px 20px;
                flex-direction: column;
                align-items: flex-start;
            }

            .topbar-left h1 {
                font-size: 27px;
            }

            .topbar-right {
                width: 100%;
                flex-wrap: wrap;
            }

            .user-chip {
                max-width: 100%;
                flex: 1;
            }
        }

        @media (max-width: 520px) {
            .brand-logo {
                width: 60px;
                height: 60px;
                border-radius: 19px;
                font-size: 21px;
            }

            .brand-title {
                font-size: 19px;
            }

            .nav-link {
                font-size: 14px;
                min-height: 50px;
            }

            .topbar-left h1 {
                font-size: 25px;
            }

            .btn {
                padding: 12px 15px;
            }
        }
    </style>
</head>

<body>
<?php
    $role       = session()->get('role');
    $activeMenu = $activeMenu ?? '';
    $userName   = session()->get('name') ?? session()->get('email') ?? 'User';
    $userEmail  = session()->get('email') ?? '-';

    $isActive = static function ($keys) use ($activeMenu): string {
        $keys = is_array($keys) ? $keys : [$keys];
        return in_array($activeMenu, $keys, true) ? 'active' : '';
    };

    if ($role === 'admin') {
        $navItems = [
            ['keys' => ['dashboard'], 'label' => 'Dashboard', 'url' => '/dashboard'],
            ['keys' => ['admin_users'], 'label' => 'Kelola Users', 'url' => '/admin/users'],
            ['keys' => ['periode_akademik', 'admin_periode'], 'label' => 'Periode Akademik', 'url' => '/admin/periode-akademik'],
            ['keys' => ['program_studi', 'admin_prodi'], 'label' => 'Program Studi', 'url' => '/admin/program-studi'],
            ['keys' => ['monitoring_pembimbing', 'admin_monitoring_pembimbing'], 'label' => 'Monitoring Pembimbing', 'url' => '/admin/monitoring-pembimbing'],
            ['keys' => ['monitoring_judul', 'admin_monitoring_judul'], 'label' => 'Monitoring Judul', 'url' => '/admin/monitoring-judul'],
            ['keys' => ['monitoring_proposal', 'admin_monitoring_proposal'], 'label' => 'Monitoring Proposal', 'url' => '/admin/monitoring-proposal'],
            ['keys' => ['surat_keputusan_admin', 'admin_surat_keputusan'], 'label' => 'Surat Keputusan', 'url' => '/admin/surat-keputusan'],
            ['keys' => ['laporan_arsip', 'admin_laporan'], 'label' => 'Laporan / Arsip', 'url' => '/admin/laporan'],
            ['keys' => ['audit_log', 'admin_audit'], 'label' => 'Notifikasi / Audit Log', 'url' => '/admin/audit-log'],
            ['keys' => ['profile'], 'label' => 'Profil Saya', 'url' => '/profile'],
        ];
    } elseif ($role === 'dosen') {
        $navItems = [
            ['keys' => ['dashboard'], 'label' => 'Dashboard', 'url' => '/dashboard'],
            ['keys' => ['permohonan_dosen'], 'label' => 'Permohonan Masuk', 'url' => '/dosen/permohonan', 'count' => $jumlahMenungguSidebar ?? null],
            ['keys' => ['pengajuan_judul_dosen'], 'label' => 'Pengajuan Judul', 'url' => '/dosen/pengajuan-judul'],
            ['keys' => ['riwayat_review_dosen'], 'label' => 'Riwayat Review', 'url' => '/dosen/pengajuan-judul/riwayat'],
            ['keys' => ['proposal_dosen'], 'label' => 'Proposal TA', 'url' => '/dosen/proposal-ta'],
            ['keys' => ['riwayat_proposal_dosen'], 'label' => 'Riwayat Proposal', 'url' => '/dosen/proposal-ta/riwayat'],
            ['keys' => ['profile'], 'label' => 'Profil Saya', 'url' => '/profile'],
        ];
    } else {
        $navItems = [
            ['keys' => ['dashboard'], 'label' => 'Dashboard', 'url' => '/dashboard'],
            ['keys' => ['pembimbing'], 'label' => 'Pembimbing', 'url' => '/pembimbing'],
            ['keys' => ['pengajuan_judul'], 'label' => 'Pengajuan Judul', 'url' => '/pengajuan-judul'],
            ['keys' => ['proposal_ta'], 'label' => 'Proposal TA', 'url' => '/proposal-ta'],
            ['keys' => ['surat_keputusan'], 'label' => 'Surat Keputusan', 'url' => '/surat-keputusan'],
            ['keys' => ['profile'], 'label' => 'Profil Saya', 'url' => '/profile'],
        ];
    }
?>

<button class="mobile-toggle" type="button" onclick="toggleSidebar()">☰</button>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">TA</div>
            <div>
                <h2 class="brand-title">Sistem TA</h2>
                <div class="brand-subtitle">MI UNSRI</div>
            </div>
        </div>

        <nav class="nav-menu">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= base_url($item['url']) ?>" class="nav-link <?= $isActive($item['keys']) ?>">
                    <span class="nav-dot"></span>
                    <span class="nav-text"><?= esc($item['label']) ?></span>

                    <?php if (! empty($item['count'])): ?>
                        <span class="nav-badge"><?= esc((string) $item['count']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-name"><?= esc((string) $userName) ?></div>
            <div class="sidebar-user-role">
                Role: <?= esc((string) ($role ?? '-')) ?><br>
                Sistem informasi pengajuan tugas akhir modern dan terpusat.
            </div>
        </div>
    </aside>

    <main class="content">
        <div class="topbar">
            <div class="topbar-left">
                <h1><?= esc($pageTitle ?? 'Pengajuan Pembimbing') ?></h1>
                <p><?= esc($pageSubtitle ?? 'Panel sistem tugas akhir') ?></p>
            </div>

            <div class="topbar-right">
                <div class="user-chip"><?= esc((string) $userEmail) ?></div>
                <a href="<?= base_url('/logout') ?>"
                   class="btn btn-danger"
                   onclick="return confirm('Yakin ingin logout dari sistem?')">
                    Logout
                </a>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
</body>
</html>