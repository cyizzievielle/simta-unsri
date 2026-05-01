<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.proposal-page {
    display: grid;
    gap: 18px;
}

.proposal-hero {
    background:
        radial-gradient(circle at top right, rgba(96,165,250,.32), transparent 30%),
        linear-gradient(135deg, #eff6ff, #ffffff);
    border: 1px solid #dbeafe;
    border-radius: 28px;
    padding: 26px;
    box-shadow: 0 18px 45px rgba(37,99,235,.08);
}

.proposal-hero h2 {
    margin: 0 0 8px;
    font-size: 28px;
    font-weight: 900;
    color: #0f172a;
}

.proposal-hero p {
    margin: 0;
    color: #64748b;
    line-height: 1.6;
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.stat-card {
    background: #fff;
    border: 1px solid #e5efff;
    border-radius: 22px;
    padding: 18px;
    box-shadow: 0 14px 30px rgba(37,99,235,.06);
}

.stat-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 900;
}

.stat-value {
    margin: 8px 0 4px;
    font-size: 30px;
    font-weight: 900;
    color: #0f172a;
}

.stat-desc {
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.card-soft {
    background: #fff;
    border: 1px solid #e5efff;
    border-radius: 26px;
    padding: 22px;
    box-shadow: 0 14px 34px rgba(37,99,235,.06);
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.card-head {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 16px;
}

.card-title {
    margin: 0 0 6px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.card-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
}

.highlight-box {
    border: 1px solid #bfdbfe;
    background: linear-gradient(135deg, #f8fbff, #eef6ff);
    border-radius: 22px;
    padding: 18px;
}

.highlight-title {
    font-size: 17px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.55;
    margin-bottom: 12px;
}

.meta-list {
    display: grid;
    gap: 9px;
    color: #64748b;
    font-size: 13px;
}

.meta-list strong {
    color: #334155;
}

.note-box {
    border: 1px dashed #bfdbfe;
    background: #f8fbff;
    border-radius: 20px;
    padding: 16px;
    color: #64748b;
    line-height: 1.7;
    font-size: 14px;
}

.btn-soft {
    border: none;
    border-radius: 15px;
    padding: 12px 16px;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color: #fff;
    font-weight: 900;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 14px 28px rgba(37,99,235,.18);
}

.btn-light {
    background: #fff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    box-shadow: none;
}

.upload-panel {
    display: none;
    margin-top: 16px;
    border-radius: 22px;
    border: 1px solid #dbeafe;
    background: #f8fbff;
    padding: 18px;
}

.upload-panel.show {
    display: block;
}

.form-grid {
    display: grid;
    gap: 14px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-size: 13px;
    font-weight: 900;
    color: #334155;
}

.input,
textarea,
input[type="file"] {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 15px;
    padding: 12px 14px;
    background: #fff;
    font-size: 14px;
    outline: none;
}

.input:focus,
textarea:focus,
input[type="file"]:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59,130,246,.12);
}

textarea {
    min-height: 105px;
    resize: vertical;
}

.help-text {
    margin-top: 7px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.status-pill {
    display: inline-flex;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
    text-transform: capitalize;
}

.status-diajukan { background: #dbeafe; color: #1d4ed8; }
.status-direview { background: #fef3c7; color: #92400e; }
.status-revisi { background: #ffedd5; color: #c2410c; }
.status-disetujui { background: #dcfce7; color: #166534; }
.status-ditolak { background: #fee2e2; color: #b91c1c; }

.table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 18px;
    border: 1px solid #eef2f7;
    -webkit-overflow-scrolling: touch;
}

.proposal-table {
    width: 100%;
    min-width: 1080px;
    border-collapse: collapse;
    background: #fff;
}

.proposal-table thead {
    background: #f8fbff;
}

.proposal-table th,
.proposal-table td {
    padding: 14px;
    border-bottom: 1px solid #eef2f7;
    text-align: left;
    vertical-align: top;
    font-size: 13px;
    line-height: 1.5;
}

.proposal-table th {
    color: #334155;
    font-size: 12px;
    font-weight: 900;
    white-space: nowrap;
}

.row-title {
    font-weight: 900;
    color: #0f172a;
    max-width: 260px;
}

.empty-box {
    padding: 22px;
    border-radius: 20px;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
    text-align: center;
    color: #64748b;
    line-height: 1.6;
}

.action-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

@media (max-width: 1100px) {
    .grid-2,
    .stat-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .proposal-page {
        gap: 14px;
    }

    .proposal-hero,
    .card-soft {
        padding: 16px;
        border-radius: 20px;
    }

    .proposal-hero h2 {
        font-size: 22px;
    }

    .proposal-hero p,
    .card-subtitle {
        font-size: 13px;
    }

    .stat-card {
        border-radius: 18px;
        padding: 15px;
    }

    .stat-value {
        font-size: 25px;
    }

    .card-head {
        flex-direction: column;
        align-items: stretch;
    }

    .card-title {
        font-size: 19px;
    }

    .btn-soft {
        width: 100%;
        padding: 11px 14px;
        font-size: 13px;
    }

    .upload-panel {
        padding: 14px;
        border-radius: 18px;
    }

    .highlight-box {
        padding: 14px;
        border-radius: 18px;
    }

    .highlight-title {
        font-size: 14px;
    }

    .proposal-table {
        min-width: 880px;
    }

    .proposal-table th,
    .proposal-table td {
        padding: 10px;
        font-size: 11px;
    }

    .status-pill {
        padding: 5px 8px;
        font-size: 10px;
    }

    .row-title {
        max-width: 190px;
        font-size: 11px;
    }
}
</style>

<?php
$judulDisetujui  = $judulDisetujui ?? null;
$riwayatProposal = $riwayatProposal ?? [];
$masihDiproses   = $masihDiproses ?? false;
$totalProposal   = $totalProposal ?? count($riwayatProposal);
$totalDisetujui  = $totalDisetujui ?? 0;

$statusClass = static function (?string $status): string {
    return match (strtolower((string) $status)) {
        'diajukan'  => 'status-diajukan',
        'direview'  => 'status-direview',
        'revisi'    => 'status-revisi',
        'disetujui' => 'status-disetujui',
        'ditolak'   => 'status-ditolak',
        default     => 'status-diajukan',
    };
};
?>

<div class="proposal-page">

    <div class="grid-2">
        <div class="card-soft">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Dasar Pengajuan</h3>
                    <p class="card-subtitle">Proposal dapat diajukan setelah judul TA disetujui.</p>
                </div>
            </div>

            <?php if ($judulDisetujui): ?>
                <div class="highlight-box">
                    <div class="highlight-title">
                        <?= esc((string) ($judulDisetujui['judul'] ?? '-')) ?>
                    </div>

                    <div class="meta-list">
                        <div><strong>Status Judul:</strong> <span class="status-pill status-disetujui"><?= esc((string) ($judulDisetujui['status'] ?? 'disetujui')) ?></span></div>
                        <div><strong>Bidang Topik:</strong> <?= esc((string) (($judulDisetujui['bidang_topik'] ?? '') !== '' ? $judulDisetujui['bidang_topik'] : '-')) ?></div>
                        <div><strong>Kata Kunci:</strong> <?= esc((string) (($judulDisetujui['kata_kunci'] ?? '') !== '' ? $judulDisetujui['kata_kunci'] : '-')) ?></div>
                        <div><strong>Tanggal Persetujuan:</strong> <?= esc((string) ($judulDisetujui['tanggal_review'] ?? $judulDisetujui['updated_at'] ?? $judulDisetujui['tanggal_pengajuan'] ?? '-')) ?></div>
                    </div>
                </div>
            <?php else: ?>
                <div class="note-box">
                    Belum ada judul yang berstatus <strong>disetujui</strong>, jadi proposal belum bisa diajukan.
                </div>
            <?php endif; ?>
        </div>

        <div class="card-soft">
            <div class="card-head">
                <div>
                    <h3 class="card-title">Upload Proposal</h3>
                    <p class="card-subtitle">Form upload tidak langsung terbuka. Klik tombol di bawah untuk mulai.</p>
                </div>
            </div>

            <?php if (! $judulDisetujui): ?>
                <div class="note-box">Form upload belum aktif karena judul belum disetujui.</div>
            <?php elseif ($masihDiproses): ?>
                <div class="note-box">Masih ada proposal yang sedang diproses. Tunggu review dosen terlebih dahulu.</div>
            <?php else: ?>
                <button type="button" class="btn-soft" onclick="toggleUploadProposal()">
                    Upload Proposal Baru
                </button>

                <div class="upload-panel" id="uploadProposalPanel">
                    <form action="<?= base_url('/proposal-ta/upload') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <input type="hidden" name="pengajuan_judul_id" value="<?= esc((string) ($judulDisetujui['id'] ?? '')) ?>">

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Judul Disetujui</label>
                                <input type="text" class="input" value="<?= esc((string) ($judulDisetujui['judul'] ?? '-')) ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label for="file_proposal">File Proposal</label>
                                <input type="file" name="file_proposal" id="file_proposal" required>
                                <div class="help-text">Format disarankan PDF/DOCX. Sesuaikan dengan validasi controller.</div>
                            </div>

                            <div class="form-group">
                                <label for="catatan_mahasiswa">Catatan Mahasiswa</label>
                                <textarea name="catatan_mahasiswa" id="catatan_mahasiswa" placeholder="Tulis catatan tambahan jika perlu..."></textarea>
                            </div>

                            <button type="submit" class="btn-soft">Simpan Proposal</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card-soft">
        <div class="card-head">
            <div>
                <h3 class="card-title">Riwayat Proposal</h3>
                <p class="card-subtitle">Data proposal yang pernah diunggah akan muncul di sini.</p>
            </div>
        </div>

        <?php if (! empty($riwayatProposal)): ?>
            <div class="table-wrap">
                <table class="proposal-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Tanggal Upload</th>
                            <th>Tanggal Review</th>
                            <th>Catatan Reviewer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayatProposal as $row): ?>
                            <?php
                                $status = strtolower((string) ($row['status'] ?? ''));
                                $proposalId = (string) ($row['id'] ?? '');
                            ?>

                            <tr>
                                <td>
                                    <div class="row-title"><?= esc((string) ($row['judul'] ?? '-')) ?></div>
                                </td>

                                    <td>
                                        <?php if (! empty($row['file_proposal'])): ?>
                                            <span style="color:#2563eb;font-weight:700;">File tersedia</span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                <td>
                                    <span class="status-pill <?= $statusClass($row['status'] ?? '') ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td><?= esc((string) ($row['versi_ke'] ?? '1')) ?></td>
                                <td><?= esc((string) ($row['tanggal_upload'] ?? $row['created_at'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['tanggal_review'] ?? '-')) ?></td>
                                <td><?= esc((string) (($row['catatan_reviewer'] ?? '') !== '' ? $row['catatan_reviewer'] : '-')) ?></td>

                                <td>
                                    <div class="action-row">
                                        <?php if (in_array($status, ['revisi', 'ditolak'], true) && $proposalId !== ''): ?>
                                            <a href="<?= base_url('/proposal-ta/revisi/' . $proposalId) ?>" class="btn-soft">
                                                Revisi
                                            </a>
                                        <?php else: ?>
                                            <span style="color:#94a3b8;">-</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">Belum ada riwayat proposal tugas akhir.</div>
        <?php endif; ?>
    </div>

</div>

<script>
function toggleUploadProposal() {
    const panel = document.getElementById('uploadProposalPanel');
    if (panel) {
        panel.classList.toggle('show');
    }
}
</script>

<?= $this->endSection() ?>