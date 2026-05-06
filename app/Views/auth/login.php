<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem TA</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="auth-page">
        <div class="auth-left">
            <div class="brand">
                <div class="brand-badge">TA</div>
                <div>
                    <h3>Sistem Tugas Akhir</h3>
                    <span class="muted">Manajemen Informatika UNSRI</span>
                </div>
            </div>

            <h1>Kelola proses Tugas Akhir</h1>
            <p>
                Platform ini membantu mahasiswa, dosen, dan admin dalam mengelola proses
                pengajuan pembimbing, judul tugas akhir, proposal, hingga penerbitan surat keputusan.
            </p>

            <div class="auth-features">
                <div class="auth-feature">
                    <strong>Manajemen Pembimbing</strong>
                    <span class="muted">Atur pembimbing 1 dan pembimbing 2 dengan kuota dosen.</span>
                </div>
                <div class="auth-feature">
                    <strong>Review Judul & Proposal</strong>
                    <span class="muted">Pemantauan status akademik lebih tertata dan transparan.</span>
                </div>
                <div class="auth-feature">
                    <strong>Arsip Dokumen</strong>
                    <span class="muted">Semua data penting tersimpan rapi dalam satu sistem.</span>
                </div>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-card">
                <h2>Masuk</h2>
                <p>Silakan login menggunakan akun yang telah terdaftar.</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php $errors = session()->getFlashdata('errors'); ?>
                <?php if ($errors): ?>
                    <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= esc((string) (is_array($error) ? implode(', ', $error) : $error)) ?></div>
                    <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form id="loginForm" method="post" action="<?= site_url('login') ?>">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" name="email" id="email" value="<?= esc((string) old('email')) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" type="password" name="password" id="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn" id="loginBtn" style="width:100%;">
                        <span class="btn-text">Login ke Sistem</span>
                        <span class="btn-loader"></span>
                    </button>
                </form>

                <p class="muted" style="margin-top:18px;">
                    Pastikan email dan password sesuai dengan data pada sistem.
                </p>
            </div>
        </div>
    </div>

<div id="pageLoader" class="page-loader">
    <div class="loader-card">
        <div class="loader-logo">TA</div>
        <h3>Menyiapkan Dashboard</h3>
        <p>Mohon tunggu sebentar, sistem sedang memverifikasi akun kamu.</p>

        <div class="loader-bar">
            <span></span>
        </div>
    </div>
</div>

<script>
const loginForm = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');
const pageLoader = document.getElementById('pageLoader');

if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();

        if (loginBtn) {
            loginBtn.disabled = true;
        }

        if (pageLoader) {
            pageLoader.classList.add('show');
        }

        setTimeout(function () {
            loginForm.submit();
        }, 1200);
    });
}
</script>
</body>
</html>