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
    .stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }

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
    }
    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }
    .section-title-premium {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }
    .section-subtitle-premium {
        margin: 0;
        color: #64748b;
        line-height: 1.7;
    }

    .premium-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid #eef2f7;
    }
    .premium-table {
        width: 100%;
        min-width: 1250px;
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

    .judul-cell-title {
        font-weight: 800;
        color: #0f172a;
        line-height: 1.55;
        margin-bottom: 6px;
        max-width: 540px;
        word-break: break-word;
    }

    .status-text {
        font-weight: 700;
    }
    .status-disetujui { color: #15803d; }
    .status-ditolak { color: #475569; }
    .status-revisi { color: #dc2626; }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 28px;
        text-align: center;
        color: #64748b;
        line-height: 1.8;
    }

    @media (max-width: 1100px) {
        .premium-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $riwayat = $riwayat ?? [];
    $totalRiwayat = count($riwayat);
    $totalDisetujui = 0;
    $totalDitolak = 0;
    $totalRevisi = 0;

    foreach ($riwayat as $row) {
        $status = strtolower((string) ($row['status_review'] ?? ''));
        if ($status === 'disetujui') $totalDisetujui++;
        if ($status === 'ditolak') $totalDitolak++;
        if ($status === 'revisi') $totalRevisi++;
    }

    $statusClass = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'disetujui' => 'status-disetujui',
            'ditolak'   => 'status-ditolak',
            'revisi'    => 'status-revisi',
            default     => 'status-ditolak',
        };
    };
?>

<div class="page-hero">
    <h2>Riwayat Review Judul</h2>
    <p>Lihat seluruh keputusan final review judul yang pernah Anda berikan kepada mahasiswa bimbingan.</p>
</div>

<div class="premium-stats">
    <div class="premium-stat-card stat-blue">
        <div class="premium-stat-label">Total Riwayat</div>
        <div class="premium-stat-value"><?= esc((string) $totalRiwayat) ?></div>
        <div class="premium-stat-desc">Semua review final tersimpan</div>
    </div>

    <div class="premium-stat-card stat-emerald">
        <div class="premium-stat-label">Disetujui</div>
        <div class="premium-stat-value"><?= esc((string) $totalDisetujui) ?></div>
        <div class="premium-stat-desc">Judul yang Anda setujui</div>
    </div>

    <div class="premium-stat-card stat-amber">
        <div class="premium-stat-label">Tolak / Revisi</div>
        <div class="premium-stat-value"><?= esc((string) ($totalDitolak + $totalRevisi)) ?></div>
        <div class="premium-stat-desc">Judul yang Anda kembalikan</div>
    </div>
</div>

<div class="card-premium">
    <div class="section-head">
        <div>
            <h3 class="section-title-premium">Riwayat Review Judul</h3>
            <p class="section-subtitle-premium">Semua review final yang pernah Anda berikan.</p>
        </div>
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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat as $row): ?>
                        <tr>
                            <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                            <td>
                                <div class="judul-cell-title"><?= esc((string) ($row['judul'] ?? '-')) ?></div>
                            </td>
                            <td>
                                <span class="status-text <?= $statusClass($row['status_review'] ?? '') ?>">
                                    <?= esc((string) ($row['status_review'] ?? '-')) ?>
                                </span>
                            </td>
                            <td><?= esc((string) (($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-')) ?></td>
                            <td><?= esc((string) ($row['created_at'] ?? '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="premium-empty">
            Belum ada riwayat review judul yang tersimpan.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>