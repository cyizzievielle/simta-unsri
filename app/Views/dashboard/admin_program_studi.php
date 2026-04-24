<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h3 class="section-title">Program Studi</h3>
            <p class="section-subtitle">Kelola data program studi yang digunakan di sistem.</p>
        </div>
        <a href="<?= base_url('/admin/program-studi/create') ?>" class="btn btn-primary">Tambah Program Studi</a>
    </div>

    <?php if (! empty($programStudi)): ?>
        <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:1000px;">
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
                    <?php foreach ($programStudi as $row): ?>
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
    <?php else: ?>
        <div class="placeholder-box">Belum ada data program studi.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>