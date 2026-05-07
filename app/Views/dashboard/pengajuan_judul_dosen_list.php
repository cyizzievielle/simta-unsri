<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$pengajuanAktif = $pengajuanAktif ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$totalJudul    = count($pengajuanAktif);
$totalDiajukan = 0;
$totalDireview = 0;

foreach ($pengajuanAktif as $row) {
    $statusRow = strtolower($safe($row['status'] ?? '', ''));

    if ($statusRow === 'diajukan') {
        $totalDiajukan++;
    }

    if ($statusRow === 'direview') {
        $totalDireview++;
    }
}

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'badge-info',
        'direview'  => 'badge-warning',
        'revisi'    => 'badge-danger',
        'disetujui' => 'badge-success',
        'ditolak'   => 'badge-muted',
        default     => 'badge-info',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'Diajukan',
        'direview'  => 'Direview',
        'revisi'    => 'Revisi',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
        default     => $safe($status, '-'),
    };
};
?>

<div class="judul-page dosen-title-page">
    <section class="page-hero title-review-hero">
        <span class="page-kicker">
            Review Judul Tugas Akhir
        </span>
        <h2>Pengajuan Judul Mahasiswa</h2>
        <p>
            Tinjau dan kelola pengajuan judul tugas akhir mahasiswa bimbingan.
        </p>
    </section>

    <section class="dosen-stat-grid">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Judul Aktif</div>
            <div class="stat-value"><?= esc($safe($totalJudul, '0')) ?></div>
            <div class="stat-desc">Judul aktif yang perlu ditinjau</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Baru Diajukan</div>
            <div class="stat-value"><?= esc((string) $totalDiajukan) ?></div>
            <div class="stat-desc">Menunggu review awal</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Sedang Direview</div>
            <div class="stat-value"><?= esc((string) $totalDireview) ?></div>
            <div class="stat-desc">Masih dalam proses peninjauan</div>
        </div>
    </section>

    <section class="card-main title-list-card">
        <div class="page-head">
            <div>
                <h3>Daftar Pengajuan Judul Aktif</h3>
                <p>
                    Hanya pengajuan berstatus diajukan atau direview yang tampil pada halaman ini.
                </p>
            </div>
        </div>

        <?php if (! empty($pengajuanAktif) && is_array($pengajuanAktif)): ?>
            <div class="table-wrap title-table-wrap">
                <table class="admin-table title-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($pengajuanAktif as $row): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                </td>

                                <td>
                                    <span class="student-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="title-cell title-cell-wide">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($statusClass($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="version-pill">
                                        v<?= esc($safe($row['versi_ke'] ?? '1')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="date-text">
                                        <?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="<?= base_url('/dosen/pengajuan-judul/detail/' . $safe($row['id'] ?? '')) ?>"
                                        class="icon-btn icon-open"
                                        title="Lihat Detail"
                                    >
                                        <span aria-hidden="true">👁</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="title-mobile-list">
                <?php foreach ($pengajuanAktif as $row): ?>
                    <article class="title-mobile-card">
                        <div class="title-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>

                            <span class="badge <?= esc($statusClass($row['status'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="title-mobile-title">
                            <?= esc($safe($row['judul'] ?? '-')) ?>
                        </div>

                        <div class="title-mobile-meta">
                            <div>
                                <small>Versi</small>
                                <b>v<?= esc($safe($row['versi_ke'] ?? '1')) ?></b>
                            </div>

                            <div>
                                <small>Tanggal Pengajuan</small>
                                <b><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></b>
                            </div>
                        </div>

                        <a
                            href="<?= base_url('/dosen/pengajuan-judul/detail/' . $safe($row['id'] ?? '')) ?>"
                            class="btn btn-primary title-mobile-btn"
                        >
                            Lihat Detail
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada pengajuan judul aktif dari mahasiswa bimbingan Anda.
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>
