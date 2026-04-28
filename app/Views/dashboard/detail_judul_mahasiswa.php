<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.detail-page {
    --blue: #2563eb;
    --blue-soft: #dbeafe;
    --green: #16a34a;
    --green-soft: #dcfce7;
    --orange: #ea580c;
    --orange-soft: #ffedd5;
    --red: #dc2626;
    --red-soft: #fee2e2;
    --text: #0f172a;
    --muted: #64748b;
    --line: #e2e8f0;

    width: 100%;
}

.detail-hero {
    background: linear-gradient(135deg, #0f172a, #2563eb);
    color: #fff;
    border-radius: 30px;
    padding: 30px;
    margin-bottom: 22px;
    box-shadow: 0 22px 44px rgba(37,99,235,.18);
    overflow: hidden;
    position: relative;
}

.detail-hero::after {
    content: "";
    position: absolute;
    width: 210px;
    height: 210px;
    border-radius: 999px;
    right: -70px;
    top: -70px;
    background: rgba(255,255,255,.12);
}

.detail-hero-content {
    position: relative;
    z-index: 1;
}

.hero-kicker {
    display: inline-flex;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    font-weight: 900;
    font-size: 12px;
    margin-bottom: 12px;
}

.hero-title {
    margin: 0 0 10px;
    font-size: 32px;
    font-weight: 950;
    line-height: 1.25;
}

.hero-desc {
    margin: 0;
    color: rgba(255,255,255,.86);
    line-height: 1.7;
}

.detail-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 0;
    border-radius: 16px;
    padding: 12px 18px;
    font-weight: 900;
    text-decoration: none;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    box-shadow: 0 12px 26px rgba(37,99,235,.18);
}

.btn-light {
    background: #fff;
    color: #334155;
    border: 1px solid #dbe3ef;
    box-shadow: none;
}

.detail-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.15fr) minmax(340px, .85fr);
    gap: 22px;
    align-items: start;
}

.detail-card {
    background: rgba(255,255,255,.92);
    border: 1px solid rgba(226,232,240,.95);
    border-radius: 28px;
    padding: 26px;
    box-shadow: 0 18px 42px rgba(15,23,42,.055);
    margin-bottom: 22px;
    backdrop-filter: blur(8px);
}

.card-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 18px;
}

.card-title {
    margin: 0 0 7px;
    font-size: 25px;
    font-weight: 950;
    color: var(--text);
    line-height: 1.25;
}

.card-subtitle {
    margin: 0;
    color: var(--muted);
    line-height: 1.6;
}

.badge {
    display: inline-flex;
    align-items: center;
    width: fit-content;
    max-width: 100%;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 950;
    white-space: normal;
    line-height: 1.35;
}

