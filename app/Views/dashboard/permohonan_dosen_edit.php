<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$jenisLabel = static function (mixed $jenis) use ($safe): string {
    return match (strtolower($safe($jenis, ''))) {
        '2', 'pembimbing_2' => 'Pembimbing 2',
        default => 'Pembimbing 1',
    };
};

$status = $safe($row['status'] ?? 'menunggu', 'menunggu');
?>

<div class="request-page">
    <section class="card-main request-card-main">
        <div class="page-head">
            <div>
                <h3>Edit Keputusan Pembimbing</h3>
                <p>Perbarui status dan catatan keputusan pembimbing secara akademik dan terdokumentasi.</p>
            </div>

            <a href="<?= base_url('/dosen/permohonan') ?>" class="btn btn-outline">
                Kembali
            </a>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc((string) session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <div class="edit-summary">
            <div>
                <span>Mahasiswa</span>
                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
            </div>

            <div>
                <span>NIM</span>
                <strong><?= esc($safe($row['nim'] ?? '-')) ?></strong>
            </div>

            <div>
                <span>Jenis Pembimbing</span>
                <strong><?= esc($jenisLabel($row['jenis_pembimbing'] ?? '')) ?></strong>
            </div>
        </div>

        <form action="<?= base_url('/dosen/permohonan/update-riwayat/' . $safe($row['id'] ?? '0')) ?>" method="post" class="edit-decision-form">
            <?= csrf_field() ?>

            <div class="form-grid form-grid-2">
                <div class="form-group">
                    <label for="status">Status Keputusan</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="menunggu" <?= $status === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="kuota_penuh" <?= $status === 'kuota_penuh' ? 'selected' : '' ?>>Kuota Penuh</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Respon Terakhir</label>
                    <input type="text" class="form-control" value="<?= esc($safe($row['tanggal_respon'] ?? '-')) ?>" readonly>
                </div>

                <div class="form-group form-full">
                    <label for="catatan">Catatan Keputusan</label>
                    <textarea name="catatan" id="catatan" class="form-control textarea" placeholder="Tuliskan catatan keputusan secara singkat dan jelas."><?= esc($safe($row['catatan'] ?? '', '')) ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="<?= base_url('/dosen/permohonan') ?>" class="btn btn-outline">
                    Batal
                </a>
            </div>
        </form>
    </section>
</div>

<?= $this->endSection() ?>