<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$proposalAktif = $proposalAktif ?? $proposalList ?? $proposals ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$totalProposal = is_array($proposalAktif) ? count($proposalAktif) : 0;
$totalDiajukan = 0;
$totalDireview = 0;

foreach ($proposalAktif as $row) {
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

$fileName = static function (array $row) use ($safe): string {
    return $safe(($row['nama_file_asli'] ?? '') !== '' ? $row['nama_file_asli'] : ($row['file_proposal'] ?? '-'));
};
?>

<div class="proposal-dosen-page dosen-title-page">
    <section class="dosen-hero">
        <div>
            <span class="dosen-kicker">Proposal Tugas Akhir</span>

            <h2>Daftar Proposal Mahasiswa</h2>

            <p>
                Tinjau proposal tugas akhir mahasiswa bimbingan dan lanjutkan
                ke halaman detail untuk memberikan review akademik.
            </p>
        </div>
    </section>

    <section class="dosen-stat-grid stat-count-3">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Proposal</div>
            <div class="stat-value"><?= esc($safe($totalProposal, '0')) ?></div>
            <div class="stat-desc">Proposal aktif yang perlu ditinjau</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Baru Diajukan</div>
            <div class="stat-value"><?= esc($safe($totalDiajukan, '0')) ?></div>
            <div class="stat-desc">Menunggu review awal dosen</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Sedang Direview</div>
            <div class="stat-value"><?= esc($safe($totalDireview, '0')) ?></div>
            <div class="stat-desc">Dalam proses peninjauan</div>
        </div>
    </section>

    <section class="card-main proposal-dosen-card">
        <div class="page-head">
            <div>
                <h3>Proposal Aktif</h3>
                <p>Proposal yang berstatus diajukan atau direview ditampilkan untuk proses peninjauan dosen.</p>
            </div>
        </div>

        <?php if (! empty($proposalAktif) && is_array($proposalAktif)): ?>
            <div class="table-wrap proposal-dosen-table-wrap">
                <table class="admin-table proposal-dosen-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>File Proposal</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($proposalAktif as $row): ?>
                            <tr>
                                <td>
                                    <strong class="proposal-student-name">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="proposal-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="proposal-title-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="proposal-file-cell">
                                        <strong><?= esc($fileName($row)) ?></strong>

                                        <?php if (($row['file_proposal'] ?? '') !== ''): ?>
                                            <small>File sistem: <?= esc($safe($row['file_proposal'] ?? '-')) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($statusClass($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="proposal-version-pill">
                                        v<?= esc($safe($row['versi_ke'] ?? '1')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="proposal-date">
                                        <?= esc($safe($row['tanggal_upload'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="<?= base_url('/dosen/proposal-ta/detail/' . $safe($row['id'] ?? '')) ?>" class="table-icon-btn btn-view" title="Lihat Detail">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="proposal-mobile-list">
                <?php foreach ($proposalAktif as $row): ?>
                    <article class="proposal-mobile-card">
                        <div class="proposal-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>

                            <span class="badge <?= esc($statusClass($row['status'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="proposal-mobile-title">
                            <?= esc($safe($row['judul'] ?? '-')) ?>
                        </div>

                        <div class="proposal-mobile-info">
                            <div>
                                <small>File Proposal</small>
                                <b><?= esc($fileName($row)) ?></b>
                            </div>

                            <div>
                                <small>Versi</small>
                                <b>v<?= esc($safe($row['versi_ke'] ?? '1')) ?></b>
                            </div>

                            <div>
                                <small>Tanggal Upload</small>
                                <b><?= esc($safe($row['tanggal_upload'] ?? '-')) ?></b>
                            </div>
                        </div>

                        <a href="<?= base_url('/dosen/proposal-ta/detail/' . $safe($row['id'] ?? '')) ?>" class="btn btn-primary proposal-mobile-btn">
                            Lihat Detail Proposal
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum terdapat proposal aktif yang perlu direview saat ini.
            </div>
        <?php endif; ?>
    </section>

</div>

<?= $this->endSection() ?>
