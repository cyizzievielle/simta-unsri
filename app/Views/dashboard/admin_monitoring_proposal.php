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
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'badge-info',
        'direview'  => 'badge-warning',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        'disetujui' => 'badge-success',
        default     => 'badge-muted',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'Diajukan',
        'direview'  => 'Direview',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        'disetujui' => 'Disetujui',
        default     => $safe($status),
    };
};

$queryParams = [
    'q'        => $keyword,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    return site_url('admin/monitoring-proposal?' . http_build_query(array_merge($queryParams, [
        'page' => $targetPage,
    ])));
};
?>

<div class="monitoring-page admin-monitoring-proposal-page">

    <section class="stat-grid admin-monitoring-stats stat-count-5">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Data</div>
            <div class="stat-value"><?= esc($safe($summary['total'] ?? 0, '0')) ?></div>
            <div class="stat-desc">Seluruh proposal tugas akhir</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Diajukan</div>
            <div class="stat-value"><?= esc($safe($summary['diajukan'] ?? 0, '0')) ?></div>
            <div class="stat-desc">Menunggu review dosen</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Disetujui</div>
            <div class="stat-value"><?= esc($safe($summary['disetujui'] ?? 0, '0')) ?></div>
            <div class="stat-desc">Proposal telah diterima</div>
        </div>

        <div class="stat-card stat-purple">
            <div class="stat-label">Revisi</div>
            <div class="stat-value"><?= esc($safe($summary['revisi'] ?? 0, '0')) ?></div>
            <div class="stat-desc">Perlu perbaikan</div>
        </div>

        <div class="stat-card stat-rose">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value"><?= esc($safe($summary['ditolak'] ?? 0, '0')) ?></div>
            <div class="stat-desc">Tidak dilanjutkan</div>
        </div>
    </section>

    <section class="card-main admin-monitoring-card">
        <div class="page-head">
            <div>
                <h3>Data Monitoring Proposal</h3>
                <p>Cari, filter, dan pantau proposal mahasiswa dari satu tabel yang rapi.</p>
            </div>
        </div>

        <div class="filter-box admin-monitoring-filter">
            <form method="get" action="<?= site_url('admin/monitoring-proposal') ?>" class="filter-form admin-proposal-filter-form">
                <div class="filter-field">
                    <label for="q">Cari Mahasiswa / NIM / Judul / File</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="<?= esc($safe($keyword, '')) ?>"
                        placeholder="Cari data proposal..."
                    >
                </div>

                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
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
                    <a href="<?= site_url('admin/monitoring-proposal') ?>" class="btn-filter btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <div class="table-meta">
            <div class="table-meta-text">
                Menampilkan <?= esc($safe($startRow, '0')) ?> - <?= esc($safe($endRow, '0')) ?> dari <?= esc($safe($totalRows, '0')) ?> data
            </div>
        </div>

        <?php if (! empty($rows) && is_array($rows)): ?>
            <div class="table-wrap admin-monitoring-table-wrap">
                <table class="admin-table admin-monitoring-table admin-proposal-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>File Proposal</th>
                            <th>Versi</th>
                            <th>Status</th>
                            <th>Tanggal Upload</th>
                            <th>Tanggal Review</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <?php
                            $fileProposal = $safe($row['file_proposal'] ?? '', '');
                            $fileAsli     = $safe($row['nama_file_asli'] ?? '', '');
                            $catatan      = $safe(($row['catatan_reviewer'] ?? '') !== '' ? $row['catatan_reviewer'] : '-', '-');

                            $fileUrl = $fileProposal !== ''
                                ? base_url('uploads/proposal/' . $fileProposal)
                                : '';
                            ?>

                            <tr>
                                <td>
                                    <div class="student-name"><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></div>
                                    <div class="student-sub">NIM: <?= esc($safe($row['nim'] ?? '-')) ?></div>
                                </td>

                                <td>
                                    <div class="judul-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="file-cell">
                                        <div class="muted-text">
                                            <?= esc($fileAsli !== '' ? $fileAsli : '-') ?>
                                        </div>
                                    <?php if ($fileUrl !== ''): ?>
                                        <div class="file-actions">

                                            <a
                                                href="<?= esc($fileUrl) ?>"
                                                target="_blank"
                                                rel="noopener"
                                                class="table-icon-btn view-btn"
                                                title="Lihat File"
                                            >
                                                <i class="ri-eye-line"></i>
                                            </a>

                                            <a
                                                href="<?= esc($fileUrl) ?>"
                                                download
                                                class="table-icon-btn download-btn"
                                                title="Download File"
                                            >
                                                <i class="ri-download-2-line"></i>
                                            </a>

                                        </div>
                                                <?php endif; ?>
                                    </div>
                                </td>

                                <td class="cell-nowrap">
                                    <span class="version-pill">
                                        v<?= esc($safe($row['versi_ke'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td class="cell-nowrap">
                                    <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td class="cell-nowrap date-text">
                                    <?= esc($safe($row['tanggal_upload'] ?? '-')) ?>
                                </td>

                                <td class="cell-nowrap date-text">
                                    <?= esc($safe($row['tanggal_review'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?php if ($catatan !== '-'): ?>
                                        <button
                                            type="button"
                                            class="icon-btn icon-open"
                                            onclick="openNoteModal(this)"
                                            data-note="<?= esc($catatan) ?>"
                                            title="Lihat Catatan"
                                        >
                                            👁
                                        </button>
                                    <?php else: ?>
                                        <span class="muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada data monitoring proposal yang cocok dengan filter.
            </div>
        <?php endif; ?>

        <div class="pagination-bar">
            <div class="pagination-info">
                Halaman <?= esc($safe($page, '1')) ?> dari <?= esc($safe($totalPages, '1')) ?>
            </div>

            <div class="pagination-links">
                <?php if ((int) $page > 1): ?>
                    <a class="page-link" href="<?= $buildPageUrl((int) $page - 1) ?>">&laquo;</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= (int) $totalPages; $i++): ?>
                    <a
                        class="page-link <?= $i === (int) $page ? 'active' : '' ?>"
                        href="<?= $buildPageUrl($i) ?>"
                    >
                        <?= esc((string) $i) ?>
                    </a>
                <?php endfor; ?>

                <?php if ((int) $page < (int) $totalPages): ?>
                    <a class="page-link" href="<?= $buildPageUrl((int) $page + 1) ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<div class="note-modal-overlay" id="noteModal" onclick="closeNoteModal(event)">
    <div class="note-modal">
        <h3>Catatan Review</h3>
        <p id="noteModalText">-</p>

        <button
            type="button"
            class="note-modal-close"
            onclick="closeNoteModal()"
        >
            Tutup
        </button>
    </div>
</div>

<script>
function openNoteModal(button) {
    const modal = document.getElementById('noteModal');
    const text = document.getElementById('noteModalText');

    if (!modal || !text) {
        return;
    }

    text.textContent = button.dataset.note || '-';
    modal.classList.add('show');
}

function closeNoteModal(event) {
    const modal = document.getElementById('noteModal');

    if (!modal) {
        return;
    }

    if (!event || event.target === modal) {
        modal.classList.remove('show');
    }
}
</script>

<?= $this->endSection() ?>
