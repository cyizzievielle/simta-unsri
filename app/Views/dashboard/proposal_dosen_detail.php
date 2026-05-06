<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$proposal = $proposal ?? [];
$reviewSaya = $reviewSaya ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$statusProposal = $safe($proposal['status'] ?? 'diajukan', 'diajukan');
$oldStatus = $safe(old('status_review', $reviewSaya['status_review'] ?? 'direview'), 'direview');
$oldCatatan = $safe(old('catatan', $reviewSaya['catatan'] ?? ''), '');

$fileProposal = $safe($proposal['file_proposal'] ?? '', '');
$fileAsli = $safe($proposal['nama_file_asli'] ?? '', '');

$fileUrl = $fileProposal !== ''
    ? base_url('uploads/proposal/' . $fileProposal)
    : '';

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'status-diajukan',
        'direview'  => 'status-direview',
        'disetujui' => 'status-disetujui',
        'revisi'    => 'status-revisi',
        'ditolak'   => 'status-ditolak',
        default     => 'status-diajukan',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'Diajukan',
        'direview'  => 'Direview',
        'disetujui' => 'Disetujui',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        default     => $safe($status, '-'),
    };
};
?>

<style>
.proposal-detail-page {
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
    letter-spacing: -.02em;
}

.soft-hero p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.detail-grid {
    display: grid;
    grid-template-columns: 1.15fr .85fr;
    gap: 14px;
}

.card-premium {
    background: #fff;
    border: 1px solid #eef2f7;
    border-radius: 24px;
    padding: 18px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
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
    font-size: 12.5px;
    line-height: 1.55;
}

.info-list {
    display: grid;
    gap: 10px;
}

.info-item {
    border: 1px solid #eef2f7;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
    border-radius: 16px;
    padding: 13px;
}

.info-label {
    display: block;
    margin-bottom: 5px;
    color: #64748b;
    font-size: 11px;
    font-weight: 850;
}

