<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php $review = $review ?? []; ?>

<style>
    .edit-wrap {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 22px;
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
        margin: 0 0 8px;
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-subtitle-premium {
        margin: 0 0 16px;
        color: #64748b;
        line-height: 1.7;
    }

    .info-box {
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 14px;
    }

    .info-label {
        font-size: 13px;
        font-weight: 800;
        color: #475569;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

    .info-value {
        color: #0f172a;
        line-height: 1.75;
        font-size: 15px;
    }

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
        min-height: 180px;
        resize: vertical;
    }

    .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 1100px) {
        .edit-wrap {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card-premium">
    <h3 class="section-title-premium">✏️ Edit Review Judul</h3>
    <p class="section-subtitle-premium">Perbarui status review dan catatan untuk pengajuan judul ini.</p>
</div>

<div class="edit-wrap">
    <div class="card-premium">
        <h4 class="section-title-premium" style="font-size:22px;">Informasi Pengajuan</h4>

        <div class="info-box">
            <div class="info-label">Mahasiswa</div>
            <div class="info-value">
                <?= esc((string) ($review['nama_mahasiswa'] ?? '-')) ?><br>
                NIM: <?= esc((string) ($review['nim'] ?? '-')) ?>
            </div>
        </div>

        <div class="info-box">
            <div class="info-label">Judul</div>
            <div class="info-value">
                <?= esc((string) ($review['judul'] ?? '-')) ?>
            </div>
        </div>

        <div class="info-box">
            <div class="info-label">Review Saat Ini</div>
            <div class="info-value">
                Status: <?= esc((string) ($review['status_review'] ?? '-')) ?><br>
                Catatan: <?= esc((string) (($review['catatan'] ?? '') !== '' ? $review['catatan'] : '-')) ?>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <h4 class="section-title-premium" style="font-size:22px;">Form Edit Review</h4>

        <form method="post" action="<?= base_url('/dosen/pengajuan-judul/review/update/' . (string) ($review['id'] ?? '')) ?>">
            <?= csrf_field() ?>

            <div class="form-group-premium">
                <label>Status Review</label>
                <select name="status_review" required>
                    <option value="direview" <?= ($review['status_review'] ?? '') === 'direview' ? 'selected' : '' ?>>Direview</option>
                    <option value="disetujui" <?= ($review['status_review'] ?? '') === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="revisi" <?= ($review['status_review'] ?? '') === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                    <option value="ditolak" <?= ($review['status_review'] ?? '') === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>

            <div class="form-group-premium">
                <label>Catatan</label>
                <textarea name="catatan"><?= esc((string) ($review['catatan'] ?? '')) ?></textarea>
            </div>

            <div class="action-row">
                <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                <a href="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155;">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>