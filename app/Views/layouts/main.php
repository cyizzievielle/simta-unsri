<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Sistem Tugas Akhir') ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
    <div class="app-shell">
        <?php if (session()->get('isLoggedIn')): ?>
            <aside class="sidebar">
                <div class="sidebar-brand">
                    <div class="brand-badge">TA</div>
                    <div>
                        <h2>Sistem TA</h2>
                        <p>MI UNSRI</p>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    <a href="<?= base_url('/dashboard') ?>" class="<?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">
                        Dashboard
                    </a>

                    <?php if (session('role') === 'mahasiswa'): ?>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'pembimbing' ? 'active' : '' ?>">Pembimbing</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'judul' ? 'active' : '' ?>">Pengajuan Judul</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'proposal' ? 'active' : '' ?>">Proposal TA</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'sk' ? 'active' : '' ?>">Surat Keputusan</a>
                    <?php endif; ?>

                    <?php if (session('role') === 'dosen'): ?>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'permohonan' ? 'active' : '' ?>">Permohonan Bimbingan</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'review-judul' ? 'active' : '' ?>">Review Judul</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'review-proposal' ? 'active' : '' ?>">Review Proposal</a>
                    <?php endif; ?>

                    <?php if (session('role') === 'admin'): ?>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?>">Kelola User</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'mahasiswa' ? 'active' : '' ?>">Kelola Mahasiswa</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'dosen' ? 'active' : '' ?>">Kelola Dosen</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'judul-admin' ? 'active' : '' ?>">Pengajuan Judul</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'proposal-admin' ? 'active' : '' ?>">Proposal TA</a>
                        <a href="#" class="<?= ($activeMenu ?? '') === 'sk-admin' ? 'active' : '' ?>">Surat Keputusan</a>
                    <?php endif; ?>
                </nav>

                <div class="sidebar-footer">
                    <div class="user-mini">
                        <div class="avatar"><?= strtoupper(substr(session('name') ?? 'U', 0, 1)) ?></div>
                        <div>
                            <strong><?= esc(session('name') ?? '-') ?></strong>
                            <span><?= esc(ucfirst(session('role') ?? '-')) ?></span>
                        </div>
                    </div>
                </div>
            </aside>
        <?php endif; ?>

        <div class="main-wrapper <?= session()->get('isLoggedIn') ? '' : 'full-width' ?>">
            <?php if (session()->get('isLoggedIn')): ?>
                <header class="topbar">
                    <div>
                        <h1><?= esc($pageTitle ?? 'Dashboard') ?></h1>
                        <p><?= esc($pageSubtitle ?? 'Sistem Informasi Tugas Akhir') ?></p>
                    </div>

                    <div class="topbar-actions">
                        <div class="topbar-user">
                            <span><?= esc(session('email')) ?></span>
                        </div>
                        <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Logout</a>
                    </div>
                </header>
            <?php endif; ?>

            <main class="content-area">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
</body>
</html>