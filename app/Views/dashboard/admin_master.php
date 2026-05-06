<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$rows         = $rows ?? [];
$pageType     = $pageType ?? '';
$pageTitle    = $pageTitle ?? 'Master Data';
$pageSubtitle = $pageSubtitle ?? '';

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};
?>

<style>
.master-page {
    background: #fff;
    border: 1px solid #eef2f7;
    border-radius: 22px;
    padding: 18px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
}

.master-head {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.master-head h3 {
    margin: 0 0 5px;
    font-size: 21px;
    font-weight: 900;
    color: #0f172a;
}

.master-head p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.btn-add {
    height: 36px;
    padding: 0 13px;
    border-radius: 12px;
    background: #2563eb;
    color: #fff;
    font-size: 12px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    white-space: nowrap;
}

.table-wrap {
    overflow-x: auto;
    border: 1px solid #eef2f7;
    border-radius: 16px;
    background: #fff;
}

.master-table {
    width: 100%;
    border-collapse: collapse;
}

.master-table.periode {
    min-width: 560px;
}

.master-table.prodi {
    min-width: 760px;
}

.master-table th {
    background: #f8fafc;
    color: #334155;
    font-size: 10.5px;
    font-weight: 900;
    text-align: left;
    padding: 11px 10px;
    white-space: nowrap;
}

.master-table td {
    padding: 11px 10px;
    border-top: 1px solid #eef2f7;
    font-size: 12px;
    color: #0f172a;
    vertical-align: middle;
    white-space: nowrap;
}

.cell-strong {
    font-weight: 900;
}

.cell-long {
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge {
    display: inline-flex;
    padding: 5px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 900;
}

.badge-active {
    background: #dcfce7;
    color: #166534;
}

.badge-inactive {
    background: #fee2e2;
    color: #b91c1c;
}

.actions {
    display: flex;
    gap: 6px;
    flex-wrap: nowrap;
}

.icon-btn {
    width: 29px;
    height: 29px;
    border-radius: 10px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
    font-weight: 900;
}

.icon-edit {
    background: #dbeafe;
    color: #1d4ed8;
}

.icon-delete {
    background: #fff5f5;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.delete-form {
    margin: 0;
    display: inline-flex;
}

.empty-box {
    padding: 22px;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    background: #f8fafc;
    color: #64748b;
    text-align: center;
    font-size: 13px;
    font-weight: 700;
}

@media (max-width: 600px) {
    .master-page {
        padding: 13px;
        border-radius: 18px;
    }

    .master-head h3 {
        font-size: 18px;
    }

    .master-head p {
        font-size: 11.5px;
    }

    .btn-add {
        height: 34px;
        font-size: 11px;
        padding: 0 11px;
    }

    .master-table.periode {
        min-width: 500px;
    }

    .master-table.prodi {
        min-width: 680px;
    }

    .master-table th,
    .master-table td {
        padding: 9px 8px;
        font-size: 11px;
    }

    .master-table th {
        font-size: 10px;
    }

    .cell-long {
        max-width: 160px;
    }

    .badge {
        font-size: 9.5px;
        padding: 5px 7px;
    }

    .icon-btn {
        width: 27px;
        height: 27px;
        font-size: 12px;
    }
}
</style>

<div class="master-page">
    <div class="master-head">
        <div>
            <h3><?= esc($safe($pageTitle, 'Master Data')) ?></h3>
            <p><?= esc($safe($pageSubtitle, 'Kelola data master sistem.')) ?></p>
        </div>

        <?php if ($pageType === 'periode_akademik'): ?>
            <a href="<?= site_url('admin/periode-akademik/create') ?>" class="btn-add">
                + Tambah Periode
            </a>
        <?php elseif ($pageType === 'program_studi'): ?>
            <a href="<?= site_url('admin/program-studi/create') ?>" class="btn-add">
                + Tambah Prodi
            </a>
        <?php endif; ?>
    </div>

    <?php if (! empty($rows) && is_array($rows)): ?>

        <?php if ($pageType === 'periode_akademik'): ?>
            <div class="table-wrap">
                <table class="master-table periode">
                    <thead>
                        <tr>
                            <th>Tahun Ajaran</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td class="cell-strong">
                                    <?= esc($safe($row['tahun_ajaran'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= esc($safe($row['semester'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?php if ((int) ($row['is_active'] ?? 0) === 1): ?>
                                        <span class="badge badge-active">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-inactive">Nonaktif</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="actions">
                                        <a
                                            href="<?= site_url('admin/periode-akademik/edit/' . $safe($row['id'] ?? '')) ?>"
                                            class="icon-btn icon-edit"
                                            title="Edit"
                                        >
                                            ✎
                                        </a>

                                        <form
                                            action="<?= site_url('admin/periode-akademik/delete/' . $safe($row['id'] ?? '')) ?>"
                                            method="post"
                                            class="delete-form"
                                            onsubmit="return confirm('Yakin ingin menghapus periode ini?');"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="icon-btn icon-delete" title="Hapus">
                                                ×
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($pageType === 'program_studi'): ?>
            <div class="table-wrap">
                <table class="master-table prodi">
                    <thead>
                        <tr>
                            <th>Kode Prodi</th>
                            <th>Nama Prodi</th>
                            <th>Jenjang</th>
                            <th>Fakultas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td class="cell-strong">
                                    <?= esc($safe($row['kode_prodi'] ?? '-')) ?>
                                </td>

                                <td>
                                    <div class="cell-long">
                                        <?= esc($safe($row['nama_prodi'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <?= esc($safe($row['jenjang'] ?? '-')) ?>
                                </td>

                                <td>
                                    <div class="cell-long">
                                        <?= esc($safe($row['fakultas'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="actions">
                                        <a
                                            href="<?= site_url('admin/program-studi/edit/' . $safe($row['id'] ?? '')) ?>"
                                            class="icon-btn icon-edit"
                                            title="Edit"
                                        >
                                            ✎
                                        </a>

                                        <form
                                            action="<?= site_url('admin/program-studi/delete/' . $safe($row['id'] ?? '')) ?>"
                                            method="post"
                                            class="delete-form"
                                            onsubmit="return confirm('Yakin ingin menghapus program studi ini?');"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="icon-btn icon-delete" title="Hapus">
                                                ×
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-box">
            Belum ada data.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>