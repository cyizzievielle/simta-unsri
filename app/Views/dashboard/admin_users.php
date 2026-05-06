<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$users        = $users ?? [];
$keyword      = $keyword ?? '';
$roleFilter   = $roleFilter ?? '';
$statusFilter = $statusFilter ?? '';
$totalRows    = $totalRows ?? 0;
$totalPages   = $totalPages ?? 1;
$currentPage  = $currentPage ?? 1;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$roleBadge = static function (mixed $role) use ($safe): string {
    return match (strtolower($safe($role, ''))) {
        'admin'     => 'badge-role-admin',
        'mahasiswa' => 'badge-role-mahasiswa',
        'dosen'     => 'badge-role-dosen',
        default     => 'badge-role-admin',
    };
};

$userCode = static function (array $row) use ($safe): string {
    if (($row['role'] ?? '') === 'mahasiswa') {
        return $safe($row['nim'] ?? '-');
    }

    if (($row['role'] ?? '') === 'dosen') {
        return $safe($row['nidn'] ?? '-');
    }

    return '-';
};

$queryParams = [
    'keyword' => $keyword,
    'role'    => $roleFilter,
    'status'  => $statusFilter,
];
?>

<style>
.users-page {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.users-card {
    background: #fff;
    border-radius: 24px;
    padding: 18px;
    border: 1px solid #eef2f7;
    box-shadow: 0 12px 30px rgba(15, 23, 42, .055);
}

.users-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.users-toolbar h3 {
    margin: 0 0 5px;
    font-size: 21px;
    font-weight: 900;
    color: #0f172a;
}

.users-toolbar p {
    margin: 0;
    color: #64748b;
    font-size: 12.5px;
    line-height: 1.55;
}

.btn-add {
    height: 38px;
    padding: 0 13px;
    border-radius: 12px;
    background: #2563eb;
    color: #fff;
    text-decoration: none;
    font-size: 12px;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
}

/* FILTER COMPACT */
.filter-box {
    width: fit-content;
    max-width: 100%;
    border: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #f8fbff, #f1f5f9);
    border-radius: 16px;
    padding: 10px;
    margin-bottom: 14px;
}

.users-filter-form {
    display: grid;
    grid-template-columns: 150px 92px 98px;
    gap: 7px;
    align-items: end;
}

.filter-field label {
    display: block;
    margin-bottom: 4px;
    font-size: 10px;
    font-weight: 900;
    color: #334155;
}

.filter-field input,
.filter-field select {
    width: 100%;
    height: 33px;
    border: 1px solid #dbe3ef;
    border-radius: 10px;
    padding: 6px 8px;
    font-size: 10.5px;
    background: #fff;
    outline: none;
    color: #0f172a;
}

.filter-field input:focus,
.filter-field select:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, .10);
}

.filter-actions {
    grid-column: 1 / -1;
    display: flex;
    gap: 7px;
}

.btn-filter {
    height: 32px;
    padding: 0 12px;
    border-radius: 10px;
    border: 0;
    cursor: pointer;
    font-size: 10.5px;
    font-weight: 900;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-apply {
    background: #2563eb;
    color: #fff;
}

.btn-reset {
    background: #fff;
    border: 1px solid #cbd5e1;
    color: #334155;
}

.users-summary-line {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 12px;
    color: #64748b;
    font-size: 12px;
    font-weight: 750;
}

.premium-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 18px;
    border: 1px solid #eef2f7;
    background: #fff;
}

.premium-table {
    width: 100%;
    min-width: 780px;
    border-collapse: collapse;
}

