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
        'admin'     => 'badge-purple',
        'mahasiswa' => 'badge-info',
        'dosen'     => 'badge-success',
        default     => 'badge-muted',
    };
};

$statusBadge = static function (mixed $status): string {
    return ((int) $status === 1) ? 'badge-success' : 'badge-danger';
};

$statusLabel = static function (mixed $status): string {
    return ((int) $status === 1) ? 'Aktif' : 'Nonaktif';
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

$initial = static function (mixed $name) use ($safe): string {
    $value = trim($safe($name, 'U'));

    return strtoupper(substr($value, 0, 1));
};

$queryParams = [
    'keyword' => $keyword,
    'role'    => $roleFilter,
    'status'  => $statusFilter,
];
?>

<div class="admin-users-page">
    <section class="page-hero admin-users-hero">
        <span class="page-kicker">
            Manajemen User
        </span>

        <h2>Kelola Data Pengguna</h2>

        <p>
            Atur akun admin, mahasiswa, dan dosen yang terdaftar pada Sistem Tugas Akhir.
        </p>
    </section>

    <section class="card-main admin-users-card">
        <div class="page-head">
            <div>
                <h3>Daftar Users</h3>
                <p>Gunakan pencarian dan filter untuk menemukan data pengguna dengan cepat.</p>
            </div>

            <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary">
                + Tambah User
            </a>
        </div>

        <div class="filter-box admin-users-filter">
            <form method="get" action="<?= site_url('admin/users') ?>" class="filter-form admin-users-filter-form">
                <div class="filter-field">
                    <label for="keyword">Cari User</label>
                    <input
                        type="text"
                        id="keyword"
                        name="keyword"
                        placeholder="Nama atau email"
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
                    <button type="submit" class="btn-filter btn-apply">
                        Terapkan
                    </button>

                    <a href="<?= site_url('admin/users') ?>" class="btn-filter btn-reset">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="data-summary-line">
            <span>
                Total hasil:
                <strong><?= esc($safe($totalRows, '0')) ?></strong>
            </span>

            <span>
                Halaman
                <strong><?= esc($safe($currentPage, '1')) ?></strong>
                dari
                <strong><?= esc($safe($totalPages, '1')) ?></strong>
            </span>
        </div>

        <?php if (! empty($users) && is_array($users)): ?>
            <div class="table-wrap admin-users-table-wrap">
                <table class="admin-table admin-users-table">
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
                                    <div class="user-cell">
                                        <div class="user-cell-avatar">
                                            <?= esc($initial($row['name'] ?? 'U')) ?>
                                        </div>

                                        <div class="user-cell-info">
                                            <strong><?= esc($safe($row['name'] ?? '-')) ?></strong>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="email-cell">
                                        <?= esc($safe($row['email'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($roleBadge($row['role'] ?? '')) ?>">
                                        <?= esc(ucfirst($safe($row['role'] ?? '-'))) ?>
                                    </span>
                                </td>

                                <td>
                                    <strong class="code-cell">
                                        <?= esc($userCode($row)) ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="badge <?= esc($statusBadge($row['is_active'] ?? 0)) ?>">
                                        <?= esc($statusLabel($row['is_active'] ?? 0)) ?>
                                    </span>
                                </td>

                                    <td>
                                        <div class="table-action-icons">

                                            <a
                                                href="<?= site_url('admin/users/edit/' . $safe($row['id'] ?? '')) ?>"
                                                class="table-icon-btn btn-edit"
                                                title="Edit User"
                                            >
                                                <i class="ri-pencil-line"></i>
                                            </a>

                                            <form
                                                action="<?= site_url('admin/users/delete/' . $safe($row['id'] ?? '')) ?>"
                                                method="post"
                                                class="action-form"
                                                onsubmit="return confirm('Yakin ingin menghapus user ini?');"
                                            >
                                                <?= csrf_field() ?>

                                                <button
                                                    type="submit"
                                                    class="table-icon-btn btn-delete"
                                                    title="Hapus User"
                                                >
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="admin-users-mobile-list">
                <?php foreach ($users as $row): ?>
                    <article class="admin-user-mobile-card">
                        <div class="admin-user-mobile-top">
                            <div class="user-cell">
                                <div class="user-cell-avatar">
                                    <?= esc($initial($row['name'] ?? 'U')) ?>
                                </div>

                                <div class="user-cell-info">
                                    <strong><?= esc($safe($row['name'] ?? '-')) ?></strong>
                                    <span><?= esc($safe($row['email'] ?? '-')) ?></span>
                                </div>
                            </div>

                            <span class="badge <?= esc($statusBadge($row['is_active'] ?? 0)) ?>">
                                <?= esc($statusLabel($row['is_active'] ?? 0)) ?>
                            </span>
                        </div>

                        <div class="admin-user-mobile-info">
                            <div>
                                <small>Role</small>
                                <b><?= esc(ucfirst($safe($row['role'] ?? '-'))) ?></b>
                            </div>

                            <div>
                                <small>NIM / NIDN</small>
                                <b><?= esc($userCode($row)) ?></b>
                            </div>
                        </div>

                        <div class="admin-user-mobile-actions">
                            <a
                                href="<?= site_url('admin/users/edit/' . $safe($row['id'] ?? '')) ?>"
                                class="btn btn-outline"
                            >
                                Edit
                            </a>

                            <form
                                action="<?= site_url('admin/users/delete/' . $safe($row['id'] ?? '')) ?>"
                                method="post"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?');"
                            >
                                <?= csrf_field() ?>

                                <button type="submit" class="btn btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ((int) $totalPages > 1): ?>
                <div class="pagination-bar">
                    <div class="pagination-info">
                        Menampilkan data halaman <?= esc($safe($currentPage, '1')) ?>
                    </div>

                    <div class="pagination-links">
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
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="empty-box">
                Data user tidak ditemukan. Coba ubah kata kunci atau filter yang digunakan.
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>
