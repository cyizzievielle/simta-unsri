<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<style>
.master-card {
    background: #fff;
    border-radius: 26px;
    padding: 24px;
    border: 1px solid #eef2f7;
    box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
}

.master-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.master-title {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 900;
    color: #0f172a;
}

.master-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
}

.master-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 18px;
    border: 1px solid #eef2f7;
}

.master-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.master-table.periode {
    min-width: 620px;
}

.master-table.prodi {
    min-width: 760px;
}

.master-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.master-table th,
.master-table td {
    padding: 13px 12px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    font-size: 13px;
    color: #0f172a;
    white-space: nowrap;
}

.master-table th {
    font-size: 12px;
    font-weight: 900;
    color: #334155;
}

.cell-strong {
    font-weight: 900;
    color: #0f172a;
}

.cell-long {
    max-width: 260px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.badge-active {
    background: #dcfce7;
    color: #166534;
}

.badge-inactive {
    background: #fee2e2;
    color: #b91c1c;
}

.action-group {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: nowrap;
}

.action-icon {
    width: 32px;
    height: 32px;
    border-radius: 11px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    transition: .2s ease;
    font-weight: 900;
}

.action-icon.edit {
    background: #dbeafe;
    color: #1d4ed8;
}

.action-icon.delete {
    background: #fee2e2;
    color: #b91c1c;
}

.action-icon:hover {
    transform: translateY(-1px);
    filter: brightness(.98);
}

.action-form {
    margin: 0;
    display: inline-flex;
}

.empty-state {
    margin-top: 16px;
    padding: 28px;
    border-radius: 22px;
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    text-align: center;
    color: #64748b;
    font-weight: 700;
}

@media(max-width: 760px) {
    .master-card {
        padding: 14px;
        border-radius: 22px;
    }

    .master-toolbar {
        gap: 10px;
        margin-bottom: 14px;
    }

    .master-title {
        font-size: 21px;
        margin-bottom: 4px;
    }

    .master-subtitle {
        font-size: 12.5px;
        line-height: 1.35;
    }

    .master-toolbar .btn {
        width: auto;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13px;
    }

    .master-table-wrap {
        border-radius: 16px;
    }

    .master-table.periode {
        min-width: 500px;
    }

    .master-table.prodi {
        min-width: 620px;
    }

    .master-table th,
    .master-table td {
        padding: 8px 8px;
        font-size: 11.5px;
    }

    .master-table th {
        font-size: 11px;
    }

    .cell-long {
        max-width: 160px;
    }

    .status-badge {
        padding: 5px 7px;
        font-size: 10px;
    }

    .action-group {
        gap: 6px;
    }

    .action-icon {
        width: 27px;
        height: 27px;
        border-radius: 9px;
        font-size: 11px;
    }

    .empty-state {
        padding: 20px;
        border-radius: 18px;
        font-size: 13px;
    }
}
</style>

<div class="master-card">
    <div class="master-toolbar">
        <div>
            <h3 class="master-title">
                <?= esc((string) ($pageTitle ?? 'Master Data')) ?>
            </h3>
            <p class="master-subtitle">
                <?= esc((string) ($pageSubtitle ?? '')) ?>
            </p>
        </div>

        <?php if (($pageType ?? '') === 'periode_akademik'): ?>
            <a href="<?= site_url('admin/periode-akademik/create') ?>" class="btn btn-primary">
                Tambah Periode
            </a>
        <?php elseif (($pageType ?? '') === 'program_studi'): ?>
            <a href="<?= site_url('admin/program-studi/create') ?>" class="btn btn-primary">
                Tambah Program Studi
            </a>
        <?php endif; ?>
    </div>

    <?php if (! empty($rows)): ?>

        <?php if (($pageType ?? '') === 'periode_akademik'): ?>
            <div class="master-table-wrap">
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
                        <?php foreach (($rows ?? []) as $row): ?>
                            <tr>
                                <td class="cell-strong">
                                    <?= esc((string) ($row['tahun_ajaran'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= esc((string) ($row['semester'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?php if ((int) ($row['is_active'] ?? 0) === 1): ?>
                                        <span class="status-badge badge-active">Aktif</span>
                                    <?php else: ?>
                                        <span class="status-badge badge-inactive">Nonaktif</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            href="<?= site_url('admin/periode-akademik/edit/' . (string) ($row['id'] ?? '')) ?>"
                                            class="action-icon edit"
                                            title="Edit"
                                        >
                                            ✎
                                        </a>

                                        <form
                                            action="<?= site_url('admin/periode-akademik/delete/' . (string) ($row['id'] ?? '')) ?>"
                                            method="post"
                                            class="action-form"
                                        >
                                            <?= csrf_field() ?>
                                            <button
                                                type="submit"
                                                class="action-icon delete"
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus periode ini?')"
                                            >
                                                🗑
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

        <?php if (($pageType ?? '') === 'program_studi'): ?>
            <div class="master-table-wrap">
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
                        <?php foreach (($rows ?? []) as $row): ?>
                            <tr>
                                <td class="cell-strong">
                                    <?= esc((string) ($row['kode_prodi'] ?? '-')) ?>
                                </td>

                                <td class="cell-long">
                                    <?= esc((string) ($row['nama_prodi'] ?? '-')) ?>
                                </td>

                                <td>
                                    <?= esc((string) ($row['jenjang'] ?? '-')) ?>
                                </td>

                                <td class="cell-long">
                                    <?= esc((string) ($row['fakultas'] ?? '-')) ?>
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            href="<?= site_url('admin/program-studi/edit/' . (string) ($row['id'] ?? '')) ?>"
                                            class="action-icon edit"
                                            title="Edit"
                                        >
                                            ✎
                                        </a>

                                        <form
                                            action="<?= site_url('admin/program-studi/delete/' . (string) ($row['id'] ?? '')) ?>"
                                            method="post"
                                            class="action-form"
                                        >
                                            <?= csrf_field() ?>
                                            <button
                                                type="submit"
                                                class="action-icon delete"
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus program studi ini?')"
                                            >
                                                🗑
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
        <div class="empty-state">
            Belum ada data.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>