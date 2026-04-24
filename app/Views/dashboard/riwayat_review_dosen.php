<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        color: #fff;
        border-radius: 28px;
        padding: 28px 30px;
        margin-bottom: 22px;
        box-shadow: 0 18px 40px rgba(37, 99, 235, 0.18);
        position: relative;
        overflow: hidden;
    }

    .page-hero::after {
        content: "";
        position: absolute;
        right: -45px;
        top: -45px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .page-hero h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .page-hero p {
        margin: 0;
        color: rgba(255,255,255,0.92);
        line-height: 1.7;
        position: relative;
        z-index: 1;
    }

    .premium-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .premium-stat-card {
        border-radius: 24px;
        padding: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.10);
    }

    .premium-stat-card::after {
        content: "";
        position: absolute;
        right: -18px;
        bottom: -18px;
        width: 85px;
        height: 85px;
        background: rgba(255,255,255,0.10);
        border-radius: 50%;
    }

    .stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
    .stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }

    .premium-stat-label {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .premium-stat-value {
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .premium-stat-desc {
        font-size: 12px;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        margin-bottom: 22px;
    }

    .section-title-premium {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-subtitle-premium {
        margin: 0 0 16px;
        color: #64748b;
        line-height: 1.7;
    }

    .inline-filter-wrap {
        border: 1px solid #eef2f7;
        background: linear-gradient(135deg, #f8fbff, #f1f5f9);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 1.8fr 1fr 1fr auto;
        gap: 14px;
        align-items: end;
    }

    .form-group-premium label {
        display: block;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-group-premium input,
    .form-group-premium select {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 13px 15px;
        font-size: 14px;
        background: #fff;
        outline: none;
        box-sizing: border-box;
    }

    .filter-actions {
        display: flex;
        gap: 10px;
        align-items: end;
        flex-wrap: wrap;
    }

    .premium-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid #eef2f7;
    }

    .premium-table {
        width: 100%;
        min-width: 1100px;
        border-collapse: collapse;
        background: #fff;
    }

    .premium-table thead tr {
        background: linear-gradient(135deg, #f8fbff, #eff6ff);
    }

    .premium-table th,
    .premium-table td {
        padding: 16px 14px;
        text-align: left;
        border-bottom: 1px solid #eef2f7;
        vertical-align: top;
    }

    .premium-table th {
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        white-space: nowrap;
    }

    .premium-table td {
        color: #0f172a;
        font-size: 14px;
    }

    .judul-cell {
        font-weight: 700;
        line-height: 1.65;
        max-width: 520px;
    }

    .status-text {
        font-weight: 800;
    }

    .status-direview { color: #b45309; }
    .status-disetujui { color: #15803d; }
    .status-revisi { color: #dc2626; }
    .status-ditolak { color: #475569; }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 28px;
        text-align: center;
        color: #64748b;
        line-height: 1.8;
    }

    .pagination-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .pagination-info {
        color: #64748b;
        font-size: 14px;
    }

    .pagination-links {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .page-link {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border-radius: 12px;
        border: 1px solid #dbe3ef;
        background: #fff;
        color: #334155;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .page-link.active {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }

    @media (max-width: 1100px) {
        .premium-stats,
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

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

    $statusClass = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'direview'  => 'status-direview',
            'disetujui' => 'status-disetujui',
            'revisi'    => 'status-revisi',
            'ditolak'   => 'status-ditolak',
            default     => 'status-direview',
        };
    };

    $queryParams = [
        'q'        => $keyword,
        'status'   => $status,
        'per_page' => $perPage,
    ];

    $buildPageUrl = static function (int $targetPage) use ($queryParams) {
        $params = array_merge($queryParams, ['page' => $targetPage]);
        return base_url('/dosen/pengajuan-judul/riwayat?' . http_build_query($params));
    };
?>

<div class="premium-stats">
    <div class="premium-stat-card stat-blue">
        <div class="premium-stat-label">Total Riwayat</div>
        <div class="premium-stat-value"><?= esc((string) $totalRows) ?></div>
        <div class="premium-stat-desc">Sesuai filter yang dipilih</div>
    </div>

    <div class="premium-stat-card stat-emerald">
        <div class="premium-stat-label">Disetujui</div>
        <div class="premium-stat-value"><?= esc((string) $totalDisetujui) ?></div>
        <div class="premium-stat-desc">Di halaman ini</div>
    </div>

    <div class="premium-stat-card stat-amber">
        <div class="premium-stat-label">Tolak / Revisi</div>
        <div class="premium-stat-value"><?= esc((string) $totalTolakRevisi) ?></div>
        <div class="premium-stat-desc">Di halaman ini</div>
    </div>
</div>

<div class="card-premium">
    <h3 class="section-title-premium">Riwayat Review Judul</h3>
    <p class="section-subtitle-premium">Search, filter, dan jumlah data per halaman bisa diatur langsung di sini.</p>

    <div class="inline-filter-wrap">
        <form method="get" action="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>">
            <div class="filter-grid">
                <div class="form-group-premium">
                    <label for="q">Cari Nama / NIM / Judul</label>
                    <input type="text" id="q" name="q" value="<?= esc((string) $keyword) ?>" placeholder="Cari data review...">
                </div>

                <div class="form-group-premium">
                    <label for="status">Status Review</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
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
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <?php if (! empty($riwayat)): ?>
        <div class="premium-table-wrap">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>NIM</th>
                        <th>Judul</th>
                        <th>Status Review</th>
                        <th>Catatan</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat as $row): ?>
                        <tr>
                            <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                            <td class="judul-cell"><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                            <td>
                                <span class="status-text <?= $statusClass($row['status_review'] ?? '') ?>">
                                    <?= esc((string) ($row['status_review'] ?? '-')) ?>
                                </span>
                            </td>
                            <td><?= esc((string) (($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-')) ?></td>
                            <td><?= esc((string) ($row['created_at'] ?? '-')) ?></td>
                            <td>
                            <div style="display:flex; gap:8px; flex-wrap:wrap;">
                                <a href="<?= base_url('/dosen/pengajuan-judul/review/edit/' . (string) ($row['id'] ?? '')) ?>"
                                class="btn btn-primary" style="padding:8px 12px; font-size:13px;">
                                    ✎ Edit
                                </a>

                                <form action="<?= base_url('/dosen/pengajuan-judul/review/delete/' . (string) ($row['id'] ?? '')) ?>" method="post" onsubmit="return confirm('Hapus review ini?');" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn" style="padding:8px 12px; font-size:13px; border:1px solid #fecaca; color:#b91c1c; background:#fff5f5;">
                                        🗑 Hapus
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
        <div class="premium-empty">Belum ada riwayat review judul yang cocok dengan filter.</div>
    <?php endif; ?>

    <div class="pagination-bar">
        <div class="pagination-info">
            Menampilkan <?= esc((string) $startRow) ?> - <?= esc((string) $endRow) ?> dari <?= esc((string) $totalRows) ?> data
        </div>

        <div class="pagination-links">
            <?php if ($page > 1): ?>
                <a class="page-link" href="<?= $buildPageUrl($page - 1) ?>">&laquo;</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="page-link <?= $i === (int) $page ? 'active' : '' ?>" href="<?= $buildPageUrl($i) ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a class="page-link" href="<?= $buildPageUrl($page + 1) ?>">&raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>