<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$riwayat          = $riwayat ?? [];
$keyword          = $keyword ?? '';
$status           = $status ?? '';
$perPage          = $perPage ?? 10;
$page             = $page ?? 1;
$totalRows        = $totalRows ?? 0;
$totalPages       = $totalPages ?? 1;
$startRow         = $startRow ?? 0;
$endRow           = $endRow ?? 0;
$totalDisetujui   = $totalDisetujui ?? 0;
$totalTolakRevisi = $totalTolakRevisi ?? 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'badge-success',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        default     => 'badge-info',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'direview'  => 'Direview',
        'disetujui' => 'Disetujui',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        default     => $safe($status, '-'),
    };
};

$reviewDetailJson = static function (array $row) use ($safe, $statusLabel): string {
    $payload = [
        'mahasiswa' => $safe($row['nama_mahasiswa'] ?? '-'),
        'nim'       => $safe($row['nim'] ?? '-'),
        'judul'     => $safe($row['judul'] ?? '-'),
        'file'      => $safe($row['nama_file_asli'] ?? '-'),
        'status'    => $statusLabel($row['status_review'] ?? '-'),
        'catatan'   => $safe(($row['catatan'] ?? '') !== '' ? $row['catatan'] : 'Tidak ada catatan.'),
        'waktu'     => $safe($row['created_at'] ?? '-'),
    ];

    $json = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

    return is_string($json) ? $json : '{}';
};

$queryParams = [
    'q'        => $keyword,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    return base_url('/dosen/proposal-ta/riwayat?' . http_build_query(array_merge($queryParams, [
        'page' => $targetPage,
    ])));
};
?>

