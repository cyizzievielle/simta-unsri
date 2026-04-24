<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<?php $isEdit = ($mode ?? 'create') === 'edit'; ?>

<div class="card">
    <h3 class="section-title"><?= $isEdit ? 'Edit Periode Akademik' : 'Tambah Periode Akademik' ?></h3>
    <p class="section-subtitle">Isi data periode akademik dengan benar.</p>

    <form action="<?= $isEdit ? base_url('/admin/periode-akademik/update/' . (string) ($periode['id'] ?? '')) : base_url('/admin/periode-akademik/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" class="input" value="<?= old('tahun_ajaran', (string) ($periode['tahun_ajaran'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label>Semester</label>
            <select name="semester" class="input">
                <?php $semesterValue = old('semester', (string) ($periode['semester'] ?? 'ganjil')); ?>
                <option value="ganjil" <?= $semesterValue === 'ganjil' ? 'selected' : '' ?>>Ganjil</option>
                <option value="genap" <?= $semesterValue === 'genap' ? 'selected' : '' ?>>Genap</option>
                <option value="pendek" <?= $semesterValue === 'pendek' ? 'selected' : '' ?>>Pendek</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status Aktif</label>
            <?php $activeValue = old('is_active', (string) ($periode['is_active'] ?? '1')); ?>
            <select name="is_active" class="input">
                <option value="1" <?= $activeValue === '1' ? 'selected' : '' ?>>Aktif</option>
                <option value="0" <?= $activeValue === '0' ? 'selected' : '' ?>>Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('/admin/periode-akademik') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155; margin-left:8px;">Kembali</a>
    </form>
</div>

<?= $this->endSection() ?>