<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$isEdit = ($mode ?? 'create') === 'edit';
$programStudi = $programStudi ?? [];

$safe = static function (mixed $value, string $default = ''): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};
?>

<style>
.master-form-page {
    max-width: 780px;
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
    grid-template-columns: 1fr 1fr;
    gap: 14px;
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

.full {
    grid-column: 1 / -1;
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

    .form-grid {
        grid-template-columns: 1fr;
        gap: 12px;
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
            <?= $isEdit ? 'Edit Program Studi' : 'Tambah Program Studi' ?>
        </h3>

        <p class="form-subtitle">
            Isi data program studi dengan lengkap.
        </p>

        <form action="<?= $isEdit ? base_url('/admin/program-studi/update/' . $safe($programStudi['id'] ?? '')) : base_url('/admin/program-studi/store') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>Kode Prodi</label>
                    <input
                        type="text"
                        name="kode_prodi"
                        class="form-control"
                        placeholder="Contoh: MI001"
                        value="<?= esc($safe(old('kode_prodi', $programStudi['kode_prodi'] ?? ''))) ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Jenjang</label>
                    <input
                        type="text"
                        name="jenjang"
                        class="form-control"
                        placeholder="Contoh: D3 / S1"
                        value="<?= esc($safe(old('jenjang', $programStudi['jenjang'] ?? ''))) ?>"
                        required
                    >
                </div>

                <div class="form-group full">
                    <label>Nama Prodi</label>
                    <input
                        type="text"
                        name="nama_prodi"
                        class="form-control"
                        placeholder="Nama lengkap program studi"
                        value="<?= esc($safe(old('nama_prodi', $programStudi['nama_prodi'] ?? ''))) ?>"
                        required
                    >
                </div>

                <div class="form-group full">
                    <label>Fakultas</label>
                    <input
                        type="text"
                        name="fakultas"
                        class="form-control"
                        placeholder="Nama fakultas"
                        value="<?= esc($safe(old('fakultas', $programStudi['fakultas'] ?? ''))) ?>"
                        required
                    >
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="btn-save">Simpan</button>
                <a href="<?= base_url('/admin/program-studi') ?>" class="btn-back">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>