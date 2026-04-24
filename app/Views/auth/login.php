<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem TA</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
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
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" type="email" name="email" id="email" value="<?= old('email') ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" type="password" name="password" id="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">Login ke Sistem</button>
                </form>

                <p class="muted" style="margin-top:18px;">
                    Pastikan email dan password sesuai dengan data pada sistem.
                </p>
            </div>
        </div>
    </div>
</body>
</html>