.badge-green { background: var(--green-soft); color: #166534; }
.badge-blue { background: var(--blue-soft); color: #1d4ed8; }
.badge-orange { background: var(--orange-soft); color: #9a3412; }
.badge-red { background: var(--red-soft); color: #991b1b; }
.badge-gray { background: #e2e8f0; color: #475569; }

.title-card {
    padding: 22px;
    border-radius: 24px;
    background: linear-gradient(135deg, #f8fbff, #eef6ff);
    border: 1px solid #dbeafe;
    margin-bottom: 18px;
}

.title-text {
    color: var(--text);
    font-size: 25px;
    font-weight: 950;
    line-height: 1.55;
    word-break: break-word;
    overflow-wrap: anywhere;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 14px;
}

.info-chip {
    padding: 16px;
    border: 1px solid #edf2f7;
    background: #f8fafc;
    border-radius: 20px;
}

.info-label {
    color: var(--muted);
    font-size: 13px;
    font-weight: 900;
    margin-bottom: 8px;
}

.info-value {
    color: var(--text);
    font-size: 15px;
    font-weight: 950;
    line-height: 1.5;
    word-break: break-word;
    overflow-wrap: anywhere;
}

.latar-card {
    margin-top: 16px;
    padding: 20px;
    border-radius: 22px;
    background: #fff;
    border: 1px solid #edf2f7;
}

.latar-title {
    font-size: 16px;
    font-weight: 950;
    color: var(--text);
    margin-bottom: 10px;
}

.latar-text {
    color: #334155;
    line-height: 1.8;
    word-break: break-word;
    overflow-wrap: anywhere;
}

.side-list {
    display: grid;
    gap: 14px;
}

.mentor-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px;
    border-radius: 22px;
    border: 1px solid #edf2f7;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
}

.mentor-avatar {
    width: 48px;
    height: 48px;
    border-radius: 17px;
    display: grid;
    place-items: center;
    color: #fff;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    font-weight: 950;
    flex-shrink: 0;
}

.mentor-body {
    min-width: 0;
}

.mentor-role {
    color: var(--muted);
    font-size: 13px;
    font-weight: 900;
    margin-bottom: 5px;
}

.mentor-name {
    color: var(--text);
    font-weight: 950;
    line-height: 1.45;
    word-break: break-word;
    overflow-wrap: anywhere;
    font-size: 13px;  
}

.review-card {
    display: grid;
    grid-template-columns: 50px minmax(0,1fr);
    gap: 14px;
    padding: 18px;
    border-radius: 22px;
    border: 1px solid #edf2f7;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
}

.review-icon {
    width: 50px;
    height: 50px;
    border-radius: 17px;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #7c3aed, #2563eb);
    color: #fff;
    font-weight: 950;
}

.review-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}

.review-name {
    color: var(--text);
    font-weight: 950;
    line-height: 1.4;
}

.review-date {
    color: var(--muted);
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 10px;
}

.review-note {
    color: #334155;
    line-height: 1.7;
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 18px;
    padding: 14px;
    word-break: break-word;
    overflow-wrap: anywhere;
}

.empty-state {
    padding: 22px;
    border-radius: 22px;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
    color: var(--muted);
    text-align: center;
    line-height: 1.7;
}

@media (max-width: 1120px) {
    .detail-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .detail-hero {
        padding: 24px 20px;
        border-radius: 24px;
    }

    .hero-title {
        font-size: 24px;
    }

    .hero-desc {
        font-size: 14px;
    }

    .detail-actions {
        flex-direction: column;
    }

    .btn-modern {
        width: 100%;
    }

    .detail-card {
        padding: 20px;
        border-radius: 24px;
        margin-bottom: 16px;
    }

    .card-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .card-title {
        font-size: 22px;
    }

    .card-subtitle {
        font-size: 14px;
    }

    .title-card {
        padding: 18px;
        border-radius: 20px;
    }

    .title-text {
        font-size: 16px;
        line-height: 1.6;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .mentor-card {
        align-items: flex-start;
    }

    .review-card {
        grid-template-columns: 44px minmax(0,1fr);
        padding: 16px;
        border-radius: 20px;
    }

    .review-icon,
    .mentor-avatar {
        width: 44px;
        height: 44px;
        border-radius: 15px;
    }
}

@media (max-width: 430px) {
    .detail-card {
        padding: 16px;
        border-radius: 20px;
    }

    .detail-hero {
        padding: 22px 18px;
    }

    .hero-title {
        font-size: 22px;
    }

    .card-title {
        font-size: 20px;
    }

    .review-card {
        grid-template-columns: 1fr;
    }
}
</style>

<?php
$judul = $judul ?? $pengajuan ?? [];
$reviews = $reviews ?? $reviewJudul ?? $riwayatReview ?? [];

$pembimbing1 = $pembimbing1 ?? ($judul['pembimbing_1'] ?? null);
$pembimbing2 = $pembimbing2 ?? ($judul['pembimbing_2'] ?? null);

$status = strtolower((string) ($judul['status'] ?? '-'));

$badgeStatus = static function (?string $status): string {
    $status = strtolower((string) $status);

    return match ($status) {
        'disetujui' => 'badge-green',
        'revisi' => 'badge-orange',
        'ditolak' => 'badge-red',
        'diajukan', 'direview', 'menunggu' => 'badge-blue',
        default => 'badge-gray',
    };
};

$cleanNote = static function ($text): string {
    $raw = (string) ($text ?? '');
    $clean = trim(strip_tags(html_entity_decode($raw)));

    return $clean !== '' ? $clean : 'Tidak ada catatan.';
};
?>

<div class="detail-page">


    <div class="detail-actions">
        <a href="<?= base_url('/pengajuan-judul') ?>" class="btn-modern btn-light">← Kembali</a>

        <?php if ($status === 'revisi'): ?>
            <a href="<?= base_url('/pengajuan-judul/revisi/' . ($judul['id'] ?? 0)) ?>" class="btn-modern">Ajukan Revisi</a>
        <?php endif; ?>
    </div>

    <div class="detail-layout">

        <div>
            <div class="detail-card">
                <div class="card-head">
                    <div>
                        <h2 class="card-title">Detail Pengajuan Judul</h2>
                        <p class="card-subtitle">Informasi utama judul tugas akhir yang kamu ajukan.</p>
                    </div>

                    <span class="badge <?= $badgeStatus($status) ?>">
                        <?= esc((string) ($judul['status'] ?? '-')) ?>
                    </span>
                </div>

                <div class="title-card">
                    <div class="title-text">
                        <?= esc((string) ($judul['judul'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-chip">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge <?= $badgeStatus($status) ?>">
                                <?= esc((string) ($judul['status'] ?? '-')) ?>
                            </span>
                        </div>
                    </div>

                    <div class="info-chip">
                        <div class="info-label">Versi</div>
                        <div class="info-value"><?= esc((string) ($judul['versi_ke'] ?? '1')) ?></div>
                    </div>

                    <div class="info-chip">
                        <div class="info-label">Bidang Topik</div>
                        <div class="info-value"><?= esc((string) (($judul['bidang_topik'] ?? '') !== '' ? $judul['bidang_topik'] : '-')) ?></div>
                    </div>

                    <div class="info-chip">
                        <div class="info-label">Kata Kunci</div>
                        <div class="info-value"><?= esc((string) (($judul['kata_kunci'] ?? '') !== '' ? $judul['kata_kunci'] : '-')) ?></div>
                    </div>

                    <div class="info-chip">
                        <div class="info-label">Similarity</div>
                        <div class="info-value"><?= esc((string) (($judul['similarity_score'] ?? '') !== '' ? $judul['similarity_score'] . '%' : '-')) ?></div>
                    </div>

                    <div class="info-chip">
                        <div class="info-label">Tanggal Pengajuan</div>
                        <div class="info-value"><?= esc((string) ($judul['tanggal_pengajuan'] ?? $judul['created_at'] ?? '-')) ?></div>
                    </div>
                </div>

                <div class="latar-card">
                    <div class="latar-title">Latar Belakang</div>
                    <div class="latar-text">
                        <?= nl2br(esc((string) (($judul['latar_belakang'] ?? '') !== '' ? $judul['latar_belakang'] : '-'))) ?>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="detail-card">
                <div class="card-head">
                    <div>
                        <h2 class="card-title">Pembimbing</h2>
                        <p class="card-subtitle">Dosen pembimbing aktif terkait judul ini.</p>
                    </div>
                </div>

                <div class="side-list">
                    <div class="mentor-card">
                        <div class="mentor-avatar">P1</div>
                        <div class="mentor-body">
                            <div class="mentor-role">Pembimbing 1</div>
                            <div class="mentor-name"><?= esc((string) ($pembimbing1 ?: '-')) ?></div>
                        </div>
                    </div>

                    <div class="mentor-card">
                        <div class="mentor-avatar">P2</div>
                        <div class="mentor-body">
                            <div class="mentor-role">Pembimbing 2</div>
                            <div class="mentor-name"><?= esc((string) ($pembimbing2 ?: '-')) ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-card">
                <div class="card-head">
                    <div>
                        <h2 class="card-title">Riwayat Review Dosen</h2>
                        <p class="card-subtitle">Catatan dan keputusan dari dosen pembimbing.</p>
                    </div>
                </div>

                <?php if (! empty($reviews)): ?>
                    <div class="side-list">
                        <?php foreach ($reviews as $review): ?>
                            <?php
                            $statusReview = strtolower((string) ($review['status_review'] ?? $review['status'] ?? '-'));
                            $catatan = $cleanNote($review['catatan'] ?? '');
                            ?>

                            <div class="review-card">
                                <div class="review-icon">R</div>

                                <div>
                                    <div class="review-top">
                                        <div class="review-name">
                                            <?= esc((string) ($review['nama_dosen'] ?? $pembimbing1 ?? 'Dosen')) ?>
                                        </div>

                                        <span class="badge <?= $badgeStatus($statusReview) ?>">
                                            <?= esc((string) ($review['status_review'] ?? $review['status'] ?? '-')) ?>
                                        </span>
                                    </div>

                                    <div class="review-date">
                                        <?= esc((string) ($review['created_at'] ?? $review['tanggal_review'] ?? '-')) ?>
                                    </div>

                                    <div class="review-note">
                                        <?= nl2br(esc($catatan)) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">Belum ada riwayat review dari dosen.</div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>