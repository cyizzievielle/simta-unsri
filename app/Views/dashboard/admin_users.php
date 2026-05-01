<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>
<?php $totalPages = $totalPages ?? 1; ?>
<?php $currentPage = $currentPage ?? 1; ?>

<style>
.users-card {
    background: #fff;
    border-radius: 26px;
    padding: 24px;
    box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
    border: 1px solid #eef2f7;
}

.users-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.users-toolbar h3 {
    margin: 0 0 6px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.users-toolbar p {
    margin: 0;
    color: #64748b;
    line-height: 1.6;
}

.users-filter-form {
    display: grid;
    grid-template-columns: 1.4fr .8fr .8fr .9fr auto auto;
    gap: 12px;
    margin-bottom: 18px;
}

.users-filter-form .input,
.users-filter-form select {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 14px;
    padding: 12px 14px;
    font-size: 14px;
    background: #fff;
    outline: none;
}

.users-filter-form .input:focus,
.users-filter-form select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
}

.users-summary-line {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
    color: #64748b;
    font-size: 14px;
}

.premium-table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 18px;
    border: 1px solid #eef2f7;
}

.premium-table {
    width: 100%;
    min-width: 760px;
    border-collapse: collapse;
    background: #fff;
}

.premium-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.premium-table th,
.premium-table td {
    padding: 14px 12px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
}

.premium-table th {
    font-size: 11px;
    font-weight: 900;
    color: #334155;
    white-space: nowrap;
}

.premium-table td {
    color: #0f172a;
    font-size: 12px;
}

