<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.detail-grid {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 22px;
    align-items: start;
}

.card-modern {
    background: #fff;
    border-radius: 28px;
    padding: 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 18px 42px rgba(15,23,42,.06);
    margin-bottom: 22px;
}

.card-head {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.section-title {
    margin: 0 0 8px;
    font-size: 26px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle {
    margin: 0;
    color: #64748b;
    line-height: 1.7;
}

.judul-box {
    padding: 22px;
    border-radius: 24px;
    background: linear-gradient(135deg, #eff6ff, #f8fbff);
    border: 1px solid #dbeafe;
}

.judul-text {
    font-size: 20px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.6;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
    white-space: nowrap;
}

.badge-green { background: #dcfce7; color: #166534; }
.badge-blue { background: #dbeafe; color: #1d4ed8; }
.badge-orange { background: #fef3c7; color: #92400e; }
.badge-red { background: #fee2e2; color: #b91c1c; }
.badge-gray { background: #e2e8f0; color: #475569; }

.info-list {
    display: grid;
    gap: 12px;
    margin-top: 18px;
}

.info-row {
    display: grid;
    grid-template-columns: 170px 1fr;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #eef2f7;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #64748b;
    font-weight: 900;
}

.info-value {
    color: #0f172a;
    font-weight: 800;
    line-height: 1.6;
}

.pembimbing-list {
    display: grid;
    gap: 14px;
}

.pembimbing-card {
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 18px;
    background: linear-gradient(135deg, #ffffff, #f8fafc);
}

.pembimbing-name {
    font-size: 17px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 8px;
}

.latar-box {
    margin-top: 18px;
    padding: 20px;
    border-radius: 22px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #334155;
    line-height: 1.8;
}

.review-list {
    display: grid;
    gap: 14px;
}

.review-card {
    display: grid;
    grid-template-columns: 48px 1fr;
    gap: 14px;
    padding: 18px;
    border-radius: 22px;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
    border: 1px solid #e2e8f0;
}

.review-icon {
    width: 48px;
    height: 48px;
    border-radius: 16px;
    background: #2563eb;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
}

.review-top {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.review-name {
    font-weight: 900;
    color: #0f172a;
}

.review-note {
    color: #475569;
    line-height: 1.7;
    margin-top: 8px;
}

.empty-box {
    padding: 22px;
    border-radius: 22px;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
    color: #64748b;
    text-align: center;
}

.action-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 15px;
    padding: 12px 18px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    font-weight: 900;
    text-decoration: none;
    box-shadow: 0 12px 26px rgba(37,99,235,.18);
}

.btn-light {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #dbe3ef;
    box-shadow: none;
}

@media (max-width: 1000px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 700px) {
    .card-modern {
        padding: 22px;
        border-radius: 22px;
    }

    .section-title {
        font-size: 22px;
    }

    .judul-text {
        font-size: 16px;
    }

    .info-row {
        grid-template-columns: 1fr;
        gap: 6px;
    }

    .review-card {
        grid-template-columns: 1fr;
    }

    .btn-modern {
        width: 100%;
    }
}
</style>

<?php
$judul = $judul ?? $pengajuan ?? [];

$reviews = $reviews ?? $reviewJudul ?? $riwayatReview ?? [];

$pembimbing1 = $pembimbing1 ?? ($judul['pembimbing_1'] ?? null);
$pembimbing2 = $pembimbing2 ?? ($judul['pembimbing_2'] ?? null);

$status = $judul['status'] ?? '-';

$badgeStatus = static function (?string $status): string {
    return match ($status) {
        'disetujui' => 'badge-green',
        'revisi' => 'badge-orange',
        'ditolak' => 'badge-red',
        'diajukan', 'direview', 'menunggu' => 'badge-blue',
        default => 'badge-gray',
    };
};
?>

<div class="action-row">
    <a href="<?= base_url('/pengajuan-judul') ?>" class="btn-modern btn-light">← Kembali</a>

    <?php if (($status ?? '') === 'revisi'): ?>
        <a href="<?= base_url('/pengajuan-judul/revisi/' . ($judul['id'] ?? 0)) ?>" class="btn-modern">
            Ajukan Revisi
        </a>
    <?php endif; ?>
</div>

<div class="detail-grid">

    <div>
        <div class="card-modern">
            <div class="card-head">
                <div>
                    <h2 class="section-title">Detail Pengajuan Judul</h2>
                    <p class="section-subtitle">Informasi utama judul tugas akhir yang kamu ajukan.</p>
                </div>

                <span class="badge <?= $badgeStatus($status) ?>">
                    <?= esc((string) $status) ?>
                </span>
            </div>

            <div class="judul-box">
                <div class="judul-text">
                    <?= esc((string) ($judul['judul'] ?? '-')) ?>
                </div>
            </div>

            <div class="info-list">
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge <?= $badgeStatus($status) ?>">
                            <?= esc((string) $status) ?>
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Versi</div>
                    <div class="info-value"><?= esc((string) ($judul['versi_ke'] ?? '1')) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Bidang Topik</div>
                    <div class="info-value"><?= esc((string) (($judul['bidang_topik'] ?? '') !== '' ? $judul['bidang_topik'] : '-')) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Kata Kunci</div>
                    <div class="info-value"><?= esc((string) (($judul['kata_kunci'] ?? '') !== '' ? $judul['kata_kunci'] : '-')) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Similarity</div>
                    <div class="info-value">
                        <?= esc((string) (($judul['similarity_score'] ?? '') !== '' ? $judul['similarity_score'] . '%' : '-')) ?>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Tanggal Pengajuan</div>
                    <div class="info-value"><?= esc((string) ($judul['tanggal_pengajuan'] ?? $judul['created_at'] ?? '-')) ?></div>
                </div>
            </div>

            <div class="latar-box">
                <strong>Latar Belakang</strong><br>
                <?= nl2br(esc((string) (($judul['latar_belakang'] ?? '') !== '' ? $judul['latar_belakang'] : '-'))) ?>
            </div>
        </div>
    </div>

    <div>
        <div class="card-modern">
            <div class="card-head">
                <div>
                    <h2 class="section-title">Pembimbing</h2>
                    <p class="section-subtitle">Dosen pembimbing aktif terkait judul ini.</p>
                </div>
            </div>

            <div class="pembimbing-list">
                <div class="pembimbing-card">
                    <div class="pembimbing-name">Pembimbing 1</div>
                    <span class="badge badge-blue">
                        <?= esc((string) ($pembimbing1 ?: '-')) ?>
                    </span>
                </div>

                <div class="pembimbing-card">
                    <div class="pembimbing-name">Pembimbing 2</div>
                    <span class="badge badge-blue">
                        <?= esc((string) ($pembimbing2 ?: '-')) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-head">
                <div>
                    <h2 class="section-title">Riwayat Review Dosen</h2>
                    <p class="section-subtitle">Catatan dan keputusan dari dosen pembimbing.</p>
                </div>
            </div>

            <?php if (! empty($reviews)): ?>
                <div class="review-list">
                    <?php foreach ($reviews as $review): ?>
                        <?php $statusReview = $review['status_review'] ?? $review['status'] ?? '-'; ?>

                        <div class="review-card">
                            <div class="review-icon">R</div>

                            <div>
                                <div class="review-top">
                                    <div class="review-name">
                                       <?= esc((string) ($review['nama_dosen'] ?? $pembimbing1 ?? 'Dosen')) ?> 
                                    </div>

                                    <span class="badge <?= $badgeStatus($statusReview) ?>">
                                        <?= esc((string) $statusReview) ?>
                                    </span>
                                </div>

                                <div class="muted">
                                    <?= esc((string) ($review['created_at'] ?? $review['tanggal_review'] ?? '-')) ?>
                                </div>

                                <div class="review-note">
                                    <?php
$catatanMentah = (string) ($review['catatan'] ?? '');
$catatanBersih = trim(strip_tags(html_entity_decode($catatanMentah)));
?>

<?= nl2br(esc($catatanBersih !== '' ? $catatanBersih : 'Tidak ada catatan.')) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-box">
                    Belum ada riwayat review dari dosen.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>