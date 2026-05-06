<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$judulDisetujui  = $judulDisetujui ?? null;
$riwayatProposal = $riwayatProposal ?? $riwayat ?? $rows ?? [];
$masihDiproses   = $masihDiproses ?? false;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
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
?>

<div class="proposal-ta-page">
    <section class="proposal-step-grid">
        <div class="proposal-step">
            <span>1</span>
            <strong>Judul Disetujui</strong>
            <p>Proposal hanya bisa diunggah setelah judul TA kamu disetujui.</p>
        </div>

        <div class="proposal-step">
            <span>2</span>
            <strong>Upload File</strong>
            <p>Unggah file proposal dan tambahkan catatan jika diperlukan.</p>
        </div>

        <div class="proposal-step">
            <span>3</span>
            <strong>Review Dosen</strong>
            <p>Dosen pembimbing meninjau proposal dan memberi catatan.</p>
        </div>

        <div class="proposal-step">
            <span>4</span>
            <strong>Keputusan</strong>
            <p>Proposal dapat disetujui, ditolak, atau diminta revisi.</p>
        </div>
    </section>

<section class="proposal-main-card">
    <div class="page-head">
        <div>
            <h3>Dasar Pengajuan</h3>
            <p>Judul TA yang sudah disetujui sebagai dasar upload proposal.</p>
        </div>
    </div>

    <?php if ($judulDisetujui): ?>
        <div class="proposal-title-box">
            <div class="proposal-title-main">
                <?= esc($safe($judulDisetujui['judul'] ?? '-')) ?>
            </div>

            <div class="proposal-meta-grid">
                <div>
                    <span>Status Judul</span>
                    <strong><span class="badge badge-success">Disetujui</span></strong>
                </div>

                <div>
                    <span>Bidang Topik</span>
                    <strong><?= esc($safe($judulDisetujui['bidang_topik'] ?? '-')) ?></strong>
                </div>

                <div>
                    <span>Kata Kunci</span>
                    <strong><?= esc($safe($judulDisetujui['kata_kunci'] ?? '-')) ?></strong>
                </div>

                <div>
                    <span>Tanggal Persetujuan</span>
                    <strong><?= esc($safe($judulDisetujui['updated_at'] ?? $judulDisetujui['tanggal_pengajuan'] ?? '-')) ?></strong>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="empty-box">Belum ada judul yang disetujui.</div>
    <?php endif; ?>

<div class="upload-box" style="margin-top:16px;">

    <div class="upload-head">
        <div>
            <h3>Upload Proposal</h3>
            <p>Unggah file proposal setelah judul kamu disetujui.</p>
        </div>

        <?php if ($judulDisetujui && ! $masihDiproses): ?>
            <button type="button" class="btn btn-primary upload-btn" onclick="toggleUploadProposal()">
                + Upload Proposal Baru
            </button>
        <?php endif; ?>
    </div>

    <?php if (! $judulDisetujui): ?>
        <div class="empty-box">Upload belum aktif.</div>

    <?php elseif ($masihDiproses): ?>
        <div class="empty-box">Masih ada proposal yang sedang diproses.</div>

    <?php else: ?>
        <div class="proposal-upload-panel" id="uploadProposalPanel">
                <form action="<?= base_url('/proposal-ta/upload') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <input type="hidden" name="pengajuan_judul_id" value="<?= esc($safe($judulDisetujui['id'] ?? '')) ?>">

                    <div class="form-grid form-grid-1">
                        <div class="form-group">
                            <label>File Proposal</label>
                            <input type="file" name="file_proposal" class="input" required>
                        </div>

                        <div class="form-group">
                            <label>Catatan Mahasiswa</label>
                            <textarea name="catatan_mahasiswa" class="input textarea" placeholder="Catatan tambahan..."></textarea>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Proposal</button>
                        <button type="button" class="btn btn-outline" onclick="toggleUploadProposal()">Batal</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Riwayat Proposal</h3>
                <p>Data proposal yang pernah diunggah akan tampil di sini.</p>
            </div>
        </div>

        <?php if (! empty($riwayatProposal) && is_array($riwayatProposal)): ?>
            <div class="table-wrap">
                <table class="admin-table proposal-ta-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Upload</th>
                            <th>Review</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayatProposal as $row): ?>
                            <?php
                                $status     = strtolower($safe($row['status'] ?? '', ''));
                                $proposalId = $safe($row['id'] ?? '', '');
                                $fileName   = $safe($row['file_proposal'] ?? '', '');
                                $fileAsli   = $safe($row['nama_file_asli'] ?? '', '');
                                $fileUrl    = $fileName !== '' ? base_url('uploads/proposal/' . $fileName) : '';
                                $catatan    = $safe(($row['catatan_reviewer'] ?? '') !== '' ? $row['catatan_reviewer'] : '-', '-');
                            ?>

                            <tr>
                                <td>
                                    <div class="title-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="file-cell">
                                        <strong><?= esc($fileAsli !== '' ? $fileAsli : ($fileName !== '' ? $fileName : '-')) ?></strong>

                                        <?php if ($fileUrl !== ''): ?>
                                            <div class="file-actions">
                                                <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="icon-btn icon-open" title="Buka File">📄</a>
                                                <a href="<?= esc($fileUrl) ?>" download class="icon-btn icon-download" title="Download">⬇</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($badgeStatus($status)) ?>">
                                        <?= esc($safe($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>v<?= esc($safe($row['versi_ke'] ?? '1')) ?></td>
                                <td><?= esc($safe($row['tanggal_upload'] ?? $row['created_at'] ?? '-')) ?></td>
                                <td><?= esc($safe($row['tanggal_review'] ?? '-')) ?></td>

                                <td>
                                    <?php if ($catatan !== '-'): ?>
                                        <button type="button" class="icon-btn icon-open" onclick="openNoteModal(this)" data-note="<?= esc($catatan) ?>">👁</button>
                                    <?php else: ?>
                                        <span class="muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if (in_array($status, ['revisi', 'ditolak'], true) && $proposalId !== ''): ?>
                                        <a href="<?= base_url('/proposal-ta/revisi/' . $proposalId) ?>" class="icon-btn icon-edit" title="Revisi">✎</a>
                                    <?php else: ?>
                                        <span class="muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">Belum ada riwayat proposal tugas akhir.</div>
        <?php endif; ?>
    </section>

</div>

<div class="note-modal-overlay" id="noteModal" onclick="closeNoteModal(event)">
    <div class="note-modal">
        <h3>Catatan Review</h3>
        <p id="noteModalText">-</p>
        <button type="button" class="note-modal-close" onclick="closeNoteModal()">Tutup</button>
    </div>
</div>

<script>
function toggleUploadProposal() {
    const panel = document.getElementById('uploadProposalPanel');
    if (!panel) return;

    panel.classList.toggle('show');
}

function openNoteModal(button) {
    const modal = document.getElementById('noteModal');
    const text = document.getElementById('noteModalText');

    if (!modal || !text) return;

    text.textContent = button.dataset.note || '-';
    modal.classList.add('show');
}

function closeNoteModal(event) {
    const modal = document.getElementById('noteModal');

    if (!modal) return;

    if (!event || event.target === modal) {
        modal.classList.remove('show');
    }
}
</script>

<?= $this->endSection() ?>