<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.profile-grid {
    display: blok;
}

.card-premium {
    background: #fff;
    border-radius: 28px;
    padding: 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 18px 42px rgba(15,23,42,.06);
}

.section-title {
    margin: 0 0 8px;
    font-size: 26px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle {
    margin: 0 0 24px;
    color: #64748b;
    font-size: 15px;
    line-height: 1.7;
}

.info-list {
    display: flex;
    flex-direction: column;
}

.info-row {
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 17px 0;
    border-bottom: 1px solid #eef2f7;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #64748b;
    font-weight: 800;
}

.info-value {
    color: #0f172a;
    font-weight: 900;
    text-align: right;
    line-height: 1.5;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 18px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    font-weight: 800;
    color: #334155;
}

.input {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 16px;
    padding: 14px 16px;
    font-size: 15px;
    outline: none;
    background: #fff;
}

.input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,.10);
}

textarea.input {
    min-height: 130px;
    resize: vertical;
}

.profile-avatar {
    width: 88px;
    height: 88px;
    border-radius: 28px;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    font-weight: 900;
    color: #07142f;
    margin-bottom: 18px;
}

.action-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 24px;
}

.btn-light {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #dbe3ef;
}

@media (max-width: 1000px) {
    .profile-grid,
    .form-grid {
        grid-template-columns: 1fr;
    }

    .info-row {
        flex-direction: column;
        gap: 6px;
    }

    .info-value {
        text-align: left;
    }
}
</style>

<?php
    $mode = $mode ?? 'show';
    $role = session()->get('role');
    $user = $user ?? [];
    $detail = $detail ?? [];

    $nama = $user['name'] ?? session()->get('name') ?? 'User';
    $email = $user['email'] ?? session()->get('email') ?? '-';
?>

<div class="profile-grid">

    <div class="card-premium">
        <?php if ($mode === 'edit'): ?>
            <h2 class="section-title">Edit Informasi Akun</h2>
            <p class="section-subtitle">Ubah data akun dan informasi pribadi yang diperlukan.</p>

            <form action="<?= base_url('/profile/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="input"
                               value="<?= esc((string) old('name', $nama)) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="input"
                               value="<?= esc((string) old('email', $email)) ?>" required>
                    </div>

                    <?php if ($role === 'mahasiswa'): ?>
                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" class="input"
                                   value="<?= esc((string) ($detail['nim'] ?? '-')) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Angkatan</label>
                            <input type="text" class="input"
                                   value="<?= esc((string) ($detail['angkatan'] ?? '-')) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Program Studi</label>
                            <input type="text" class="input"
                                   value="<?= esc((string) ($detail['nama_prodi'] ?? 'Manajemen Informatika')) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" name="no_hp" class="input"
                                   value="<?= esc((string) old('no_hp', $detail['no_hp'] ?? '')) ?>">
                        </div>

                        <div class="form-group full">
                            <label>Alamat</label>
                            <textarea name="alamat" class="input"><?= esc((string) old('alamat', $detail['alamat'] ?? '')) ?></textarea>
                        </div>
                    <?php endif; ?>

                    <?php if ($role === 'dosen'): ?>
                        <div class="form-group">
                            <label>NIDN</label>
                            <input type="text" class="input"
                                   value="<?= esc((string) ($detail['nidn'] ?? '-')) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>NIP</label>
                            <input type="text" class="input"
                                   value="<?= esc((string) ($detail['nip'] ?? '-')) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" name="no_hp" class="input"
                                   value="<?= esc((string) old('no_hp', $detail['no_hp'] ?? '')) ?>">
                        </div>

                        <div class="form-group full">
                            <label>Bidang Keahlian</label>
                            <textarea name="bidang_keahlian" class="input"><?= esc((string) old('bidang_keahlian', $detail['bidang_keahlian'] ?? '')) ?></textarea>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="action-row">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= base_url('/profile') ?>" class="btn btn-light">Batal</a>
                </div>
            </form>

        <?php else: ?>
            <h2 class="section-title">Informasi Akun</h2>
            <p class="section-subtitle">Data akun aktif yang digunakan untuk login dan identitas pada sistem.</p>

            <div class="info-list">
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value"><?= esc((string) $nama) ?></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?= esc((string) $email) ?></div>
                </div>

                <?php if ($role === 'mahasiswa'): ?>
                    <div class="info-row">
                        <div class="info-label">NIM</div>
                        <div class="info-value"><?= esc((string) ($detail['nim'] ?? '-')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Angkatan</div>
                        <div class="info-value"><?= esc((string) ($detail['angkatan'] ?? '-')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Program Studi</div>
                        <div class="info-value"><?= esc((string) ($detail['nama_prodi'] ?? 'Manajemen Informatika')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">No. HP</div>
                        <div class="info-value"><?= esc((string) ($detail['no_hp'] ?? '-')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Alamat</div>
                        <div class="info-value"><?= esc((string) ($detail['alamat'] ?? '-')) ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($role === 'dosen'): ?>
                    <div class="info-row">
                        <div class="info-label">NIDN</div>
                        <div class="info-value"><?= esc((string) ($detail['nidn'] ?? '-')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">NIP</div>
                        <div class="info-value"><?= esc((string) ($detail['nip'] ?? '-')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">No. HP</div>
                        <div class="info-value"><?= esc((string) ($detail['no_hp'] ?? '-')) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Bidang Keahlian</div>
                        <div class="info-value"><?= esc((string) ($detail['bidang_keahlian'] ?? '-')) ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="action-row">
                <a href="<?= base_url('/profile/edit') ?>" class="btn btn-primary">Edit Profil</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>