<div class="riwayat-proposal-page">
    <section class="dosen-stat-grid stat-count-3 proposal-history-stats">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Riwayat</div>
            <div class="stat-value"><?= esc($safe($totalRows, '0')) ?></div>
            <div class="stat-desc">Sesuai filter yang dipilih</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Disetujui</div>
            <div class="stat-value"><?= esc($safe($totalDisetujui, '0')) ?></div>
            <div class="stat-desc">Review proposal diterima</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Revisi / Ditolak</div>
            <div class="stat-value"><?= esc($safe($totalTolakRevisi, '0')) ?></div>
            <div class="stat-desc">Perlu tindak lanjut mahasiswa</div>
        </div>
    </section>

    <section class="card-main proposal-history-card">
        <div class="page-head">
            <div>
                <h3>Data Riwayat Proposal</h3>
                <p>Cari berdasarkan mahasiswa, NIM, judul, atau nama file proposal.</p>
            </div>
        </div>

        <div class="filter-box proposal-history-filter">
            <form method="get" action="<?= base_url('/dosen/proposal-ta/riwayat') ?>" class="filter-form proposal-history-filter-form">
                <div class="filter-field">
                    <label for="q">Cari Data</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="<?= esc($safe($keyword, '')) ?>"
                        placeholder="Nama / NIM / Judul / File"
                    >
                </div>

                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="direview" <?= $status === 'direview' ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="revisi" <?= $status === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label for="per_page">Jumlah Data</label>
                    <select id="per_page" name="per_page">
                        <option value="10" <?= (int) $perPage === 10 ? 'selected' : '' ?>>10</option>
                        <option value="50" <?= (int) $perPage === 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= (int) $perPage === 100 ? 'selected' : '' ?>>100</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                    <a href="<?= base_url('/dosen/proposal-ta/riwayat') ?>" class="btn-filter btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <?php if (! empty($riwayat) && is_array($riwayat)): ?>
            <div class="table-wrap proposal-history-table-wrap">
                <table class="admin-table proposal-history-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>File Proposal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayat as $row): ?>
                            <?php
                                $fileProposal = $safe($row['file_proposal'] ?? '', '');
                                $fileAsli     = $safe($row['nama_file_asli'] ?? '', '');
                                $proposalId   = $safe($row['proposal_ta_id'] ?? ($row['proposal_id'] ?? ($row['id'] ?? '')), '');
                                $reviewId     = $safe($row['id'] ?? '', '');
                                $catatanText  = $safe(($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-', '-');
                                $detailJson   = $reviewDetailJson($row);

                                $fileUrl = $fileProposal !== ''
                                    ? base_url('uploads/proposal/' . $fileProposal)
                                    : '';
                            ?>

                            <tr>
                                <td>
                                    <strong class="proposal-history-student">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="proposal-history-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="proposal-history-title">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="proposal-history-file">
                                        <strong><?= esc($fileAsli !== '' ? $fileAsli : '-') ?></strong>

                                        <?php if ($fileUrl !== ''): ?>
                                            <div class="file-actions">
                                                <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="icon-btn icon-open" title="Buka File">
                                                    <i class="ri-eye-line"></i>
                                                </a>

                                                <a href="<?= esc($fileUrl) ?>" download class="icon-btn icon-download" title="Download File">
                                                    <i class="ri-download-2-line"></i>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($statusClass($row['status_review'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status_review'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="proposal-history-note">
                                        <?= esc($catatanText) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="proposal-history-date">
                                        <?= esc($safe($row['created_at'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="table-action-icons">
                                        <button
                                            type="button"
                                            class="table-icon-btn btn-view proposal-detail-btn"
                                            title="Lihat Detail"
                                            data-detail="<?= esc($detailJson, 'attr') ?>"
                                        >
                                            <i class="ri-eye-line"></i>
                                            <span class="sr-only">Lihat Detail</span>
                                        </button>

                                        <a
                                            href="<?= base_url('/dosen/proposal-ta/review/edit/' . $reviewId) ?>"
                                            class="table-icon-btn btn-edit"
                                            title="Edit Review"
                                        >
                                            <i class="ri-edit-2-line"></i>
                                            <span class="sr-only">Edit Review</span>
                                        </a>

                                        <form
                                            action="<?= base_url('/dosen/proposal-ta/review/delete/' . $reviewId) ?>"
                                            method="post"
                                            onsubmit="return confirm('Hapus review ini?');"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="table-icon-btn btn-delete" title="Hapus Review">
                                                <i class="ri-delete-bin-6-line"></i>
                                                <span class="sr-only">Hapus Review</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="proposal-history-mobile-list">
                <?php foreach ($riwayat as $row): ?>
                    <?php
                        $fileProposal = $safe($row['file_proposal'] ?? '', '');
                        $fileAsli     = $safe($row['nama_file_asli'] ?? '', '');
                        $reviewId     = $safe($row['id'] ?? '', '');
                        $catatanText  = $safe(($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-', '-');
                        $detailJson   = $reviewDetailJson($row);

                        $fileUrl = $fileProposal !== ''
                            ? base_url('uploads/proposal/' . $fileProposal)
                            : '';
                    ?>

                    <article class="proposal-history-mobile-card">
                        <div class="proposal-history-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>

                            <span class="badge <?= esc($statusClass($row['status_review'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status_review'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="proposal-history-mobile-title">
                            <?= esc($safe($row['judul'] ?? '-')) ?>
                        </div>

                        <div class="proposal-history-mobile-info">
                            <div>
                                <small>File Proposal</small>
                                <b><?= esc($fileAsli !== '' ? $fileAsli : '-') ?></b>
                            </div>

                            <div>
                                <small>Waktu Review</small>
                                <b><?= esc($safe($row['created_at'] ?? '-')) ?></b>
                            </div>

                            <div class="mobile-info-full">
                                <small>Catatan</small>
                                <b><?= esc($catatanText) ?></b>
                            </div>
                        </div>

                        <div class="proposal-history-mobile-actions">
                            <button
                                type="button"
                                class="table-icon-btn btn-view proposal-detail-btn"
                                title="Lihat Detail"
                                data-detail="<?= esc($detailJson, 'attr') ?>"
                            >
                                <i class="ri-eye-line"></i>
                            </button>

                            <?php if ($fileUrl !== ''): ?>
                                <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="table-icon-btn btn-view" title="Buka File">
                                    <i class="ri-file-search-line"></i>
                                </a>
                            <?php endif; ?>

                            <a href="<?= base_url('/dosen/proposal-ta/review/edit/' . $reviewId) ?>" class="table-icon-btn btn-edit" title="Edit Review">
                                <i class="ri-edit-2-line"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada riwayat review proposal yang sesuai dengan filter.
            </div>
        <?php endif; ?>

        <div class="pagination-bar">
            <div class="pagination-info">
                Menampilkan <?= esc($safe($startRow, '0')) ?> - <?= esc($safe($endRow, '0')) ?> dari <?= esc($safe($totalRows, '0')) ?> data
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
    </section>
</div>

<div class="detail-modal" id="proposalDetailModal" aria-hidden="true">
    <div class="detail-card">
        <div class="detail-head">
            <div>
                <h4>Detail Review Proposal</h4>
                <p>Informasi hasil review proposal tugas akhir mahasiswa.</p>
            </div>

            <button type="button" class="detail-close" id="closeProposalDetail" aria-label="Tutup">
                ×
            </button>
        </div>

        <div class="detail-body" id="proposalDetailBody"></div>
    </div>
</div>

<script>
document.querySelectorAll('.proposal-detail-btn').forEach((button) => {
    button.addEventListener('click', () => {
        const data = JSON.parse(button.dataset.detail || '{}');
        const modal = document.getElementById('proposalDetailModal');
        const body = document.getElementById('proposalDetailBody');

        body.innerHTML = `
            <div class="detail-item">
                <span>Mahasiswa</span>
                <strong>${data.mahasiswa || '-'}</strong>
            </div>
            <div class="detail-item">
                <span>NIM</span>
                <strong>${data.nim || '-'}</strong>
            </div>
            <div class="detail-item">
                <span>Judul Proposal</span>
                <strong>${data.judul || '-'}</strong>
            </div>
            <div class="detail-item">
                <span>File Proposal</span>
                <strong>${data.file || '-'}</strong>
            </div>
            <div class="detail-item">
                <span>Status Review</span>
                <strong>${data.status || '-'}</strong>
            </div>
            <div class="detail-item">
                <span>Waktu Review</span>
                <strong>${data.waktu || '-'}</strong>
            </div>
            <div class="detail-item detail-note">
                <span>Catatan</span>
                <strong>${data.catatan || '-'}</strong>
            </div>
        `;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    });
});

document.getElementById('closeProposalDetail')?.addEventListener('click', () => {
    const modal = document.getElementById('proposalDetailModal');
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
});

window.addEventListener('click', (event) => {
    const modal = document.getElementById('proposalDetailModal');

    if (event.target === modal) {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    }
});
</script>

<?= $this->endSection() ?>
