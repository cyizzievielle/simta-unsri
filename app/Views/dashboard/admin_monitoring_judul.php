<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$rows       = $rows ?? [];
$summary    = $summary ?? [];
$keyword    = $keyword ?? '';
$status     = $status ?? '';
$perPage    = $perPage ?? 10;
$page       = $page ?? 1;
$totalRows  = $totalRows ?? 0;
$totalPages = $totalPages ?? 1;
$startRow   = $startRow ?? 0;
$endRow     = $endRow ?? 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$statusClass = static function (mixed $value): string {
    $status = strtolower((string) $value);

    return match ($status) {
        'diajukan'  => 'badge-info',
        'direview'  => 'badge-warning',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        'disetujui' => 'badge-success',
        'draft'     => 'badge-muted',
        default     => 'badge-muted',
    };
};

$statusLabel = static function (mixed $value): string {
    $status = strtolower((string) $value);

    return match ($status) {
        'diajukan'  => 'Diajukan',
        'direview'  => 'Direview',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        'disetujui' => 'Disetujui',
        'draft'     => 'Draft',
        default     => '-',
    };
};

$queryParams = [
    'q'        => $keyword,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    $params = array_merge($queryParams, ['page' => $targetPage]);
    return site_url('admin/monitoring-judul?' . http_build_query($params));
};
?>

<div class="admin-monitor-page admin-monitor-judul-page">

    <section class="page-hero admin-monitor-hero">
        <span class="page-kicker">Monitoring Judul</span>

        <h2>Monitoring Pengajuan Judul</h2>

        <p>
            Pantau status pengajuan judul mahasiswa, similarity, versi revisi,
            dan proses review dari dosen pembimbing.
        </p>
    </section>

    <section class="stat-grid admin-monitor-stat-grid stat-count-5">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Data</div>
            <div class="stat-value"><?= esc((string) ($summary['total'] ?? 0)) ?></div>
            <div class="stat-desc">Seluruh pengajuan judul</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Direview</div>
            <div class="stat-value"><?= esc((string) ($summary['direview'] ?? 0)) ?></div>
            <div class="stat-desc">Sedang diproses dosen</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Disetujui</div>
            <div class="stat-value"><?= esc((string) ($summary['disetujui'] ?? 0)) ?></div>
            <div class="stat-desc">Judul telah disetujui</div>
        </div>

        <div class="stat-card stat-rose">
            <div class="stat-label">Revisi</div>
            <div class="stat-value"><?= esc((string) ($summary['revisi'] ?? 0)) ?></div>
            <div class="stat-desc">Perlu perbaikan mahasiswa</div>
        </div>

        <div class="stat-card stat-slate">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value"><?= esc((string) ($summary['ditolak'] ?? 0)) ?></div>
            <div class="stat-desc">Tidak dilanjutkan</div>
        </div>
    </section>

    <section class="card-main admin-monitor-card">
        <div class="page-head">
            <div>
                <h3>Data Monitoring Judul</h3>
                <p>Cari, filter, dan pantau seluruh pengajuan judul mahasiswa.</p>
            </div>
        </div>

        <div class="filter-box admin-monitor-filter">
            <form method="get" action="<?= site_url('admin/monitoring-judul') ?>" class="filter-form admin-monitor-filter-form">
                <div class="filter-field">
                    <label for="q">Cari Mahasiswa / NIM / Judul</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="<?= esc((string) $keyword) ?>"
                        placeholder="Cari data pengajuan judul"
                    >
                </div>

                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="diajukan" <?= $status === 'diajukan' ? 'selected' : '' ?>>Diajukan</option>
                        <option value="direview" <?= $status === 'direview' ? 'selected' : '' ?>>Direview</option>
                        <option value="revisi" <?= $status === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label for="per_page">Tampilkan</label>
                    <select id="per_page" name="per_page">
                        <option value="10" <?= (int) $perPage === 10 ? 'selected' : '' ?>>10</option>
                        <option value="25" <?= (int) $perPage === 25 ? 'selected' : '' ?>>25</option>
                        <option value="50" <?= (int) $perPage === 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= (int) $perPage === 100 ? 'selected' : '' ?>>100</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                    <a href="<?= site_url('admin/monitoring-judul') ?>" class="btn-filter btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-meta admin-monitor-table-meta">
            <div class="pagination-info">
                Menampilkan <?= esc((string) $startRow) ?> - <?= esc((string) $endRow) ?> dari <?= esc((string) $totalRows) ?> data
            </div>

            <div class="pagination-info">
                Halaman <?= esc((string) $page) ?> dari <?= esc((string) $totalPages) ?>
            </div>
        </div>

        <?php if (! empty($rows)): ?>
            <div class="table-wrap admin-monitoring-table-wrap">
                <table class="admin-table admin-monitoring-table admin-monitoring-judul-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Topik</th>
                            <th>Similarity</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Tanggal Pengajuan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td>
                                    <strong class="monitor-student-name">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </strong>
                                    <small class="monitor-student-sub">
                                        NIM: <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </small>
                                </td>

                                <td>
                                    <div class="monitor-title-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="monitor-topic-cell">
                                        <?= esc($safe($row['bidang_topik'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="monitor-similarity-cell">
                                        <strong><?= esc($safe($row['similarity_score'] ?? '-')) ?></strong>
                                        <span>Flag: <?= esc($safe($row['similarity_flag'] ?? '-')) ?></span>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($statusClass($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-soft">
                                        v<?= esc($safe($row['versi_ke'] ?? '1')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="monitor-date">
                                        <?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada data monitoring judul yang cocok dengan filter.
            </div>
        <?php endif; ?>

        <?php if ((int) $totalPages > 1): ?>
            <div class="pagination-bar">
                <div class="pagination-info">
                    Halaman <?= esc((string) $page) ?> dari <?= esc((string) $totalPages) ?>
                </div>

                <div class="pagination-links">
                    <?php if ((int) $page > 1): ?>
                        <a class="page-link" href="<?= $buildPageUrl((int) $page - 1) ?>">&laquo;</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= (int) $totalPages; $i++): ?>
                        <a class="page-link <?= $i === (int) $page ? 'active' : '' ?>" href="<?= $buildPageUrl($i) ?>">
                            <?= esc((string) $i) ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ((int) $page < (int) $totalPages): ?>
                        <a class="page-link" href="<?= $buildPageUrl((int) $page + 1) ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>
