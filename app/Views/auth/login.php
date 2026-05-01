<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem TA</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    
<?php
function safeText(mixed $value, string $default = ''): string
{
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map(static fn ($item): string => is_array($item) ? implode(', ', array_map('strval', $item)) : (string) $item, $value));
    }

    return (string) $value;
}

$errorMsg   = session()->getFlashdata('error');
$successMsg = session()->getFlashdata('success');
$errors     = session()->getFlashdata('errors');
$oldEmail   = old('email');
?>

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

                <?php if (! empty($errorMsg)): ?>
                    <div class="alert alert-danger">
                        <?= esc(safeText($errorMsg)) ?>
                    </div>
                <?php endif; ?>

                <?php if (! empty($successMsg)): ?>
                    <div class="alert alert-success">
                        <?= esc(safeText($successMsg)) ?>
                    </div>
                <?php endif; ?>

                <?php if (! empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php if (is_array($errors)): ?>
                            <?php foreach ($errors as $error): ?>
                                <div><?= esc(safeText($error)) ?></div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div><?= esc(safeText($errors)) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/login') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input
                            class="form-control"
                            type="email"
                            name="email"
                            id="email"
                            value="<?= esc(is_array($oldEmail) ? '' : safeText($oldEmail)) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input
                            class="form-control"
                            type="password"
                            name="password"
                            id="password"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        Login ke Sistem
                    </button>
                </form>

                <p class="muted" style="margin-top:18px;">
                    Pastikan email dan password sesuai dengan data pada sistem.
                </p>
            </div>
        </div>
    </div>
</body>
</html>