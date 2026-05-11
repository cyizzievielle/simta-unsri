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

$avatarUrl = static function (array $row) use ($safe): string {
    $foto = trim($safe($row['foto'] ?? '', ''));

    if ($foto === '') {
        return '';
    }

    $path = FCPATH . 'uploads/profile/' . $foto;

    return is_file($path) ? base_url('uploads/profile/' . $foto) : '';
};

$userDetailJson = static function (array $row) use ($safe, $userCode, $statusLabel, $avatarUrl): string {
    $payload = [
        'name'   => $safe($row['name'] ?? '-'),
        'email'  => $safe($row['email'] ?? '-'),
        'role'   => ucfirst($safe($row['role'] ?? '-')),
        'code'   => $userCode($row),
        'status' => $statusLabel($row['is_active'] ?? 0),
        'avatar' => $avatarUrl($row),
    ];

    $json = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

    return is_string($json) ? $json : '{}';
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
                            <?php
                                $userAvatar = $avatarUrl($row);
                                $userDetail = $userDetailJson($row);
                            ?>
                            <tr>
                                <td>
                                    <button
                                        type="button"
                                        class="user-cell user-cell-profile-trigger user-detail-trigger"
                                        title="Lihat detail akun"
                                        data-detail="<?= esc($userDetail, 'attr') ?>"
                                    >
                                        <span class="user-cell-avatar" aria-hidden="true">
                                            <?php if ($userAvatar !== ''): ?>
                                                <img src="<?= esc($userAvatar, 'attr') ?>" alt="<?= esc($safe($row['name'] ?? 'User'), 'attr') ?>">
                                            <?php else: ?>
                                                <?= esc($initial($row['name'] ?? 'U')) ?>
                                            <?php endif; ?>
                                        </span>

                                        <div class="user-cell-info">
                                            <strong><?= esc($safe($row['name'] ?? '-')) ?></strong>
                                        </div>
                                    </button>
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
                    <?php
                        $userAvatar = $avatarUrl($row);
                        $userDetail = $userDetailJson($row);
                    ?>
                    <article class="admin-user-mobile-card">
                        <div class="admin-user-mobile-top">
                            <button
                                type="button"
                                class="user-cell user-cell-profile-trigger user-detail-trigger"
                                title="Lihat detail akun"
                                data-detail="<?= esc($userDetail, 'attr') ?>"
                            >
                                <span class="user-cell-avatar" aria-hidden="true">
                                    <?php if ($userAvatar !== ''): ?>
                                        <img src="<?= esc($userAvatar, 'attr') ?>" alt="<?= esc($safe($row['name'] ?? 'User'), 'attr') ?>">
                                    <?php else: ?>
                                        <?= esc($initial($row['name'] ?? 'U')) ?>
                                    <?php endif; ?>
                                </span>

                                <div class="user-cell-info">
                                    <strong><?= esc($safe($row['name'] ?? '-')) ?></strong>
                                    <span><?= esc($safe($row['email'] ?? '-')) ?></span>
                                </div>
                            </button>

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

<div class="user-profile-modal" id="userProfileModal" aria-hidden="true">
    <div class="user-profile-card" role="dialog" aria-modal="true" aria-labelledby="userProfileName">
        <button type="button" class="user-profile-close" id="closeUserProfile" aria-label="Tutup detail akun">
            <i class="ri-close-line"></i>
        </button>

        <div class="user-profile-cover"></div>

        <div class="user-profile-avatar" id="userProfileAvatar"></div>

        <div class="user-profile-main">
            <h4 id="userProfileName">-</h4>
            <p id="userProfileEmail">-</p>

            <div class="user-profile-badges">
                <span id="userProfileRole">-</span>
                <span id="userProfileStatus">-</span>
            </div>
        </div>

        <div class="user-profile-detail">
            <div>
                <span>NIM / NIDN</span>
                <strong id="userProfileCode">-</strong>
            </div>
            <div>
                <span>Email</span>
                <strong id="userProfileEmailDetail">-</strong>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('userProfileModal');
    const closeBtn = document.getElementById('closeUserProfile');
    const avatarBox = document.getElementById('userProfileAvatar');
    const nameEl = document.getElementById('userProfileName');
    const emailEl = document.getElementById('userProfileEmail');
    const roleEl = document.getElementById('userProfileRole');
    const statusEl = document.getElementById('userProfileStatus');
    const codeEl = document.getElementById('userProfileCode');
    const emailDetailEl = document.getElementById('userProfileEmailDetail');

    if (!modal || !avatarBox || !nameEl || !emailEl || !roleEl || !statusEl || !codeEl || !emailDetailEl) return;

    const escapeHtml = (value) => String(value ?? '-').replace(/[&<>"']/g, (char) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    }[char]));

    const getInitial = (name) => String(name || 'U').trim().charAt(0).toUpperCase() || 'U';

    const openModal = (data) => {
        const name = data.name || '-';
        const email = data.email || '-';

        avatarBox.innerHTML = data.avatar
            ? `<img src="${escapeHtml(data.avatar)}" alt="${escapeHtml(name)}">`
            : `<span>${escapeHtml(getInitial(name))}</span>`;

        nameEl.textContent = name;
        emailEl.textContent = email;
        roleEl.textContent = data.role || '-';
        statusEl.textContent = data.status || '-';
        statusEl.classList.toggle('is-inactive', String(data.status || '').toLowerCase() !== 'aktif');
        codeEl.textContent = data.code || '-';
        emailDetailEl.textContent = email;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    };

    const closeModal = () => {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('.user-detail-trigger').forEach((button) => {
        button.addEventListener('click', () => {
            try {
                openModal(JSON.parse(button.dataset.detail || '{}'));
            } catch (error) {
                openModal({});
            }
        });
    });

    closeBtn?.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeModal();
    });
})();
</script>

<?= $this->endSection() ?>
