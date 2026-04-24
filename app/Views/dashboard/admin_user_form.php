<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = ($mode ?? 'create') === 'edit';
    $roleValue = old('role', (string) ($user['role'] ?? 'admin'));
?>

<div class="card">
    <h3 class="section-title"><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h3>
    <p class="section-subtitle">Isi data akun dan detail sesuai role user.</p>

    <?php if (session()->getFlashdata('error')): ?>
        <div style="margin-bottom:16px; padding:14px 16px; border-radius:14px; background:#fee2e2; color:#991b1b; border:1px solid #fecaca;">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div style="margin-bottom:16px; padding:14px 16px; border-radius:14px; background:#dcfce7; color:#166534; border:1px solid #bbf7d0;">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= $isEdit ? base_url('/admin/users/update/' . (string) ($user['id'] ?? '')) : base_url('/admin/users/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-grid">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="input" value="<?= old('name', (string) ($user['name'] ?? '')) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="input" value="<?= old('email', (string) ($user['email'] ?? '')) ?>" required>
            </div>

            <div class="form-group">
                <label>Password <?= $isEdit ? '(kosongkan jika tidak diubah)' : '' ?></label>
                <input type="password" name="password" class="input" <?= $isEdit ? '' : 'required' ?>>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role" class="input" required>
                    <option value="admin" <?= $roleValue === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="mahasiswa" <?= $roleValue === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                    <option value="dosen" <?= $roleValue === 'dosen' ? 'selected' : '' ?>>Dosen</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status Aktif</label>
                <select name="is_active" class="input">
                    <option value="1" <?= (string) old('is_active', (string) ($user['is_active'] ?? '1')) === '1' ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= (string) old('is_active', (string) ($user['is_active'] ?? '1')) === '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>

        <hr style="margin:24px 0; border:none; border-top:1px solid #e5e7eb;">

        <div class="grid-2">
            <div class="card" id="form-mahasiswa" style="box-shadow:none; border:1px solid #e5e7eb;">
                <h4 class="section-title" style="font-size:18px;">Detail Mahasiswa</h4>

                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" class="input" value="<?= old('nim', (string) ($detail['nim'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="text" name="angkatan" class="input" value="<?= old('angkatan', (string) ($detail['angkatan'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label>Program Studi</label>
                    <select name="program_studi_id" class="input">
                        <option value="">-- Pilih Program Studi --</option>
                        <?php foreach (($programStudi ?? []) as $ps): ?>
                            <option value="<?= esc((string) ($ps['id'] ?? '')) ?>" <?= (string) old('program_studi_id', (string) ($detail['program_studi_id'] ?? '')) === (string) ($ps['id'] ?? '') ? 'selected' : '' ?>>
                                <?= esc((string) ($ps['nama_prodi'] ?? '-')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp_mahasiswa" class="input" value="<?= old('no_hp_mahasiswa', (string) (($roleValue === 'mahasiswa') ? ($detail['no_hp'] ?? '') : '')) ?>">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat"><?= old('alamat', (string) ($detail['alamat'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="card" id="form-dosen" style="box-shadow:none; border:1px solid #e5e7eb;">
                <h4 class="section-title" style="font-size:18px;">Detail Dosen</h4>

                <div class="form-group">
                    <label>NIDN</label>
                    <input type="text" name="nidn" class="input" value="<?= old('nidn', (string) ($detail['nidn'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label>NIP (Opsional)</label>
                    <input type="text" name="nip" class="input" value="<?= old('nip', (string) ($detail['nip'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label>Kuota Maksimal</label>
                    <input type="number" name="kuota_maksimal" class="input" value="<?= old('kuota_maksimal', (string) ($detail['kuota_maksimal'] ?? '25')) ?>">
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" name="no_hp_dosen" class="input" value="<?= old('no_hp_dosen', (string) (($roleValue === 'dosen') ? ($detail['no_hp'] ?? '') : '')) ?>">
                </div>

                <div class="form-group">
                    <label>Bidang Keahlian</label>
                    <textarea name="bidang_keahlian"><?= old('bidang_keahlian', (string) ($detail['bidang_keahlian'] ?? '')) ?></textarea>
                </div>
            </div>
        </div>

        <div style="margin-top:24px;">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('/admin/users') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155; margin-left:8px;">Kembali</a>
        </div>
    </form>
</div>

<script>
    function toggleRoleDetail() {
        const role = document.getElementById('role').value;
        const formMahasiswa = document.getElementById('form-mahasiswa');
        const formDosen = document.getElementById('form-dosen');

        formMahasiswa.style.display = role === 'mahasiswa' ? 'block' : 'none';
        formDosen.style.display = role === 'dosen' ? 'block' : 'none';
    }

    document.getElementById('role').addEventListener('change', toggleRoleDetail);
    toggleRoleDetail();
</script>

<?= $this->endSection() ?>