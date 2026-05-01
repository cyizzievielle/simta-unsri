<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.profile-page {
    width: 100%;
}

.profile-shell {
    display: grid;
    grid-template-columns: 330px minmax(0, 1fr);
    gap: 22px;
    align-items: start;
}

.profile-card,
.profile-form-card {
    background: rgba(255,255,255,.94);
    border: 1px solid #dbeafe;
    border-radius: 28px;
    box-shadow: 0 18px 45px rgba(37,99,235,.08);
    overflow: hidden;
}

.profile-card {
    padding: 26px;
    text-align: center;
    position: sticky;
    top: 110px;
}

.profile-cover {
    height: 96px;
    margin: -26px -26px 0;
    background:
        radial-gradient(circle at 20% 20%, rgba(96,165,250,.55), transparent 35%),
        linear-gradient(135deg, #dbeafe, #eff6ff);
}

.avatar-wrap {
    width: 126px;
    height: 126px;
    margin: -54px auto 14px;
    border-radius: 999px;
    padding: 6px;
    background: #fff;
    box-shadow: 0 16px 35px rgba(37,99,235,.18);
}

.profile-photo,
.avatar-fallback-big {
    width: 100%;
    height: 100%;
    border-radius: 999px;
    object-fit: cover;
}

.avatar-fallback-big {
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
    font-weight: 900;
}

.profile-name {
    margin: 0;
    font-size: 23px;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -.03em;
}

.profile-email {
    margin: 7px 0 14px;
    color: #64748b;
    font-size: 14px;
    word-break: break-word;
}

.role-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 13px;
    border-radius: 999px;
    background: #eaf3ff;
    color: #2563eb;
    font-size: 12px;
    font-weight: 900;
    text-transform: capitalize;
}

.profile-mini {
    margin-top: 20px;
    display: grid;
    gap: 10px;
}

.mini-item {
    padding: 13px;
    border-radius: 18px;
    background: #f8fbff;
    border: 1px solid #e5efff;
    text-align: left;
}

.mini-label {
    font-size: 11px;
    color: #64748b;
    font-weight: 800;
    margin-bottom: 4px;
}

.mini-value {
    font-size: 13px;
    color: #0f172a;
    font-weight: 900;
    line-height: 1.45;
}

.profile-form-card {
    padding: 26px;
}

.section-head {
    margin-bottom: 22px;
}

.section-title {
    margin: 0 0 7px;
    font-size: 25px;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -.03em;
}

.section-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
}

.info-list {
    display: grid;
    gap: 12px;
}

.info-row {
    display: grid;
    grid-template-columns: 190px minmax(0, 1fr);
    gap: 16px;
    align-items: start;
    padding: 15px 16px;
    border-radius: 18px;
    background: #f8fbff;
    border: 1px solid #e5efff;
}

.info-label {
    color: #64748b;
    font-weight: 800;
    font-size: 13px;
}

.info-value {
    color: #0f172a;
    font-weight: 900;
    font-size: 14px;
    line-height: 1.5;
    word-break: break-word;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
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
    font-weight: 900;
    color: #334155;
    font-size: 13px;
}

.input {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 16px;
    padding: 13px 15px;
    font-size: 14px;
    outline: none;
    background: #fff;
    color: #0f172a;
}

.input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59,130,246,.12);
}

.input[readonly] {
    background: #f8fafc;
    color: #64748b;
}

textarea.input {
    min-height: 120px;
    resize: vertical;
}

