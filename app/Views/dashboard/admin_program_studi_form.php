<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<?php $isEdit = ($mode ?? 'create') === 'edit'; ?>

<div class="card">
    <h3 class="section-title"><?= $isEdit ? 'Edit Program Studi' : 'Tambah Program Studi' ?></h3>
    <p class="section-subtitle">Isi data program studi dengan lengkap.</p>

    <form action="<?= $isEdit ? base_url('/admin/program-studi/update/' . (string) ($programStudi['id'] ?? '')) : base_url('/admin/program-studi/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label>Kode Prodi</label>
            <input type="text" name="kode_prodi" class="input" value="<?= old('kode_prodi', (string) ($programStudi['kode_prodi'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label>Nama Prodi</label>
            <input type="text" name="nama_prodi" class="input" value="<?= old('nama_prodi', (string) ($programStudi['nama_prodi'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label>Jenjang</label>
            <input type="text" name="jenjang" class="input" value="<?= old('jenjang', (string) ($programStudi['jenjang'] ?? '')) ?>">
        </div>

        <div class="form-group">
            <label>Fakultas</label>
            <input type="text" name="fakultas" class="input" value="<?= old('fakultas', (string) ($programStudi['fakultas'] ?? '')) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= base_url('/admin/program-studi') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155; margin-left:8px;">Kembali</a>
    </form>
</div>

<?= $this->endSection() ?>