.cell-title {
    font-weight: 900;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 220px; /* desktop */
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

.badge-role-admin { background: #ede9fe; color: #6d28d9; }
.badge-role-mahasiswa { background: #dbeafe; color: #1d4ed8; }
.badge-role-dosen { background: #dcfce7; color: #166534; }
.badge-aktif { background: #dcfce7; color: #166534; }
.badge-nonaktif { background: #fee2e2; color: #b91c1c; }

.action-group {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: nowrap;
}

.action-icon {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 15px;
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

.premium-empty {
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 22px;
    padding: 28px;
    text-align: center;
    color: #64748b;
}

.pagination-wrap {
    display: flex;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.page-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 42px;
    height: 42px;
    padding: 0 14px;
    border-radius: 12px;
    border: 1px solid #dbe3ef;
    background: #fff;
    color: #334155;
    text-decoration: none;
    font-weight: 800;
}

.page-link.active {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    border-color: #2563eb;
}

@media(max-width: 1200px) {
    .users-filter-form {
        grid-template-columns: 1fr 1fr;
    }
}

@media(max-width: 760px) {
    .users-card {
        padding: 14px;
        border-radius: 22px;
    }

    .users-toolbar {
        gap: 10px;
        margin-bottom: 14px;
    }

    .users-toolbar h3 {
        font-size: 21px;
        margin-bottom: 4px;
    }

    .users-toolbar p {
        font-size: 13px;
        line-height: 1.35;
    }

    .users-toolbar .btn {
        width: auto;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13px;
    }

    .users-filter-form {
        grid-template-columns: 1fr;
        gap: 9px;
        margin-bottom: 12px;
    }

    .users-filter-form .input,
    .users-filter-form select {
        padding: 10px 12px;
        border-radius: 13px;
        font-size: 13px;
    }

    .users-filter-form .btn {
        min-height: 40px;
        padding: 10px 12px;
        border-radius: 13px;
        font-size: 13px;
    }

    .users-summary-line {
        padding: 0;
        margin-bottom: 10px;
        background: transparent;
        border: 0;
        font-size: 12px;
    }

    .premium-table-wrap {
        border-radius: 16px;
    }

    .premium-table {
        min-width: 580px;
    }

    .premium-table th,
    .premium-table td {
        padding: 9px 9px;
        font-size: 11.5px;
    }

    .premium-table th {
        font-size: 11.5px;
    }

    .cell-title {
        font-size: 12.5px;
        max-width: 135px;
    }

    .status-badge {
        padding: 6px 8px;
        font-size: 10.5px;
    }

    .action-group {
        gap: 6px;
    }

    .action-icon {
        width: 26px;
        height: 26px;
        border-radius: 10px;
        font-size: 12px;
    }
}
</style>

<?php
$roleBadge = static function (?string $role): string {
    $role = strtolower((string) $role);

    return match ($role) {
        'admin'     => 'badge-role-admin',
        'mahasiswa' => 'badge-role-mahasiswa',
        'dosen'     => 'badge-role-dosen',
        default     => 'badge-role-admin',
    };
};

$userCode = static function (array $row): string {
    if (($row['role'] ?? '') === 'mahasiswa') {
        return (string) ($row['nim'] ?? '-');
    }

    if (($row['role'] ?? '') === 'dosen') {
        return (string) ($row['nidn'] ?? '-');
    }

    return '-';
};

$queryParams = [
    'keyword' => $keyword ?? '',
    'role'    => $roleFilter ?? '',
    'status'  => $statusFilter ?? '',
    'sort'    => $sortFilter ?? 'newest',
];
?>

<div class="users-card">
    <div class="users-toolbar">
        <div>
            <h3>Kelola Data Users</h3>
            <p>Gunakan search, filter, dan sorting untuk menemukan user dengan cepat.</p>
        </div>

        <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">
            Tambah User
        </a>
    </div>

    <form method="get" action="<?= site_url('admin/users') ?>" class="users-filter-form">
        <input
            type="text"
            name="keyword"
            class="input"
            placeholder="Cari nama atau email..."
            value="<?= esc((string) ($keyword ?? '')) ?>"
        >

        <select name="role">
            <option value="">Semua Role</option>
            <option value="admin" <?= (($roleFilter ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="mahasiswa" <?= (($roleFilter ?? '') === 'mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
            <option value="dosen" <?= (($roleFilter ?? '') === 'dosen') ? 'selected' : '' ?>>Dosen</option>
        </select>

        <select name="status">
            <option value="">Semua Status</option>
            <option value="aktif" <?= (($statusFilter ?? '') === 'aktif') ? 'selected' : '' ?>>Aktif</option>
            <option value="nonaktif" <?= (($statusFilter ?? '') === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
        </select>

        <select name="sort">
            <option value="newest" <?= (($sortFilter ?? '') === 'newest') ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= (($sortFilter ?? '') === 'oldest') ? 'selected' : '' ?>>Terlama</option>
            <option value="name_asc" <?= (($sortFilter ?? '') === 'name_asc') ? 'selected' : '' ?>>Nama A-Z</option>
            <option value="name_desc" <?= (($sortFilter ?? '') === 'name_desc') ? 'selected' : '' ?>>Nama Z-A</option>
            <option value="email_asc" <?= (($sortFilter ?? '') === 'email_asc') ? 'selected' : '' ?>>Email A-Z</option>
            <option value="email_desc" <?= (($sortFilter ?? '') === 'email_desc') ? 'selected' : '' ?>>Email Z-A</option>
        </select>

        <button type="submit" class="btn btn-primary">Terapkan</button>

        <a href="<?= site_url('admin/users') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155;">
            Reset
        </a>
    </form>

    <div class="users-summary-line">
        <div>Total hasil: <strong><?= esc((string) ($totalRows ?? 0)) ?></strong></div>
        <div>Halaman <strong><?= esc((string) ($currentPage ?? 1)) ?></strong> dari <strong><?= esc((string) ($totalPages ?? 1)) ?></strong></div>
    </div>

    <?php if (! empty($users)): ?>
        <div class="premium-table-wrap">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>NIM / NIDN</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $row): ?>
                        <tr>
                            <td>
                                <div class="cell-title">
                                    <?= esc((string) ($row['name'] ?? '-')) ?>
                                </div>
                            </td>

                            <td>
                                <?= esc((string) ($row['email'] ?? '-')) ?>
                            </td>

                            <td>
                                <span class="status-badge <?= $roleBadge($row['role'] ?? '') ?>">
                                    <?= esc((string) ($row['role'] ?? '-')) ?>
                                </span>
                            </td>

                            <td>
                                <?= esc($userCode($row)) ?>
                            </td>

                            <td>
                                <?php if ((int) ($row['is_active'] ?? 0) === 1): ?>
                                    <span class="status-badge badge-aktif">Aktif</span>
                                <?php else: ?>
                                    <span class="status-badge badge-nonaktif">Nonaktif</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="action-group">
                                    <a
                                        href="<?= site_url('admin/users/edit/' . (string) ($row['id'] ?? '')) ?>"
                                        class="action-icon edit"
                                        title="Edit"
                                    >
                                        ✎
                                    </a>

                                    <form
                                        action="<?= site_url('admin/users/delete/' . (string) ($row['id'] ?? '')) ?>"
                                        method="post"
                                        class="action-form"
                                    >
                                        <?= csrf_field() ?>

                                        <button
                                            type="submit"
                                            class="action-icon delete"
                                            title="Hapus"
                                            onclick="return confirm('Yakin ingin menghapus user ini?')"
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

        <?php if (($totalPages ?? 1) > 1): ?>
            <div class="pagination-wrap">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                    $linkParams = $queryParams;
                    $linkParams['page'] = $i;
                    $url = site_url('admin/users?' . http_build_query($linkParams));
                    ?>

                    <a href="<?= $url ?>" class="page-link <?= ((int) ($currentPage ?? 1) === $i) ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="premium-empty">
            Data user tidak ditemukan. Coba ubah kata kunci, filter, atau sorting yang dipakai.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>