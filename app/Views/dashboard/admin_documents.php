<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
    .docs-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        color: #fff;
        border-radius: 28px;
        padding: 28px 30px;
        margin-bottom: 22px;
        box-shadow: 0 18px 40px rgba(37, 99, 235, 0.18);
        position: relative;
        overflow: hidden;
    }

    .docs-hero::after {
        content: "";
        position: absolute;
        right: -45px;
        top: -45px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .docs-hero h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .docs-hero p {
        margin: 0;
        color: rgba(255,255,255,0.92);
        line-height: 1.7;
        position: relative;
        z-index: 1;
    }

    .docs-top-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 22px;
        margin-bottom: 22px;
    }

    .docs-summary-card,
    .docs-side-card,
    .docs-main-card {
        background: #fff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
    }

    .docs-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 14px;
    }

    .docs-mini-stat {
        border-radius: 20px;
        padding: 18px;
        color: #fff;
        position: relative;
        overflow: hidden;
        min-height: 108px;
    }

    .docs-mini-stat::after {
        content: "";
        position: absolute;
        right: -16px;
        bottom: -16px;
        width: 78px;
        height: 78px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .docs-stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .docs-stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }
    .docs-stat-slate { background: linear-gradient(135deg, #334155, #0f172a); }

    .docs-mini-label {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .docs-mini-value {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .docs-mini-desc {
        font-size: 12px;
        opacity: 0.95;
        position: relative;
        z-index: 1;
    }

    .docs-side-card h3,
    .docs-main-card h3 {
        margin: 0 0 8px;
        font-size: 22px;
        color: #0f172a;
    }

    .docs-side-card p,
    .docs-main-card p {
        margin: 0;
        color: #64748b;
        line-height: 1.7;
    }

    .docs-header-inline {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 18px;
    }

    .premium-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid #eef2f7;
    }

    .premium-table {
        width: 100%;
        min-width: 1350px;
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

    .premium-table tbody tr:hover {
        background: #fafcff;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .badge-terbit {
        background: #dcfce7;
        color: #166534;
    }

    .badge-draft {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .badge-arsip {
        background: #f3f4f6;
        color: #374151;
    }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 28px;
        text-align: center;
        color: #64748b;
    }

    .report-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }

    .report-card {
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        padding: 22px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .report-card .label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 12px;
    }

    .report-card .value {
        font-size: 34px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .report-card .desc {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    @media (max-width: 1100px) {
        .docs-top-grid,
        .report-grid,
        .docs-summary-grid {
            grid-template-columns: 1fr;
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
        if ($status === 'draft') $countDraft++;
        if ($status === 'terbit') $countTerbit++;
        if ($status === 'arsip') $countArsip++;
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

            <div style="margin-top:18px;">
                <a href="<?= base_url('/admin/surat-keputusan/create') ?>" class="btn btn-primary">
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
                            <tr>
                                <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nama_file_asli'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nomor_sk'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['tanggal_terbit'] ?? '-')) ?></td>
                                <td>
    <?php
    $files = [];

    if (! empty($row['file_sk'])) {
        $decoded = json_decode($row['file_sk'], true);

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

    <?php if (! empty($files)): ?>
        <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <?php foreach ($files as $file): ?>
                <a href="<?= base_url($file['path'] ?? '') ?>" 
                   target="_blank" 
                   class="btn btn-primary">
                    📄 Lihat
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
                                    <a href="<?= base_url('/admin/surat-keputusan/delete/' . (string) ($row['id'] ?? '')) ?>"
                                       class="btn btn-danger"
                                       onclick="return confirm('Hapus SK ini?')">
                                        Hapus
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