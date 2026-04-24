<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<style>
    .users-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        color: #fff;
        border-radius: 28px;
        padding: 28px 30px;
        margin-bottom: 22px;
        box-shadow: 0 18px 40px rgba(37, 99, 235, 0.18);
        position: relative;
        overflow: hidden;
    }

    .users-hero::after {
        content: "";
        position: absolute;
        right: -45px;
        top: -45px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .users-hero h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .users-hero p {
        margin: 0;
        color: rgba(255,255,255,0.92);
        line-height: 1.7;
        position: relative;
        z-index: 1;
    }

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
        color: #0f172a;
    }

    .users-toolbar p {
        margin: 0;
        color: #64748b;
    }

    .users-filter-form {
        display: grid;
        grid-template-columns: 1.4fr 0.8fr 0.8fr 0.9fr auto auto;
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
        align-items: center;
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
        min-width: 1100px;
        border-collapse: collapse;
        background: #fff;
    }

    .premium-table thead tr {
        background: linear-gradient(135deg, #f8fbff, #eff6ff);
    }

    .premium-table th,
    .premium-table td {
        padding: 16px 14px;
        text-align: left;
        border-bottom: 1px solid #eef2f7;
        vertical-align: top;
    }

    .premium-table th {
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        white-space: nowrap;
    }

    .premium-table td {
        color: #0f172a;
        font-size: 14px;
    }

    .premium-table tbody tr:hover {
        background: #fafcff;
    }

    .cell-title {
        font-weight: 800;
        color: #0f172a;
        line-height: 1.5;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
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
        gap: 8px;
        flex-wrap: wrap;
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
        font-weight: 700;
    }

    .page-link.active {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        border-color: #2563eb;
    }

    @media (max-width: 1200px) {
        .users-filter-form {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 700px) {
        .users-filter-form {
            grid-template-columns: 1fr;
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

    $queryParams = [
        'keyword' => $keyword ?? '',
        'role'    => $roleFilter ?? '',
        'status'  => $statusFilter ?? '',
        'sort'    => $sortFilter ?? 'newest',
    ];
?>

<div class="users-hero">
    <h2>Kelola Users</h2>
    <p>Tambah, cari, filter, urutkan, edit, dan hapus akun admin, mahasiswa, dan dosen dari satu halaman yang rapi dan cepat digunakan.</p>
</div>

<div class="users-card">
    <div class="users-toolbar">
        <div>
            <h3>Kelola Data Users</h3>
            <p>Gunakan search, filter, dan sorting untuk menemukan user dengan cepat.</p>
        </div>

        <a href="<?= base_url('/admin/users/create') ?>" class="btn btn-primary">
            Tambah User
        </a>
    </div>

    <form method="get" action="<?= base_url('/admin/users') ?>" class="users-filter-form">
        <input
            type="text"
            name="keyword"
            class="input"
            placeholder="Cari nama atau email..."
            value="<?= esc((string) ($keyword ?? '')) ?>">

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

        <a href="<?= base_url('/admin/users') ?>" class="btn" style="border:1px solid #cbd5e1; color:#334155;">
            Reset
        </a>
    </form>

    <div class="users-summary-line">
        <div>
            Total hasil: <strong><?= esc((string) ($totalRows ?? 0)) ?></strong>
        </div>
        <div>
            Halaman <strong><?= esc((string) ($currentPage ?? 1)) ?></strong> dari <strong><?= esc((string) ($totalPages ?? 1)) ?></strong>
        </div>
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
                                <div class="cell-title"><?= esc((string) ($row['name'] ?? '-')) ?></div>
                            </td>
                            <td><?= esc((string) ($row['email'] ?? '-')) ?></td>
                            <td>
                                <span class="status-badge <?= $roleBadge($row['role'] ?? '') ?>">
                                    <?= esc((string) ($row['role'] ?? '-')) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (($row['role'] ?? '') === 'mahasiswa'): ?>
                                    <?= esc((string) ($row['nim'] ?? '-')) ?>
                                <?php elseif (($row['role'] ?? '') === 'dosen'): ?>
                                    <?= esc((string) ($row['nidn'] ?? '-')) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
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
                                    <a href="<?= base_url('/admin/users/edit/' . (string) ($row['id'] ?? '')) ?>" class="btn btn-primary" style="padding:10px 14px;">
                                        Edit
                                    </a>

                                    <form action="<?= base_url('/admin/users/delete/' . (string) ($row['id'] ?? '')) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger" style="padding:10px 14px;" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                            Hapus
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
                        $url = base_url('/admin/users?' . http_build_query($linkParams));
                    ?>
                    <a href="<?= $url ?>" class="page-link <?= (($currentPage ?? 1) === $i) ? 'active' : '' ?>">
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