.info-value {
    color: #0f172a;
    font-size: 13px;
    font-weight: 800;
    line-height: 1.55;
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

.status-diajukan {
    background: #dbeafe;
    color: #1d4ed8;
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

.file-card {
    border: 1px solid #dbeafe;
    background: linear-gradient(135deg, #f8fbff, #eef4ff);
    border-radius: 18px;
    padding: 14px;
}

.file-title {
    margin: 0 0 5px;
    color: #0f172a;
    font-size: 13px;
    font-weight: 900;
    line-height: 1.4;
    word-break: break-word;
}

.file-meta {
    margin: 0;
    color: #64748b;
    font-size: 11px;
    line-height: 1.45;
    word-break: break-word;
}

.file-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 12px;
}

.file-btn {
    height: 34px;
    padding: 0 12px;
    border-radius: 11px;
    font-size: 11.5px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

.file-open {
    background: #2563eb;
    color: #fff;
    box-shadow: 0 10px 20px rgba(37, 99, 235, .16);
}

.file-download {
    background: #fff;
    color: #334155;
    border: 1px solid #cbd5e1;
}

.review-form {
    display: grid;
    gap: 12px;
}

.form-group-premium label {
    display: block;
    margin-bottom: 6px;
    color: #334155;
    font-size: 12px;
    font-weight: 900;
}

.form-group-premium select,
.form-group-premium textarea {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 14px;
    padding: 11px 12px;
    background: #fff;
    color: #0f172a;
    font-size: 13px;
    outline: none;
    font-family: inherit;
}

.form-group-premium textarea {
    min-height: 125px;
    resize: vertical;
    line-height: 1.6;
}

.form-group-premium select:focus,
.form-group-premium textarea:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
}

.btn-submit {
    width: 100%;
    height: 42px;
    border: 0;
    border-radius: 14px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    cursor: pointer;
    font-size: 13px;
    font-weight: 900;
    box-shadow: 0 12px 24px rgba(37, 99, 235, .18);
}

.btn-submit:hover {
    filter: brightness(.98);
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

.back-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    padding: 0 13px;
    border-radius: 12px;
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    width: fit-content;
}

@media (max-width: 900px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .proposal-detail-page {
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

    .info-item {
        padding: 11px;
        border-radius: 14px;
    }

    .info-label {
        font-size: 10px;
    }

    .info-value {
        font-size: 11.5px;
    }

    .judul-value {
        -webkit-line-clamp: 4;
        line-clamp: 4;
    }

    .status-badge {
        font-size: 10px;
        padding: 5px 8px;
    }

    .file-card {
        padding: 12px;
        border-radius: 16px;
    }

    .file-title {
        font-size: 11.5px;
    }

    .file-meta {
        font-size: 10px;
    }

    .file-btn {
        height: 30px;
        padding: 0 10px;
        font-size: 10.5px;
        border-radius: 10px;
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
        min-height: 105px;
    }

    .btn-submit {
        height: 38px;
        font-size: 12px;
        border-radius: 12px;
    }

    .review-note {
        font-size: 11px;
        padding: 10px;
        border-radius: 12px;
    }

    .back-link {
        height: 34px;
        font-size: 11px;
    }
}
</style>

<div class="proposal-detail-page">
    <a href="<?= base_url('/dosen/proposal-ta') ?>" class="back-link">
        Kembali
    </a>

    <div class="detail-grid">

        <section class="card-premium">
            <h3 class="section-title-premium">Informasi Proposal</h3>
            <p class="section-subtitle-premium">
                Detail proposal tugas akhir mahasiswa bimbingan.
            </p>

            <div class="info-list">
                <div class="info-item">
                    <span class="info-label">Mahasiswa</span>
                    <div class="info-value">
                        <?= esc($safe($proposal['nama_mahasiswa'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">NIM</span>
                    <div class="info-value">
                        <?= esc($safe($proposal['nim'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Judul Tugas Akhir</span>
                    <div class="info-value judul-value">
                        <?= esc($safe($proposal['judul'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Status Proposal</span>
                    <div class="info-value">
                        <span class="status-badge <?= esc($statusClass($statusProposal)) ?>">
                            <?= esc($statusLabel($statusProposal)) ?>
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Tanggal Upload</span>
                    <div class="info-value">
                        <?= esc($safe($proposal['tanggal_upload'] ?? '-')) ?>
                    </div>
                </div>

                <div class="info-item">
                    <span class="info-label">Versi</span>
                    <div class="info-value">
                        v<?= esc($safe($proposal['versi_ke'] ?? '1')) ?>
                    </div>
                </div>

                <div class="file-card">
                    <h4 class="file-title">
                        <?= esc($safe($fileAsli !== '' ? $fileAsli : 'File proposal')) ?>
                    </h4>

                    <p class="file-meta">
                        File sistem: <?= esc($safe($fileProposal !== '' ? $fileProposal : '-')) ?>
                    </p>

                    <?php if ($fileUrl !== ''): ?>
                        <div class="file-actions">
                            <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="file-btn file-open">
                                Buka File
                            </a>

                            <a href="<?= esc($fileUrl) ?>" download class="file-btn file-download">
                                Download
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section class="card-premium">
            <h3 class="section-title-premium">Review Proposal</h3>
            <p class="section-subtitle-premium">
                Pilih status review dan tuliskan catatan untuk mahasiswa.
            </p>

            <div class="review-note">
                Gunakan status <strong>Direview</strong> jika masih tahap pengecekan. Gunakan <strong>Revisi</strong> jika mahasiswa perlu memperbaiki proposal.
            </div>

            <form
                action="<?= base_url('/dosen/proposal-ta/' . $safe($proposal['id'] ?? '') . '/review') ?>"
                method="post"
                class="review-form"
                style="margin-top:12px;"
            >
                <?= csrf_field() ?>

                <div class="form-group-premium">
                    <label for="status_review">Status Review</label>
                    <select name="status_review" id="status_review" required>
                        <option value="direview" <?= $oldStatus === 'direview' ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui" <?= $oldStatus === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="revisi" <?= $oldStatus === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="ditolak" <?= $oldStatus === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="form-group-premium">
                    <label for="catatan">Catatan Review</label>
                    <textarea
                        name="catatan"
                        id="catatan"
                        placeholder="Tulis catatan review proposal..."
                    ><?= esc($safe($oldCatatan, '')) ?></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    Simpan Review
                </button>
            </form>
        </section>

    </div>

</div>

<?= $this->endSection() ?>