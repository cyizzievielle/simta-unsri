<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        margin-bottom: 22px;
    }

    .section-title-premium {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-subtitle-premium {
        margin: 0 0 18px;
        color: #64748b;
        line-height: 1.7;
    }

    .stats-grid-premium {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .stat-card-premium {
        border-radius: 24px;
        padding: 22px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.10);
    }

    .stat-card-premium::after {
        content: "";
        position: absolute;
        right: -18px;
        bottom: -18px;
        width: 84px;
        height: 84px;
        background: rgba(255,255,255,0.10);
        border-radius: 50%;
    }

    .stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
    .stat-rose { background: linear-gradient(135deg, #e11d48, #fb7185); }
    .stat-slate { background: linear-gradient(135deg, #334155, #475569); }

    .stat-label-premium {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .stat-value-premium {
        font-size: 34px;
        font-weight: 800;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .stat-desc-premium {
        font-size: 12px;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .filter-wrap {
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
        box-sizing: border-box;
        outline: none;
    }

    .form-group-premium input:focus,
    .form-group-premium select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
    }

    .filter-actions {
        display: flex;
        gap: 10px;
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
        min-width: 1300px;
        border-collapse: collapse;
        background: #fff;
    }

    .premium-table thead tr {
        background: linear-gradient(135deg, #f8fbff, #eff6ff);
    }

    .premium-table th,
    .premium-table td {
        padding: 15px 14px;
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
        line-height: 1.65;
    }

    .student-name {
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .student-sub {
        color: #64748b;
        font-size: 13px;
    }

    .judul-cell {
        max-width: 420px;
        line-height: 1.7;
        font-weight: 700;
        color: #0f172a;
    }

    .muted-text {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .badge-draft { background: #e2e8f0; color: #475569; }
    .badge-diajukan { background: #dbeafe; color: #1d4ed8; }
    .badge-direview { background: #fef3c7; color: #92400e; }
    .badge-revisi { background: #fee2e2; color: #b91c1c; }
    .badge-ditolak { background: #fecdd3; color: #be123c; }
    .badge-disetujui { background: #dcfce7; color: #166534; }

    .table-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
    }

    .table-meta-text {
        color: #64748b;
        font-size: 14px;
    }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 24px;
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

    @media (max-width: 1200px) {
        .stats-grid-premium,
        .filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $rows = $rows ?? [];
    $summary = $summary ?? [];
    $keyword = $keyword ?? '';
    $status = $status ?? '';
    $perPage = $perPage ?? 10;
    $page = $page ?? 1;
    $totalRows = $totalRows ?? 0;
    $totalPages = $totalPages ?? 1;
    $startRow = $startRow ?? 0;
    $endRow = $endRow ?? 0;

    $badgeStatus = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'draft'      => 'badge-draft',
            'diajukan'   => 'badge-diajukan',
            'direview'   => 'badge-direview',
            'revisi'     => 'badge-revisi',
            'ditolak'    => 'badge-ditolak',
            'disetujui'  => 'badge-disetujui',
            default      => 'badge-draft',
        };
    };

    $queryParams = [
        'q'        => $keyword,
        'status'   => $status,
        'per_page' => $perPage,
    ];

    $buildPageUrl = static function (int $targetPage) use ($queryParams) {
        $params = array_merge($queryParams, ['page' => $targetPage]);
        return base_url('/admin/monitoring-judul?' . http_build_query($params));
    };
?>

<div class="stats-grid-premium">
    <div class="stat-card-premium stat-blue">
        <div class="stat-label-premium">Total Data</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['total'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Seluruh pengajuan judul</div>
    </div>

    <div class="stat-card-premium stat-amber">
        <div class="stat-label-premium">Direview</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['direview'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Sedang diproses dosen</div>
    </div>

    <div class="stat-card-premium stat-emerald">
        <div class="stat-label-premium">Disetujui</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['disetujui'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Sudah final disetujui</div>
    </div>

    <div class="stat-card-premium stat-rose">
        <div class="stat-label-premium">Revisi</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['revisi'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Perlu perbaikan mahasiswa</div>
    </div>

    <div class="stat-card-premium stat-slate">
        <div class="stat-label-premium">Ditolak</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['ditolak'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Tidak dilanjutkan</div>
    </div>
</div>

<div class="card-premium">
    <h3 class="section-title-premium">Data Monitoring Judul</h3>
    <p class="section-subtitle-premium">Cari, filter, dan pantau seluruh pengajuan judul mahasiswa dari satu tabel yang lebih rapih.</p>

    <div class="filter-wrap">
        <form method="get" action="<?= base_url('/admin/monitoring-judul') ?>">
            <div class="filter-grid">
                <div class="form-group-premium">
                    <label for="q">Cari Mahasiswa / NIM / Judul</label>
                    <input type="text" id="q" name="q" value="<?= esc((string) $keyword) ?>" placeholder="Cari data pengajuan judul...">
                </div>

                <div class="form-group-premium">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>Draft</option>
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
                    <button type="submit" class="btn btn-primary">Terapkan</button>
                    <a href="<?= base_url('/admin/monitoring-judul') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155;">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-meta">
        <div class="table-meta-text">
            Menampilkan <?= esc((string) $startRow) ?> - <?= esc((string) $endRow) ?> dari <?= esc((string) $totalRows) ?> data
        </div>
    </div>

    <?php if (! empty($rows)): ?>
        <div class="premium-table-wrap">
            <table class="premium-table">
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
                                <div class="student-name"><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></div>
                                <div class="student-sub">NIM: <?= esc((string) ($row['nim'] ?? '-')) ?></div>
                            </td>
                            <td class="judul-cell"><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                            <td><?= esc((string) (($row['bidang_topik'] ?? '') !== '' ? $row['bidang_topik'] : '-')) ?></td>
                            <td>
                                <?= esc((string) (($row['similarity_score'] ?? '') !== '' ? $row['similarity_score'] : '-')) ?>
                                <div class="muted-text">
                                    Flag: <?= esc((string) (($row['similarity_flag'] ?? '') !== '' ? $row['similarity_flag'] : '-')) ?>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                    <?= esc((string) ($row['status'] ?? '-')) ?>
                                </span>
                            </td>
                            <td><?= esc((string) (($row['versi_ke'] ?? '') !== '' ? $row['versi_ke'] : '-')) ?></td>
                            <td><?= esc((string) (($row['tanggal_pengajuan'] ?? '') !== '' ? $row['tanggal_pengajuan'] : '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="premium-empty">Belum ada data monitoring judul yang cocok dengan filter.</div>
    <?php endif; ?>

    <div class="pagination-bar">
        <div class="pagination-info">
            Halaman <?= esc((string) $page) ?> dari <?= esc((string) $totalPages) ?>
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