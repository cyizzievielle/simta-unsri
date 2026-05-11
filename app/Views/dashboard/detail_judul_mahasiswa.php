<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$judul          = $judul ?? [];
$reviews        = $reviews ?? [];
$similarityLogs = $similarityLogs ?? [];
$pembimbing1    = $pembimbing1 ?? '-';
$pembimbing2    = $pembimbing2 ?? '-';

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$cleanNote = static function (mixed $value): string {
    $text = is_scalar($value) ? trim((string) $value) : '';
    return $text !== '' ? $text : 'Belum ada catatan.';
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'badge-success',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        'diajukan',
        'direview'  => 'badge-info',
        default     => 'badge-muted',
    };
};

$status = $safe($judul['status'] ?? '-');

$similarityScore = (float) ($judul['similarity_score'] ?? 0);

$similarityClass = $similarityScore >= 80
    ? 'danger'
    : ($similarityScore >= 60 ? 'warning' : 'success');

$similarityLabel = $similarityScore >= 80
    ? 'Terlalu Mirip'
    : ($similarityScore >= 60 ? 'Perlu Ditinjau' : 'Aman');

$similarityDesc = $similarityScore >= 80
    ? 'Judul memiliki kemiripan tinggi dan perlu diperiksa ulang.'
    : ($similarityScore >= 60
        ? 'Judul cukup mirip dengan judul lain, namun masih dapat ditinjau pembimbing.'
        : 'Judul belum terdeteksi mirip signifikan dengan data yang ada.');

$similarityLog = $similarityLogs[0] ?? null;
?>

<div class="judul-detail-page">
    <section class="judul-detail-hero">
        <div>
            <span class="judul-detail-kicker">
                <i class="ri-file-search-fill"></i>
                Detail Pengajuan
            </span>

            <h2>Detail Pengajuan Judul</h2>
            <p>Lihat status, pembimbing, hasil similarity, dan catatan review judul tugas akhir kamu.</p>
        </div>

        <div class="judul-detail-actions">
            <a href="<?= base_url('/pengajuan-judul') ?>" class="btn btn-light">
                <i class="ri-arrow-left-line"></i>
                Kembali
            </a>

            <?php if (in_array(($judul['status'] ?? ''), ['revisi', 'ditolak'], true)): ?>
                <a href="<?= base_url('/pengajuan-judul/revisi/' . (int) ($judul['id'] ?? 0)) ?>" class="btn btn-primary">
                    <i class="ri-edit-2-line"></i>
                    Ajukan Revisi
                </a>
            <?php endif; ?>
        </div>
    </section>

    <div class="judul-detail-layout">
        <main class="judul-main">
            <section class="card-main detail-main-card">
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
                                <?= esc($status) ?>
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
                        <span>Tanggal Pengajuan</span>
                        <strong><?= esc($safe($judul['tanggal_pengajuan'] ?? $judul['created_at'] ?? '-')) ?></strong>
                    </div>

                    <div class="info-chip">
                        <span>Similarity</span>
                        <strong><?= esc((string) $similarityScore) ?>%</strong>
                    </div>
                </div>

                <div class="latar-box">
                    <h4>Latar Belakang</h4>
                    <p><?= nl2br(esc($safe($judul['latar_belakang'] ?? '-'))) ?></p>
                </div>
            </section>

            <section class="similarity-detail-card <?= esc($similarityClass) ?>">
                <div class="similarity-detail-icon">
                    <i class="ri-radar-line"></i>
                </div>

                <div class="similarity-detail-content">
                    <div class="similarity-detail-head">
                        <div>
                            <h4>Analisis Kemiripan Judul</h4>
                            <p><?= esc($similarityDesc) ?></p>
                        </div>

                        <span class="similarity-score-badge">
                            <?= esc((string) $similarityScore) ?>%
                        </span>
                    </div>

                    <div class="similarity-detail-body">
                        <div>
                            <span>Status Similarity</span>
                            <strong><?= esc($similarityLabel) ?></strong>
                        </div>

                        <div>
                            <span>Pembanding Terdekat</span>
                            <strong>
                                <?= esc($safe($similarityLog['judul_pembanding'] ?? null, 'Tidak ada pembanding signifikan')) ?>
                            </strong>
                        </div>

                        <div>
                            <span>Hasil Sistem</span>
                            <strong>
                                <?= esc($safe($similarityLog['hasil'] ?? null, $similarityScore >= 60 ? 'warning' : 'aman')) ?>
                            </strong>
                        </div>

                        <div>
                            <span>Skor Pembanding</span>
                            <strong>
                                <?= esc(isset($similarityLog['score']) ? (string) $similarityLog['score'] . '%' : (string) $similarityScore . '%') ?>
                            </strong>
                        </div>
                    </div>
                </div>
            </section>
        </main>

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