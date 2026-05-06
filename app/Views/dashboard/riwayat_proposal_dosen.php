<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$riwayat = $riwayat ?? [];
$keyword = $keyword ?? '';
$status  = $status ?? '';
$perPage = $perPage ?? 10;
$page    = $page ?? 1;
$totalRows = $totalRows ?? 0;
$totalPages = $totalPages ?? 1;
$startRow = $startRow ?? 0;
$endRow = $endRow ?? 0;
$totalDisetujui = $totalDisetujui ?? 0;
$totalTolakRevisi = $totalTolakRevisi ?? 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'direview'  => 'status-direview',
        'disetujui' => 'status-disetujui',
        'revisi'    => 'status-revisi',
        'ditolak'   => 'status-ditolak',
        default     => 'status-direview',
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

<style>
.riwayat-proposal-page {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.soft-hero {
    background: linear-gradient(135deg, #f8fbff, #eef4ff);
    border: 1px solid #dbeafe;
    border-radius: 22px;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(37, 99, 235, .06);
}

.soft-hero h2 {
    margin: 0 0 5px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.soft-hero p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.premium-stats {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}

.premium-stat-card {
    border-radius: 20px;
    padding: 16px;
    color: #fff;
    min-height: 105px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, .10);
    overflow: hidden;
    position: relative;
}

.premium-stat-card::after {
    content: "";
    position: absolute;
    right: -18px;
    bottom: -18px;
    width: 80px;
    height: 80px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
}

.stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
.stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }

.premium-stat-label {
    position: relative;
    z-index: 1;
    font-size: 11px;
    font-weight: 850;
    margin-bottom: 8px;
}

.premium-stat-value {
    position: relative;
    z-index: 1;
    font-size: 29px;
    font-weight: 950;
    line-height: 1;
    margin-bottom: 6px;
}

.premium-stat-desc {
    position: relative;
    z-index: 1;
    font-size: 11px;
    opacity: .93;
    line-height: 1.35;
}

.card-premium {
    background: #fff;
    border: 1px solid #eef2f7;
    border-radius: 24px;
    padding: 18px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
}

.section-title-premium {
    margin: 0 0 5px;
    font-size: 20px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle-premium {
    margin: 0 0 14px;
    color: #64748b;
    font-size: 12.5px;
    line-height: 1.55;
}

.inline-filter-wrap {
    border: 1px solid #eef2f7;
    background: linear-gradient(135deg, #f8fbff, #f1f5f9);
    border-radius: 18px;
    padding: 12px;
    margin-bottom: 14px;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1.5fr .75fr .65fr auto auto;
    gap: 9px;
    align-items: end;
}

.form-group-premium label {
    display: block;
    margin-bottom: 5px;
    color: #334155;
    font-size: 11px;
    font-weight: 850;
}

.form-group-premium input,
.form-group-premium select {
    width: 100%;
    height: 38px;
    border: 1px solid #dbe3ef;
    border-radius: 12px;
    padding: 8px 10px;
    background: #fff;
    color: #0f172a;
    font-size: 12px;
    outline: none;
}

.filter-actions {
    display: contents;
}

.btn-filter {
    height: 38px;
    padding: 0 13px;
    border-radius: 12px;
    border: 0;
    cursor: pointer;
    font-size: 12px;
    font-weight: 850;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.btn-apply {
    background: #2563eb;
    color: #fff;
}

.btn-reset {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #334155;
}

.premium-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border: 1px solid #eef2f7;
    border-radius: 18px;
    background: #fff;
}

.premium-table {
    width: 100%;
    min-width: 1120px;
    border-collapse: collapse;
}

.premium-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.premium-table th,
.premium-table td {
    padding: 12px 11px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: top;
}

.premium-table th {
    color: #334155;
    font-size: 11px;
    font-weight: 950;
    white-space: nowrap;
}

.premium-table td {
    color: #0f172a;
    font-size: 12.5px;
}

.premium-table tbody tr:hover {
    background: #fafcff;
}

.student-name {
    font-weight: 900;
    color: #0f172a;
    white-space: nowrap;
}

.student-nim {
    color: #64748b;
    font-weight: 800;
    white-space: nowrap;
}

.judul-cell {
    max-width: 370px;
    line-height: 1.45;
    font-weight: 760;
    word-break: break-word;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.file-cell {
    max-width: 270px;
}

.file-title {
    font-weight: 850;
    color: #0f172a;
    line-height: 1.35;
    word-break: break-word;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.file-meta {
    margin-top: 4px;
    color: #94a3b8;
    font-size: 10.5px;
    line-height: 1.35;
    word-break: break-word;
}

.file-actions {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-top: 8px;
}

.file-btn {
    height: 29px;
    padding: 0 9px;
    border-radius: 10px;
    font-size: 11px;
    font-weight: 850;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.file-open {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.file-download {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.status-text {
    display: inline-flex;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.status-direview {
    background: #fef3c7;
    color: #92400e;
}

.status-disetujui {
    background: #dcfce7;
    color: #166534;
}

.status-revisi {
    background: #fee2e2;
    color: #b91c1c;
}

.status-ditolak {
    background: #f1f5f9;
    color: #475569;
}

.catatan-cell {
    max-width: 240px;
    color: #475569;
    line-height: 1.45;
    word-break: break-word;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.date-text {
    color: #475569;
    font-weight: 750;
    white-space: nowrap;
}

.action-group {
    display: flex;
    gap: 7px;
    flex-wrap: nowrap;
}

.btn-action {
    height: 32px;
    padding: 0 10px;
    border-radius: 11px;
    border: 0;
    cursor: pointer;
    font-size: 11.5px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.btn-detail {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.action-group {
    display: flex;
    gap: 6px;
    align-items: center;
}

.icon-action {
    width: 34px;
    height: 34px;
    border-radius: 12px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    transition: .2s ease;
}

/* warna beda biar jelas */
.icon-open {
    background: #eff6ff;
    color: #2563eb;
}

.icon-download {
    background: #ecfeff;
    color: #0891b2;
}

.icon-detail {
    background: #f1f5f9;
    color: #475569;
}

.icon-edit {
    background: #2563eb;
    color: #fff;
}

.icon-delete {
    background: #fff5f5;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.icon-action:hover {
    transform: scale(1.08);
}

/* MOBILE */
@media (max-width: 768px) {
    .icon-action {
        width: 30px;
        height: 30px;
        font-size: 13px;
        border-radius: 10px;
    }
}

.btn-edit {
    background: #2563eb;
    color: #fff;
}

.btn-delete {
    background: #fff5f5;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.premium-empty {
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 18px;
    padding: 24px;
    text-align: center;
    color: #64748b;
    font-size: 13px;
    line-height: 1.7;
}

.pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 14px;
}

.pagination-info {
    color: #64748b;
    font-size: 12px;
    font-weight: 750;
}

.pagination-links {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.page-link {
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 11px;
    border: 1px solid #dbe3ef;
    background: #fff;
    color: #334155;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 850;
    font-size: 12px;
}

.page-link.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

@media (max-width: 768px) {
    .riwayat-proposal-page {
        gap: 12px;
    }

    .soft-hero {
        padding: 15px;
        border-radius: 18px;
    }

    .soft-hero h2 {
        font-size: 18px;
    }

    .soft-hero p {
        font-size: 11.5px;
    }

    .premium-stats {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 7px;
    }

    .premium-stat-card {
        padding: 11px 8px;
        border-radius: 16px;
        min-height: 82px;
    }

    .premium-stat-label {
        font-size: 9.5px;
        margin-bottom: 6px;
    }

    .premium-stat-value {
        font-size: 19px;
        margin-bottom: 3px;
    }

    .premium-stat-desc {
        display: none;
    }

    .card-premium {
        padding: 13px;
        border-radius: 18px;
    }

    .section-title-premium {
        font-size: 17px;
    }

    .section-subtitle-premium {
        font-size: 11.5px;
        margin-bottom: 12px;
    }

    .inline-filter-wrap {
        padding: 10px;
        border-radius: 16px;
    }

    .filter-grid {
        grid-template-columns: 1.35fr .8fr .7fr;
        gap: 7px;
    }

    .filter-actions {
        display: flex;
        grid-column: 1 / -1;
        gap: 7px;
    }

    .btn-filter {
        height: 36px;
        padding: 0 11px;
        font-size: 11.5px;
    }

    .form-group-premium label {
        font-size: 10px;
    }

    .form-group-premium input,
    .form-group-premium select {
        height: 36px;
        font-size: 11px;
        padding: 7px 9px;
    }

    .premium-table {
        min-width: 960px;
    }

    .premium-table th,
    .premium-table td {
        padding: 10px 8px;
        font-size: 11.5px;
    }

    .premium-table th {
        font-size: 10.5px;
    }

    .judul-cell {
        max-width: 250px;
        font-size: 11.5px;
        -webkit-line-clamp: 3;
        line-clamp: 3;
    }

    .file-cell {
        max-width: 220px;
    }

    .file-title {
        font-size: 11.5px;
    }

    .file-meta {
        font-size: 10px;
    }

    .catatan-cell {
        max-width: 190px;
        font-size: 11px;
    }

    .status-text {
        font-size: 10px;
        padding: 5px 8px;
    }

    .date-text,
    .student-name,
    .student-nim {
        font-size: 11.5px;
    }

    .btn-action,
    .file-btn {
        height: 28px;
        padding: 0 8px;
        font-size: 10.5px;
    }

    .pagination-info,
    .pagination-links {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
}
</style>

<div class="riwayat-proposal-page">
    <div class="premium-stats">
        <div class="premium-stat-card stat-blue">
            <div class="premium-stat-label">Total Riwayat</div>
            <div class="premium-stat-value"><?= esc($safe($totalRows, '0')) ?></div>
            <div class="premium-stat-desc">Sesuai filter yang dipilih</div>
        </div>

        <div class="premium-stat-card stat-emerald">
            <div class="premium-stat-label">Disetujui</div>
            <div class="premium-stat-value"><?= esc($safe($totalDisetujui, '0')) ?></div>
            <div class="premium-stat-desc">Review diterima</div>
        </div>

        <div class="premium-stat-card stat-amber">
            <div class="premium-stat-label">Tolak / Revisi</div>
            <div class="premium-stat-value"><?= esc($safe($totalTolakRevisi, '0')) ?></div>
            <div class="premium-stat-desc">Perlu tindak lanjut</div>
        </div>
    </div>

    <div class="card-premium">
        <h3 class="section-title-premium">Data Riwayat Proposal</h3>
        <p class="section-subtitle-premium">
            Cari berdasarkan mahasiswa, NIM, judul, atau nama file proposal.
        </p>

        <div class="inline-filter-wrap">
            <form method="get" action="<?= base_url('/dosen/proposal-ta/riwayat') ?>">
                <div class="filter-grid">
                    <div class="form-group-premium">
                        <label for="q">Cari Data</label>
                        <input
                            type="text"
                            id="q"
                            name="q"
                            value="<?= esc($safe($keyword, '')) ?>"
                            placeholder="Nama / NIM / Judul / File"
                        >
                    </div>

                    <div class="form-group-premium">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">Semua</option>
                            <option value="direview" <?= $status === 'direview' ? 'selected' : '' ?>>Direview</option>
                            <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="revisi" <?= $status === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                            <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </div>

                    <div class="form-group-premium">
                        <label for="per_page">Tampil</label>
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
                </div>
            </form>
        </div>

        <?php if (! empty($riwayat) && is_array($riwayat)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
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
                                $fileAsli = $safe($row['nama_file_asli'] ?? '', '');
                                $proposalId = $safe($row['proposal_ta_id'] ?? ($row['proposal_id'] ?? ($row['id'] ?? '')), '');
                                $reviewId = $safe($row['id'] ?? '', '');

                                /*
                                 * Kalau folder upload kamu beda, ganti bagian ini:
                                 * uploads/proposal/
                                 */
                                $fileUrl = $fileProposal !== ''
                                    ? base_url('uploads/proposal/' . $fileProposal)
                                    : '';
                            ?>

                            <tr>
                                <td>
                                    <div class="student-name"><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></div>
                                </td>

                                <td>
                                    <div class="student-nim"><?= esc($safe($row['nim'] ?? '-')) ?></div>
                                </td>

                                <td>
                                    <div class="judul-cell"><?= esc($safe($row['judul'] ?? '-')) ?></div>
                                </td>

                                <td>
                                    <div class="file-cell">
                                        <div class="file-title">
                                            <?= esc($fileAsli !== '' ? $fileAsli : '-') ?>
                                        </div>

                                        <?php if ($fileUrl !== ''): ?>
                                            <div class="file-actions">
                                                <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="file-btn file-open">
                                                    Buka
                                                </a>

                                                <a href="<?= esc($fileUrl) ?>" download class="file-btn file-download">
                                                    Download
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-text <?= esc($statusClass($row['status_review'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status_review'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="catatan-cell">
                                        <?= esc($safe(($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="date-text"><?= esc($safe($row['created_at'] ?? '-')) ?></span>
                                </td>

                                <td>
                                    <div class="action-group">
                                    <a href="<?= base_url('/dosen/proposal-ta/detail/' . $proposalId) ?>"
                                    class="icon-action icon-detail"
                                    title="Detail">
                                        👁
                                    </a>

                                    <a href="<?= base_url('/dosen/proposal-ta/review/edit/' . $reviewId) ?>"
                                    class="icon-action icon-edit"
                                    title="Edit">
                                        ✎
                                    </a>

                                    <form
                                        action="<?= base_url('/dosen/proposal-ta/review/delete/' . $reviewId) ?>"
                                        method="post"
                                        onsubmit="return confirm('Hapus review ini?');"
                                        style="display:inline;"
                                    >
                                        <?= csrf_field() ?>
                                        <button type="submit" class="icon-action icon-delete" title="Hapus">
                                            🗑
                                        </button>
                                    </form>

                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">
                Belum ada riwayat review proposal yang cocok dengan filter.
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
    </div>
</div>

<?= $this->endSection() ?>