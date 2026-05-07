<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem TA MI UNSRI</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, .16), transparent 35%),
                radial-gradient(circle at bottom right, rgba(124, 58, 237, .18), transparent 38%),
                linear-gradient(135deg, #f8fbff, #eef5ff 45%, #f7f3ff);
            overflow-x: hidden;
        }

        .login-page {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            align-items: center;
            gap: 48px;
            padding: 48px 72px;
            position: relative;
        }

        .login-page::before,
        .login-page::after {
            content: "";
            position: absolute;
            width: 240px;
            height: 240px;
            border-radius: 999px;
            filter: blur(8px);
            opacity: .35;
            z-index: 0;
        }

        .login-page::before {
            top: 40px;
            right: 120px;
            background: linear-gradient(135deg, #60a5fa, #7c3aed);
        }

        .login-page::after {
            bottom: 40px;
            left: 140px;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
        }

        .login-left,
        .login-card-wrap {
            position: relative;
            z-index: 1;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 54px;
        }

        .brand-logo {
            width: 58px;
            height: 58px;
            object-fit: contain;
        }

        .brand-text h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -.04em;
        }

        .brand-text p {
            margin: 4px 0 0;
            font-size: 13px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: .16em;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            border-radius: 999px;
            background: rgba(37, 99, 235, .09);
            color: #1d4ed8;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .login-left h1 {
            margin: 0;
            max-width: 680px;
            font-size: clamp(38px, 5vw, 70px);
            line-height: .98;
            letter-spacing: -.07em;
            font-weight: 950;
        }

        .login-left h1 span {
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            background-clip: text;
            color: transparent;
        }

        .login-left .desc {
            max-width: 620px;
            margin: 24px 0 30px;
            font-size: 17px;
            line-height: 1.8;
            color: #64748b;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            max-width: 760px;
        }

        .feature-card {
            padding: 18px;
            border-radius: 24px;
            background: rgba(255, 255, 255, .72);
            border: 1px solid rgba(226, 232, 240, .9);
            backdrop-filter: blur(18px);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .06);
            transition: .25s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 50px rgba(37, 99, 235, .12);
        }

        .feature-card i {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 20px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            margin-bottom: 12px;
        }

        .feature-card h3 {
            margin: 0 0 6px;
            font-size: 15px;
            font-weight: 900;
        }

        .feature-card p {
            margin: 0;
            font-size: 13px;
            line-height: 1.55;
            color: #64748b;
        }

        .login-card-wrap {
            display: flex;
            justify-content: center;
        }

        .login-card {
            width: min(100%, 470px);
            padding: 34px;
            border-radius: 34px;
            background: rgba(255, 255, 255, .82);
            border: 1px solid rgba(226, 232, 240, .95);
            box-shadow:
                0 28px 80px rgba(15, 23, 42, .14),
                inset 0 1px 0 rgba(255,255,255,.8);
            backdrop-filter: blur(24px);
        }

        .login-card-head {
            margin-bottom: 26px;
        }

        .login-card-head .icon {
            width: 54px;
            height: 54px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 24px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            box-shadow: 0 16px 30px rgba(37, 99, 235, .22);
            margin-bottom: 18px;
        }

        .login-card h2 {
            margin: 0;
            font-size: 32px;
            font-weight: 950;
            letter-spacing: -.05em;
        }

        .login-card p {
            margin: 8px 0 0;
            color: #64748b;
            line-height: 1.6;
            font-size: 14px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 18px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 700;
        }

        .alert-danger {
            background: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
        }

        .alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 900;
            color: #334155;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 19px;
        }

        .form-control {
            width: 100%;
            height: 54px;
            padding: 0 48px;
            border-radius: 18px;
            border: 1px solid #dbe7fb;
            background: #f8fbff;
            color: #0f172a;
            font-size: 15px;
            font-weight: 650;
            outline: none;
            transition: .2s ease;
        }

        .form-control:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 5px rgba(37, 99, 235, .11);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border: 0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            border-radius: 12px;
            font-size: 18px;
        }

        .password-toggle:hover {
            background: #eef5ff;
            color: #2563eb;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin: 8px 0 18px;
            font-size: 13px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            font-weight: 700;
        }

        .forgot-link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 900;
        }

        .btn-login {
            width: 100%;
            height: 56px;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: #fff;
            font-size: 15px;
            font-weight: 950;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(37, 99, 235, .24);
            transition: .25s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 40px rgba(37, 99, 235, .3);
        }

        .login-note {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin-top: 18px;
            padding: 14px;
            border-radius: 18px;
            background: #f8fbff;
            color: #64748b;
            font-size: 13px;
            line-height: 1.55;
        }

        .login-note i {
            color: #2563eb;
            font-size: 18px;
            margin-top: 1px;
        }

        @media (max-width: 1100px) {
            .login-page {
                grid-template-columns: 1fr;
                padding: 34px 24px;
                gap: 28px;
            }

            .brand {
                margin-bottom: 32px;
            }

            .login-left {
                text-align: center;
            }

            .login-left .desc {
                margin-left: auto;
                margin-right: auto;
            }

            .feature-grid {
                margin: 0 auto;
            }
        }

        @media (max-width: 720px) {
            body {
                background: linear-gradient(180deg, #f8fbff, #eef5ff);
            }

            .login-page {
                padding: 22px 14px 28px;
                display: block;
            }

            .brand {
                justify-content: center;
                margin-bottom: 24px;
            }

            .brand-logo {
                width: 48px;
                height: 48px;
            }

            .brand-text h2 {
                font-size: 18px;
            }

            .brand-text p {
                font-size: 11px;
            }

            .hero-badge {
                font-size: 12px;
                padding: 8px 12px;
            }

            .login-left h1 {
                font-size: 34px;
                line-height: 1.08;
            }

            .login-left .desc {
                font-size: 14px;
                line-height: 1.7;
                margin: 16px auto 20px;
            }

            .feature-grid {
                grid-template-columns: 1fr;
                gap: 10px;
                margin-bottom: 22px;
            }

            .feature-card {
                padding: 14px 16px;
                border-radius: 20px;
                display: flex;
                align-items: center;
                gap: 12px;
                text-align: left;
            }

            .feature-card i {
                margin: 0;
                width: 38px;
                height: 38px;
                border-radius: 14px;
                flex-shrink: 0;
            }

            .feature-card h3 {
                font-size: 14px;
            }

            .feature-card p {
                font-size: 12px;
            }

            .login-card {
                padding: 24px 18px;
                border-radius: 28px;
            }

            .login-card-head {
                text-align: center;
                margin-bottom: 22px;
            }

            .login-card-head .icon {
                margin-left: auto;
                margin-right: auto;
            }

            .login-card h2 {
                font-size: 28px;
            }

            .form-control,
            .btn-login {
                height: 52px;
                border-radius: 16px;
            }

            .form-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>

<body>
    <main class="login-page">
        <section class="login-left">
            <div class="brand">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo UNSRI" class="brand-logo">

                <div class="brand-text">
                    <h2>Sistem TA</h2>
                    <p>MI UNSRI</p>
                </div>
            </div>

            <div class="hero-badge">
                <i class="ri-graduation-cap-line"></i>
                Sistem Pengajuan Tugas Akhir
            </div>

            <h1>Kelola Tugas Akhir <span>lebih rapi.</span></h1>

            <p class="desc">
                Platform akademik untuk mahasiswa, dosen, dan admin dalam mengelola pembimbing,
                pengajuan judul, proposal, sampai penerbitan Surat Keputusan.
            </p>

            <div class="feature-grid">
                <div class="feature-card">
                    <i class="ri-team-line"></i>
                    <div>
                        <h3>Pembimbing</h3>
                        <p>Ajukan dan pantau status dosen pembimbing.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <i class="ri-file-text-line"></i>
                    <div>
                        <h3>Judul & Proposal</h3>
                        <p>Review akademik lebih jelas dan terarsip.</p>
                    </div>
                </div>

                <div class="feature-card">
                    <i class="ri-folder-shield-2-line"></i>
                    <div>
                        <h3>Arsip SK</h3>
                        <p>Dokumen penting tersimpan dalam satu sistem.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="login-card-wrap">
            <form action="<?= base_url('/login') ?>" method="post" class="login-card">
                <?= csrf_field() ?>

                <div class="login-card-head">
                    <div class="icon">
                        <i class="ri-lock-unlock-line"></i>
                    </div>

                    <h2>Masuk</h2>
                    <p>Gunakan akun yang sudah terdaftar untuk mengakses dashboard.</p>
                </div>

                <?php
                $errorFlash = session()->getFlashdata('error');
                $errorText = is_array($errorFlash) ? implode(', ', array_map('strval', $errorFlash)) : (string) ($errorFlash ?? '');
                ?>

                <?php if ($errorText !== ''): ?>
                    <div class="alert alert-danger">
                        <?= esc($errorText) ?>
                    </div>
                <?php endif; ?>

                <?php
                $successFlash = session()->getFlashdata('success');
                $successText = is_array($successFlash) ? implode(', ', array_map('strval', $successFlash)) : (string) ($successFlash ?? '');
                ?>

                <?php if ($successText !== ''): ?>
                    <div class="alert alert-success">
                        <?= esc($successText) ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-wrap">
                        <i class="ri-mail-line"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control"
                            value="<?= old('email') ?>"
                            placeholder="Masukkan email"
                            autocomplete="email"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="ri-key-2-line"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Tampilkan password">
                            <i class="ri-eye-line"></i>
                        </button>
                    </div>
                </div>

                <div class="form-row">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1">
                        Ingat saya
                    </label>

                    <a href="<?= base_url('/forgot-password') ?>" class="forgot-link">
                        Lupa password?
                    </a>
                </div>

                <button type="submit" class="btn-login">
                    Login ke Sistem
                </button>

                <div class="login-note">
                    <i class="ri-information-line"></i>
                    <span>Pastikan email dan password sesuai dengan data akun pada sistem.</span>
                </div>
            </form>
        </section>
    </main>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword?.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';
            togglePassword.innerHTML = isPassword
                ? '<i class="ri-eye-off-line"></i>'
                : '<i class="ri-eye-line"></i>';
        });
    </script>
</body>
</html>