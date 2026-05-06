<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$isEdit = ($mode ?? 'create') === 'edit';
$periode = $periode ?? [];

$safe = static function (mixed $value, string $default = ''): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$semesterValue = $safe(old('semester', $periode['semester'] ?? 'ganjil'), 'ganjil');
$activeValue   = $safe(old('is_active', $periode['is_active'] ?? '1'), '1');
?>

<style>
.master-form-page {
    max-width: 760px;
}

.form-card {
    background: #fff;
    border: 1px solid #eef2f7;
    border-radius: 22px;
    padding: 20px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
}

.form-title {
    margin: 0 0 5px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.form-subtitle {
    margin: 0 0 18px;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.form-grid {
    display: grid;
    gap: 13px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-size: 12px;
    font-weight: 900;
    color: #334155;
}

.form-control {
    width: 100%;
    height: 40px;
    border: 1px solid #dbe3ef;
    border-radius: 13px;
    padding: 9px 11px;
    font-size: 13px;
    color: #0f172a;
    background: #fff;
    outline: none;
}

.form-control:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .10);
}

.action-row {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.btn-save,
.btn-back {
    height: 38px;
    padding: 0 14px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-save {
    border: 0;
    background: #2563eb;
    color: #fff;
    cursor: pointer;
}

.btn-back {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #334155;
}

@media (max-width: 600px) {
    .form-card {
        padding: 14px;
        border-radius: 18px;
    }

    .form-title {
        font-size: 18px;
    }

    .form-subtitle {
        font-size: 11.5px;
        margin-bottom: 14px;
    }

    .form-group label {
        font-size: 10.5px;
    }

    .form-control {
        height: 36px;
        font-size: 11.5px;
        border-radius: 11px;
    }

    .action-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .btn-save,
    .btn-back {
        width: 100%;
        height: 36px;
        font-size: 11px;
    }
}
</style>

<div class="master-form-page">
    <div class="form-card">
        <h3 class="form-title">
            <?= $isEdit ? 'Edit Periode Akademik' : 'Tambah Periode Akademik' ?>
        </h3>

        <p class="form-subtitle">
            Isi data periode akademik dengan benar.
        </p>

        <form action="<?= $isEdit ? base_url('/admin/periode-akademik/update/' . $safe($periode['id'] ?? '')) : base_url('/admin/periode-akademik/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="tahun_ajaran">Tahun Ajaran</label>
                    <input
                        type="text"
                        id="tahun_ajaran"
                        name="tahun_ajaran"
                        class="form-control"
                        placeholder="Contoh: 2025/2026"
                        value="<?= esc($safe(old('tahun_ajaran', $periode['tahun_ajaran'] ?? ''))) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select name="semester" id="semester" class="form-control" required>
                        <option value="ganjil" <?= $semesterValue === 'ganjil' ? 'selected' : '' ?>>Ganjil</option>
                        <option value="genap" <?= $semesterValue === 'genap' ? 'selected' : '' ?>>Genap</option>
                        <option value="pendek" <?= $semesterValue === 'pendek' ? 'selected' : '' ?>>Pendek</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="is_active">Status Aktif</label>
                    <select name="is_active" id="is_active" class="form-control" required>
                        <option value="1" <?= $activeValue === '1' ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= $activeValue === '0' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-save">Simpan</button>
                <a href="<?= base_url('/admin/periode-akademik') ?>" class="btn-back">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>