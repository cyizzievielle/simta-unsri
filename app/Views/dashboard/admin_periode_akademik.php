<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h3 class="section-title">Periode Akademik</h3>
            <p class="section-subtitle">Kelola semester aktif dan riwayat periode akademik.</p>
        </div>
        <a href="<?= base_url('/admin/periode-akademik/create') ?>" class="btn btn-primary">Tambah Periode</a>
    </div>

    <?php if (! empty($periode)): ?>
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:900px;">
                <thead>
                    <tr>
                        <td style="font-weight:800; padding-bottom:14px;">Tahun Ajaran</td>
                        <td style="font-weight:800; padding-bottom:14px;">Semester</td>
                        <td style="font-weight:800; padding-bottom:14px;">Status Aktif</td>
                        <td style="font-weight:800; padding-bottom:14px;">Aksi</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periode as $row): ?>
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
    <?php else: ?>
        <div class="placeholder-box">Belum ada data periode akademik.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>