<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$rows         = $rows ?? [];
$pageType     = $pageType ?? '';
$pageTitle    = $pageTitle ?? 'Master Data';
$pageSubtitle = $pageSubtitle ?? 'Kelola data master sistem.';

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$isPeriode = $pageType === 'periode_akademik';
$isProdi   = $pageType === 'program_studi';
$totalRows = is_array($rows) ? count($rows) : 0;

$activeRows = 0;

if ($isPeriode && is_array($rows)) {
    foreach ($rows as $row) {
        if ((int) ($row['is_active'] ?? 0) === 1) {
            $activeRows++;
        }
    }
}
?>

<div class="master-page">
    <section class="page-hero master-hero">
        <span class="page-kicker">
            <?= $isPeriode ? 'Periode Akademik' : ($isProdi ? 'Program Studi' : 'Master Data') ?>
        </span>

        <h2><?= esc($safe($pageTitle, 'Master Data')) ?></h2>

        <p><?= esc($safe($pageSubtitle, 'Kelola data master sistem.')) ?></p>
    </section>

    <section class="dosen-stat-grid stat-count-3 master-stat-grid">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Data</div>
            <div class="stat-value"><?= esc((string) $totalRows) ?></div>
            <div class="stat-desc">Data tersimpan pada modul ini</div>
        </div>

        <?php if ($isPeriode): ?>
            <div class="stat-card stat-green">
                <div class="stat-label">Periode Aktif</div>
                <div class="stat-value"><?= esc((string) $activeRows) ?></div>
                <div class="stat-desc">Periode yang sedang digunakan</div>
            </div>

            <div class="stat-card stat-amber">
                <div class="stat-label">Riwayat Periode</div>
                <div class="stat-value"><?= esc((string) max(0, $totalRows - $activeRows)) ?></div>
                <div class="stat-desc">Data periode nonaktif</div>
            </div>
        <?php else: ?>
            <div class="stat-card stat-green">
                <div class="stat-label">Data Prodi</div>
                <div class="stat-value"><?= esc((string) $totalRows) ?></div>
                <div class="stat-desc">Program studi yang tersedia</div>
            </div>

            <div class="stat-card stat-amber">
                <div class="stat-label">Status Modul</div>
                <div class="stat-value">OK</div>
                <div class="stat-desc">Master data siap digunakan</div>
            </div>
        <?php endif; ?>
    </section>

    <section class="card-main master-card">
        <div class="page-head master-head">
            <div>
                <h3><?= esc($safe($pageTitle, 'Master Data')) ?></h3>
                <p><?= esc($safe($pageSubtitle, 'Kelola data master sistem.')) ?></p>
            </div>

            <?php if ($isPeriode): ?>
                <a href="<?= site_url('admin/periode-akademik/create') ?>" class="btn btn-primary">
                    + Tambah Periode
                </a>
            <?php elseif ($isProdi): ?>
                <a href="<?= site_url('admin/program-studi/create') ?>" class="btn btn-primary">
                    + Tambah Prodi
                </a>
            <?php endif; ?>
        </div>

        <?php if (! empty($rows) && is_array($rows)): ?>
            <?php if ($isPeriode): ?>
                <div class="table-wrap master-table-wrap">
                    <table class="admin-table master-table master-table-periode">
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
                                    <td>
                                        <strong><?= esc($safe($row['tahun_ajaran'] ?? '-')) ?></strong>
                                    </td>

                                    <td>
                                        <?= esc($safe($row['semester'] ?? '-')) ?>
                                    </td>

                                    <td>
                                        <?php if ((int) ($row['is_active'] ?? 0) === 1): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-muted">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="table-action-icons">
                                            <a
                                                href="<?= site_url('admin/periode-akademik/edit/' . $safe($row['id'] ?? '')) ?>"
                                                class="table-icon-btn btn-edit"
                                                title="Edit Periode"
                                            >
                                                <i class="ri-edit-2-line"></i>
                                                <span class="sr-only">Edit</span>
                                            </a>

                                            <form
                                                action="<?= site_url('admin/periode-akademik/delete/' . $safe($row['id'] ?? '')) ?>"
                                                method="post"
                                                class="action-form"
                                                onsubmit="return confirm('Yakin ingin menghapus periode ini?');"
                                            >
                                                <?= csrf_field() ?>

                                                <button
                                                    type="submit"
                                                    class="table-icon-btn btn-delete"
                                                    title="Hapus Periode"
                                                >
                                                    <i class="ri-delete-bin-6-line"></i>
                                                    <span class="sr-only">Hapus</span>
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

            <?php if ($isProdi): ?>
                <div class="table-wrap master-table-wrap">
                    <table class="admin-table master-table master-table-prodi">
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
                                    <td>
                                        <strong><?= esc($safe($row['kode_prodi'] ?? '-')) ?></strong>
                                    </td>

                                    <td>
                                        <div class="master-long-cell">
                                            <?= esc($safe($row['nama_prodi'] ?? '-')) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?= esc($safe($row['jenjang'] ?? '-')) ?>
                                    </td>

                                    <td>
                                        <div class="master-long-cell">
                                            <?= esc($safe($row['fakultas'] ?? '-')) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="table-action-icons">
                                            <a
                                                href="<?= site_url('admin/program-studi/edit/' . $safe($row['id'] ?? '')) ?>"
                                                class="table-icon-btn btn-edit"
                                                title="Edit Program Studi"
                                            >
                                                <i class="ri-edit-2-line"></i>
                                                <span class="sr-only">Edit</span>
                                            </a>

                                            <form
                                                action="<?= site_url('admin/program-studi/delete/' . $safe($row['id'] ?? '')) ?>"
                                                method="post"
                                                class="action-form"
                                                onsubmit="return confirm('Yakin ingin menghapus program studi ini?');"
                                            >
                                                <?= csrf_field() ?>

                                                <button
                                                    type="submit"
                                                    class="table-icon-btn btn-delete"
                                                    title="Hapus Program Studi"
                                                >
                                                    <i class="ri-delete-bin-6-line"></i>
                                                    <span class="sr-only">Hapus</span>
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
                Belum ada data master yang tersedia.
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>
