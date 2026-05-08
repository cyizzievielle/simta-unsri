<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem TA') ?></title>

    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --sidebar-width: 284px;
            --bg: #eef5ff;
            --bg-2: #f8fbff;
            --surface: #ffffff;
            --surface-soft: #f8fbff;
            --primary: #2563eb;
            --primary-2: #7c3aed;
            --danger: #ef4444;
            --success: #16a34a;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
            --shadow: 0 18px 45px rgba(37, 99, 235, .10);
            --shadow-soft: 0 10px 26px rgba(15, 23, 42, .07);
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: Inter, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .14), transparent 32%),
                radial-gradient(circle at top right, rgba(124, 58, 237, .10), transparent 32%),
                linear-gradient(180deg, var(--bg), var(--bg-2));
            color: var(--text);
        }

        body {
            overflow: hidden;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font-family: inherit;
        }

        button {
            -webkit-tap-highlight-color: transparent;
        }

        .app-shell {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            padding: 18px 16px;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .10), transparent 32%),
                linear-gradient(180deg, #ffffff 0%, #f8fbff 58%, #f2f6ff 100%);
            border-right: 1px solid rgba(219, 234, 254, .95);
            box-shadow: 16px 0 36px rgba(37, 99, 235, .08);
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
        }

        .brand-logo {
            width: 58px !important;
            height: 58px !important;
        }

        .mobile-logo {
            width: 40px !important;
            height: 40px !important;
        }

        .brand-logo img,
        .mobile-logo img {
            object-fit: contain !important;
            display: block !important;
            border: 0 !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .brand-logo img {
            width: 52px !important;
            height: 52px !important;
        }

        .mobile-logo img {
            width: 34px !important;
            height: 34px !important;
        }

        .brand-title {
            margin: 0;
            font-size: 18px;
            font-weight: 950;
            letter-spacing: -.04em;
            line-height: 1.1;
        }

        .brand-subtitle {
            display: inline-flex;
            margin-top: 5px;
            padding: 4px 8px;
            border-radius: 999px;
            background: #eaf2ff;
            color: var(--primary);
            font-size: 10px;
            font-weight: 950;
            letter-spacing: .13em;
        }

        .sidebar-profile {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 12px;
            margin: 12px 0 14px;
            border-radius: 20px;
            background: linear-gradient(135deg, #eff6ff, #ffffff 58%, #faf5ff);
            border: 1px solid #dbeafe;
            box-shadow: 0 12px 26px rgba(37, 99, 235, .07);
            transition: .18s ease;
        }

        .sidebar-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(37, 99, 235, .11);
        }

        .sidebar-profile-info {
            min-width: 0;
        }

        .sidebar-profile-name {
            display: block;
            font-size: 13px;
            font-weight: 950;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-profile-role {
            display: block;
            margin-top: 3px;
            font-size: 11px;
            font-weight: 750;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: capitalize;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 7px;
            padding: 2px 0 14px;
        }

        .nav-link,
        .sidebar-logout-btn {
            width: 100%;
            min-height: 46px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 10px 12px;
            border-radius: 16px;
            color: #475569;
            font-size: 13px;
            font-weight: 850;
            border: 1px solid transparent;
            background: transparent;
            cursor: pointer;
            transition: .18s ease;
            text-align: left;
        }

        .nav-link:hover,
        .sidebar-logout-btn:hover {
            background: #f1f7ff;
            color: var(--primary);
            transform: translateX(2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-color: rgba(255, 255, 255, .4);
            color: #ffffff;
            box-shadow: 0 14px 28px rgba(37, 99, 235, .25);
        }

        .nav-icon,
        .menu-icon {
            width: 23px;
            height: 23px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: currentColor;
            flex-shrink: 0;
            font-size: 20px;
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
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: #fff;
            font-size: 11px;
            font-weight: 950;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-divider {
            height: 1px;
            background: #e7f0ff;
            margin: 10px 4px;
        }

        .sidebar-logout-btn {
            color: #ef4444;
            background: #fff5f5;
            border-color: #fecaca;
        }

        .content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 26px 30px;
        }

        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .topbar {
            position: sticky;
            top: 14px;
            z-index: 700;
            min-height: 74px;
            margin: 14px 0 24px;
            padding: 12px 14px 12px 18px;
            background:
                radial-gradient(circle at top right, rgba(37, 99, 235, .06), transparent 34%),
                rgba(255, 255, 255, .96);
            border: 1px solid rgba(219, 234, 254, .98);
            border-radius: 22px;
            box-shadow: 0 18px 42px rgba(37, 99, 235, .10);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .topbar-left {
            min-width: 0;
        }

        .topbar-left h1 {
            margin: 0;
            font-size: 25px;
            font-weight: 950;
            letter-spacing: -.045em;
            line-height: 1.15;
            color: var(--text);
        }

        .topbar-left p {
            margin: 5px 0 0;
            max-width: 680px;
            font-size: 13px;
            line-height: 1.45;
            color: var(--muted);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .topbar-action-wrap,
        .profile-dropdown {
            position: relative;
        }

        .topbar-icon,
        .mobile-icon {
            position: relative;
            width: 43px;
            height: 43px;
            border-radius: 999px;
            border: 1px solid #dbeafe;
            background: #fff;
            color: var(--primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(37, 99, 235, .10);
            transition: .18s ease;
            font-size: 20px;
        }

        .topbar-icon:hover,
        .mobile-icon:hover {
            transform: translateY(-1px);
            background: #eff6ff;
        }

        .notification-dot {
            position: absolute;
            top: 6px;
            right: 7px;
            min-width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #ef4444;
            border: 2px solid #fff;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, .13);
            display: none;
        }

        .notification-dot.show {
            display: block;
        }

        .notification-menu {
            position: absolute;
            top: 56px;
            right: 0;
            width: min(370px, calc(100vw - 28px));
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .16);
            overflow: hidden;
            display: none;
            z-index: 2600;
        }

        .notification-menu.show,
        .profile-menu.show {
            display: block;
            animation: menuPop .16s ease;
        }

        @keyframes menuPop {
            from {
                opacity: 0;
                transform: translateY(7px) scale(.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .dropdown-head {
            padding: 15px 16px;
            border-bottom: 1px solid #eef2f7;
            background: linear-gradient(135deg, #eff6ff, #fff 55%, #faf5ff);
        }

        .dropdown-head strong {
            display: block;
            font-size: 14px;
            font-weight: 950;
            color: var(--text);
        }

        .dropdown-head span {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--muted);
        }

        .notification-list {
            max-height: 330px;
            overflow: auto;
        }

        .notification-empty {
            padding: 20px 16px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.5;
            text-align: center;
        }

        .notification-item {
            display: flex;
            gap: 10px;
            padding: 13px 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
            font-size: 13px;
            line-height: 1.45;
            transition: .16s ease;
        }

        .notification-item:hover {
            background: #f8fbff;
        }

        .notification-item::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #cbd5e1;
            margin-top: 5px;
            flex: 0 0 auto;
        }

        .notification-item.is-unread {
            background: #eff6ff;
        }

        .notification-item.is-unread::before {
            background: var(--primary);
        }

        .notification-body strong {
            display: block;
            margin-bottom: 4px;
            color: #0f172a;
            font-size: 13px;
            font-weight: 900;
        }

        .notification-body p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
            line-height: 1.45;
        }

        .notification-body small {
            display: block;
            margin-top: 7px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
        }

        .profile-trigger {
            border: 1px solid #dbeafe;
            background: linear-gradient(135deg, #ffffff, #f8fbff);
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 9px 6px 6px;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(37, 99, 235, .08);
        }

        .profile-trigger:hover {
            background: #eff6ff;
        }

        .profile-avatar,
        .avatar-fallback {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            object-fit: cover;
            border: 2px solid #dbeafe;
            flex-shrink: 0;
        }

        .avatar-fallback {
            background: linear-gradient(135deg, #60a5fa, #2563eb, #7c3aed);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 950;
            font-size: 14px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            max-width: 180px;
            min-width: 0;
        }

        .profile-info strong {
            font-size: 13px;
            font-weight: 950;
            color: var(--text);
            line-height: 1.2;
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-info span {
            margin-top: 2px;
            font-size: 11.5px;
            font-weight: 750;
            color: var(--muted);
            max-width: 180px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-transform: capitalize;
        }

        .profile-arrow {
            color: #334155;
            display: inline-flex;
            align-items: center;
            font-size: 18px;
        }

        .profile-menu {
            position: absolute;
            top: 58px;
            right: 0;
            width: min(320px, calc(100vw - 28px));
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .16);
            overflow: hidden;
            display: none;
            z-index: 2500;
        }

        .profile-menu-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: linear-gradient(135deg, #eff6ff, #ffffff 56%, #faf5ff);
            border-bottom: 1px solid #eef2f7;
        }

        .profile-menu-head strong {
            display: block;
            font-size: 15px;
            font-weight: 950;
            color: var(--text);
            max-width: 210px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-menu-head span {
            display: block;
            margin-top: 4px;
            max-width: 210px;
            font-size: 12.5px;
            color: var(--muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .profile-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            color: #475569;
            font-size: 13.5px;
            font-weight: 850;
            border: 0;
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
            cursor: pointer;
            text-align: left;
        }

        .profile-menu-item:hover {
            background: #f8fbff;
            color: var(--primary);
        }

        .profile-menu-item.logout {
            color: #ef4444;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 18px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.6;
            box-shadow: var(--shadow-soft);
        }

        .alert-success {
            background: #ecfdf5;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error,
        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .mobile-topnav,
        .sidebar-overlay {
            display: none;
        }

        .hidden-logout-form {
            display: none;
        }

        .logout-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .48);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 9999;
        }

        .logout-modal-overlay.show {
            display: flex;
        }

        .logout-modal {
            width: min(380px, 100%);
            background: #fff;
            border: 1px solid #eaf1ff;
            border-radius: 28px;
            padding: 26px 22px;
            text-align: center;
            box-shadow: 0 30px 80px rgba(37, 99, 235, .22);
            animation: popLogout .18s ease;
        }

        @keyframes popLogout {
            from {
                opacity: 0;
                transform: translateY(10px) scale(.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .logout-modal-icon {
            width: 64px;
            height: 64px;
            border-radius: 22px;
            margin: 0 auto 14px;
            background: linear-gradient(135deg, #dbeafe, #fce7f3);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
        }

        .logout-modal h3 {
            margin: 0 0 8px;
            font-size: 22px;
            font-weight: 950;
            color: var(--text);
        }

        .logout-modal p {
            margin: 0 auto 20px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        .logout-modal-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .logout-cancel,
        .logout-confirm {
            height: 42px;
            border-radius: 14px;
            border: 0;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }

        .logout-cancel {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
        }

        .logout-confirm {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
        }

        @media (max-width: 980px) {
            html,
            body {
                width: 100%;
                max-width: 100%;
                overflow-x: hidden;
            }

            body {
                overflow: auto;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .app-shell {
                display: block;
                min-height: 100vh;
            }

            .mobile-topnav {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 64px;
                z-index: 1200;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                padding: 0 12px;
                background: rgba(255, 255, 255, .96);
                border-bottom: 1px solid rgba(219, 234, 254, .95);
                box-shadow: 0 10px 28px rgba(37, 99, 235, .10);
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }

            .mobile-topnav::before {
                content: "";
                position: absolute;
                inset: 0 0 auto 0;
                height: 3px;
                background: linear-gradient(90deg, #2563eb, #06b6d4, #7c3aed, #ec4899);
            }

            .mobile-brand {
                min-width: 0;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .mobile-brand-copy {
                min-width: 0;
                display: flex;
                flex-direction: column;
            }

            .mobile-brand strong {
                max-width: 126px;
                display: block;
                color: var(--text);
                font-size: 13px;
                font-weight: 950;
                line-height: 1.1;
                letter-spacing: -.03em;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .mobile-brand small {
                display: block;
                margin-top: 3px;
                color: var(--primary);
                font-size: 9px;
                font-weight: 950;
                letter-spacing: .08em;
                white-space: nowrap;
            }

            .mobile-actions {
                display: flex;
                align-items: center;
                gap: 7px;
                flex-shrink: 0;
            }

            .mobile-icon {
                width: 38px;
                height: 38px;
                padding: 0;
                border-radius: 14px;
                box-shadow: 0 8px 18px rgba(37, 99, 235, .08);
                font-size: 18px;
            }

            .mobile-profile .profile-avatar,
            .mobile-profile .avatar-fallback {
                width: 27px;
                height: 27px;
                font-size: 11px;
                border-width: 0;
            }

            .mobile-logout-btn {
                color: #ef4444;
                border-color: #fecaca;
                background: #fff5f5;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1300;
                width: min(82vw, 310px);
                height: 100dvh;
                min-height: 100dvh;
                padding: 16px 14px;
                border-radius: 0 26px 26px 0;
                transform: translateX(-105%);
                transition: transform .25s ease;
                overflow-y: auto;
                overflow-x: hidden;
                background:
                    radial-gradient(circle at top right, rgba(124, 58, 237, .12), transparent 30%),
                    linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
                box-shadow: 18px 0 45px rgba(15, 23, 42, .18);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 1250;
                display: none;
                background: rgba(15, 23, 42, .38);
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .brand {
                padding: 10px 8px 14px;
                margin-bottom: 10px;
                padding-right: 6px;
            }

            .brand-logo {
                width: 50px !important;
                height: 50px !important;
            }

            .brand-logo img {
                width: 46px !important;
                height: 46px !important;
            }

            .brand-title {
                font-size: 18px;
                line-height: 1.15;
            }

            .brand-subtitle {
                font-size: 9.5px;
                padding: 3px 7px;
            }

            .sidebar-profile {
                margin: 10px 2px 14px;
                padding: 12px;
                border-radius: 20px;
            }

            .nav-menu {
                gap: 7px;
            }

            .nav-link,
            .sidebar-logout-btn {
                min-height: 46px;
                padding: 10px 12px;
                border-radius: 16px;
                font-size: 13px;
            }

            .nav-icon {
                width: 34px;
                height: 34px;
                border-radius: 13px;
                flex: 0 0 auto;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                background: rgba(37, 99, 235, .08);
            }

            .nav-link.active .nav-icon {
                background: rgba(255, 255, 255, .20);
            }

            .sidebar-divider {
                margin: 14px 4px;
            }

            .content {
                margin-left: 0;
                width: 100%;
                max-width: 100%;
                height: auto;
                min-height: 100vh;
                overflow: visible;
                padding: 78px 12px 24px;
            }

            .topbar {
                position: relative;
                top: auto;
                z-index: 1;
                min-height: auto;
                margin: 0 0 14px;
                padding: 14px;
                border-radius: 20px;
            }

            .topbar-left h1 {
                font-size: 20px;
                line-height: 1.2;
            }

            .topbar-left p {
                font-size: 11.5px;
                line-height: 1.45;
            }

            .topbar-right {
                display: none;
            }

            .notification-menu {
                position: fixed;
                top: 72px;
                left: 12px;
                right: 12px;
                width: auto;
                max-height: 70vh;
                z-index: 3000;
            }

            .profile-menu {
                right: 0;
                max-width: calc(100vw - 24px);
            }
        }

        @media (max-width: 420px) {
            .mobile-topnav {
                height: 66px;
                padding: 0 10px;
            }

            .mobile-actions {
                gap: 5px;
            }

            .mobile-icon {
                width: 36px;
                height: 36px;
            }

            .mobile-brand strong {
                max-width: 100px;
            }

            .content {
                padding: 80px 10px 22px;
            }

            .logout-modal {
                border-radius: 24px;
                padding: 24px 18px;
            }
        }
    </style>
</head>

<body>
<?php
$session = session();

$roleRaw  = $session->get('role');
$nameRaw  = $session->get('name');
$emailRaw = $session->get('email');
$fotoRaw  = $session->get('foto');

$role = is_scalar($roleRaw) ? (string) $roleRaw : '';

$userName = is_scalar($nameRaw) && $nameRaw !== ''
    ? (string) $nameRaw
    : (is_scalar($emailRaw) && $emailRaw !== '' ? (string) $emailRaw : 'User');

$userEmail = is_scalar($emailRaw) && $emailRaw !== ''
    ? (string) $emailRaw
    : '-';

$userFoto = is_scalar($fotoRaw) && $fotoRaw !== ''
    ? (string) $fotoRaw
    : '';

$activeMenu = isset($activeMenu) && is_scalar($activeMenu)
    ? (string) $activeMenu
    : '';

$title = isset($title) && is_scalar($title)
    ? (string) $title
    : 'Sistem TA';

$pageTitle = isset($pageTitle) && is_scalar($pageTitle)
    ? (string) $pageTitle
    : $title;

$pageSubtitle = isset($pageSubtitle) && is_scalar($pageSubtitle)
    ? (string) $pageSubtitle
    : '';

$userInitial = strtoupper(substr($userName, 0, 1));
$avatarUrl   = $userFoto !== '' ? base_url('uploads/profile/' . $userFoto) : '';

$avatarHtml = static function () use ($avatarUrl, $userInitial): string {
    if ($avatarUrl !== '') {
        return '<img src="' . esc($avatarUrl, 'attr') . '" alt="Foto Profil" class="profile-avatar">';
    }

    return '<span class="avatar-fallback">' . esc($userInitial) . '</span>';
};

$icon = static function (string $name): string {
    $map = [
        'dashboard'    => 'ri-dashboard-fill',
        'users'        => 'ri-team-line',
        'user'         => 'ri-user-3-line',
        'file'         => 'ri-file-list-3-line',
        'folder'       => 'ri-folder-3-line',
        'archive'      => 'ri-archive-line',
        'bell'         => 'ri-notification-3-line',
        'logout'       => 'ri-logout-box-r-line',
        'settings'     => 'ri-settings-3-line',
        'menu'         => 'ri-menu-2-line',
        'close'        => 'ri-close-line',
        'chevron'      => 'ri-arrow-down-s-line',
        'calendar'     => 'ri-calendar-line',
        'database'     => 'ri-database-2-line',
        'chat'         => 'ri-chat-3-line',
        'history'      => 'ri-history-line',
        'shield'       => 'ri-shield-check-line',
        'report'       => 'ri-bar-chart-box-line',
        'notification' => 'ri-notification-badge-line',
    ];


    $classRaw = $map[$name] ?? 'ri-checkbox-blank-circle-fill';
    $class = is_string($classRaw) ? $classRaw : 'ri-checkbox-blank-circle-fill';

    return '<i class="' . esc($class, 'attr') . '"></i>';
};

$isActive = static function (array|string $keys) use ($activeMenu): string {
    $menuKeys = is_array($keys) ? $keys : [$keys];

    foreach ($menuKeys as $key) {
        if (is_string($key) && $key === $activeMenu) {
            return 'active';
        }
    }

    return '';
};

if ($role === 'admin') {
    $navItems = [
        ['keys' => ['dashboard'], 'icon' => 'dashboard', 'label' => 'Dashboard', 'url' => '/dashboard'],
        ['keys' => ['users', 'kelola_users'], 'icon' => 'users', 'label' => 'Kelola Users', 'url' => '/admin/users'],
        ['keys' => ['periode_akademik'], 'icon' => 'calendar', 'label' => 'Periode Akademik', 'url' => '/admin/periode-akademik'],
        ['keys' => ['program_studi'], 'icon' => 'database', 'label' => 'Program Studi', 'url' => '/admin/program-studi'],
        ['keys' => ['monitoring_pembimbing'], 'icon' => 'users', 'label' => 'Monitoring Pembimbing', 'url' => '/admin/monitoring-pembimbing'],
        ['keys' => ['monitoring_judul'], 'icon' => 'file', 'label' => 'Monitoring Judul', 'url' => '/admin/monitoring-judul'],
        ['keys' => ['monitoring_proposal'], 'icon' => 'folder', 'label' => 'Monitoring Proposal', 'url' => '/admin/monitoring-proposal'],
        ['keys' => ['monitoring_sk', 'surat_keputusan'], 'icon' => 'archive', 'label' => 'Monitoring SK', 'url' => '/admin/surat-keputusan'],
        ['keys' => ['laporan', 'arsip'], 'icon' => 'report', 'label' => 'Laporan & Arsip', 'url' => '/admin/laporan'],
        ['keys' => ['audit_logs', 'notifikasi'], 'icon' => 'shield', 'label' => 'Audit & Notifikasi', 'url' => '/admin/audit-log'],
        ['keys' => ['chat'], 'icon' => 'chat', 'label' => 'Chat Akademik', 'url' => '/chat'],
        ['keys' => ['profile'], 'icon' => 'user', 'label' => 'Profil Saya', 'url' => '/profile'],
    ];
} elseif ($role === 'dosen') {
    $navItems = [
        ['keys' => ['dashboard'], 'icon' => 'dashboard', 'label' => 'Dashboard', 'url' => '/dashboard'],
        ['keys' => ['permohonan_dosen'], 'icon' => 'users', 'label' => 'Permohonan Pembimbing', 'url' => '/dosen/permohonan'],
        ['keys' => ['pengajuan_judul_dosen', 'review_judul'], 'icon' => 'file', 'label' => 'Review Judul', 'url' => '/dosen/pengajuan-judul'],
        ['keys' => ['riwayat_review_dosen'], 'icon' => 'history', 'label' => 'Riwayat Review Judul', 'url' => '/dosen/pengajuan-judul/riwayat'],
        ['keys' => ['proposal_dosen'], 'icon' => 'folder', 'label' => 'Proposal TA', 'url' => '/dosen/proposal-ta'],
        ['keys' => ['riwayat_proposal_dosen'], 'icon' => 'history', 'label' => 'Riwayat Proposal', 'url' => '/dosen/proposal-ta/riwayat'],
        ['keys' => ['chat'], 'icon' => 'chat', 'label' => 'Chat Bimbingan', 'url' => '/chat'],
        ['keys' => ['profile'], 'icon' => 'user', 'label' => 'Profil Saya', 'url' => '/profile'],
    ];
} else {
    $navItems = [
        ['keys' => ['dashboard'], 'icon' => 'dashboard', 'label' => 'Dashboard', 'url' => '/dashboard'],
        ['keys' => ['pembimbing'], 'icon' => 'users', 'label' => 'Pembimbing', 'url' => '/pembimbing'],
        ['keys' => ['pengajuan_judul'], 'icon' => 'file', 'label' => 'Pengajuan Judul', 'url' => '/pengajuan-judul'],
        ['keys' => ['proposal_ta'], 'icon' => 'folder', 'label' => 'Proposal TA', 'url' => '/proposal-ta'],
        ['keys' => ['surat_keputusan'], 'icon' => 'archive', 'label' => 'Surat Keputusan', 'url' => '/surat-keputusan'],
        ['keys' => ['chat'], 'icon' => 'chat', 'label' => 'Chat Bimbingan', 'url' => '/chat'],
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
        <button type="button" class="mobile-icon" onclick="toggleNotificationMenu(event)" aria-label="Notifikasi">
            <?= $icon('bell') ?>
            <span class="notification-dot" id="mobileNotifDot"></span>
        </button>

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

<div class="notification-menu" id="notificationMenu">
    <div class="dropdown-head">
        <strong>Notifikasi</strong>
        <span id="notificationCountText">Memuat notifikasi...</span>
    </div>

    <div class="notification-list" id="notificationList">
        <div class="notification-empty">Memuat notifikasi...</div>
    </div>

    <a href="<?= base_url('/notifikasi') ?>" class="notification-view-all">
        Lihat semua notifikasi
    </a>
</div>

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
                <span class="sidebar-profile-name"><?= esc($userName) ?></span>
                <span class="sidebar-profile-role"><?= esc($role !== '' ? $role : 'user') ?></span>
            </span>
        </a>

        <nav class="nav-menu">
<?php foreach ($navItems as $item): ?>
    <?php
    $itemUrlRaw   = $item['url'] ?? '#';
    $itemIconRaw  = $item['icon'] ?? 'file';
    $itemLabelRaw = $item['label'] ?? '-';
    $itemKeysRaw  = $item['keys'] ?? '';

    $itemUrl = is_string($itemUrlRaw) ? $itemUrlRaw : '#';
    $itemIcon = is_string($itemIconRaw) ? $itemIconRaw : 'file';
    $itemLabel = is_string($itemLabelRaw) ? $itemLabelRaw : '-';

    if (is_array($itemKeysRaw)) {
        $itemKeys = array_values(array_filter($itemKeysRaw, 'is_string'));
    } elseif (is_string($itemKeysRaw)) {
        $itemKeys = $itemKeysRaw;
    } else {
        $itemKeys = '';
    }

    $itemCountRaw = $item['count'] ?? null;
    $itemCount = is_scalar($itemCountRaw) ? (string) $itemCountRaw : '';
    ?>

    <a href="<?= esc(base_url($itemUrl), 'attr') ?>" class="nav-link <?= esc($isActive($itemKeys), 'attr') ?>">
        <span class="nav-icon"><?= $icon($itemIcon) ?></span>
        <span class="nav-text"><?= esc($itemLabel) ?></span>

        <?php if ($itemCount !== ''): ?>
            <span class="nav-badge"><?= esc($itemCount) ?></span>
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
                <h1><?= esc($pageTitle) ?></h1>

                <?php if ($pageSubtitle !== ''): ?>
                    <p><?= esc($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>

            <div class="topbar-right">
                <div class="topbar-action-wrap">
                    <button type="button" class="topbar-icon" onclick="toggleNotificationMenu(event)" aria-label="Notifikasi">
                        <?= $icon('bell') ?>
                        <span class="notification-dot" id="notifDot"></span>
                    </button>
                </div>

                <div class="profile-dropdown">
                    <button type="button" class="profile-trigger" onclick="toggleProfileMenu(event)">
                        <?= $avatarHtml() ?>

                        <span class="profile-info">
                            <strong><?= esc($userName) ?></strong>
                            <span><?= esc($userEmail) ?></span>
                        </span>

                        <span class="profile-arrow"><?= $icon('chevron') ?></span>
                    </button>

                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-menu-head">
                            <?= $avatarHtml() ?>

                            <span>
                                <strong><?= esc($userName) ?></strong>
                                <span><?= esc($userEmail) ?></span>
                            </span>
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
                <?= esc(is_array($success) ? implode(', ', array_map('strval', $success)) : (string) $success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error = session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= esc(is_array($error) ? implode(', ', array_map('strval', $error)) : (string) $error) ?>
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

    if (toggleIcon) {
        toggleIcon.innerHTML = `<?= $icon('close') ?>`;
    }
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleIcon = document.getElementById('mobileToggleIcon');

    sidebar?.classList.remove('show');
    overlay?.classList.remove('show');
    document.body.classList.remove('sidebar-open');

    if (toggleIcon) {
        toggleIcon.innerHTML = `<?= $icon('menu') ?>`;
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');

    if (sidebar?.classList.contains('show')) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function toggleProfileMenu(event) {
    event?.stopPropagation();
    closeFloatingMenus();

    document.getElementById('profileMenu')?.classList.toggle('show');
}

function closeProfileMenu() {
    document.getElementById('profileMenu')?.classList.remove('show');
}

function openLogoutModal() {
    closeProfileMenu();
    closeFloatingMenus();
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

    const menu = document.getElementById('notificationMenu');
    menu?.classList.toggle('show');
}

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>'"]/g, function(char) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            "'": '&#039;',
            '"': '&quot;'
        }[char];
    });
}

async function loadRealtimeNotifications() {
    try {
        const response = await fetch('<?= base_url('/notifikasi/realtime') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) return;

        const data = await response.json();

        if (!data.success) return;

        const count = Number(data.unread ?? data.count ?? data.total ?? 0);
        const items = Array.isArray(data.items)
            ? data.items
            : (Array.isArray(data.notifications) ? data.notifications : []);

        updateNotificationUI(count, items);
    } catch (error) {
        console.error(error);
    }
}

function updateNotificationUI(count, items) {
    document.getElementById('notifDot')?.classList.toggle('show', count > 0);
    document.getElementById('mobileNotifDot')?.classList.toggle('show', count > 0);

    const countText = document.getElementById('notificationCountText');
    if (countText) {
        countText.textContent = count > 0
            ? count + ' belum dibaca'
            : 'Tidak ada notifikasi baru';
    }

    const list = document.getElementById('notificationList');
    if (!list) return;

    if (!items.length) {
        list.innerHTML = '<div class="notification-empty">Belum ada notifikasi.</div>';
        return;
    }

    list.innerHTML = items.slice(0, 8).map(function(item) {
        const id = escapeHtml(item.id ?? '');
        const judul = escapeHtml(item.judul || item.title || 'Notifikasi');
        const pesan = escapeHtml(item.pesan || item.message || 'Ada update akademik terbaru.');
        const tanggal = escapeHtml(item.created_at || '');
        const readClass = Number(item.is_read || 0) === 1 ? 'is-read' : 'is-unread';

        return `
            <a href="<?= base_url('/notifikasi/read') ?>/${id}" class="notification-item ${readClass}">
                <div class="notification-body">
                    <strong>${judul}</strong>
                    <p>${pesan}</p>
                    ${tanggal ? `<small>${tanggal}</small>` : ''}
                </div>
            </a>
        `;
    }).join('');
}

document.addEventListener('click', function(event) {
    const profileDropdown = document.querySelector('.profile-dropdown');
    const notificationWrap = document.querySelector('.topbar-action-wrap');

    if (profileDropdown && !profileDropdown.contains(event.target)) {
        closeProfileMenu();
    }

    if (
        !event.target.closest('.topbar-action-wrap') &&
        !event.target.closest('.mobile-actions')
    ) {
        closeFloatingMenus();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSidebar();
        closeProfileMenu();
        closeFloatingMenus();
        closeLogoutModal();
    }
});

document.querySelectorAll('.nav-link').forEach(function(link) {
    link.addEventListener('click', function() {
        closeSidebar();
    });
});

loadRealtimeNotifications();
setInterval(loadRealtimeNotifications, 5000);
</script>

</body>
</html>