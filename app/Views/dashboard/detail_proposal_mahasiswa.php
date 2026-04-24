<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="grid-2">
    <div class="card">
        <h3 class="section-title">Detail Proposal</h3>
        <p class="section-subtitle">Informasi file proposal dan pembimbing aktif.</p>

        <div class="mini-card">
            <strong>Judul</strong><br>
            <?= esc((string) ($proposal['judul'] ?? '-')) ?>
        </div>

        <div class="mini-card">
            <strong>File</strong><br>
            <?= esc((string) ($proposal['nama_file_asli'] ?? '-')) ?>
        </div>

        <div class="mini-card">
            <strong>Status</strong><br>
            <?= esc((string) ($proposal['status'] ?? '-')) ?>
        </div>

        <div class="mini-card">
            <strong>Versi</strong><br>
            <?= esc((string) ($proposal['versi_ke'] ?? 1)) ?>
        </div>

        <div class="mini-card">
            <strong>Pembimbing</strong><br>
            <?php if (! empty($pembimbingList)): ?>
                <?php foreach ($pembimbingList as $item): ?>
                    <div style="margin-top:8px;">
                        <?= esc(str_replace('_', ' ', ucwords((string) ($item['jenis_pembimbing'] ?? ''), '_'))) ?>:
                        <?= esc((string) ($item['nama_dosen'] ?? '-')) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                -
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <h3 class="section-title">Riwayat Review Proposal</h3>
        <p class="section-subtitle">Semua histori review dari pembimbing.</p>

        <?php if (! empty($riwayatReview)): ?>
            <?php foreach ($riwayatReview as $item): ?>
                <div class="mini-card">
                    <strong><?= esc((string) ($item['nama_reviewer'] ?? '-')) ?></strong><br>
                    Status: <?= esc((string) ($item['status_review'] ?? '-')) ?><br>
                    Waktu: <?= esc((string) ($item['created_at'] ?? '-')) ?><br>
                    Catatan: <?= esc((string) ($item['catatan'] ?? '-')) ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="placeholder-box">Belum ada histori review proposal.</div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>