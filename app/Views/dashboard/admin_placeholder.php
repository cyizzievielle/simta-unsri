<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="card">
    <h3 class="section-title"><?= esc((string) ($placeholderTitle ?? 'Admin Module')) ?></h3>
    <p class="section-subtitle"><?= esc((string) ($placeholderText ?? 'Modul admin sedang disiapkan.')) ?></p>

    <div class="placeholder-box">
        Modul ini sudah masuk panel admin operasional dan siap disambungkan ke data real.
    </div>
</div>

<?= $this->endSection() ?>