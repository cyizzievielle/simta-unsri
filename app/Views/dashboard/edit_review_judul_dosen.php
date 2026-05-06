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

$statusNow = $safe($review['status_review'] ?? 'direview', 'direview');
$catatanNow = $safe($review['catatan'] ?? '', '');
?>

<style>
.edit-review-page {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.soft-hero {
    background: linear-gradient(135deg, #f8fbff, #eef4ff);
    border: 1px solid #dbeafe;
    border-radius: 22px;
    padding: 18px;
    box-shadow: 0 10px 24px rgba(37, 99, 235, .06);
}

.soft-hero h2 {
    margin: 0 0 6px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.soft-hero p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.edit-wrap {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 14px;
}

.card-premium {
    background: #fff;
    border-radius: 24px;
    padding: 18px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
    border: 1px solid #eef2f7;
}

.section-title-premium {
    margin: 0 0 5px;
    font-size: 20px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle-premium {
    margin: 0 0 14px;
    color: #64748b;
    line-height: 1.55;
    font-size: 12.5px;
}

.info-list {
    display: grid;
    gap: 10px;
}

.info-box {
    border: 1px solid #eef2f7;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
    border-radius: 16px;
    padding: 13px;
}

.info-label {
    display: block;
    margin-bottom: 5px;
    font-size: 11px;
    font-weight: 900;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .03em;
}

.info-value {
    color: #0f172a;
    line-height: 1.55;
    font-size: 13px;
    font-weight: 750;
    word-break: break-word;
}

.judul-value {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.status-badge {
    display: inline-flex;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.status-direview {
    background: #fef3c7;
    color: #92400e;
}

.status-disetujui {
    background: #dcfce7;
    color: #166534;
}

.status-revisi {
    background: #fee2e2;
    color: #b91c1c;
}

.status-ditolak {
    background: #f1f5f9;
    color: #475569;
}

.form-edit {
    display: grid;
    gap: 12px;
}

.form-group-premium label {
    display: block;
    font-weight: 900;
    color: #334155;
    margin-bottom: 6px;
    font-size: 12px;
}

.form-group-premium select,
.form-group-premium textarea {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 14px;
    padding: 11px 12px;
    font-size: 13px;
    background: #fff;
    outline: none;
    box-sizing: border-box;
    font-family: inherit;
    color: #0f172a;
}

.form-group-premium select:focus,
.form-group-premium textarea:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
}

.form-group-premium textarea {
    min-height: 140px;
    resize: vertical;
    line-height: 1.6;
}

.review-note {
    border: 1px dashed #bfdbfe;
    background: #eff6ff;
    color: #1d4ed8;
    border-radius: 14px;
    padding: 11px;
    font-size: 12px;
    line-height: 1.55;
    font-weight: 750;
}

.action-row {
    display: flex;
    gap: 9px;
    flex-wrap: wrap;
}

.btn-save,
.btn-back {
    height: 38px;
    border-radius: 12px;
    padding: 0 13px;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.btn-save {
    border: 0;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    box-shadow: 0 12px 24px rgba(37, 99, 235, .18);
}

.btn-back {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #334155;
}

@media (max-width: 900px) {
    .edit-wrap {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .edit-review-page {
        gap: 12px;
    }

    .soft-hero {
        padding: 15px;
        border-radius: 18px;
    }

    .soft-hero h2 {
        font-size: 18px;
    }

    .soft-hero p {
        font-size: 11.5px;
    }

    .card-premium {
        padding: 13px;
        border-radius: 18px;
    }

    .section-title-premium {
        font-size: 17px;
    }

    .section-subtitle-premium {
        font-size: 11.5px;
        margin-bottom: 12px;
    }

    .info-list {
        gap: 8px;
    }

    .info-box {
        padding: 11px;
        border-radius: 14px;
    }

    .info-label {
        font-size: 10px;
    }

    .info-value {
        font-size: 11.5px;
    }

    .status-badge {
        font-size: 10px;
        padding: 5px 8px;
    }

    .form-group-premium label {
        font-size: 10.5px;
    }

    .form-group-premium select,
    .form-group-premium textarea {
        font-size: 11.5px;
        border-radius: 12px;
        padding: 9px 10px;
    }

    .form-group-premium textarea {
        min-height: 110px;
    }

    .review-note {
        font-size: 11px;
        padding: 10px;
        border-radius: 12px;
    }

    .action-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .btn-save,
    .btn-back {
        width: 100%;
        height: 36px;
        font-size: 11.5px;
        padding: 0 10px;
    }
}
</style>

<div class="edit-review-page">
    <div class="edit-wrap">
        <section class="card-premium">
            <h3 class="section-title-premium">Informasi Pengajuan</h3>
            <p class="section-subtitle-premium">
                Detail judul dan review yang sedang diedit.
            </p>

            <div class="info-list">
                <div class="info-box">
                    <span class="info-label">Mahasiswa</span>
                    <div class="info-value">
                        <?= esc($safe($review['nama_mahasiswa'] ?? '-')) ?><br>
                        NIM: <?= esc($safe($review['nim'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-label">Judul</span>
                    <div class="info-value judul-value">
                        <?= esc($safe($review['judul'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-box">
                    <span class="info-label">Review Saat Ini</span>
                    <div class="info-value">
                        <span class="status-badge status-<?= esc($safe($statusNow, 'direview')) ?>">
                            <?= esc(ucfirst($safe($statusNow, '-'))) ?>
                        </span>
                        <br><br>
                        Catatan: <?= esc($safe($catatanNow, '-')) ?>
                    </div>
                </div>
            </div>
        </section>

        <section class="card-premium">
            <h3 class="section-title-premium">Form Edit Review</h3>
            <p class="section-subtitle-premium">
                Simpan perubahan jika status atau catatan review perlu diperbarui.
            </p>

            <div class="review-note">
                Gunakan status <strong>Revisi</strong> jika mahasiswa perlu memperbaiki judul, dan <strong>Disetujui</strong> jika judul sudah layak dilanjutkan.
            </div>

            <form
                method="post"
                action="<?= base_url('/dosen/pengajuan-judul/review/update/' . $safe($review['id'] ?? '')) ?>"
                class="form-edit"
                style="margin-top:12px;"
            >
                <?= csrf_field() ?>

                <div class="form-group-premium">
                    <label for="status_review">Status Review</label>
                    <select name="status_review" id="status_review" required>
                        <option value="direview" <?= $statusNow === 'direview' ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui" <?= $statusNow === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="revisi" <?= $statusNow === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="ditolak" <?= $statusNow === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="form-group-premium">
                    <label for="catatan">Catatan</label>
                    <textarea
                        name="catatan"
                        id="catatan"
                        placeholder="Tulis catatan review judul..."
                    ><?= esc($safe($catatanNow, '')) ?></textarea>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn-save">
                        Simpan Perubahan
                    </button>

                    <a href="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>" class="btn-back">
                        Kembali
                    </a>
                </div>
            </form>
        </section>
    </div>

</div>

<?= $this->endSection() ?>