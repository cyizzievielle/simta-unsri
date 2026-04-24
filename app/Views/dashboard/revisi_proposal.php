<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="card">
    <h3 class="section-title"><?= esc((string) ($pageTitle ?? 'Revisi Proposal')) ?></h3>
    <p class="section-subtitle"><?= esc((string) ($pageSubtitle ?? '')) ?></p>

    <div class="mini-card" style="margin-bottom:20px;">
        <strong>Judul:</strong> <?= esc((string) ($proposal['judul'] ?? '-')) ?><br>
        <strong>Status Sebelumnya:</strong> <?= esc((string) ($proposal['status'] ?? '-')) ?><br>
        <strong>Catatan Reviewer:</strong><br>
        <?= nl2br(esc((string) ($proposal['catatan_reviewer'] ?? '-'))) ?>
    </div>

    <form action="<?= base_url('/proposal-ta/revisi/' . (string) ($proposal['id'] ?? '')) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="file_proposal">File Proposal Baru</label>
            <input type="file" name="file_proposal" id="file_proposal" class="input" accept=".pdf,.doc,.docx">
        </div>

        <button type="submit" class="btn btn-primary">Kirim Ulang</button>
    </form>
</div>

<?= $this->endSection() ?>