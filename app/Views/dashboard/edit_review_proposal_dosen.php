<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$review = $review ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'direview'  => 'Direview',
        'disetujui' => 'Disetujui',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        default     => $safe($status, '-'),
    };
};

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'direview'  => 'badge-warning',
        'disetujui' => 'badge-success',
        'revisi'    => 'badge-info',
        'ditolak'   => 'badge-danger',
        default     => 'badge-muted',
    };
};

$reviewId = $safe($review['id'] ?? '', '');
?>

<div class="proposal-review-edit-page">
    <section class="proposal-review-edit-layout">
        <div class="card-main proposal-review-info-card">
            <div class="page-head">
                <div>
                    <h3>Informasi Proposal</h3>
                    <p>Ringkasan data proposal dan review yang sedang diperbarui.</p>
                </div>

                <span class="badge <?= esc($statusClass($review['status_review'] ?? '')) ?>">
                    <?= esc($statusLabel($review['status_review'] ?? '-')) ?>
                </span>
            </div>

            <div class="info-list proposal-info-list">
                <div class="info-item">
                    <span>Mahasiswa</span>
                    <strong><?= esc($safe($review['nama_mahasiswa'] ?? '-')) ?></strong>
                    <small>NIM: <?= esc($safe($review['nim'] ?? '-')) ?></small>
                </div>

                <div class="info-item">
                    <span>Judul Proposal</span>
                    <strong><?= esc($safe($review['judul'] ?? '-')) ?></strong>
                </div>

                <div class="info-item">
                    <span>File Proposal</span>
                    <strong><?= esc($safe($review['nama_file_asli'] ?? '-')) ?></strong>
                </div>

                <div class="info-item">
                    <span>Catatan Saat Ini</span>
                    <strong><?= esc($safe(($review['catatan'] ?? '') !== '' ? $review['catatan'] : '-')) ?></strong>
                </div>
            </div>
        </div>

        <div class="card-main proposal-review-form-card">
            <div class="page-head">
                <div>
                    <h3>Form Edit Review</h3>
                    <p>Pilih status review dan tuliskan catatan akademik dengan jelas.</p>
                </div>
            </div>

            <form
                method="post"
                action="<?= base_url('/dosen/proposal-ta/review/update/' . $reviewId) ?>"
                class="proposal-review-form"
            >
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="status_review">Status Review</label>
                    <select id="status_review" name="status_review" class="form-control" required>
                        <option value="direview" <?= ($review['status_review'] ?? '') === 'direview' ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui" <?= ($review['status_review'] ?? '') === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="revisi" <?= ($review['status_review'] ?? '') === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="ditolak" <?= ($review['status_review'] ?? '') === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan Review</label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        class="form-control textarea proposal-review-textarea"
                        placeholder="Tuliskan catatan review proposal secara ringkas dan akademik."
                    ><?= esc($safe($review['catatan'] ?? '', '')) ?></textarea>
                </div>

                <div class="form-actions proposal-review-actions">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="<?= base_url('/dosen/proposal-ta/riwayat') ?>" class="btn btn-outline">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </section>
</div>

<?= $this->endSection() ?>
