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

    .detail-box {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 20px;
        padding: 18px;
        color: #64748b;
        line-height: 1.7;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group-premium {
        margin-bottom: 16px;
    }

    .form-group-premium.full {
        grid-column: 1 / -1;
    }

    .form-group-premium label {
        display: block;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-group-premium input,
    .form-group-premium select,
    .form-group-premium textarea {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 13px 15px;
        font-size: 14px;
        background: #fff;
        outline: none;
        box-sizing: border-box;
    }

    .form-group-premium input:focus,
    .form-group-premium select:focus,
    .form-group-premium textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
    }

    .form-group-premium textarea {
        min-height: 120px;
        resize: vertical;
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

    .badge-diajukan { background: #dbeafe; color: #1d4ed8; }
    .badge-direview { background: #fef3c7; color: #92400e; }
    .badge-revisi { background: #fee2e2; color: #b91c1c; }
    .badge-disetujui { background: #dcfce7; color: #166534; }
    .badge-ditolak { background: #e5e7eb; color: #374151; }

    .premium-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid #eef2f7;
    }

    .premium-table {
        width: 100%;
        min-width: 1000px;
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

    @media (max-width: 1100px) {
        .premium-stats,
        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group-premium.full {
            grid-column: auto;
        }
    }
</style>

<?php
    $riwayatJudul = $riwayatJudul ?? [];
    $judulAktif = $judulAktif ?? null;

    $totalPengajuan = count($riwayatJudul);
    $totalDiproses = 0;
    $totalDisetujui = 0;

    foreach ($riwayatJudul as $row) {
        $status = strtolower((string) ($row['status'] ?? ''));
        if (in_array($status, ['diajukan', 'direview', 'revisi'], true)) $totalDiproses++;
        if ($status === 'disetujui') $totalDisetujui++;
    }

    $badgeStatus = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'diajukan'  => 'badge-diajukan',
            'direview'  => 'badge-direview',
            'revisi'    => 'badge-revisi',
            'disetujui' => 'badge-disetujui',
            'ditolak'   => 'badge-ditolak',
            default     => 'badge-diajukan',
        };
    };
?>

<div class="premium-stats">
    <div class="premium-stat-card stat-blue">
        <div class="premium-stat-label">Total Pengajuan</div>
        <div class="premium-stat-value"><?= esc((string) $totalPengajuan) ?></div>
        <div class="premium-stat-desc">Seluruh riwayat judul yang pernah diajukan</div>
    </div>

    <div class="premium-stat-card stat-amber">
        <div class="premium-stat-label">Sedang Diproses</div>
        <div class="premium-stat-value"><?= esc((string) $totalDiproses) ?></div>
        <div class="premium-stat-desc">Judul yang masih menunggu hasil akhir</div>
    </div>

    <div class="premium-stat-card stat-emerald">
        <div class="premium-stat-label">Judul Disetujui</div>
        <div class="premium-stat-value"><?= esc((string) $totalDisetujui) ?></div>
        <div class="premium-stat-desc">Judul yang sudah final dan disetujui</div>
    </div>
</div>

<div class="card-premium">
    <h3 class="section-title-premium">Status Judul Aktif</h3>
    <p class="section-subtitle-premium">Ringkasan judul yang saat ini menjadi acuan tugas akhirmu.</p>

    <?php if (! empty($judulAktif)): ?>
        <div class="premium-table-wrap">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Versi</th>
                        <th>Similarity</th>
                        <th>Tanggal</th>
                        <th>Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= esc((string) ($judulAktif['judul'] ?? '-')) ?></td>
                        <td>
                            <span class="status-badge <?= $badgeStatus($judulAktif['status'] ?? '') ?>">
                                <?= esc((string) ($judulAktif['status'] ?? '-')) ?>
                            </span>
                        </td>
                        <td><?= esc((string) ($judulAktif['versi_ke'] ?? '1')) ?></td>
                        <td><?= esc((string) ($judulAktif['similarity_score'] ?? '0')) ?>%</td>
                        <td><?= esc((string) ($judulAktif['tanggal_pengajuan'] ?? '-')) ?></td>
                        <td><?= esc((string) (($judulAktif['catatan_reviewer'] ?? '') !== '' ? $judulAktif['catatan_reviewer'] : '-')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="detail-box">
            Belum ada judul yang disetujui. Kamu bisa mengajukan judul baru atau melakukan revisi jika diminta.
        </div>
    <?php endif; ?>
</div>

<div class="card-premium">
    <h3 class="section-title-premium">Form Pengajuan Judul</h3>
    <p class="section-subtitle-premium">Isi form berikut untuk mengajukan judul tugas akhirmu.</p>

    <form action="<?= base_url('/pengajuan-judul/simpan') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div class="form-group-premium full">
                <label for="judul">Judul</label>
                <input type="text" name="judul" id="judul" value="<?= old('judul') ?>" required>
            </div>

            <div class="form-group-premium">
                <label for="bidang_topik">Bidang Topik</label>
                <input type="text" name="bidang_topik" id="bidang_topik" value="<?= old('bidang_topik') ?>" required>
            </div>

            <div class="form-group-premium">
                <label for="kata_kunci">Kata Kunci</label>
                <input type="text" name="kata_kunci" id="kata_kunci" value="<?= old('kata_kunci') ?>" required>
            </div>

            <div class="form-group-premium full">
                <label for="latar_belakang">Latar Belakang</label>
                <textarea name="latar_belakang" id="latar_belakang" required><?= old('latar_belakang') ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Ajukan Judul</button>
    </form>
</div>

<div class="card-premium">
    <h3 class="section-title-premium">Riwayat Pengajuan Judul</h3>
    <p class="section-subtitle-premium">Semua pengajuan dan revisi judul yang pernah kamu kirim.</p>

    <?php if (! empty($riwayatJudul)): ?>
        <div class="premium-table-wrap">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Versi</th>
                        <th>Similarity</th>
                        <th>Tanggal</th>
                        <th>Catatan Reviewer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayatJudul as $row): ?>
                        <tr>
                            <td><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                            <td>
                                <span class="status-badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                    <?= esc((string) ($row['status'] ?? '-')) ?>
                                </span>
                            </td>
                            <td><?= esc((string) ($row['versi_ke'] ?? '1')) ?></td>
                            <td><?= esc((string) ($row['similarity_score'] ?? '0')) ?>%</td>
                            <td><?= esc((string) ($row['tanggal_pengajuan'] ?? '-')) ?></td>
                            <td><?= esc((string) (($row['catatan_reviewer'] ?? '') !== '' ? $row['catatan_reviewer'] : '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="detail-box">Belum ada riwayat pengajuan judul.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>