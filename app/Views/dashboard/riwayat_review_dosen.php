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
        'disetujui' => 'status-disetujui',
        'revisi'    => 'status-revisi',
        'ditolak'   => 'status-ditolak',
        default     => 'status-direview',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'Disetujui',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        'direview'  => 'Direview',
        default     => $safe($status, '-'),
    };
};

$queryParams = [
    'q'        => $keyword,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    return base_url('/dosen/pengajuan-judul/riwayat?' . http_build_query(array_merge($queryParams, [
        'page' => $targetPage,
    ])));
};
?>

<style>
.review-page {
    display: flex;
    flex-direction: column;
    gap: 14px;
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

.filter-actions {
    display: contents;
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
    border-radius: 18px;
    border: 1px solid #eef2f7;
    background: #fff;
}

.premium-table {
    width: 100%;
    min-width: 980px;
    border-collapse: collapse;
}

.premium-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.premium-table th,
.premium-table td {
    padding: 12px 10px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: top;
}

.premium-table th {
    font-size: 11px;
    font-weight: 950;
    color: #334155;
    white-space: nowrap;
}

.premium-table td {
    color: #0f172a;
    font-size: 12px;
}

.premium-table tbody tr:hover {
    background: #fafcff;
}

.student-name {
    font-weight: 900;
    white-space: nowrap;
}

.student-nim {
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.judul-cell {
    max-width: 300px;
    line-height: 1.45;
    font-weight: 750;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.status-badge {
    display: inline-flex;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 950;
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

.note-cell {
    max-width: 145px;
    line-height: 1.45;
    color: #475569;
    font-size: 11px;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
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

.date-text {
    color: #475569;
    font-weight: 750;
    white-space: nowrap;
    font-size: 11px;
}

.action-group {
    display: flex;
    gap: 6px;
    flex-wrap: nowrap;
    align-items: center;
}

.icon-action {
    width: 31px;
    height: 31px;
    border-radius: 11px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    cursor: pointer;
    font-size: 14px;
    font-weight: 900;
    font-family: inherit;
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

@media (max-width: 768px) {
    .review-page {
        gap: 12px;
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
        min-width: 900px;
    }

    .premium-table th,
    .premium-table td {
        padding: 9px 7px;
        font-size: 11px;
    }

    .premium-table th {
        font-size: 10px;
    }

    .judul-cell {
        max-width: 220px;
        font-size: 11px;
        -webkit-line-clamp: 3;
        line-clamp: 3;
    }

    .note-cell {
        max-width: 95px;
        font-size: 10.5px;
        -webkit-line-clamp: 2;
        line-clamp: 2;
    }

    .note-btn {
        font-size: 9.5px;
        padding: 4px 7px;
    }

    .status-badge {
        font-size: 9.5px;
        padding: 5px 7px;
    }

    .student-name,
    .student-nim,
    .date-text {
        font-size: 10.5px;
    }

    .icon-action {
        width: 29px;
        height: 29px;
        font-size: 13px;
        border-radius: 10px;
    }

    .pagination-info,
    .pagination-links {
        width: 100%;
        justify-content: center;
        text-align: center;
    }
}
</style>

<div class="review-page">

    <section class="card-premium">
        <h3 class="section-title-premium">Riwayat Review Judul</h3>
        <p class="section-subtitle-premium">
            Cari review berdasarkan mahasiswa, NIM, judul, atau status review.
        </p>

        <div class="inline-filter-wrap">
            <form method="get" action="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>">
                <div class="filter-grid">
                    <div class="form-group-premium">
                        <label for="q">Cari Mahasiswa / NIM / Judul</label>
                        <input
                            type="text"
                            id="q"
                            name="q"
                            value="<?= esc($safe($keyword, '')) ?>"
                            placeholder="Cari data pengajuan"
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
                        <label for="per_page">Tampilkan</label>
                        <select id="per_page" name="per_page">
                            <option value="10" <?= (int) $perPage === 10 ? 'selected' : '' ?>>10</option>
                            <option value="50" <?= (int) $perPage === 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= (int) $perPage === 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                        <a href="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>" class="btn-filter btn-reset">Reset</a>
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
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Waktu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayat as $row): ?>
                            <?php $catatanText = $safe(($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-', '-'); ?>

                            <tr>
                                <td>
                                    <div class="student-name">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="student-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="judul-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge <?= esc($statusClass($row['status_review'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status_review'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="note-cell">
                                        <?= esc($catatanText) ?>
                                    </div>

                                    <?php if ($catatanText !== '-'): ?>
                                        <button
                                            type="button"
                                            class="note-btn"
                                            onclick="openNoteModal(this)"
                                            data-note="<?= esc($catatanText) ?>"
                                        >
                                            Lihat
                                        </button>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="date-text">
                                        <?= esc($safe($row['created_at'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            href="<?= base_url('/dosen/pengajuan-judul/review/edit/' . $safe($row['id'] ?? '')) ?>"
                                            class="icon-action icon-edit"
                                            title="Edit"
                                        >
                                            ✎
                                        </a>

                                        <form
                                            action="<?= base_url('/dosen/pengajuan-judul/review/delete/' . $safe($row['id'] ?? '')) ?>"
                                            method="post"
                                            onsubmit="return confirm('Hapus review ini?');"
                                            style="display:inline;"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="icon-action icon-delete" title="Hapus">
                                                ×
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
                Belum ada riwayat review judul yang cocok dengan filter.
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