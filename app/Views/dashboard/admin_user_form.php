<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = ($mode ?? 'create') === 'edit';
    $roleValue = old('role', (string) ($user['role'] ?? 'admin'));
?>

<style>
.user-form-page {
    max-width: 1180px;
    margin: 0 auto;
}

.user-form-hero {
    background: linear-gradient(135deg, #0f1f46, #2563eb);
    color: #fff;
    border-radius: 28px;
    padding: 28px;
    margin-bottom: 22px;
    box-shadow: 0 18px 42px rgba(37,99,235,.18);
}

.user-form-hero h3 {
    margin: 0 0 8px;
    font-size: 28px;
    font-weight: 900;
}

.user-form-hero p {
    margin: 0;
    color: rgba(255,255,255,.88);
    line-height: 1.6;
}

.user-form-card {
    background: #fff;
    border-radius: 28px;
    padding: 26px;
    border: 1px solid #eef2f7;
    box-shadow: 0 16px 38px rgba(15,23,42,.06);
}

.flash-alert {
    margin-bottom: 16px;
    padding: 14px 16px;
    border-radius: 16px;
    font-size: 14px;
    font-weight: 700;
}

.flash-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.flash-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.form-section-title {
    margin: 0 0 16px;
    font-size: 18px;
    font-weight: 900;
    color: #0f172a;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    color: #334155;
    font-size: 13px;
    font-weight: 900;
}

.input,
.form-group textarea {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 15px;
    padding: 13px 14px;
    font-size: 14px;
    color: #0f172a;
    background: #fff;
    outline: none;
    transition: .2s ease;
}

.input:focus,
.form-group textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,.10);
}

.form-group textarea {
    min-height: 108px;
    resize: vertical;
    font-family: inherit;
}

.form-divider {
    margin: 26px 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}

.role-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}

.detail-card {
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    padding: 22px;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
}

.detail-card-head {
    margin-bottom: 18px;
}

.detail-card-title {
    margin: 0 0 5px;
    font-size: 18px;
    font-weight: 900;
    color: #0f172a;
}

.detail-card-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.detail-card .form-group {
    margin-bottom: 14px;
}

.detail-card .form-group:last-child {
    margin-bottom: 0;
}

.form-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 24px;
}

.form-actions .btn {
    min-height: 44px;
    padding: 11px 18px;
    border-radius: 14px;
    font-weight: 900;
}

.btn-light-custom {
    border: 1px solid #cbd5e1;
    color: #334155;
    background: #fff;
    text-decoration: none;
}

@media(max-width: 900px) {
    .form-grid,
    .role-detail-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 640px) {
    .user-form-page {
        margin: 0;
    }

    .user-form-hero {
        padding: 22px;
        border-radius: 24px;
        margin-bottom: 16px;
    }

    .user-form-hero h3 {
        font-size: 23px;
    }

    .user-form-hero p {
        font-size: 13px;
        line-height: 1.45;
    }

    .user-form-card {
        padding: 16px;
        border-radius: 22px;
    }

    .form-section-title {
        font-size: 16px;
        margin-bottom: 12px;
    }

    .form-grid,
    .role-detail-grid {
        gap: 12px;
    }

    .form-group {
        gap: 6px;
    }

    .form-group label {
        font-size: 12px;
    }

    .input,
    .form-group textarea {
        padding: 11px 12px;
        border-radius: 13px;
        font-size: 13px;
    }

    .form-group textarea {
        min-height: 88px;
    }

    .form-divider {
        margin: 20px 0;
    }

    .detail-card {
        padding: 16px;
        border-radius: 20px;
    }

    .detail-card-title {
        font-size: 16px;
    }

    .detail-card-subtitle {
        font-size: 12px;
    }

    .detail-card .form-group {
        margin-bottom: 11px;
    }

    .form-actions {
        display: grid;
        grid-template-columns: 1fr;
        gap: 9px;
    }

    .form-actions .btn {
        width: 100%;
        justify-content: center;
        min-height: 42px;
        font-size: 13px;
    }
}
</style>

<div class="user-form-page">
    <div class="user-form-hero">
        <h3><?= $isEdit ? 'Edit User' : 'Tambah User' ?></h3>
        <p>Isi data akun dan detail sesuai role user. Detail mahasiswa atau dosen akan muncul otomatis sesuai role yang dipilih.</p>
    </div>

    <div class="user-form-card">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="flash-alert flash-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="flash-alert flash-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= $isEdit ? site_url('admin/users/update/' . (string) ($user['id'] ?? '')) : site_url('admin/users/store') ?>" method="post">
            <?= csrf_field() ?>

            <h4 class="form-section-title">Informasi Akun</h4>

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

            <hr class="form-divider">

            <div class="role-detail-grid">
                <div class="detail-card" id="form-mahasiswa">
                    <div class="detail-card-head">
                        <h4 class="detail-card-title">Detail Mahasiswa</h4>
                        <p class="detail-card-subtitle">Lengkapi data mahasiswa seperti NIM, angkatan, prodi, dan kontak.</p>
                    </div>

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

                <div class="detail-card" id="form-dosen">
                    <div class="detail-card-head">
                        <h4 class="detail-card-title">Detail Dosen</h4>
                        <p class="detail-card-subtitle">Lengkapi data dosen seperti NIDN, NIP, kuota bimbingan, dan bidang keahlian.</p>
                    </div>

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

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?= site_url('admin/users') ?>" class="btn btn-light-custom">Kembali</a>
            </div>
        </form>
    </div>
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