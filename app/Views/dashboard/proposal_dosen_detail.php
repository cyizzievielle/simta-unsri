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

    .detail-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 22px;
        margin-bottom: 22px;
    }

    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
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
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .detail-box-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .detail-box-value {
        color: #0f172a;
        line-height: 1.75;
        font-size: 15px;
        word-break: break-word;
    }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .status-text {
        font-weight: 700;
    }

    .status-diajukan { color: #2563eb; }
    .status-direview { color: #b45309; }
    .status-revisi { color: #dc2626; }
    .status-disetujui { color: #15803d; }
    .status-ditolak { color: #475569; }

    .form-group-premium {
        margin-bottom: 16px;
    }

    .form-group-premium label {
        display: block;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

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

    .form-group-premium select:focus,
    .form-group-premium textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
    }

    .form-group-premium textarea {
        min-height: 130px;
        resize: vertical;
    }

    .review-timeline {
        display: grid;
        gap: 14px;
    }

    .review-item {
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border-radius: 20px;
        padding: 16px;
    }

    .review-item-head {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .review-item strong {
        color: #0f172a;
    }

    .review-meta {
        color: #64748b;
        font-size: 13px;
        line-height: 1.7;
    }

    .file-link-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 14px 16px;
        border: 1px solid #dbeafe;
        background: linear-gradient(135deg, #f8fbff, #eef4ff);
        border-radius: 18px;
    }

    .file-link-box .file-name {
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
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

    @media (max-width: 1100px) {
        .detail-grid,
        .detail-meta-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $proposal = $proposal ?? [];
    $riwayatReview = $riwayatReview ?? [];

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
    <h2>Detail Proposal Mahasiswa</h2>
    <p>Tinjau detail proposal, file yang diunggah mahasiswa, lalu simpan keputusan review proposal dari satu halaman.</p>
</div>

<div class="detail-grid">
    <div class="card-premium">
        <h3 class="section-title-premium">Informasi Proposal</h3>
        <p class="section-subtitle-premium">Detail proposal, mahasiswa, file, dan data pendukung.</p>

        <div class="detail-box">
            <div class="detail-box-title">Judul</div>
            <div class="detail-box-value">
                <?= esc((string) ($proposal['judul'] ?? '-')) ?>
            </div>
        </div>

        <div class="detail-meta-grid">
            <div class="detail-box">
                <div class="detail-box-title">Mahasiswa</div>
                <div class="detail-box-value">
                    <?= esc((string) ($proposal['nama_mahasiswa'] ?? '-')) ?><br>
                    NIM: <?= esc((string) ($proposal['nim'] ?? '-')) ?>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Status Saat Ini</div>
                <div class="detail-box-value">
                    <span class="status-text <?= $statusClass($proposal['status'] ?? '') ?>">
                        <?= esc((string) ($proposal['status'] ?? '-')) ?>
                    </span>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Versi Proposal</div>
                <div class="detail-box-value">
                    <?= esc((string) ($proposal['versi_ke'] ?? '1')) ?>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Tanggal Upload</div>
                <div class="detail-box-value">
                    <?= esc((string) ($proposal['tanggal_upload'] ?? '-')) ?>
                </div>
            </div>
        </div>

        <div class="detail-box">
            <div class="detail-box-title">File Proposal</div>
            <div class="file-link-box">
                <div>
                    <div class="file-name">
                        <?= esc((string) (($proposal['nama_file_asli'] ?? '') !== '' ? $proposal['nama_file_asli'] : '-')) ?>
                    </div>
                    <div style="color:#64748b; font-size:13px; margin-top:4px;">
                        File sistem: <?= esc((string) (($proposal['file_proposal'] ?? '') !== '' ? $proposal['file_proposal'] : '-')) ?>
                    </div>
                </div>

                <?php if (! empty($proposal['file_proposal'])): ?>
                    <a href="<?= base_url('uploads/proposal/' . $proposal['file_proposal']) ?>" target="_blank" class="btn btn-primary">
                        Lihat File
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="detail-box">
            <div class="detail-box-title">Catatan Mahasiswa</div>
            <div class="detail-box-value">
                <?= nl2br(esc((string) (($proposal['catatan_mahasiswa'] ?? '') !== '' ? $proposal['catatan_mahasiswa'] : '-'))) ?>
            </div>
        </div>
    </div>

    <div>
        <div class="card-premium" style="margin-bottom:22px;">
            <h3 class="section-title-premium">Form Review</h3>
            <p class="section-subtitle-premium">Simpan keputusan review untuk proposal ini.</p>

            <form action="<?= base_url('/dosen/proposal-ta/' . (string) ($proposal['id'] ?? '') . '/review') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group-premium">
                    <label>Status Review</label>
                    <select name="status" required>
                        <option value="direview" <?= (($proposal['status'] ?? '') === 'direview') ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui">Setujui</option>
                        <option value="revisi">Minta Revisi</option>
                        <option value="ditolak">Tolak</option>
                    </select>
                </div>

                <div class="form-group-premium">
                    <label>Catatan Reviewer</label>
                    <textarea name="catatan_reviewer" placeholder="Tulis catatan review, saran revisi, atau alasan penolakan..."><?= esc((string) ($proposal['catatan_reviewer'] ?? '')) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Simpan Review</button>
            </form>
        </div>

        <div class="card-premium">
            <h3 class="section-title-premium">Riwayat Review</h3>
            <p class="section-subtitle-premium">Semua review yang pernah tersimpan untuk proposal ini.</p>

            <?php if (! empty($riwayatReview)): ?>
                <div class="review-timeline">
                    <?php foreach ($riwayatReview as $row): ?>
                        <div class="review-item">
                            <div class="review-item-head">
                                <strong><?= esc((string) ($row['nama_reviewer'] ?? '-')) ?></strong>
                                <span class="status-text <?= $statusClass($row['status_review'] ?? '') ?>">
                                    <?= esc((string) ($row['status_review'] ?? '-')) ?>
                                </span>
                            </div>

                            <div style="color:#0f172a; line-height:1.7;">
                                <?= esc((string) (($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-')) ?>
                            </div>

                            <div class="review-meta">
                                Waktu: <?= esc((string) ($row['created_at'] ?? '-')) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="premium-empty">Belum ada riwayat review untuk proposal ini.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>