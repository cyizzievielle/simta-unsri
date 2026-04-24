<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h3 class="section-title"><?= esc((string) ($pageTitle ?? 'Master Data')) ?></h3>
            <p class="section-subtitle"><?= esc((string) ($pageSubtitle ?? '')) ?></p>
        </div>

        <?php if (($pageType ?? '') === 'periode_akademik'): ?>
            <a href="<?= base_url('/admin/periode-akademik/create') ?>" class="btn btn-primary">Tambah Periode</a>
        <?php elseif (($pageType ?? '') === 'program_studi'): ?>
            <a href="<?= base_url('/admin/program-studi/create') ?>" class="btn btn-primary">Tambah Program Studi</a>
        <?php endif; ?>
    </div>

    <?php if (($pageType ?? '') === 'periode_akademik'): ?>
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:1000px;">
                <thead>
                    <tr>
                        <td style="font-weight:800; padding-bottom:14px;">Tahun Ajaran</td>
                        <td style="font-weight:800; padding-bottom:14px;">Semester</td>
                        <td style="font-weight:800; padding-bottom:14px;">Status Aktif</td>
                        <td style="font-weight:800; padding-bottom:14px;">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($rows ?? []) as $row): ?>
                        <tr>
                            <td><?= esc((string) ($row['tahun_ajaran'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['semester'] ?? '-')) ?></td>
                            <td><?= ((int) ($row['is_active'] ?? 0) === 1) ? 'Aktif' : 'Nonaktif' ?></td>
                            <td>
                                <a href="<?= base_url('/admin/periode-akademik/edit/' . (string) ($row['id'] ?? '')) ?>" class="btn btn-primary" style="padding:10px 14px; margin-right:8px;">Edit</a>

                                <form action="<?= base_url('/admin/periode-akademik/delete/' . (string) ($row['id'] ?? '')) ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger" style="padding:10px 14px;" onclick="return confirm('Yakin ingin menghapus periode ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (($pageType ?? '') === 'program_studi'): ?>
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:1100px;">
                <thead>
                    <tr>
                        <td style="font-weight:800; padding-bottom:14px;">Kode Prodi</td>
                        <td style="font-weight:800; padding-bottom:14px;">Nama Prodi</td>
                        <td style="font-weight:800; padding-bottom:14px;">Jenjang</td>
                        <td style="font-weight:800; padding-bottom:14px;">Fakultas</td>
                        <td style="font-weight:800; padding-bottom:14px;">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($rows ?? []) as $row): ?>
                        <tr>
                            <td><?= esc((string) ($row['kode_prodi'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['nama_prodi'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['jenjang'] ?? '-')) ?></td>
                            <td><?= esc((string) ($row['fakultas'] ?? '-')) ?></td>
                            <td>
                                <a href="<?= base_url('/admin/program-studi/edit/' . (string) ($row['id'] ?? '')) ?>" class="btn btn-primary" style="padding:10px 14px; margin-right:8px;">Edit</a>

                                <form action="<?= base_url('/admin/program-studi/delete/' . (string) ($row['id'] ?? '')) ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger" style="padding:10px 14px;" onclick="return confirm('Yakin ingin menghapus program studi ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
        <div class="placeholder-box">Belum ada data.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>