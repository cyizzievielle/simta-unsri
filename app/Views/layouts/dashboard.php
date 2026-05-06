<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem TA') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <style>
        * { box-sizing: border-box; }

        :root {
            --sidebar-width: 284px;
            --bg: #eef5ff;
            --bg-2: #f8fbff;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --primary: #2563eb;
            --primary-2: #7c3aed;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --shadow: 0 18px 45px rgba(37, 99, 235, .10);
            --shadow-soft: 0 10px 26px rgba(15, 23, 42, .07);
        }

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: Inter, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .14), transparent 32%),
                radial-gradient(circle at top right, rgba(124, 58, 237, .10), transparent 32%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
            color: var(--text);
        }

        body { overflow: hidden; }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font-family: inherit; }

        .app-shell { display: flex; width: 100%; min-height: 100vh; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            padding: 18px 16px;
            background: rgba(255, 255, 255, .98);
            border-right: 1px solid rgba(219, 234, 254, .95);
            box-shadow: 16px 0 36px rgba(37, 99, 235, .08);
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1000;
        }
        .sidebar::-webkit-scrollbar { width: 0; }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 10px 18px;
            margin-bottom: 10px;
            border-bottom: 1px solid #e7f0ff;
        }

        .brand-logo,
        .mobile-logo {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: transparent !important;
            border: 0 !important;
            outline: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            overflow: visible !important;
            flex-shrink: 0 !important;
            color: transparent !important;
        }

        .brand-logo { width: 58px !important; height: 58px !important; }
        .mobile-logo { width: 40px !important; height: 40px !important; }

        .brand-logo img,
        .mobile-logo img {
            object-fit: contain !important;
            display: block !important;
            border: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .brand-logo img { width: 52px !important; height: 52px !important; }
        .mobile-logo img { width: 34px !important; height: 34px !important; }

        .brand-title { margin: 0; font-size: 18px; font-weight: 950; letter-spacing: -.04em; line-height: 1.1; }
        .brand-subtitle { margin-top: 4px; font-size: 10px; font-weight: 950; letter-spacing: .11em; color: var(--primary); }

        .sidebar-profile {
            display: flex; align-items: center; gap: 11px;
            padding: 12px; margin: 12px 0 14px;
            border-radius: 20px;
            background: linear-gradient(135deg, #eff6ff, #ffffff 58%, #faf5ff);
            border: 1px solid #dbeafe;
            box-shadow: 0 12px 26px rgba(37, 99, 235, .07);
        }
        .sidebar-profile-info { min-width: 0; }
        .sidebar-profile-name { display: block; font-size: 13px; font-weight: 950; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-profile-role { display: block; margin-top: 3px; font-size: 11px; font-weight: 750; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform: capitalize; }

        .nav-menu { display: flex; flex-direction: column; gap: 7px; padding: 2px 0 14px; }
        .nav-link, .sidebar-logout-btn {
            width: 100%; min-height: 44px; display: flex; align-items: center; gap: 11px;
            padding: 10px 12px; border-radius: 15px; color: #475569; font-size: 13px; font-weight: 850;
            border: 1px solid transparent; background: transparent; cursor: pointer; transition: .18s ease; text-align: left;
        }
        .nav-link:hover, .sidebar-logout-btn:hover { background: #f1f7ff; color: var(--primary); transform: translateX(2px); }
        .nav-link.active { background: linear-gradient(135deg, #eaf3ff, #eef2ff); border-color: #bfdbfe; color: #1d4ed8; box-shadow: 0 12px 24px rgba(37, 99, 235, .10); }
        .nav-icon, .menu-icon { width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center; color: currentColor; flex-shrink: 0; }
        .nav-icon svg, .menu-icon svg, .topbar-icon svg, .mobile-icon svg { width: 20px; height: 20px; stroke-width: 2.15; }
        .nav-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .nav-badge { margin-left: auto; min-width: 22px; height: 22px; padding: 0 7px; border-radius: 999px; background: linear-gradient(135deg, #f97316, #ef4444); color: #fff; font-size: 11px; font-weight: 950; display: inline-flex; align-items: center; justify-content: center; }
        .sidebar-divider { height: 1px; background: #e7f0ff; margin: 10px 4px; }
        .logout-form { margin: 0; padding: 0; }
        .sidebar-logout-btn { color: #ef4444; }

        .content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 26px 30px;
        }

        .topbar {
            position: sticky; top: 14px; z-index: 700; min-height: 74px; margin: 14px 0 24px; padding: 12px 14px 12px 18px;
            background: rgba(255,255,255,.96); border: 1px solid rgba(219,234,254,.98); border-radius: 22px;
            box-shadow: 0 18px 42px rgba(37,99,235,.10); backdrop-filter: blur(18px);
            display: flex; align-items: center; justify-content: space-between; gap: 18px;
        }
        .topbar-left { min-width: 0; }
        .topbar-left h1 { margin: 0; font-size: 25px; font-weight: 950; letter-spacing: -.045em; line-height: 1.15; color: var(--text); }
        .topbar-left p { margin: 5px 0 0; max-width: 680px; font-size: 13px; line-height: 1.45; color: var(--muted); }
        .topbar-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }

        .topbar-action-wrap, .profile-dropdown { position: relative; }
        .topbar-icon, .mobile-icon {
            position: relative; width: 43px; height: 43px; border-radius: 999px; border: 1px solid #dbeafe; background: #fff; color: var(--primary);
            display: inline-flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 10px 22px rgba(37,99,235,.10); transition: .18s ease;
        }
        .topbar-icon:hover, .mobile-icon:hover { transform: translateY(-1px); background: #eff6ff; }
        .notification-dot { position: absolute; top: 7px; right: 8px; width: 9px; height: 9px; border-radius: 999px; background: #ef4444; border: 2px solid #fff; box-shadow: 0 0 0 4px rgba(239,68,68,.13); display: none; }
        .notification-dot.show { display: block; }
        .notification-menu {
            position: absolute; top: 56px; right: 0; width: min(360px, calc(100vw - 28px)); background: #fff; border: 1px solid #e2e8f0;
            border-radius: 22px; box-shadow: 0 24px 60px rgba(15,23,42,.16); overflow: hidden; display: none; z-index: 2600;
        }
        .notification-menu.show, .profile-menu.show { display: block; animation: menuPop .16s ease; }
        .dropdown-head { padding: 15px 16px; border-bottom: 1px solid #eef2f7; background: linear-gradient(135deg, #eff6ff, #fff 55%, #faf5ff); }
        .dropdown-head strong { display:block; font-size: 14px; font-weight: 950; color: var(--text); }
        .dropdown-head span { display:block; margin-top:4px; font-size: 12px; color: var(--muted); }
        .notification-list { max-height: 320px; overflow:auto; }
        .notification-empty { padding: 18px 16px; color: var(--muted); font-size: 13px; line-height:1.5; }
        .notification-item { display:flex; gap:10px; padding:13px 16px; border-bottom:1px solid #f1f5f9; color:#475569; font-size:13px; line-height:1.45; }
        .notification-item::before { content:""; width:9px; height:9px; border-radius:999px; background:var(--primary); margin-top:5px; flex:0 0 auto; }

        .profile-trigger {
            border: 1px solid #dbeafe; background: linear-gradient(135deg, #ffffff, #f8fbff); display: flex; align-items: center; gap: 10px;
            cursor: pointer; padding: 6px 9px 6px 6px; border-radius: 18px; box-shadow: 0 10px 24px rgba(37,99,235,.08);
        }
        .profile-trigger:hover { background: #eff6ff; }
        .profile-avatar, .avatar-fallback { width: 42px; height: 42px; border-radius: 999px; object-fit: cover; border: 2px solid #dbeafe; flex-shrink: 0; }
        .avatar-fallback { background: linear-gradient(135deg, #60a5fa, #2563eb, #7c3aed); color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 950; font-size: 14px; }
        .profile-info { display: flex; flex-direction: column; align-items: flex-start; max-width: 180px; min-width: 0; }
        .profile-info strong { font-size: 13px; font-weight: 950; color: var(--text); line-height: 1.2; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-info span { margin-top: 2px; font-size: 11.5px; font-weight: 750; color: var(--muted); max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-arrow { color: #334155; display: inline-flex; align-items: center; }
        .profile-arrow svg { width: 17px; height: 17px; }
        .profile-menu { position: absolute; top: 58px; right: 0; width: min(320px, calc(100vw - 28px)); background: #fff; border: 1px solid #e2e8f0; border-radius: 22px; box-shadow: 0 24px 60px rgba(15,23,42,.16); overflow: hidden; display: none; z-index: 2500; }
        @keyframes menuPop { from { opacity: 0; transform: translateY(7px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .profile-menu-head { display: flex; align-items: center; gap: 12px; padding: 16px; background: linear-gradient(135deg, #eff6ff, #ffffff 56%, #faf5ff); border-bottom: 1px solid #eef2f7; }
        .profile-menu-head strong { display: block; font-size: 15px; font-weight: 950; color: var(--text); max-width: 210px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-menu-head span { display: block; margin-top: 4px; max-width: 210px; font-size: 12.5px; color: var(--muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-menu-item { width: 100%; display: flex; align-items: center; gap: 12px; padding: 14px 16px; color: #475569; font-size: 13.5px; font-weight: 850; border: 0; border-bottom: 1px solid #f1f5f9; background: #fff; cursor: pointer; text-align: left; }
        .profile-menu-item:hover { background: #f8fbff; color: var(--primary); }
        .profile-menu-item.logout { color: #ef4444; }

        .alert { padding: 14px 16px; border-radius: 18px; margin-bottom: 18px; font-size: 14px; line-height: 1.6; box-shadow: var(--shadow-soft); }
        .alert-success { background: #ecfdf5; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        .mobile-topnav, .sidebar-overlay { display: none; }

        .logout-modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,.48); backdrop-filter: blur(6px); display: none; align-items: center; justify-content: center; padding: 18px; z-index: 9999; }
        .logout-modal-overlay.show { display: flex; }
        .logout-modal { width: min(380px, 100%); background: #fff; border: 1px solid #eaf1ff; border-radius: 28px; padding: 26px 22px; text-align: center; box-shadow: 0 30px 80px rgba(37,99,235,.22); animation: popLogout .18s ease; }
        @keyframes popLogout { from { opacity: 0; transform: translateY(10px) scale(.96); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .logout-modal-icon { width: 64px; height: 64px; border-radius: 22px; margin: 0 auto 14px; background: linear-gradient(135deg, #dbeafe, #fce7f3); color: var(--primary); display: flex; align-items: center; justify-content: center; }
        .logout-modal-icon svg { width: 28px; height: 28px; }
        .logout-modal h3 { margin: 0 0 8px; font-size: 22px; font-weight: 950; color: var(--text); }
        .logout-modal p { margin: 0 auto 20px; color: var(--muted); font-size: 14px; line-height: 1.6; max-width: 300px; }
        .logout-modal-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .logout-cancel, .logout-confirm { border: 0; min-height: 44px; border-radius: 15px; font-weight: 950; cursor: pointer; }
        .logout-cancel { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
        .logout-confirm { background: linear-gradient(135deg, #2563eb, #7c3aed); color: #fff; box-shadow: 0 14px 28px rgba(37,99,235,.20); }
        .hidden-logout-form { display: none; }

        @media (max-width: 980px) {
            html, body { width: 100%; max-width: 100%; overflow-x: hidden; }
            body { overflow-y: auto; }
            body.sidebar-open { overflow: hidden; }
            .app-shell { display: block; min-height: 100vh; }
            .mobile-topnav { position: fixed; top: 0; left: 0; right: 0; height: 72px; z-index: 1300; display: flex; align-items: center; justify-content: space-between; padding: 0 14px; background: rgba(255,255,255,.98); border-bottom: 1px solid rgba(219,234,254,.96); box-shadow: 0 12px 30px rgba(37,99,235,.11); backdrop-filter: blur(14px); }
            .mobile-topnav::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #2563eb, #06b6d4, #7c3aed, #ec4899); }
            .mobile-brand { display: flex; align-items: center; gap: 9px; min-width: 0; }
            .mobile-brand-copy { display: flex !important; flex-direction: column !important; min-width: 0 !important; }
            .mobile-brand strong { display: block; font-size: 14px; font-weight: 950; letter-spacing: -.03em; line-height: 1.1; color: var(--text); }
            .mobile-brand small { display: block; margin-top: 3px; font-size: 9px; font-weight: 950; color: var(--primary); letter-spacing: .09em; }
            .mobile-actions { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
            .mobile-icon { width: 39px; height: 39px; padding: 0; }
            .mobile-profile .profile-avatar, .mobile-profile .avatar-fallback { width: 28px; height: 28px; border-width: 0; font-size: 11px; }
            .mobile-logout-btn { color: #ef4444; border-color: #fecaca; background: #fff5f5; }
            .sidebar { width: min(86vw, 304px); padding: 18px 14px; transform: translateX(-110%); transition: transform .28s ease; border-radius: 0 28px 28px 0; box-shadow: 18px 0 45px rgba(15,23,42,.20); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay { position: fixed; inset: 0; background: rgba(15,23,42,.42); backdrop-filter: blur(3px); z-index: 900; display: none; }
            .sidebar-overlay.show { display: block; }
            .content { margin-left: 0 !important; width: 100% !important; max-width: 100% !important; height: auto; min-height: 100vh; overflow-x: hidden !important; overflow-y: visible; padding: 90px 14px 24px !important; }
            .topbar { display: none !important; }
            body.sidebar-open .mobile-actions > :not(.mobile-toggle), body.sidebar-open .mobile-brand { opacity: 0 !important; visibility: hidden !important; pointer-events: none !important; }
            body.sidebar-open .mobile-topnav { background: transparent; border-bottom: none; box-shadow: none; backdrop-filter: none; }
            body.sidebar-open .mobile-toggle { opacity: 1 !important; visibility: visible !important; pointer-events: auto !important; background: #fff !important; border-color: #dbeafe !important; color: #2563eb !important; box-shadow: 0 10px 22px rgba(37,99,235,.12) !important; z-index: 1400; }
        }

        @media (max-width: 420px) {
            .mobile-topnav { height: 68px; padding: 0 12px; }
            .mobile-icon { width: 38px; height: 38px; }
            .content { padding: 84px 12px 22px !important; }
            .logout-modal { border-radius: 24px; padding: 24px 18px; }
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

    $avatarUrl = ! empty($userFoto) ? base_url('uploads/profile/' . $userFoto) : null;

    $avatarHtml = static function (?string $extraClass = '') use ($avatarUrl, $userInitial): string {
        $extraClass = trim((string) $extraClass);
        if ($avatarUrl) {
            return '<img src="' . esc($avatarUrl) . '" alt="Avatar" class="profile-avatar ' . esc($extraClass) . '">';
        }
        return '<div class="avatar-fallback ' . esc($extraClass) . '">' . esc($userInitial) . '</div>';
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
            'settings' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 15.5A3.5 3.5 0 1 0 12 8a3.5 3.5 0 0 0 0 7.5Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06A1.7 1.7 0 0 0 15 19.4a1.7 1.7 0 0 0-1 .6 1.7 1.7 0 0 0-.4 1.1V21a2 2 0 1 1-4 0v-.09A1.7 1.7 0 0 0 8.6 19.4a1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-.6-1 1.7 1.7 0 0 0-1.1-.4H3a2 2 0 1 1 0-4h.09A1.7 1.7 0 0 0 4.6 8.6a1.7 1.7 0 0 0-.34-1.88l-.06-.06A2 2 0 1 1 7.03 3.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-.6 1.7 1.7 0 0 0 .4-1.1V3a2 2 0 1 1 4 0v.09A1.7 1.7 0 0 0 15.4 4.6a1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.7 1.7 0 0 0 19.4 9c.2.37.31.78.31 1.2s-.11.83-.31 1.2Z"/></svg>',
            'sun' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
            'moon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 12.8A8.5 8.5 0 1 1 11.2 3 6.5 6.5 0 0 0 21 12.8Z"/></svg>',
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
            ['keys' => ['permohonan_dosen'], 'icon' => 'users', 'label' => 'Permohonan Pembimbing', 'url' => '/dosen/permohonan', 'count' => $jumlahMenungguSidebar ?? null],
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
    <a href="<?= base_url('/dashboard') ?>" class="mobile-brand">
        <span class="mobile-logo">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Sistem TA">
        </span>

        <span class="mobile-brand-copy">
            <strong>Sistem TA</strong>
            <small>MI UNSRI</small>
        </span>
    </a>

    <div class="mobile-actions">
        <a href="<?= base_url('/notifikasi') ?>" class="mobile-icon" aria-label="Notifikasi">
            <?= $icon('bell') ?>
            <span class="notification-dot" id="mobileNotifDot"></span>
        </a>

        <a href="<?= base_url('/profile') ?>" class="mobile-icon mobile-profile" aria-label="Profil">
            <?= $avatarHtml() ?>
        </a>

        <button type="button" class="mobile-icon mobile-logout-btn" onclick="openLogoutModal()" aria-label="Logout">
            <?= $icon('logout') ?>
        </button>

        <button class="mobile-icon mobile-toggle" id="mobileToggle" type="button" onclick="toggleSidebar()" aria-label="Menu">
            <span id="mobileToggleIcon"><?= $icon('menu') ?></span>
        </button>
    </div>
</header>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a href="<?= base_url('/dashboard') ?>" class="brand">

            <div class="brand-logo">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Sistem TA">
            </div>

            <span>
                <h2 class="brand-title">Sistem TA</h2>
                <span class="brand-subtitle">MI UNSRI</span>
            </span>
        </a>

        <a href="<?= base_url('/profile') ?>" class="sidebar-profile">
            <?= $avatarHtml() ?>
            <span class="sidebar-profile-info">
                <span class="sidebar-profile-name"><?= esc((string) $userName) ?></span>
                <span class="sidebar-profile-role"><?= esc((string) ($role ?? 'user')) ?></span>
            </span>
        </a>

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

            <div class="sidebar-divider"></div>

            <button type="button" class="sidebar-logout-btn" onclick="openLogoutModal()">
                <span class="nav-icon"><?= $icon('logout') ?></span>
                <span class="nav-text">Logout</span>
            </button>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <div class="topbar-left">
                <h1><?= esc($pageTitle ?? 'Dashboard') ?></h1>
                <p><?= esc($pageSubtitle ?? 'Kelola aktivitas sistem tugas akhir secara ringkas dan terpusat.') ?></p>
            </div>

            <div class="topbar-right">
                <div class="topbar-action-wrap">
                    <button type="button" class="topbar-icon" onclick="toggleNotificationMenu(event)" aria-label="Notifikasi">
                        <?= $icon('bell') ?>
                        <span class="notification-dot" id="notifDot"></span>
                    </button>
                    <div class="notification-menu" id="notificationMenu">
                        <div class="dropdown-head"><strong>Notifikasi</strong><span>Update akademik terbaru dari sistem</span></div>
                        <div class="notification-list" id="notificationList"><div class="notification-empty">Belum ada notifikasi baru.</div></div>
                    </div>
                </div>

                <div class="profile-dropdown">
                    <button class="profile-trigger" type="button" onclick="toggleProfileMenu(event)">
                        <?= $avatarHtml() ?>
                        <span class="profile-info">
                            <strong><?= esc((string) $userName) ?></strong>
                            <span><?= esc((string) $userEmail) ?></span>
                        </span>
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
                            Profile
                        </a>

                        <a href="<?= base_url('/profile/edit') ?>" class="profile-menu-item">
                            <span class="menu-icon"><?= $icon('settings') ?></span>
                            Edit Profile
                        </a>

                        <button type="button" class="profile-menu-item logout" onclick="openLogoutModal()">
                            <span class="menu-icon"><?= $icon('logout') ?></span>
                            Logout
                        </button>
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

<form action="<?= base_url('/logout') ?>" method="post" id="logoutForm" class="hidden-logout-form">
    <?= csrf_field() ?>
</form>

<div class="logout-modal-overlay" id="logoutModal">
    <div class="logout-modal">
        <div class="logout-modal-icon"><?= $icon('logout') ?></div>
        <h3>Keluar dari sistem?</h3>
        <p>Pastikan semua perubahan sudah tersimpan sebelum keluar dari Sistem TA.</p>
        <div class="logout-modal-actions">
            <button type="button" class="logout-cancel" onclick="closeLogoutModal()">Batal</button>
            <button type="button" class="logout-confirm" onclick="submitLogout()">Ya, Logout</button>
        </div>
    </div>
</div>

<script>
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('mobileToggleIcon');

    sidebar?.classList.add('show');
    overlay?.classList.add('show');
    document.body.classList.add('sidebar-open');

    if (toggleIcon) toggleIcon.innerHTML = `<?= $icon('close') ?>`;
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('mobileToggleIcon');

    sidebar?.classList.remove('show');
    overlay?.classList.remove('show');
    document.body.classList.remove('sidebar-open');

    if (toggleIcon) toggleIcon.innerHTML = `<?= $icon('menu') ?>`;
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar?.classList.contains('show') ? closeSidebar() : openSidebar();
}

function toggleProfileMenu(event) {
    event?.stopPropagation();
    document.getElementById('profileMenu')?.classList.toggle('show');
}

function closeProfileMenu() {
    document.getElementById('profileMenu')?.classList.remove('show');
}

function openLogoutModal() {
    closeProfileMenu();
    closeSidebar();
    document.getElementById('logoutModal')?.classList.add('show');
}

function closeLogoutModal() {
    document.getElementById('logoutModal')?.classList.remove('show');
}

function submitLogout() {
    document.getElementById('logoutForm')?.submit();
}


function closeFloatingMenus() {
    document.getElementById('notificationMenu')?.classList.remove('show');
}

function toggleNotificationMenu(event) {
    event?.stopPropagation();
    closeProfileMenu();
    document.getElementById('notificationMenu')?.classList.toggle('show');
}



async function loadRealtimeNotifications() {
    const endpoints = ['<?= base_url('/notifikasi/unread') ?>', '<?= base_url('/notifikasi/unread-count') ?>'];
    for (const url of endpoints) {
        try {
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) continue;
            const data = await response.json();
            const count = Number(data.count ?? data.total ?? data.unread ?? 0);
            const items = Array.isArray(data.items) ? data.items : (Array.isArray(data.notifications) ? data.notifications : []);
            updateNotificationUI(count, items);
            return;
        } catch (error) {}
    }
}

function updateNotificationUI(count, items) {
    document.getElementById('notifDot')?.classList.toggle('show', count > 0);
    document.getElementById('mobileNotifDot')?.classList.toggle('show', count > 0);
    const list = document.getElementById('notificationList');
    if (!list) return;
    if (!items.length) {
        list.innerHTML = '<div class="notification-empty">Belum ada notifikasi baru.</div>';
        return;
    }
    list.innerHTML = items.slice(0, 6).map(item => {
        const text = String(item.pesan || item.message || item.judul || item.title || 'Notifikasi baru');
        return `<div class="notification-item"><span>${escapeHtml(text)}</span></div>`;
    }).join('');
}

function escapeHtml(value) {
    return String(value).replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
}

loadRealtimeNotifications();
setInterval(loadRealtimeNotifications, 30000);

document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.profile-dropdown');
    if (dropdown && !dropdown.contains(event.target)) closeProfileMenu();
    const notif = document.querySelector('.topbar-action-wrap');
    if (!event.target.closest('.topbar-action-wrap')) closeFloatingMenus();

    const modal = document.getElementById('logoutModal');
    if (modal && event.target === modal) closeLogoutModal();
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeProfileMenu();
        closeFloatingMenus();
        closeLogoutModal();
        closeSidebar();
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 980) closeSidebar();
});
</script>
</body>
</html>
