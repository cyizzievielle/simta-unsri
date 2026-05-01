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
            --bg: #f4f8ff;
            --surface: #ffffff;
            --blue: #3b82f6;
            --blue-dark: #2563eb;
            --blue-soft: #eaf3ff;
            --blue-line: #dbeafe;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --red: #ef4444;
            --shadow: 0 18px 45px rgba(37, 99, 235, .08);
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
            color: inherit;
            text-decoration: none;
        }

        .app-shell {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            padding: 22px 18px;
            background: rgba(255,255,255,.97);
            border-right: 1px solid var(--blue-line);
            box-shadow: 12px 0 35px rgba(37, 99, 235, .06);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 10px 22px;
            margin-bottom: 14px;
            border-bottom: 1px solid #e5efff;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            border-radius: 17px;
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            font-weight: 900;
            box-shadow: 0 14px 26px rgba(37, 99, 235, .24);
            flex-shrink: 0;
        }

        .brand-title {
            margin: 0;
            font-size: 18px;
            font-weight: 900;
            letter-spacing: -.03em;
            color: var(--text);
            line-height: 1.2;
        }

        .brand-subtitle {
            margin-top: 4px;
            font-size: 11px;
            font-weight: 800;
            color: var(--blue);
            letter-spacing: .08em;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 7px;
            padding: 6px 0 18px;
        }

        .nav-link {
            min-height: 45px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 13px;
            border-radius: 15px;
            color: #475569;
            font-size: 13px;
            font-weight: 800;
            border: 1px solid transparent;
            transition: .22s ease;
        }

        .nav-link:hover {
            background: #f1f7ff;
            color: var(--blue-dark);
            transform: translateX(2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #eaf3ff, #dbeafe);
            border-color: #bfdbfe;
            color: #1d4ed8;
            box-shadow: 0 12px 24px rgba(37, 99, 235, .10);
        }

        .nav-icon {
            width: 22px;
            height: 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: currentColor;
            flex-shrink: 0;
        }

        .nav-icon svg {
            width: 20px;
            height: 20px;
            stroke-width: 2.1;
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
            margin-top: 14px;
            padding: 14px;
            border-radius: 18px;
            background: linear-gradient(135deg, #eff6ff, #ffffff);
            border: 1px solid var(--blue-line);
        }

        .sidebar-user-name {
            font-size: 13px;
            font-weight: 900;
            color: var(--text);
        }

        .sidebar-user-role {
            margin-top: 6px;
            font-size: 11px;
            color: var(--muted);
            line-height: 1.55;
        }

        .content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 22px 26px 28px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            min-height: 82px;
            background: linear-gradient(135deg, rgba(255,255,255,.94), rgba(234,243,255,.92));
            border: 1px solid var(--blue-line);
            border-radius: 26px;
            padding: 18px 22px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 24px;
            backdrop-filter: blur(16px);
        }

        .topbar-left {
            min-width: 0;
        }

        .topbar-left h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -.04em;
            color: var(--text);
            line-height: 1.15;
        }

        .topbar-left p {
            margin: 7px 0 0;
            font-size: 13px;
            color: var(--muted);
            line-height: 1.5;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .topbar-icon {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            border: 1px solid var(--blue-line);
            background: #fff;
            color: #1e3a8a;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(37,99,235,.06);
        }

        .topbar-icon svg {
            width: 20px;
            height: 20px;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-trigger {
            border: none;
            background: transparent;
            display: flex;
            align-items: center;
            gap: 11px;
            cursor: pointer;
            padding: 4px;
        }

        .profile-avatar,
        .avatar-fallback {
            width: 48px;
            height: 48px;
            border-radius: 999px;
            object-fit: cover;
            border: 3px solid var(--blue-soft);
            flex-shrink: 0;
        }

        .avatar-fallback {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 15px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            max-width: 170px;
        }

        .profile-info strong {
            font-size: 14px;
            font-weight: 900;
            color: var(--text);
            line-height: 1.2;
        }

        .profile-info span {
            margin-top: 3px;
            font-size: 12px;
            font-weight: 700;
            color: var(--muted);
            max-width: 170px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-arrow {
            color: #334155;
            display: inline-flex;
            align-items: center;
        }

        .profile-arrow svg {
            width: 18px;
            height: 18px;
        }

        .profile-menu {
            position: absolute;
            top: 62px;
            right: 0;
            width: 310px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15,23,42,.13);
            overflow: hidden;
            display: none;
            z-index: 2000;
        }

        .profile-menu.show {
            display: block;
        }

        .profile-menu-head {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .profile-menu-head strong {
            display: block;
            font-size: 16px;
            font-weight: 900;
            color: var(--text);
        }

        .profile-menu-head span {
            display: block;
            margin-top: 4px;
            max-width: 200px;
            font-size: 13px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px 16px;
            color: #475569;
            font-size: 14px;
            font-weight: 800;
            border-bottom: 1px solid #f1f5f9;
        }

        .profile-menu-item:hover {
            background: #f8fbff;
            color: var(--blue-dark);
        }

        .profile-menu-item.logout {
            color: var(--red);
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .menu-icon svg {
            width: 20px;
            height: 20px;
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

        .mobile-topnav {
            display: none;
        }

        .mobile-toggle {
            display: none;
        }

        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 980px) {
            html, body {
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            body {
                overflow-y: auto;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .app-shell {
                display: block;
                width: 100%;
                height: auto;
                min-height: 100vh;
                overflow: visible;
            }

            .mobile-topnav {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 74px;
                z-index: 1200;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 14px;
                background: linear-gradient(135deg, rgba(255,255,255,.96), rgba(234,243,255,.96));
                border-bottom: 1px solid var(--blue-line);
                box-shadow: 0 12px 30px rgba(37, 99, 235, .10);
                backdrop-filter: blur(14px);
            }

            .mobile-brand {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 0;
            }

            .mobile-logo {
                width: 42px;
                height: 42px;
                border-radius: 15px;
                background: linear-gradient(135deg, #60a5fa, #2563eb);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                font-weight: 900;
                box-shadow: 0 12px 22px rgba(37, 99, 235, .22);
                flex-shrink: 0;
            }

            .mobile-brand strong {
                display: block;
                font-size: 16px;
                font-weight: 900;
                letter-spacing: -.03em;
                color: var(--text);
                line-height: 1.1;
            }

            .mobile-brand small {
                display: block;
                margin-top: 3px;
                font-size: 10px;
                font-weight: 900;
                color: var(--blue);
                letter-spacing: .08em;
            }

            .mobile-actions {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-shrink: 0;
            }

            .mobile-icon {
                width: 42px;
                height: 42px;
                border-radius: 999px;
                border: 1px solid var(--blue-line);
                background: #ffffff;
                color: var(--blue-dark);
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 10px 22px rgba(37, 99, 235, .10);
            }

            .mobile-icon svg {
                width: 20px;
                height: 20px;
            }

            .mobile-toggle {
                position: static;
                cursor: pointer;
            }

            .sidebar {
                width: min(84vw, 292px);
                padding: 18px 14px;
                transform: translateX(-110%);
                transition: transform .28s ease;
                border-radius: 0 26px 26px 0;
                box-shadow: 18px 0 45px rgba(15, 23, 42, .18);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .42);
                backdrop-filter: blur(3px);
                z-index: 900;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            body.sidebar-open .mobile-brand {
                opacity: 0;
                pointer-events: none;
            }

            body.sidebar-open .mobile-topnav {
                background: transparent;
                border-bottom: none;
                box-shadow: none;
                backdrop-filter: none;
            }

            body.sidebar-open .mobile-profile {
                display: none;
            }

            body.sidebar-open .mobile-toggle {
                background: #fff;
            }

            .brand {
                padding: 10px 8px 16px;
                margin-bottom: 12px;
            }

            .brand-logo {
                width: 44px;
                height: 44px;
                border-radius: 15px;
                font-size: 16px;
            }

            .brand-title {
                font-size: 17px;
            }

            .brand-subtitle {
                font-size: 10px;
            }

            .nav-menu {
                gap: 7px;
            }

            .nav-link {
                min-height: 43px;
                padding: 10px 12px;
                border-radius: 14px;
                font-size: 12.5px;
            }

            .sidebar-user {
                margin-top: 13px;
                padding: 13px;
                border-radius: 16px;
            }

            .sidebar-user-name {
                font-size: 12px;
            }

            .sidebar-user-role {
                font-size: 10.5px;
            }

            .content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                height: auto;
                min-height: 100vh;
                overflow-x: hidden !important;
                overflow-y: visible;
                padding: 92px 14px 24px !important;
            }

            .topbar {
                position: relative;
                top: auto;
                min-height: auto;
                border-radius: 22px;
                padding: 18px 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
                margin-bottom: 18px;
            }

            .topbar-left h1 {
                font-size: 22px;
                line-height: 1.25;
            }

            .topbar-left p {
                font-size: 13px;
                line-height: 1.5;
            }

            .topbar-right {
                display: none;
            }
        }

        @media (max-width: 420px) {
            .mobile-topnav {
                height: 70px;
                padding: 0 12px;
            }

            .mobile-logo {
                width: 39px;
                height: 39px;
                border-radius: 14px;
                font-size: 14px;
            }

            .mobile-brand strong {
                font-size: 15px;
            }

            .mobile-brand small {
                font-size: 9.5px;
            }

            .mobile-icon {
                width: 39px;
                height: 39px;
            }

            .content {
                padding: 86px 12px 22px !important;
            }

            .topbar {
                padding: 17px 15px;
                border-radius: 20px;
            }

            .topbar-left h1 {
                font-size: 20px;
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
    $userFoto   = session()->get('foto');

    $userInitial = strtoupper(substr((string) $userName, 0, 1));

    $avatarUrl = null;
    if (! empty($userFoto)) {
        $avatarUrl = base_url('uploads/profile/' . $userFoto);
    }

    $avatarHtml = static function () use ($avatarUrl, $userInitial): string {
        if ($avatarUrl) {
            return '<img src="' . esc($avatarUrl) . '" alt="Avatar" class="profile-avatar">';
        }

        return '<div class="avatar-fallback">' . esc($userInitial) . '</div>';
    };

    $icon = static function (string $name): string {
        $icons = [
            'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 13h6V4H4v9Z"/><path d="M14 20h6V11h-6v9Z"/><path d="M4 20h6v-3H4v3Z"/><path d="M14 7h6V4h-6v3Z"/></svg>',
            'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
            'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>',
            'book' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/></svg>',
            'file' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></svg>',
            'archive' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>',
            'bell' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
            'user' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>',
            'logout' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/></svg>',
            'menu' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/></svg>',
            'close' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>',
            'moon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.8A8.5 8.5 0 1 1 11.2 3a6.5 6.5 0 0 0 9.8 9.8z"/></svg>',
            'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06A2 2 0 1 1 7.03 3.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.2.37.31.78.31 1.2s-.11.83-.31 1.2Z"/></svg>',
            'chevron' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="m6 9 6 6 6-6"/></svg>',
        ];

        return $icons[$name] ?? $icons['file'];
    };

    $isActive = static function ($keys) use ($activeMenu): string {
        $keys = is_array($keys) ? $keys : [$keys];
        return in_array($activeMenu, $keys, true) ? 'active' : '';
    };

    if ($role === 'admin') {
        $navItems = [
            ['keys' => ['dashboard'], 'icon' => 'dashboard', 'label' => 'Dashboard', 'url' => '/dashboard'],
            ['keys' => ['admin_users'], 'icon' => 'users', 'label' => 'Kelola Users', 'url' => '/admin/users'],
            ['keys' => ['periode_akademik', 'admin_periode'], 'icon' => 'calendar', 'label' => 'Periode Akademik', 'url' => '/admin/periode-akademik'],
            ['keys' => ['program_studi', 'admin_prodi'], 'icon' => 'book', 'label' => 'Program Studi', 'url' => '/admin/program-studi'],
            ['keys' => ['monitoring_pembimbing', 'admin_monitoring_pembimbing'], 'icon' => 'users', 'label' => 'Monitoring Pembimbing', 'url' => '/admin/monitoring-pembimbing'],
            ['keys' => ['monitoring_judul', 'admin_monitoring_judul'], 'icon' => 'file', 'label' => 'Monitoring Judul', 'url' => '/admin/monitoring-judul'],
            ['keys' => ['monitoring_proposal', 'admin_monitoring_proposal'], 'icon' => 'file', 'label' => 'Monitoring Proposal', 'url' => '/admin/monitoring-proposal'],
            ['keys' => ['surat_keputusan_admin', 'admin_surat_keputusan'], 'icon' => 'archive', 'label' => 'Surat Keputusan', 'url' => '/admin/surat-keputusan'],
            ['keys' => ['laporan_arsip', 'admin_laporan'], 'icon' => 'archive', 'label' => 'Laporan / Arsip', 'url' => '/admin/laporan'],
            ['keys' => ['audit_log', 'admin_audit'], 'icon' => 'bell', 'label' => 'Notifikasi / Audit Log', 'url' => '/admin/audit-log'],
            ['keys' => ['profile'], 'icon' => 'user', 'label' => 'Profil Saya', 'url' => '/profile'],
        ];
    } elseif ($role === 'dosen') {
        $navItems = [
            ['keys' => ['dashboard'], 'icon' => 'dashboard', 'label' => 'Dashboard', 'url' => '/dashboard'],
            ['keys' => ['permohonan_dosen'], 'icon' => 'users', 'label' => 'Permohonan Masuk', 'url' => '/dosen/permohonan', 'count' => $jumlahMenungguSidebar ?? null],
            ['keys' => ['pengajuan_judul_dosen'], 'icon' => 'file', 'label' => 'Pengajuan Judul', 'url' => '/dosen/pengajuan-judul'],
            ['keys' => ['riwayat_review_dosen'], 'icon' => 'archive', 'label' => 'Riwayat Review', 'url' => '/dosen/pengajuan-judul/riwayat'],
            ['keys' => ['proposal_dosen'], 'icon' => 'file', 'label' => 'Proposal TA', 'url' => '/dosen/proposal-ta'],
            ['keys' => ['riwayat_proposal_dosen'], 'icon' => 'archive', 'label' => 'Riwayat Proposal', 'url' => '/dosen/proposal-ta/riwayat'],
            ['keys' => ['profile'], 'icon' => 'user', 'label' => 'Profil Saya', 'url' => '/profile'],
        ];
    } else {
        $navItems = [
            ['keys' => ['dashboard'], 'icon' => 'dashboard', 'label' => 'Dashboard', 'url' => '/dashboard'],
            ['keys' => ['pembimbing'], 'icon' => 'users', 'label' => 'Pembimbing', 'url' => '/pembimbing'],
            ['keys' => ['pengajuan_judul'], 'icon' => 'file', 'label' => 'Pengajuan Judul', 'url' => '/pengajuan-judul'],
            ['keys' => ['proposal_ta'], 'icon' => 'file', 'label' => 'Proposal TA', 'url' => '/proposal-ta'],
            ['keys' => ['surat_keputusan'], 'icon' => 'archive', 'label' => 'Surat Keputusan', 'url' => '/surat-keputusan'],
            ['keys' => ['profile'], 'icon' => 'user', 'label' => 'Profil Saya', 'url' => '/profile'],
        ];
    }
?>

<header class="mobile-topnav">
    <div class="mobile-brand">
        <span class="mobile-logo">TA</span>
        <div>
            <strong>Sistem TA</strong>
            <small>MI UNSRI</small>
        </div>
    </div>

    <div class="mobile-actions">
        <a href="<?= base_url('/profile') ?>" class="mobile-icon mobile-profile">
            <?= $avatarHtml() ?>
        </a>

        <button class="mobile-icon mobile-toggle" id="mobileToggle" type="button" onclick="toggleSidebar()">
            <span id="mobileToggleIcon"><?= $icon('menu') ?></span>
        </button>
    </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

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
                    <span class="nav-icon"><?= $icon($item['icon'] ?? 'file') ?></span>
                    <span class="nav-text"><?= esc($item['label']) ?></span>

                    <?php if (! empty($item['count'])): ?>
                        <span class="nav-badge"><?= esc((string) $item['count']) ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <div class="topbar-left">
                <h1><?= esc($pageTitle ?? 'Pengajuan Pembimbing') ?></h1>
                <p><?= esc($pageSubtitle ?? 'Ajukan Pembimbing 1 dan Pembimbing 2 kamu disini.') ?></p>
            </div>

            <div class="topbar-right">

                <div class="profile-dropdown">
                    <button class="profile-trigger" type="button" onclick="toggleProfileMenu()">
                        <?= $avatarHtml() ?>

                        <div class="profile-info">
                            <strong><?= esc((string) $userName) ?></strong>
                            <span><?= esc((string) $userEmail) ?></span>
                        </div>

                        <span class="profile-arrow"><?= $icon('chevron') ?></span>
                    </button>

                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-menu-head">
                            <?= $avatarHtml() ?>
                            <div>
                                <strong><?= esc((string) $userName) ?></strong>
                                <span><?= esc((string) $userEmail) ?></span>
                            </div>
                        </div>

                        <a href="<?= base_url('/profile') ?>" class="profile-menu-item">
                            <span class="menu-icon"><?= $icon('user') ?></span>
                            Profil Saya
                        </a>

                        <a href="<?= base_url('/profile') ?>" class="profile-menu-item">
                            <span class="menu-icon"><?= $icon('settings') ?></span>
                            Edit Profile
                        </a>

                        <a href="<?= base_url('/logout') ?>"
                           class="profile-menu-item logout"
                           onclick="return confirm('Yakin ingin logout dari sistem?')">
                            <span class="menu-icon"><?= $icon('logout') ?></span>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($success = session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= esc(is_array($success) ? implode(', ', $success) : (string) $success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(is_array($error) ? implode(', ', $error) : (string) $error) ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</div>

<script>
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('mobileToggleIcon');

    sidebar.classList.add('show');
    overlay.classList.add('show');
    document.body.classList.add('sidebar-open');

    if (toggleIcon) {
        toggleIcon.innerHTML = `<?= $icon('close') ?>`;
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('mobileToggleIcon');

    sidebar.classList.remove('show');
    overlay.classList.remove('show');
    document.body.classList.remove('sidebar-open');

    if (toggleIcon) {
        toggleIcon.innerHTML = `<?= $icon('menu') ?>`;
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');

    if (sidebar.classList.contains('show')) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');

    if (menu) {
        menu.classList.toggle('show');
    }
}

document.addEventListener('click', function(e) {
    const dropdown = document.querySelector('.profile-dropdown');
    const menu = document.getElementById('profileMenu');

    if (dropdown && menu && !dropdown.contains(e.target)) {
        menu.classList.remove('show');
    }
});

window.addEventListener('resize', function () {
    if (window.innerWidth > 980) {
        closeSidebar();
    }
});
const root = document.documentElement;
const savedTheme = localStorage.getItem('theme');

if (savedTheme === 'dark') {
    document.body.classList.add('dark-mode');
}

function toggleTheme() {
    document.body.classList.toggle('dark-mode');

    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
    } else {
        localStorage.setItem('theme', 'light');
    }
}

function toggleProfileMenu() {
    const menu = document.getElementById('profileMenu');
    menu.classList.toggle('show');
}

document.addEventListener('click', function(e) {
    const profile = document.querySelector('.profile-dropdown');

    if (profile && !profile.contains(e.target)) {
        document.getElementById('profileMenu')?.classList.remove('show');
    }
});
</script>
</body>
</html>