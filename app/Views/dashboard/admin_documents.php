<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.docs-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
    color: #fff;
    border-radius: 24px;
    padding: 24px;
    margin-bottom: 18px;
    box-shadow: 0 16px 36px rgba(37, 99, 235, 0.16);
    position: relative;
    overflow: hidden;
}

.docs-hero::after {
    content: "";
    position: absolute;
    right: -45px;
    top: -45px;
    width: 160px;
    height: 160px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}

.docs-hero h2 {
    margin: 0 0 6px;
    font-size: 26px;
    font-weight: 900;
    position: relative;
    z-index: 1;
}

.docs-hero p {
    margin: 0;
    color: rgba(255,255,255,0.9);
    line-height: 1.5;
    font-size: 13px;
    position: relative;
    z-index: 1;
}

.docs-top-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 16px;
    margin-bottom: 18px;
}

.docs-summary-card,
.docs-side-card,
.docs-main-card {
    background: #fff;
    border-radius: 22px;
    padding: 20px;
    box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
    border: 1px solid #eef2f7;
}

.docs-summary-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-top: 12px;
}

.docs-mini-stat {
    border-radius: 18px;
    padding: 15px;
    color: #fff;
    min-height: 100px;
    position: relative;
    overflow: hidden;
}

.docs-mini-stat::after {
    content: "";
    position: absolute;
    right: -16px;
    bottom: -16px;
    width: 72px;
    height: 72px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.docs-stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.docs-stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
.docs-stat-slate { background: linear-gradient(135deg, #334155, #0f172a); }

.docs-mini-label,
.docs-mini-value,
.docs-mini-desc {
    position: relative;
    z-index: 1;
}

.docs-mini-label {
    font-size: 11.5px;
    font-weight: 800;
    margin-bottom: 8px;
}

.docs-mini-value {
    font-size: 28px;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 6px;
}

.docs-mini-desc {
    font-size: 10.5px;
    line-height: 1.35;
    opacity: .95;
}

.docs-side-card h3,
.docs-main-card h3 {
    margin: 0 0 6px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.docs-side-card p,
.docs-main-card p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.docs-header-inline {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.premium-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 16px;
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
    padding: 9px 9px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    color: #0f172a;
    font-size: 11.5px;
    line-height: 1.3;
    white-space: nowrap;
}

.premium-table th {
    font-size: 10.5px;
    font-weight: 900;
    color: #334155;
}

.cell-name {
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    font-weight: 900;
}

.cell-title-long {
    min-width: 420px;
    max-width: 420px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cell-file {
    min-width: 230px;
    max-width: 230px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cell-sk {
    min-width: 150px;
    max-width: 180px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 5px 7px;
    border-radius: 999px;
    font-size: 9.8px;
    font-weight: 900;
    line-height: 1;
    white-space: nowrap;
}

.badge-terbit { background: #dcfce7; color: #166534; }
.badge-draft { background: #dbeafe; color: #1d4ed8; }
.badge-arsip { background: #f3f4f6; color: #374151; }

.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: nowrap;
}

.action-icon {
    width: 29px;
    height: 29px;
    border-radius: 9px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 12px;
    cursor: pointer;
    font-weight: 900;
}

.action-icon.view {
    background: #dbeafe;
    color: #1d4ed8;
}

.action-icon.delete {
    background: #fee2e2;
    color: #b91c1c;
}

.premium-empty {
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 20px;
    padding: 22px;
    text-align: center;
    color: #64748b;
    font-size: 13px;
}

.report-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.report-card {
    background: linear-gradient(135deg, #ffffff, #f8fbff);
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
}

.report-card .label {
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    margin-bottom: 10px;
}

.report-card .value {
    font-size: 30px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1;
    margin-bottom: 8px;
}

.report-card .desc {
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
}

@media(max-width: 1100px) {
    .docs-top-grid {
        grid-template-columns: 1fr;
    }

    .report-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width: 760px) {
    .docs-hero {
        padding: 18px;
        border-radius: 20px;
        margin-bottom: 14px;
    }

    .docs-hero h2 {
        font-size: 22px;
    }

    .docs-hero p {
        font-size: 12px;
        line-height: 1.4;
    }

    .docs-summary-card,
    .docs-side-card,
    .docs-main-card {
        padding: 14px;
        border-radius: 20px;
    }

    .docs-summary-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .docs-mini-stat {
        min-height: 86px;
        padding: 11px;
        border-radius: 15px;
    }

    .docs-mini-label {
        font-size: 9.8px;
    }

    .docs-mini-value {
        font-size: 23px;
    }

    .docs-mini-desc {
        font-size: 9px;
    }

    .docs-side-card h3,
    .docs-main-card h3 {
        font-size: 19px;
    }

    .docs-side-card p,
    .docs-main-card p {
        font-size: 12px;
    }

    .docs-side-card .btn {
        padding: 9px 12px;
        border-radius: 12px;
        font-size: 12px;
    }

    .premium-table {
        min-width: 1320px;
    }

    .premium-table th,
    .premium-table td {
        padding: 7px 7px;
        font-size: 10.5px;
    }

    .premium-table th {
        font-size: 9.8px;
    }

    .cell-title-long {
        min-width: 420px;
        max-width: 420px;
    }

    .report-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .report-card {
        padding: 14px;
        border-radius: 17px;
    }

    .report-card .label {
        font-size: 10.5px;
        margin-bottom: 7px;
    }

    .report-card .value {
        font-size: 24px;
    }

    .report-card .desc {
        font-size: 10.5px;
    }
}
</style>

<?php
$pageType = (string) ($pageType ?? '');

$badgeClass = static function (?string $status): string {
    $status = strtolower((string) $status);

    return match ($status) {
        'draft'  => 'badge-draft',
        'terbit' => 'badge-terbit',
        'arsip'  => 'badge-arsip',
        default  => 'badge-draft',
    };
};

$rows = $rows ?? [];
$totalRows = count($rows);

$countDraft = 0;
$countTerbit = 0;
$countArsip = 0;

foreach ($rows as $r) {
    $status = strtolower((string) ($r['status'] ?? ''));

    if ($status === 'draft') {
        $countDraft++;
    }

    if ($status === 'terbit') {
        $countTerbit++;
    }

    if ($status === 'arsip') {
        $countArsip++;
    }
}

$heroTitle = $pageType === 'surat_keputusan' ? 'Surat Keputusan' : 'Laporan / Arsip';
$heroDesc = $pageType === 'surat_keputusan'
    ? 'Kelola penerbitan, penyimpanan, dan arsip Surat Keputusan berdasarkan proposal yang sudah disetujui.'
    : 'Lihat ringkasan statistik operasional sistem dan arsip data utama dalam satu panel yang rapi.';
?>

<div class="docs-hero">
    <h2><?= esc($heroTitle) ?></h2>
    <p><?= esc($heroDesc) ?></p>
</div>

<?php if ($pageType === 'surat_keputusan'): ?>
    <div class="docs-top-grid">
        <div class="docs-summary-card">
            <h3 class="section-title" style="margin-bottom:4px;">Ringkasan Surat Keputusan</h3>
            <p class="section-subtitle">Statistik status SK saat ini.</p>

            <div class="docs-summary-grid">
                <div class="docs-mini-stat docs-stat-blue">
                    <div class="docs-mini-label">Total SK</div>
                    <div class="docs-mini-value"><?= esc((string) $totalRows) ?></div>
                    <div class="docs-mini-desc">Seluruh data SK tersimpan</div>
                </div>

                <div class="docs-mini-stat docs-stat-emerald">
                    <div class="docs-mini-label">Terbit</div>
                    <div class="docs-mini-value"><?= esc((string) $countTerbit) ?></div>
                    <div class="docs-mini-desc">Sudah resmi diterbitkan</div>
                </div>

                <div class="docs-mini-stat docs-stat-slate">
                    <div class="docs-mini-label">Arsip</div>
                    <div class="docs-mini-value"><?= esc((string) $countArsip) ?></div>
                    <div class="docs-mini-desc">Sudah masuk arsip</div>
                </div>
            </div>
        </div>

        <div class="docs-side-card">
            <h3>Quick Action</h3>
            <p>Admin dapat langsung menambah SK baru dari proposal yang sudah disetujui.</p>

            <div style="margin-top:14px;">
                <a href="<?= site_url('admin/surat-keputusan/create') ?>" class="btn btn-primary">
                    + Tambah SK
                </a>
            </div>
        </div>
    </div>

    <div class="docs-main-card">
        <div class="docs-header-inline">
            <div>
                <h3><?= esc((string) ($pageTitle ?? 'Surat Keputusan')) ?></h3>
                <p><?= esc((string) ($pageSubtitle ?? '')) ?></p>
            </div>
        </div>

        <?php if (! empty($rows)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>Proposal</th>
                            <th>Nomor SK</th>
                            <th>Tanggal Terbit</th>
                            <th>File SK</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach (($rows ?? []) as $row): ?>
                            <?php
                            $files = [];

                            if (! empty($row['file_sk'])) {
                                $decoded = json_decode((string) $row['file_sk'], true);

                                if (is_array($decoded)) {
                                    $files = $decoded;
                                } else {
                                    $files[] = [
                                        'nama_asli' => basename((string) $row['file_sk']),
                                        'path'      => (string) $row['file_sk'],
                                    ];
                                }
                            }
                            ?>

                            <tr>
                                <td class="cell-name">
                                    <?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= esc((string) ($row['nim'] ?? '-')) ?>
                                </td>

                                <td class="cell-title-long">
                                    <?= esc((string) ($row['judul'] ?? '-')) ?>
                                </td>

                                <td class="cell-file">
                                    <?= esc((string) ($row['nama_file_asli'] ?? '-')) ?>
                                </td>

                                <td class="cell-sk">
                                    <?= esc((string) ($row['nomor_sk'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= esc((string) ($row['tanggal_terbit'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?php if (! empty($files)): ?>
                                        <div class="action-group">
                                            <?php foreach ($files as $file): ?>
                                                <a
                                                    href="<?= base_url($file['path'] ?? '') ?>"
                                                    target="_blank"
                                                    class="action-icon view"
                                                    title="Lihat File"
                                                >
                                                    📄
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span class="status-badge <?= $badgeClass($row['status'] ?? '') ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="<?= site_url('admin/surat-keputusan/delete/' . (string) ($row['id'] ?? '')) ?>"
                                        class="action-icon delete"
                                        title="Hapus"
                                        onclick="return confirm('Hapus SK ini?')"
                                    >
                                        🗑
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada data SK.</div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if ($pageType === 'laporan'): ?>
    <div class="docs-main-card">
        <div class="docs-header-inline">
            <div>
                <h3><?= esc((string) ($pageTitle ?? 'Laporan / Arsip')) ?></h3>
                <p><?= esc((string) ($pageSubtitle ?? '')) ?></p>
            </div>
        </div>

        <div class="report-grid">
            <div class="report-card">
                <div class="label">Total Users</div>
                <div class="value"><?= esc((string) ($summary['users'] ?? 0)) ?></div>
                <div class="desc">Semua akun yang tersimpan di sistem.</div>
            </div>

            <div class="report-card">
                <div class="label">Total Mahasiswa</div>
                <div class="value"><?= esc((string) ($summary['mahasiswa'] ?? 0)) ?></div>
                <div class="desc">Mahasiswa aktif yang terdaftar.</div>
            </div>

            <div class="report-card">
                <div class="label">Total Dosen</div>
                <div class="value"><?= esc((string) ($summary['dosen'] ?? 0)) ?></div>
                <div class="desc">Dosen pembimbing di sistem.</div>
            </div>

            <div class="report-card">
                <div class="label">Total Judul</div>
                <div class="value"><?= esc((string) ($summary['judul'] ?? 0)) ?></div>
                <div class="desc">Pengajuan judul yang pernah masuk.</div>
            </div>

            <div class="report-card">
                <div class="label">Total Proposal</div>
                <div class="value"><?= esc((string) ($summary['proposal'] ?? 0)) ?></div>
                <div class="desc">Proposal yang sudah diunggah mahasiswa.</div>
            </div>

            <div class="report-card">
                <div class="label">Total SK</div>
                <div class="value"><?= esc((string) ($summary['sk'] ?? 0)) ?></div>
                <div class="desc">Surat keputusan yang sudah dibuat.</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>