<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$judul   = $judul ?? $pengajuan ?? [];
$reviews = $reviews ?? $reviewJudul ?? $riwayatReview ?? [];

$pembimbing1 = $pembimbing1 ?? ($judul['pembimbing_1'] ?? null);
$pembimbing2 = $pembimbing2 ?? ($judul['pembimbing_2'] ?? null);

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$status = strtolower($safe($judul['status'] ?? '-', '-'));

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'badge-success',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        'diajukan',
        'direview',
        'menunggu'  => 'badge-info',
        default     => 'badge-muted',
    };
};

$cleanNote = static function (mixed $text): string {
    $raw = (string) ($text ?? '');
    $clean = trim(strip_tags(html_entity_decode($raw)));

    return $clean !== '' ? $clean : 'Tidak ada catatan.';
};
?>

<div class="judul-detail-page">

    <div class="detail-actions">
        <a href="<?= base_url('/pengajuan-judul') ?>" class="btn btn-outline">← Kembali</a>

        <?php if ($status === 'revisi'): ?>
            <a href="<?= base_url('/pengajuan-judul/revisi/' . $safe($judul['id'] ?? '0')) ?>" class="btn btn-primary">
                Ajukan Revisi
            </a>
        <?php endif; ?>
    </div>

    <div class="judul-detail-layout">

        <section class="card-main">
            <div class="page-head">
                <div>
                    <h3>Informasi Judul</h3>
                    <p>Data utama judul tugas akhir yang kamu ajukan.</p>
                </div>
            </div>

            <div class="title-box">
                <?= esc($safe($judul['judul'] ?? '-')) ?>
            </div>

            <div class="info-grid">
                <div class="info-chip">
                    <span>Status</span>
                    <strong>
                        <span class="badge <?= esc($badgeStatus($status)) ?>">
                            <?= esc($safe($judul['status'] ?? '-')) ?>
                        </span>
                    </strong>
                </div>

                <div class="info-chip">
                    <span>Versi</span>
                    <strong>v<?= esc($safe($judul['versi_ke'] ?? '1')) ?></strong>
                </div>

                <div class="info-chip">
                    <span>Bidang Topik</span>
                    <strong><?= esc($safe($judul['bidang_topik'] ?? '-')) ?></strong>
                </div>

                <div class="info-chip">
                    <span>Kata Kunci</span>
                    <strong><?= esc($safe($judul['kata_kunci'] ?? '-')) ?></strong>
                </div>

                <div class="info-chip">
                    <span>Similarity</span>
                    <strong>
                        <?= esc(($safe($judul['similarity_score'] ?? '', '') !== '') ? $safe($judul['similarity_score']) . '%' : '-') ?>
                    </strong>
                </div>

                <div class="info-chip">
                    <span>Tanggal Pengajuan</span>
                    <strong><?= esc($safe($judul['tanggal_pengajuan'] ?? $judul['created_at'] ?? '-')) ?></strong>
                </div>
            </div>

            <div class="latar-box">
                <h4>Latar Belakang</h4>
                <p><?= nl2br(esc($safe($judul['latar_belakang'] ?? '-'))) ?></p>
            </div>
        </section>

        <aside class="judul-side">
            <section class="card-main">
                <div class="page-head">
                    <div>
                        <h3>Pembimbing</h3>
                        <p>Dosen pembimbing aktif terkait judul ini.</p>
                    </div>
                </div>

                <div class="mentor-list">
                    <div class="mentor-card">
                        <div class="mentor-avatar">P1</div>
                        <div>
                            <span>Pembimbing 1</span>
                            <strong><?= esc($safe($pembimbing1 ?: '-')) ?></strong>
                        </div>
                    </div>

                    <div class="mentor-card">
                        <div class="mentor-avatar">P2</div>
                        <div>
                            <span>Pembimbing 2</span>
                            <strong><?= esc($safe($pembimbing2 ?: '-')) ?></strong>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card-main">
                <div class="page-head">
                    <div>
                        <h3>Riwayat Review</h3>
                        <p>Catatan dan keputusan dari dosen pembimbing.</p>
                    </div>
                </div>

                <?php if (! empty($reviews) && is_array($reviews)): ?>
                    <div class="review-list">
                        <?php foreach ($reviews as $review): ?>
                            <?php
                                $statusReview = $safe($review['status_review'] ?? $review['status'] ?? '-');
                                $catatan = $cleanNote($review['catatan'] ?? '');
                            ?>

                            <div class="review-card">
                                <div class="review-icon">R</div>

                                <div class="review-body">
                                    <div class="review-top">
                                        <strong><?= esc($safe($review['nama_dosen'] ?? $pembimbing1 ?? 'Dosen')) ?></strong>
                                        <span class="badge <?= esc($badgeStatus($statusReview)) ?>">
                                            <?= esc($statusReview) ?>
                                        </span>
                                    </div>

                                    <div class="review-date">
                                        <?= esc($safe($review['created_at'] ?? $review['tanggal_review'] ?? '-')) ?>
                                    </div>

                                    <div class="review-note">
                                        <?= nl2br(esc($catatan)) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-box">Belum ada riwayat review dari dosen.</div>
                <?php endif; ?>
            </section>
        </aside>

    </div>
</div>

<?= $this->endSection() ?>