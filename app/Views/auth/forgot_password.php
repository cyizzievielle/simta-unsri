<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Sistem TA</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, sans-serif;
            color: #0f172a;
            background:
                radial-gradient(circle at top left, rgba(37,99,235,.16), transparent 35%),
                radial-gradient(circle at bottom right, rgba(124,58,237,.18), transparent 38%),
                linear-gradient(135deg, #f8fbff, #eef5ff 45%, #f7f3ff);
            display: grid;
            place-items: center;
            padding: 22px;
        }

        .forgot-card {
            width: min(100%, 460px);
            padding: 34px;
            border-radius: 32px;
            background: rgba(255,255,255,.86);
            border: 1px solid rgba(226,232,240,.95);
            box-shadow: 0 28px 80px rgba(15,23,42,.14);
            backdrop-filter: blur(22px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .brand h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
        }

        .brand p {
            margin: 3px 0 0;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .14em;
            color: #2563eb;
        }

        .icon {
            width: 56px;
            height: 56px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: 26px;
            background: linear-gradient(135deg,#2563eb,#7c3aed);
            box-shadow: 0 16px 30px rgba(37,99,235,.22);
            margin-bottom: 18px;
        }

        h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 950;
            letter-spacing: -.05em;
        }

        .desc {
            margin: 10px 0 24px;
            color: #64748b;
            line-height: 1.7;
            font-size: 14px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 16px;
            margin-bottom: 16px;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.5;
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

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 900;
            color: #334155;
        }

        .input-wrap {
            position: relative;
            margin-bottom: 18px;
        }

        .input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            font-size: 18px;
        }

        input {
            width: 100%;
            height: 54px;
            border-radius: 18px;
            border: 1px solid #dbe7fb;
            background: #f8fbff;
            padding: 0 18px 0 48px;
            font-size: 15px;
            font-weight: 650;
            outline: none;
        }

        input:focus {
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 5px rgba(37,99,235,.11);
        }

        button {
            width: 100%;
            height: 56px;
            border: 0;
            border-radius: 18px;
            background: linear-gradient(135deg,#2563eb,#7c3aed);
            color: #fff;
            font-size: 15px;
            font-weight: 950;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(37,99,235,.24);
        }

        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 18px;
            color: #2563eb;
            font-size: 14px;
            font-weight: 850;
            text-decoration: none;
        }

        @media (max-width: 520px) {
            .forgot-card {
                padding: 24px 18px;
                border-radius: 26px;
            }

            h1 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>

<form action="<?= base_url('/forgot-password') ?>" method="post" class="forgot-card">
    <?= csrf_field() ?>

    <div class="brand">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
        <div>
            <h2>Sistem TA</h2>
            <p>MI UNSRI</p>
        </div>
    </div>

    <div class="icon">
        <i class="ri-mail-send-line"></i>
    </div>

    <h1>Lupa Password?</h1>
    <p class="desc">
        Masukkan email akun kamu. Sistem akan membuat link reset password untuk mengganti password baru.
    </p>

    <?php
    $errorFlash = session()->getFlashdata('error');
    $errorText = is_array($errorFlash) ? implode(', ', array_map('strval', $errorFlash)) : (string) ($errorFlash ?? '');

    $successFlash = session()->getFlashdata('success');
    $successText = is_array($successFlash) ? implode(', ', array_map('strval', $successFlash)) : (string) ($successFlash ?? '');
    ?>

    <?php if ($errorText !== ''): ?>
        <div class="alert alert-danger"><?= esc($errorText) ?></div>
    <?php endif; ?>

    <?php if ($successText !== ''): ?>
        <div class="alert alert-success"><?= esc($successText) ?></div>
    <?php endif; ?>

    <label for="email">Email Akun</label>
    <div class="input-wrap">
        <i class="ri-mail-line"></i>
        <input type="email" id="email" name="email" placeholder="contoh@student.unsri.ac.id" required>
    </div>

    <button type="submit">Kirim Link Reset</button>

    <a href="<?= base_url('/login') ?>" class="back-link">
        <i class="ri-arrow-left-line"></i>
        Kembali ke Login
    </a>
</form>

</body>
</html>