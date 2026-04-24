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

    .mentor-list {
        display: grid;
        gap: 12px;
    }

    .mentor-item {
        border: 1px solid #e2e8f0;
        background: #f8fbff;
        border-radius: 18px;
        padding: 16px;
    }

    .mentor-item strong {
        display: block;
        margin-bottom: 6px;
        color: #0f172a;
    }

    .status-text {
        font-weight: 700;
    }

    .status-diajukan { color: #2563eb; }
    .status-direview { color: #b45309; }
    .status-revisi { color: #dc2626; }
    .status-disetujui { color: #15803d; }
    .status-ditolak { color: #475569; }

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

    .badge-p1 { background: #ede9fe; color: #6d28d9; }
    .badge-p2 { background: #e0f2fe; color: #0369a1; }

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
    $pengajuan = $pengajuan ?? [];
    $pembimbingList = $pembimbingList ?? [];
    $riwayatReview = $riwayatReview ?? [];
    $reviewPembimbing = $reviewPembimbing ?? [];

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

    $labelJenis = static function (?string $jenis): string {
        $jenis = strtolower((string) $jenis);
        return match ($jenis) {
            '1', 'pembimbing_1' => 'Pembimbing 1',
            '2', 'pembimbing_2' => 'Pembimbing 2',
            default             => (string) $jenis,
        };
    };

    $badgeJenis = static function (?string $jenis): string {
        $jenis = strtolower((string) $jenis);
        return match ($jenis) {
            '1', 'pembimbing_1' => 'badge-p1',
            '2', 'pembimbing_2' => 'badge-p2',
            default             => 'badge-p1',
        };
    };
?>

<div class="page-hero">
    <h2>Detail Pengajuan Judul</h2>
    <p>Tinjau detail judul, mahasiswa, pembimbing aktif, lalu simpan keputusan review secara rapi dari satu halaman.</p>
</div>

<div class="detail-grid">
    <div class="card-premium">
        <h3 class="section-title-premium">Informasi Pengajuan</h3>
        <p class="section-subtitle-premium">Detail judul, mahasiswa, dan data pendukung pengajuan.</p>

        <div class="detail-box">
            <div class="detail-box-title">Judul</div>
            <div class="detail-box-value">
                <?= esc((string) ($pengajuan['judul'] ?? '-')) ?>
            </div>
        </div>

        <div class="detail-meta-grid">
            <div class="detail-box">
                <div class="detail-box-title">Mahasiswa</div>
                <div class="detail-box-value">
                    <?= esc((string) ($pengajuan['nama_mahasiswa'] ?? '-')) ?><br>
                    NIM: <?= esc((string) ($pengajuan['nim'] ?? '-')) ?>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Status Saat Ini</div>
                <div class="detail-box-value">
                    <span class="status-text <?= $statusClass($pengajuan['status'] ?? '') ?>">
                        <?= esc((string) ($pengajuan['status'] ?? '-')) ?>
                    </span>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Bidang Topik</div>
                <div class="detail-box-value">
                    <?= esc((string) (($pengajuan['bidang_topik'] ?? '') !== '' ? $pengajuan['bidang_topik'] : '-')) ?>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Kata Kunci</div>
                <div class="detail-box-value">
                    <?= esc((string) (($pengajuan['kata_kunci'] ?? '') !== '' ? $pengajuan['kata_kunci'] : '-')) ?>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Similarity Score</div>
                <div class="detail-box-value">
                    <?= esc((string) ($pengajuan['similarity_score'] ?? '0')) ?>%
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Tanggal Pengajuan</div>
                <div class="detail-box-value">
                    <?= esc((string) ($pengajuan['tanggal_pengajuan'] ?? '-')) ?>
                </div>
            </div>
        </div>

        <div class="detail-box">
            <div class="detail-box-title">Latar Belakang</div>
            <div class="detail-box-value">
                <?= nl2br(esc((string) (($pengajuan['latar_belakang'] ?? '') !== '' ? $pengajuan['latar_belakang'] : '-'))) ?>
            </div>
        </div>

        <div class="detail-box">
            <div class="detail-box-title">Pembimbing Aktif</div>
            <?php if (! empty($pembimbingList)): ?>
                <div class="mentor-list">
                    <?php foreach ($pembimbingList as $item): ?>
                        <div class="mentor-item">
                            <strong><?= esc((string) ($item['nama_dosen'] ?? '-')) ?></strong>
                            <span class="status-badge <?= $badgeJenis($item['jenis_pembimbing'] ?? '') ?>">
                                <?= esc($labelJenis($item['jenis_pembimbing'] ?? '')) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="detail-box-value">Belum ada pembimbing aktif.</div>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="card-premium" style="margin-bottom:22px;">
            <h3 class="section-title-premium">Form Review</h3>
            <p class="section-subtitle-premium">Simpan keputusan review untuk judul ini.</p>

            <form action="<?= base_url('/dosen/pengajuan-judul/' . (string) ($pengajuan['id'] ?? '') . '/review') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group-premium">
                    <label>Status Review</label>
                    <select name="status" required>
                        <option value="direview" <?= (($pengajuan['status'] ?? '') === 'direview') ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui">Setujui</option>
                        <option value="revisi">Minta Revisi</option>
                        <option value="ditolak">Tolak</option>
                    </select>
                </div>

                <div class="form-group-premium">
                    <label>Catatan Reviewer</label>
                    <textarea name="catatan_reviewer" placeholder="Tulis catatan review, saran revisi, atau alasan penolakan..."><?= esc((string) ($pengajuan['catatan_reviewer'] ?? '')) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Simpan Review</button>
            </form>
        </div>

        <div class="card-premium" style="margin-bottom:22px;">
    <h3 class="section-title-premium">Review Pembimbing</h3>
    <p class="section-subtitle-premium">
        Hasil review dari Pembimbing 1 dan Pembimbing 2.
    </p>

    <?php if (! empty($reviewPembimbing)): ?>
        <div class="review-timeline">
            <?php foreach ($reviewPembimbing as $row): ?>
                <div class="review-item">
                    <div class="review-item-head">
                        <div>
                            <strong><?= esc((string) ($row['nama_dosen'] ?? '-')) ?></strong>
                            <div class="review-meta">
                                <?= esc((string) (($row['jenis_pembimbing'] ?? '') === 'pembimbing_1' ? 'Pembimbing 1' : 'Pembimbing 2')) ?>
                            </div>
                        </div>

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
        <div class="premium-empty">
            Belum ada review dari pembimbing.
        </div>
    <?php endif; ?>
</div>

        <div class="card-premium">
            <h3 class="section-title-premium">Riwayat Review</h3>
            <p class="section-subtitle-premium">Semua review yang pernah tersimpan untuk judul ini.</p>

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
                <div class="premium-empty">Belum ada riwayat review untuk judul ini.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>