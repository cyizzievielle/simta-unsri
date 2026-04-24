<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="card">
    <h3 class="section-title"><?= esc((string) ($pageTitle ?? 'Revisi Judul')) ?></h3>
    <p class="section-subtitle"><?= esc((string) ($pageSubtitle ?? '')) ?></p>

    <div class="mini-card" style="margin-bottom:20px;">
        <strong>Status Sebelumnya:</strong> <?= esc((string) ($pengajuan['status'] ?? '-')) ?><br>
        <strong>Catatan Reviewer:</strong><br>
        <?= nl2br(esc((string) ($pengajuan['catatan_reviewer'] ?? '-'))) ?>
    </div>

    <form action="<?= base_url('/pengajuan-judul/revisi/' . (string) ($pengajuan['id'] ?? '')) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="judul">Judul Tugas Akhir</label>
            <input type="text" name="judul" id="judul" class="input" value="<?= old('judul', (string) ($pengajuan['judul'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label for="bidang_topik">Bidang Topik</label>
            <input type="text" name="bidang_topik" id="bidang_topik" class="input" value="<?= old('bidang_topik', (string) ($pengajuan['bidang_topik'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label for="kata_kunci">Kata Kunci</label>
            <input type="text" name="kata_kunci" id="kata_kunci" class="input" value="<?= old('kata_kunci', (string) ($pengajuan['kata_kunci'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label for="latar_belakang">Latar Belakang</label>
            <textarea name="latar_belakang" id="latar_belakang"><?= old('latar_belakang', (string) ($pengajuan['latar_belakang'] ?? '')) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Ulang</button>
    </form>
</div>

<?= $this->endSection() ?>