.premium-table thead tr {
    background: linear-gradient(135deg, #f8fbff, #eff6ff);
}

.premium-table th,
.premium-table td {
    padding: 11px 10px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: middle;
    font-size: 12px;
    color: #0f172a;
}

.premium-table th {
    font-size: 10.5px;
    font-weight: 950;
    color: #334155;
    white-space: nowrap;
}

.cell-title {
    font-weight: 900;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 190px;
}

.email-cell {
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #475569;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 950;
    white-space: nowrap;
}

.badge-role-admin {
    background: #ede9fe;
    color: #6d28d9;
}

.badge-role-mahasiswa {
    background: #dbeafe;
    color: #1d4ed8;
}

.badge-role-dosen {
    background: #dcfce7;
    color: #166534;
}

.badge-aktif {
    background: #dcfce7;
    color: #166534;
}

.badge-nonaktif {
    background: #fee2e2;
    color: #b91c1c;
}

.action-group {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: nowrap;
}

.action-icon {
    width: 30px;
    height: 30px;
    border-radius: 11px;
    border: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
    transition: .18s ease;
    font-weight: 900;
    line-height: 1;
    padding: 0;
}

.action-icon.edit {
    background: #dbeafe;
    color: #1d4ed8;
}

.action-icon.delete {
    background: #fff5f5;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.action-icon:hover {
    transform: translateY(-1px);
}

.action-form {
    margin: 0;
    padding: 0;
    display: inline-flex;
}

.premium-empty {
    border: 1px dashed #cbd5e1;
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 18px;
    padding: 24px;
    text-align: center;
    color: #64748b;
    font-size: 13px;
    line-height: 1.7;
}

.pagination-wrap {
    display: flex;
    justify-content: center;
    gap: 7px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.page-link {
    min-width: 34px;
    height: 34px;
    padding: 0 10px;
    border-radius: 11px;
    border: 1px solid #dbe3ef;
    background: #fff;
    color: #334155;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 850;
    font-size: 12px;
}

.page-link.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}

@media (max-width: 760px) {
    .users-card {
        padding: 13px;
        border-radius: 18px;
    }

    .users-toolbar {
        gap: 10px;
        margin-bottom: 12px;
    }

    .users-toolbar h3 {
        font-size: 18px;
    }

    .users-toolbar p {
        font-size: 11.5px;
    }

    .btn-add {
        height: 36px;
        font-size: 11.5px;
        padding: 0 11px;
        border-radius: 12px;
    }

    .filter-box {
        width: 100%;
        padding: 10px;
        border-radius: 16px;
    }

    .users-filter-form {
        grid-template-columns: 1.25fr .8fr .8fr;
        gap: 6px;
    }

    .filter-field label {
        font-size: 9.5px;
    }

    .filter-field input,
    .filter-field select {
        height: 32px;
        font-size: 10px;
        padding: 6px 7px;
    }

    .btn-filter {
        height: 32px;
        font-size: 10px;
        padding: 0 11px;
    }

    .users-summary-line {
        font-size: 11px;
        margin-bottom: 10px;
    }

    .premium-table {
        min-width: 700px;
    }

    .premium-table th,
    .premium-table td {
        padding: 9px 8px;
        font-size: 11px;
    }

    .premium-table th {
        font-size: 10px;
    }

    .cell-title {
        max-width: 130px;
        font-size: 11px;
    }

    .email-cell {
        max-width: 160px;
        font-size: 11px;
    }

    .status-badge {
        padding: 5px 7px;
        font-size: 9.5px;
    }

    .action-icon {
        width: 28px;
        height: 28px;
        border-radius: 10px;
        font-size: 12px;
    }

    .pagination-wrap {
        overflow-x: auto;
        flex-wrap: nowrap;
        justify-content: flex-start;
        padding-bottom: 4px;
    }

    .page-link {
        min-width: 31px;
        height: 31px;
        font-size: 10.5px;
        flex-shrink: 0;
    }
}
</style>

<div class="users-page">
    <div class="users-card">
        <div class="users-toolbar">
            <div>
                <h3>Kelola Data Users</h3>
                <p>Gunakan search dan filter untuk menemukan user dengan cepat.</p>
            </div>

            <a href="<?= site_url('admin/users/create') ?>" class="btn-add">
                + Tambah User
            </a>
        </div>

        <div class="filter-box">
            <form method="get" action="<?= site_url('admin/users') ?>" class="users-filter-form">
                <div class="filter-field">
                    <label for="keyword">Cari User</label>
                    <input
                        type="text"
                        id="keyword"
                        name="keyword"
                        placeholder="Cari nama/email"
                        value="<?= esc($safe($keyword, '')) ?>"
                    >
                </div>

                <div class="filter-field">
                    <label for="role">Role</label>
                    <select id="role" name="role">
                        <option value="">Semua</option>
                        <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="mahasiswa" <?= $roleFilter === 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="dosen" <?= $roleFilter === 'dosen' ? 'selected' : '' ?>>Dosen</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="aktif" <?= $statusFilter === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="nonaktif" <?= $statusFilter === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                    <a href="<?= site_url('admin/users') ?>" class="btn-filter btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <div class="users-summary-line">
            <div>Total hasil: <strong><?= esc($safe($totalRows, '0')) ?></strong></div>
            <div>Halaman <strong><?= esc($safe($currentPage, '1')) ?></strong> dari <strong><?= esc($safe($totalPages, '1')) ?></strong></div>
        </div>

        <?php if (! empty($users) && is_array($users)): ?>
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
                                        <?= esc($safe($row['name'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="email-cell">
                                        <?= esc($safe($row['email'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge <?= esc($roleBadge($row['role'] ?? '')) ?>">
                                        <?= esc($safe($row['role'] ?? '-')) ?>
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
                                            href="<?= site_url('admin/users/edit/' . $safe($row['id'] ?? '')) ?>"
                                            class="action-icon edit"
                                            title="Edit"
                                        >
                                            ✎
                                        </a>

                                        <form
                                            action="<?= site_url('admin/users/delete/' . $safe($row['id'] ?? '')) ?>"
                                            method="post"
                                            class="action-form"
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?');"
                                        >
                                            <?= csrf_field() ?>

                                            <button type="submit" class="action-icon delete" title="Hapus">
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

            <?php if ((int) $totalPages > 1): ?>
                <div class="pagination-wrap">
                    <?php for ($i = 1; $i <= (int) $totalPages; $i++): ?>
                        <?php
                            $linkParams = $queryParams;
                            $linkParams['page'] = $i;
                            $url = site_url('admin/users?' . http_build_query($linkParams));
                        ?>

                        <a href="<?= esc($url) ?>" class="page-link <?= (int) $currentPage === $i ? 'active' : '' ?>">
                            <?= esc((string) $i) ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="premium-empty">
                Data user tidak ditemukan. Coba ubah kata kunci atau filter yang dipakai.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>