.upload-box {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: 105px minmax(0, 1fr);
    gap: 16px;
    align-items: center;
    padding: 16px;
    border-radius: 22px;
    background: linear-gradient(135deg, #f8fbff, #eef6ff);
    border: 1px dashed #bfdbfe;
}

.upload-preview {
    width: 105px;
    height: 105px;
    border-radius: 28px;
    object-fit: cover;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 34px;
    font-weight: 900;
    overflow: hidden;
}

.upload-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-content strong {
    display: block;
    font-size: 15px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 5px;
}

.upload-content p {
    margin: 0 0 12px;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.file-input {
    width: 100%;
    padding: 11px;
    border-radius: 14px;
    border: 1px solid #dbe3ef;
    background: #fff;
    font-size: 13px;
}

.action-row {
    display: flex;
    gap: 11px;
    flex-wrap: wrap;
    margin-top: 22px;
}

.btn {
    border: none;
    min-height: 44px;
    padding: 12px 17px;
    border-radius: 15px;
    font-size: 13px;
    font-weight: 900;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.btn-primary {
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color: #fff;
    box-shadow: 0 14px 28px rgba(37,99,235,.18);
}

.btn-light {
    background: #fff;
    color: #334155;
    border: 1px solid #dbe3ef;
}

.btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 1000px) {
    .profile-shell {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .profile-card {
        position: relative;
        top: auto;
        padding: 20px;
    }

    .profile-cover {
        margin: -20px -20px 0;
        height: 82px;
    }

    .avatar-wrap {
        width: 112px;
        height: 112px;
        margin-top: -48px;
    }

    .profile-form-card {
        padding: 18px;
        border-radius: 22px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }

    .info-row {
        grid-template-columns: 1fr;
        gap: 6px;
        padding: 14px;
    }

    .info-value {
        font-size: 13px;
    }
}

@media (max-width: 520px) {
    .profile-card,
    .profile-form-card {
        border-radius: 20px;
    }

    .profile-name {
        font-size: 20px;
    }

    .profile-email {
        font-size: 13px;
    }

    .section-title {
        font-size: 21px;
    }

    .section-subtitle {
        font-size: 13px;
    }

    .upload-box {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .upload-preview {
        margin: 0 auto;
        width: 96px;
        height: 96px;
        border-radius: 26px;
    }

    .action-row {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }
}
</style>

<?php
    $mode   = $mode ?? 'show';
    $role   = session()->get('role');
    $user   = $user ?? [];
    $detail = $detail ?? [];

    $nama  = $user['name'] ?? session()->get('name') ?? 'User';
    $email = $user['email'] ?? session()->get('email') ?? '-';
    $foto  = $user['foto'] ?? session()->get('foto') ?? null;

    $initial = strtoupper(substr((string) $nama, 0, 1));
    $fotoUrl = ! empty($foto) ? base_url('uploads/profile/' . $foto) : null;

    $avatarBox = static function () use ($fotoUrl, $initial): string {
        if ($fotoUrl) {
            return '<img src="' . esc($fotoUrl) . '" alt="Foto Profil" class="profile-photo">';
        }

        return '<div class="avatar-fallback-big">' . esc($initial) . '</div>';
    };
?>

<div class="profile-page">
    <div class="profile-shell">

        <aside class="profile-card">
            <div class="profile-cover"></div>

            <div class="avatar-wrap">
                <?= $avatarBox() ?>
            </div>

            <h2 class="profile-name"><?= esc((string) $nama) ?></h2>
            <div class="profile-email"><?= esc((string) $email) ?></div>
            <div class="role-pill"><?= esc((string) ($role ?? '-')) ?></div>

            <div class="profile-mini">
                <?php if ($role === 'mahasiswa'): ?>
                    <div class="mini-item">
                        <div class="mini-label">NIM</div>
                        <div class="mini-value"><?= esc((string) ($detail['nim'] ?? '-')) ?></div>
                    </div>
                    <div class="mini-item">
                        <div class="mini-label">Program Studi</div>
                        <div class="mini-value"><?= esc((string) ($detail['nama_prodi'] ?? 'Manajemen Informatika')) ?></div>
                    </div>
                <?php elseif ($role === 'dosen'): ?>
                    <div class="mini-item">
                        <div class="mini-label">NIDN</div>
                        <div class="mini-value"><?= esc((string) ($detail['nidn'] ?? '-')) ?></div>
                    </div>
                    <div class="mini-item">
                        <div class="mini-label">Bidang Keahlian</div>
                        <div class="mini-value"><?= esc((string) ($detail['bidang_keahlian'] ?? '-')) ?></div>
                    </div>
                <?php else: ?>
                    <div class="mini-item">
                        <div class="mini-label">Akses</div>
                        <div class="mini-value">Administrator Sistem</div>
                    </div>
                <?php endif; ?>
            </div>
        </aside>

        <section class="profile-form-card">
            <?php if ($mode === 'edit'): ?>
                <div class="section-head">
                    <h2 class="section-title">Edit Profil</h2>
                    <p class="section-subtitle">Perbarui foto profil, data akun, dan informasi pribadi.</p>
                </div>

                <form action="<?= base_url('/profile/update') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-grid">
                        <div class="upload-box">
                            <div class="upload-preview" id="previewBox">
                                <?php if ($fotoUrl): ?>
                                    <img src="<?= esc($fotoUrl) ?>" alt="Preview Foto" id="previewImage">
                                <?php else: ?>
                                    <span id="previewInitial"><?= esc($initial) ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="upload-content">
                                <strong>Upload Foto Profil</strong>
                                <p>Gunakan JPG, PNG, JPEG, atau WEBP. Maksimal 2MB agar tampilan tetap ringan.</p>
                                <input type="file" name="foto" class="file-input" accept="image/*" onchange="previewFoto(event)">
                            </div>
                        </div>

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
                                <input type="text" class="input" value="<?= esc((string) ($detail['nim'] ?? '-')) ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label>Angkatan</label>
                                <input type="text" class="input" value="<?= esc((string) ($detail['angkatan'] ?? '-')) ?>" readonly>
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
                                <input type="text" class="input" value="<?= esc((string) ($detail['nidn'] ?? '-')) ?>" readonly>
                            </div>

                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" class="input" value="<?= esc((string) ($detail['nip'] ?? '-')) ?>" readonly>
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
                <div class="section-head">
                    <h2 class="section-title">Informasi Profil</h2>
                    <p class="section-subtitle">Data akun aktif yang digunakan untuk login dan identitas pada sistem.</p>
                </div>

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
        </section>

    </div>
</div>

<script>
function previewFoto(event) {
    const file = event.target.files[0];
    const previewBox = document.getElementById('previewBox');

    if (!file || !previewBox) return;

    const reader = new FileReader();

    reader.onload = function(e) {
        previewBox.innerHTML = `<img src="${e.target.result}" alt="Preview Foto">`;
    };

    reader.readAsDataURL(file);
}
</script>

<?= $this->endSection() ?>