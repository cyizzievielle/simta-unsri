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

    .premium-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
        margin-bottom: 22px;
    }

    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        margin-bottom: 22px;
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

    .proposal-highlight {
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-radius: 22px;
        padding: 20px;
    }

    .proposal-highlight-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .proposal-meta {
        display: grid;
        gap: 8px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.7;
    }

    .proposal-meta strong {
        color: #334155;
    }

    .form-grid {
        display: grid;
        gap: 16px;
    }

    .form-group-premium label {
        display: block;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-group-premium .input,
    .form-group-premium textarea,
    .form-group-premium input[type="file"] {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 13px 15px;
        font-size: 14px;
        background: #fff;
        outline: none;
        box-sizing: border-box;
    }

    .form-group-premium .input:focus,
    .form-group-premium textarea:focus,
    .form-group-premium input[type="file"]:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
    }

    .form-group-premium textarea {
        min-height: 120px;
        resize: vertical;
    }

    .help-text {
        color: #64748b;
        font-size: 13px;
        margin-top: 8px;
        line-height: 1.6;
    }

    .info-note {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 20px;
        color: #64748b;
        line-height: 1.75;
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

    .proposal-row-title {
        font-weight: 800;
        color: #0f172a;
        line-height: 1.5;
        margin-bottom: 6px;
    }

    .proposal-row-sub {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .status-text {
        font-weight: 700;
    }

    .status-diajukan { color: #2563eb; }
    .status-direview { color: #b45309; }
    .status-revisi { color: #dc2626; }
    .status-disetujui { color: #15803d; }
    .status-ditolak { color: #475569; }

    .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 28px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 1100px) {
        .premium-stats,
        .premium-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $judulDisetujui  = $judulDisetujui ?? null;
    $riwayatProposal = $riwayatProposal ?? [];
    $masihDiproses   = $masihDiproses ?? false;
    $totalProposal   = $totalProposal ?? count($riwayatProposal);
    $totalDisetujui  = $totalDisetujui ?? 0;
    $totalRevisi     = $totalRevisi ?? 0;

    $statusClass = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'diajukan'  => 'status-diajukan',
            'direview'  => 'status-direview',
            'revisi'    => 'status-revisi',
            'disetujui' => 'status-disetujui',
            'ditolak'   => 'status-ditolak',
            default     => 'status-diajukan',
        };
    };
?>

<div class="page-hero">
    <h2>Proposal Tugas Akhir</h2>
    <p>Unggah proposal tugas akhir, pantau hasil review dosen, dan lihat seluruh riwayat revisi serta persetujuan proposalmu dalam satu halaman.</p>
</div>

<div class="premium-stats">
    <div class="premium-stat-card stat-blue">
        <div class="premium-stat-label">Total Proposal</div>
        <div class="premium-stat-value"><?= esc((string) $totalProposal) ?></div>
        <div class="premium-stat-desc">Seluruh riwayat proposal yang pernah diunggah</div>
    </div>

    <div class="premium-stat-card stat-amber">
        <div class="premium-stat-label">Sedang Diproses</div>
        <div class="premium-stat-value"><?= $masihDiproses ? '1' : '0' ?></div>
        <div class="premium-stat-desc">Proposal yang masih direview dosen</div>
    </div>

    <div class="premium-stat-card stat-emerald">
        <div class="premium-stat-label">Proposal Disetujui</div>
        <div class="premium-stat-value"><?= esc((string) $totalDisetujui) ?></div>
        <div class="premium-stat-desc">Proposal yang sudah final dan disetujui</div>
    </div>
</div>

<div class="premium-grid-2">
    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title-premium">Dasar Pengajuan Proposal</h3>
                <p class="section-subtitle-premium">Proposal hanya bisa diajukan jika judul tugas akhir sudah disetujui.</p>
            </div>
        </div>

        <?php if ($judulDisetujui): ?>
            <div class="proposal-highlight">
                <div class="proposal-highlight-title">
                    <?= esc((string) ($judulDisetujui['judul'] ?? '-')) ?>
                </div>

                <div class="proposal-meta">
                    <div><strong>Status Judul:</strong> <span class="status-text status-disetujui"><?= esc((string) ($judulDisetujui['status'] ?? '-')) ?></span></div>
                    <div><strong>Bidang Topik:</strong> <?= esc((string) (($judulDisetujui['bidang_topik'] ?? '') !== '' ? $judulDisetujui['bidang_topik'] : '-')) ?></div>
                    <div><strong>Kata Kunci:</strong> <?= esc((string) (($judulDisetujui['kata_kunci'] ?? '') !== '' ? $judulDisetujui['kata_kunci'] : '-')) ?></div>
                    <div><strong>Tanggal Persetujuan:</strong> <?= esc((string) ($judulDisetujui['tanggal_review'] ?? $judulDisetujui['tanggal_pengajuan'] ?? '-')) ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="info-note">
                Kamu belum bisa mengajukan proposal karena belum ada judul yang berstatus <strong>disetujui</strong>. Selesaikan proses pengajuan judul terlebih dahulu.
            </div>
        <?php endif; ?>
    </div>

    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title-premium">Upload Proposal</h3>
                <p class="section-subtitle-premium">Unggah proposal baru langsung dari halaman ini.</p>
            </div>
        </div>

        <?php if (! $judulDisetujui): ?>
            <div class="info-note">
                Form upload belum aktif karena judul belum disetujui.
            </div>
        <?php elseif ($masihDiproses): ?>
            <div class="info-note">
                Kamu masih memiliki proposal yang sedang diproses. Tunggu hasil review dosen terlebih dahulu sebelum mengunggah proposal baru.
            </div>
        <?php else: ?>
            <form action="<?= base_url('/proposal-ta/upload') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <input type="hidden" name="pengajuan_judul_id" value="<?= esc((string) ($judulDisetujui['id'] ?? '')) ?>">

                <div class="form-grid">
                    <div class="form-group-premium">
                        <label>Judul Disetujui</label>
                        <input type="text" class="input" value="<?= esc((string) ($judulDisetujui['judul'] ?? '-')) ?>" readonly>
                    </div>

                    <div class="form-group-premium">
                        <label for="file_proposal">File Proposal</label>
                        <input type="file" name="file_proposal" id="file_proposal" required>
                        <div class="help-text">Unggah file proposal dalam format yang diizinkan sistem, misalnya PDF atau DOCX sesuai validasi controller.</div>
                    </div>

                    <div class="form-group-premium">
                        <label for="catatan_mahasiswa">Catatan Mahasiswa</label>
                        <textarea name="catatan_mahasiswa" id="catatan_mahasiswa" placeholder="Tulis catatan tambahan jika diperlukan..."></textarea>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary">Upload Proposal</button>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="card-premium">
    <div class="section-head">
        <div>
            <h3 class="section-title-premium">Riwayat Proposal</h3>
            <p class="section-subtitle-premium">Lihat file proposal yang pernah kamu unggah, status review, revisi, dan catatan dosen.</p>
        </div>
    </div>

    <?php if (! empty($riwayatProposal)): ?>
        <div class="premium-table-wrap">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>File Proposal</th>
                        <th>Status</th>
                        <th>Versi</th>
                        <th>Tanggal Upload</th>
                        <th>Tanggal Review</th>
                        <th>Catatan Reviewer</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayatProposal as $row): ?>
                        <tr>
                            <td>
                                <div class="proposal-row-title"><?= esc((string) ($row['judul'] ?? '-')) ?></div>
                            </td>
                            <td>
                                <?php if (! empty($row['file_proposal'])): ?>
                                    <a href="<?= base_url('uploads/proposal/' . $row['file_proposal']) ?>"
                                    target="_blank"
                                    class="btn btn-primary">
                                        Buka
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-text <?= $statusClass($row['status'] ?? '') ?>">
                                    <?= esc((string) ($row['status'] ?? '-')) ?>
                                </span>
                            </td>
                            <td><?= esc((string) ($row['versi_ke'] ?? '1')) ?></td>
                            <td><?= esc((string) ($row['tanggal_upload'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['tanggal_review'] ?? '-')) ?></td>
                            <td><?= esc((string) (($row['catatan_reviewer'] ?? '') !== '' ? $row['catatan_reviewer'] : '-')) ?></td>
                            <td>
                                <div class="action-row">
                                    <?php
                                        $status = strtolower((string) ($row['status'] ?? ''));
                                        $proposalId = (string) ($row['id'] ?? '');
                                    ?>
                                    <?php if (in_array($status, ['revisi', 'ditolak'], true) && $proposalId !== ''): ?>
                                        <a href="<?= base_url('/proposal-ta/revisi/' . $proposalId) ?>" class="btn btn-primary" style="padding:10px 14px;">
                                            Revisi
                                        </a>
                                    <?php else: ?>
                                        <span style="color:#94a3b8;">-</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="premium-empty">Belum ada riwayat proposal tugas akhir.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>