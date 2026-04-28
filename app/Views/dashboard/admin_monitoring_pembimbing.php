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
    font-size: 26px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle-premium {
    margin: 0 0 18px;
    color: #64748b;
    line-height: 1.6;
    font-size: 14px;
}

.stats-grid-premium {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 22px;
}

.stat-card-premium {
    border-radius: 22px;
    padding: 20px;
    color: #fff;
    min-height: 145px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 14px 32px rgba(15, 23, 42, 0.10);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stat-card-premium::after {
    content: "";
    position: absolute;
    right: -22px;
    bottom: -22px;
    width: 92px;
    height: 92px;
    background: rgba(255,255,255,0.12);
    border-radius: 50%;
}

.stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }
.stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
.stat-rose { background: linear-gradient(135deg, #e11d48, #fb7185); }

.stat-label-premium,
.stat-value-premium,
.stat-desc-premium {
    position: relative;
    z-index: 1;
}

.stat-label-premium {
    font-size: 13px;
    font-weight: 800;
}

.stat-value-premium {
    font-size: 34px;
    font-weight: 900;
    line-height: 1;
}

.stat-desc-premium {
    font-size: 12px;
    opacity: .95;
    line-height: 1.4;
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
    grid-template-columns: 1.7fr 1fr 1fr .8fr auto;
    gap: 12px;
    align-items: end;
}

.form-group-premium label {
    display: block;
    font-weight: 800;
    color: #334155;
    margin-bottom: 7px;
    font-size: 12px;
}

.form-group-premium input,
.form-group-premium select {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 14px;
    padding: 11px 13px;
    font-size: 13px;
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
    gap: 8px;
    flex-wrap: nowrap;
}

.table-meta {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.table-meta-text {
    color: #64748b;
    font-size: 13px;
}

.premium-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 18px;
    border: 1px solid #eef2f7;
}

.premium-table {
    width: 100%;
    min-width: 980px;
    border-collapse: collapse;
    background: #fff;
}

.premium-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.premium-table th,
.premium-table td {
    padding: 12px 11px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    color: #0f172a;
    font-size: 12.5px;
    line-height: 1.45;
}

.premium-table th {
    font-size: 11.5px;
    font-weight: 900;
    color: #334155;
    white-space: nowrap;
}

.student-name {
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 3px;
    white-space: nowrap;
    max-width: 185px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.student-sub {
    color: #64748b;
    font-size: 11.5px;
    white-space: nowrap;
}

.cell-nowrap {
    white-space: nowrap;
}

.cell-long {
    max-width: 210px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 900;
    line-height: 1;
    white-space: nowrap;
}

.badge-menunggu { background: #dbeafe; color: #1d4ed8; }
.badge-disetujui { background: #dcfce7; color: #166534; }
.badge-ditolak { background: #fee2e2; color: #b91c1c; }
.badge-kuota { background: #fef3c7; color: #92400e; }
.badge-p1 { background: #ede9fe; color: #6d28d9; }
.badge-p2 { background: #e0f2fe; color: #0369a1; }

.premium-empty {
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 22px;
    padding: 24px;
    text-align: center;
    color: #64748b;
    line-height: 1.6;
    font-size: 13px;
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
    font-size: 13px;
}

.pagination-links {
    display: flex;
    gap: 7px;
    flex-wrap: wrap;
}

.page-link {
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 11px;
    border: 1px solid #dbe3ef;
    background: #fff;
    color: #334155;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 12px;
}

.page-link.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

@media(max-width: 1100px) {
    .stats-grid-premium {
        grid-template-columns: repeat(2, 1fr);
    }

    .filter-grid {
        grid-template-columns: 1fr 1fr;
    }

    .filter-actions {
        grid-column: span 2;
    }
}

@media(max-width: 760px) {
    .card-premium {
        padding: 14px;
        border-radius: 22px;
    }

    .section-title-premium {
        font-size: 21px;
        margin-bottom: 5px;
    }

    .section-subtitle-premium {
        font-size: 12.5px;
        line-height: 1.4;
        margin-bottom: 14px;
    }

    .stats-grid-premium {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }

    .stat-card-premium {
        min-height: 116px;
        padding: 14px;
        border-radius: 18px;
    }

    .stat-label-premium {
        font-size: 11px;
    }

    .stat-value-premium {
        font-size: 27px;
    }

    .stat-desc-premium {
        font-size: 10.5px;
    }

.filter-wrap {
    border: 1px solid #e5e7eb;
    background: #f8fafc;
    border-radius: 15px;
    padding: 10px;
    margin-bottom: 12px;
}

.filter-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr .7fr auto;
    gap: 9px;
    align-items: end;
}

.form-group-premium label {
    display: block;
    font-size: 10.5px;
    margin-bottom: 4px;
    font-weight: 800;
    color: #475569;
}

.form-group-premium input,
.form-group-premium select {
    width: 100%;
    padding: 8px 9px;
    border-radius: 10px;
    font-size: 11.5px;
    border: 1px solid #dbe3ef;
    background: #fff;
}

.filter-actions {
    display: flex;
    gap: 6px;
}

.filter-actions .btn {
    padding: 8px 12px;
    font-size: 11.5px;
    border-radius: 10px;
    min-height: auto;
}

    .table-meta-text {
        font-size: 11.5px;
    }

    .premium-table {
        min-width: 760px;
    }

    .premium-table th,
    .premium-table td {
        padding: 8px 8px;
        font-size: 11px;
        line-height: 1.35;
    }

    .premium-table th {
        font-size: 10.5px;
    }

    .student-name {
        max-width: 135px;
        font-size: 11.5px;
    }

    .student-sub {
        font-size: 10.5px;
    }

    .cell-long {
        max-width: 150px;
    }

    .status-badge {
        padding: 5px 7px;
        font-size: 9.8px;
    }

    .pagination-bar {
        gap: 10px;
        margin-top: 14px;
    }

    .pagination-info {
        font-size: 12px;
    }

    .pagination-links {
        overflow-x: auto;
        flex-wrap: nowrap;
        width: 100%;
        padding-bottom: 4px;
    }

    .page-link {
        min-width: 32px;
        height: 32px;
        font-size: 11px;
        border-radius: 10px;
        flex-shrink: 0;
    }
}
</style>

<?php
$rows = $rows ?? [];
$summary = $summary ?? [];
$keyword = $keyword ?? '';
$status = $status ?? '';
$jenis = $jenis ?? '';
$perPage = $perPage ?? 10;
$page = $page ?? 1;
$totalRows = $totalRows ?? 0;
$totalPages = $totalPages ?? 1;
$startRow = $startRow ?? 0;
$endRow = $endRow ?? 0;

$badgeStatus = static function (?string $status): string {
    $status = strtolower((string) $status);

    return match ($status) {
        'menunggu'    => 'badge-menunggu',
        'disetujui'   => 'badge-disetujui',
        'ditolak'     => 'badge-ditolak',
        'kuota_penuh' => 'badge-kuota',
        default       => 'badge-menunggu',
    };
};

$badgeJenis = static function (?string $jenis): string {
    $jenis = strtolower((string) $jenis);

    return match ($jenis) {
        'pembimbing_2', '2' => 'badge-p2',
        default             => 'badge-p1',
    };
};

$labelJenis = static function (?string $jenis): string {
    $jenis = strtolower((string) $jenis);

    return match ($jenis) {
        'pembimbing_2', '2' => 'Pembimbing 2',
        default             => 'Pembimbing 1',
    };
};

$queryParams = [
    'q'        => $keyword,
    'status'   => $status,
    'jenis'    => $jenis,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams) {
    $params = array_merge($queryParams, ['page' => $targetPage]);
    return site_url('admin/monitoring-pembimbing?' . http_build_query($params));
};
?>

<div class="stats-grid-premium">
    <div class="stat-card-premium stat-blue">
        <div class="stat-label-premium">Total Data</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['total'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Seluruh permohonan pembimbing</div>
    </div>

    <div class="stat-card-premium stat-amber">
        <div class="stat-label-premium">Menunggu</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['menunggu'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Masih perlu keputusan dosen</div>
    </div>

    <div class="stat-card-premium stat-emerald">
        <div class="stat-label-premium">Disetujui</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['disetujui'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Sudah diterima pembimbing</div>
    </div>

    <div class="stat-card-premium stat-rose">
        <div class="stat-label-premium">Ditolak</div>
        <div class="stat-value-premium"><?= esc((string) ($summary['ditolak'] ?? 0)) ?></div>
        <div class="stat-desc-premium">Permohonan yang ditolak</div>
    </div>
</div>

<div class="card-premium">
    <h3 class="section-title-premium">Data Monitoring Pembimbing</h3>
    <p class="section-subtitle-premium">
        Cari, filter, dan pantau status permohonan pembimbing mahasiswa.
    </p>

    <div class="filter-wrap">
        <form method="get" action="<?= site_url('admin/monitoring-pembimbing') ?>">
            <div class="filter-grid">
                <div class="form-group-premium">
                    <label for="q">Cari</label>
                    <input type="text" id="q" name="q" value="<?= esc((string) $keyword) ?>" placeholder="Cari data...">
                </div>

                <div class="form-group-premium">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="menunggu" <?= $status === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="kuota_penuh" <?= $status === 'kuota_penuh' ? 'selected' : '' ?>>Kuota Penuh</option>
                    </select>
                </div>

                <div class="form-group-premium">
                    <label for="jenis">Jenis</label>
                    <select id="jenis" name="jenis">
                        <option value="">Semua Jenis</option>
                        <option value="pembimbing_1" <?= $jenis === 'pembimbing_1' ? 'selected' : '' ?>>Pembimbing 1</option>
                        <option value="pembimbing_2" <?= $jenis === 'pembimbing_2' ? 'selected' : '' ?>>Pembimbing 2</option>
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
                    <a href="<?= site_url('admin/monitoring-pembimbing') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155;">
                        Reset
                    </a>
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
                        <th>Dosen</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Respon</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td>
                                <div class="student-name">
                                    <?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?>
                                </div>
                                <div class="student-sub">
                                    NIM: <?= esc((string) ($row['nim'] ?? '-')) ?>
                                </div>
                            </td>

                            <td class="cell-long">
                                <?= esc((string) ($row['nama_dosen'] ?? '-')) ?>
                            </td>

                            <td class="cell-nowrap">
                                <span class="status-badge <?= $badgeJenis($row['jenis_pembimbing'] ?? '') ?>">
                                    <?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?>
                                </span>
                            </td>

                            <td class="cell-nowrap">
                                <span class="status-badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                    <?= esc((string) ($row['status'] ?? '-')) ?>
                                </span>
                            </td>

                            <td class="cell-nowrap">
                                <?= esc((string) ($row['tanggal_pengajuan'] ?? '-')) ?>
                            </td>

                            <td class="cell-nowrap">
                                <?= esc((string) (($row['tanggal_respon'] ?? '') !== '' ? $row['tanggal_respon'] : '-')) ?>
                            </td>

                            <td class="cell-long">
                                <?= esc((string) (($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-')) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="premium-empty">
            Belum ada data monitoring pembimbing yang cocok dengan filter.
        </div>
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