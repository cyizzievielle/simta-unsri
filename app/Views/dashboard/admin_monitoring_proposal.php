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

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'badge-diajukan',
        'direview'  => 'badge-direview',
        'revisi'    => 'badge-revisi',
        'ditolak'   => 'badge-ditolak',
        'disetujui' => 'badge-disetujui',
        default     => 'badge-diajukan',
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

<style>
.monitoring-page {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.stats-grid-premium {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 10px;
}

.stat-card-premium {
    border-radius: 20px;
    padding: 15px;
    color: #fff;
    min-height: 105px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 12px 28px rgba(15, 23, 42, .10);
}

.stat-card-premium::after {
    content: "";
    position: absolute;
    right: -20px;
    bottom: -20px;
    width: 80px;
    height: 80px;
    background: rgba(255,255,255,.13);
    border-radius: 999px;
}

.stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }
.stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
.stat-rose { background: linear-gradient(135deg, #e11d48, #fb7185); }
.stat-slate { background: linear-gradient(135deg, #334155, #475569); }

.stat-label-premium,
.stat-value-premium,
.stat-desc-premium {
    position: relative;
    z-index: 1;
}

.stat-label-premium {
    font-size: 11px;
    font-weight: 850;
    margin-bottom: 7px;
}

.stat-value-premium {
    font-size: 28px;
    font-weight: 950;
    line-height: 1;
    margin-bottom: 5px;
}

.stat-desc-premium {
    font-size: 11px;
    line-height: 1.35;
    opacity: .92;
}

.card-premium {
    background: #fff;
    border-radius: 24px;
    padding: 18px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    border: 1px solid #eef2f7;
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
    line-height: 1.55;
    font-size: 12.5px;
}

.filter-wrap {
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

.filter-actions {
    display: contents;
}

.form-group-premium label {
    display: block;
    font-size: 11px;
    margin-bottom: 5px;
    font-weight: 850;
    color: #334155;
}

.form-group-premium input,
.form-group-premium select {
    width: 100%;
    height: 38px;
    padding: 8px 10px;
    border-radius: 12px;
    font-size: 12px;
    border: 1px solid #dbe3ef;
    background: #fff;
    box-sizing: border-box;
    outline: none;
}

.form-group-premium input:focus,
.form-group-premium select:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
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

.table-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.table-meta-text {
    color: #64748b;
    font-size: 12px;
    font-weight: 750;
}

.premium-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 18px;
    border: 1px solid #eef2f7;
    background: #fff;
}

.premium-table {
    width: 100%;
    min-width: 1180px;
    border-collapse: collapse;
    background: #fff;
}

.premium-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.premium-table th,
.premium-table td {
    padding: 11px 9px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: top;
    color: #0f172a;
    font-size: 12px;
    line-height: 1.4;
}

.premium-table th {
    font-size: 10.5px;
    font-weight: 950;
    color: #334155;
    white-space: nowrap;
}

.student-name {
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 2px;
    white-space: nowrap;
    max-width: 140px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.student-sub {
    color: #64748b;
    font-size: 10.5px;
    white-space: nowrap;
}

.judul-cell {
    max-width: 280px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.45;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.file-cell {
    max-width: 250px;
}

.file-name {
    font-weight: 850;
    line-height: 1.35;
    font-size: 11.5px;
    color: #0f172a;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.muted-text {
    color: #94a3b8;
    font-size: 10px;
    line-height: 1.35;
    margin-top: 4px;
    word-break: break-word;
}

.file-actions {
    margin-top: 7px;
    display: flex;
    gap: 6px;
    flex-wrap: nowrap;
}

.icon-btn {
    width: 30px;
    height: 30px;
    border-radius: 11px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    font-size: 13px;
    font-weight: 900;
    font-family: inherit;
    line-height: 1;
    padding: 0;
}

.icon-open {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.icon-download {
    background: #ecfeff;
    color: #0891b2;
    border: 1px solid #a5f3fc;
}

.cell-nowrap {
    white-space: nowrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 950;
    line-height: 1;
    white-space: nowrap;
}

.badge-diajukan { background: #dbeafe; color: #1d4ed8; }
.badge-direview { background: #fef3c7; color: #92400e; }
.badge-revisi { background: #fef3c7; color: #92400e; }
.badge-ditolak { background: #fee2e2; color: #b91c1c; }
.badge-disetujui { background: #dcfce7; color: #166534; }

.catatan-cell {
    max-width: 220px;
    color: #475569;
    font-size: 11px;
    line-height: 1.45;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.note-btn {
    margin-top: 6px;
    border: 0;
    background: #eff6ff;
    color: #2563eb;
    border-radius: 999px;
    padding: 5px 8px;
    font-size: 10px;
    font-weight: 900;
    cursor: pointer;
}

.premium-empty {
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 18px;
    padding: 24px;
    text-align: center;
    color: #64748b;
    line-height: 1.7;
    font-size: 13px;
}

.pagination-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
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
    gap: 7px;
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

.note-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, .42);
    backdrop-filter: blur(5px);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 18px;
    z-index: 9999;
}

.note-modal-overlay.show {
    display: flex;
}

.note-modal {
    width: min(380px, 100%);
    background: #fff;
    border-radius: 24px;
    padding: 20px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 24px 70px rgba(15, 23, 42, .22);
}

.note-modal h3 {
    margin: 0 0 8px;
    font-size: 18px;
    font-weight: 900;
    color: #0f172a;
}

.note-modal p {
    margin: 0;
    color: #475569;
    font-size: 13px;
    line-height: 1.7;
    white-space: pre-wrap;
}

.note-modal-close {
    margin-top: 16px;
    width: 100%;
    height: 38px;
    border-radius: 13px;
    border: 0;
    background: #2563eb;
    color: #fff;
    font-weight: 900;
    cursor: pointer;
}

@media(max-width: 1200px) {
    .stats-grid-premium {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media(max-width: 760px) {
    .monitoring-page {
        gap: 12px;
    }

    .stats-grid-premium {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 7px;
    }

    .stat-card-premium {
        min-height: 78px;
        padding: 11px 8px;
        border-radius: 16px;
    }

    .stat-label-premium {
        font-size: 9.2px;
        margin-bottom: 5px;
    }

    .stat-value-premium {
        font-size: 19px;
    }

    .stat-desc-premium {
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

    .filter-wrap {
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

    .form-group-premium label {
        font-size: 10px;
    }

    .form-group-premium input,
    .form-group-premium select {
        height: 36px;
        font-size: 11px;
        padding: 7px 9px;
    }

    .btn-filter {
        height: 36px;
        padding: 0 11px;
        font-size: 11.5px;
    }

    .premium-table {
        min-width: 1150px;
    }

    .premium-table th,
    .premium-table td {
        padding: 8px 7px;
        font-size: 10.8px;
    }

    .premium-table th {
        font-size: 9.8px;
    }

    .student-name {
        max-width: 115px;
        font-size: 10.8px;
    }

    .student-sub,
    .muted-text {
        font-size: 9.5px;
    }

    .judul-cell {
        max-width: 210px;
        font-size: 10.8px;
    }

    .file-cell {
        max-width: 190px;
    }

    .file-name {
        font-size: 10.8px;
    }

    .status-badge {
        padding: 5px 7px;
        font-size: 9.2px;
    }

    .catatan-cell {
        max-width: 150px;
        font-size: 10.5px;
    }

    .icon-btn {
        width: 28px;
        height: 28px;
        font-size: 12px;
        border-radius: 10px;
    }

    .pagination-info,
    .pagination-links {
        width: 100%;
        justify-content: center;
        text-align: center;
    }

    .pagination-links {
        overflow-x: auto;
        flex-wrap: nowrap;
        padding-bottom: 4px;
    }

    .page-link {
        min-width: 31px;
        height: 31px;
        font-size: 10.5px;
        flex-shrink: 0;
    }
}
</style>

<div class="monitoring-page">

    <div class="stats-grid-premium">
        <div class="stat-card-premium stat-blue">
            <div class="stat-label-premium">Total Data</div>
            <div class="stat-value-premium"><?= esc($safe($summary['total'] ?? 0, '0')) ?></div>
            <div class="stat-desc-premium">Seluruh proposal tugas akhir</div>
        </div>

        <div class="stat-card-premium stat-amber">
            <div class="stat-label-premium">Diajukan</div>
            <div class="stat-value-premium"><?= esc($safe($summary['diajukan'] ?? 0, '0')) ?></div>
            <div class="stat-desc-premium">Menunggu review dosen</div>
        </div>

        <div class="stat-card-premium stat-emerald">
            <div class="stat-label-premium">Disetujui</div>
            <div class="stat-value-premium"><?= esc($safe($summary['disetujui'] ?? 0, '0')) ?></div>
            <div class="stat-desc-premium">Proposal diterima</div>
        </div>

        <div class="stat-card-premium stat-rose">
            <div class="stat-label-premium">Revisi</div>
            <div class="stat-value-premium"><?= esc($safe($summary['revisi'] ?? 0, '0')) ?></div>
            <div class="stat-desc-premium">Perlu perbaikan</div>
        </div>

        <div class="stat-card-premium stat-slate">
            <div class="stat-label-premium">Ditolak</div>
            <div class="stat-value-premium"><?= esc($safe($summary['ditolak'] ?? 0, '0')) ?></div>
            <div class="stat-desc-premium">Tidak dilanjutkan</div>
        </div>
    </div>

    <div class="card-premium">
        <h3 class="section-title-premium">Data Monitoring Proposal</h3>
        <p class="section-subtitle-premium">
            Cari, filter, dan pantau proposal mahasiswa dari satu tabel yang lebih rapi dan konsisten.
        </p>

        <div class="filter-wrap">
            <form method="get" action="<?= site_url('admin/monitoring-proposal') ?>">
                <div class="filter-grid">
                    <div class="form-group-premium">
                        <label for="q">Cari Mahasiswa / NIM / Judul / File</label>
                        <input type="text" id="q" name="q" value="<?= esc($safe($keyword, '')) ?>" placeholder="Cari data proposal...">
                    </div>

                    <div class="form-group-premium">
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

                    <div class="form-group-premium">
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
                </div>
            </form>
        </div>

        <div class="table-meta">
            <div class="table-meta-text">
                Menampilkan <?= esc($safe($startRow, '0')) ?> - <?= esc($safe($endRow, '0')) ?> dari <?= esc($safe($totalRows, '0')) ?> data
            </div>
        </div>

        <?php if (! empty($rows) && is_array($rows)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
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
                                        <div class="file-name">
                                            <?= esc($fileAsli !== '' ? $fileAsli : '-') ?>
                                        </div>

                                        <div class="muted-text">
                                            <?= esc($fileProposal !== '' ? $fileProposal : '-') ?>
                                        </div>

                                        <?php if ($fileUrl !== ''): ?>
                                            <div class="file-actions">
                                                <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="icon-btn icon-open" title="Buka File">
                                                    📄
                                                </a>

                                                <a href="<?= esc($fileUrl) ?>" download class="icon-btn icon-download" title="Download">
                                                    ⬇
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="cell-nowrap">
                                    v<?= esc($safe($row['versi_ke'] ?? '-')) ?>
                                </td>

                                <td class="cell-nowrap">
                                    <span class="status-badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($safe($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td class="cell-nowrap">
                                    <?= esc($safe($row['tanggal_upload'] ?? '-')) ?>
                                </td>

                                <td class="cell-nowrap">
                                    <?= esc($safe($row['tanggal_review'] ?? '-')) ?>
                                </td>

                                <td>
                                    <div class="catatan-cell">
                                        <?= esc($catatan) ?>
                                    </div>

                                    <?php if ($catatan !== '-'): ?>
                                        <button
                                            type="button"
                                            class="note-btn"
                                            onclick="openNoteModal(this)"
                                            data-note="<?= esc($catatan) ?>"
                                        >
                                            Lihat
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">
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

<div class="note-modal-overlay" id="noteModal" onclick="closeNoteModal(event)">
    <div class="note-modal">
        <h3>Catatan Review</h3>
        <p id="noteModalText">-</p>
        <button type="button" class="note-modal-close" onclick="closeNoteModal()">Tutup</button>
    </div>
</div>

<script>
function openNoteModal(button) {
    const modal = document.getElementById('noteModal');
    const text = document.getElementById('noteModalText');

    if (!modal || !text) return;

    text.textContent = button.dataset.note || '-';
    modal.classList.add('show');
}

function closeNoteModal(event) {
    const modal = document.getElementById('noteModal');

    if (!modal) return;

    if (!event || event.target === modal) {
        modal.classList.remove('show');
    }
}
</script>

<?= $this->endSection() ?>