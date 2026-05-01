<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.sk-container {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.card-premium {
    background: #fff;
    border-radius: 24px;
    padding: 24px;
    border: 1px solid #edf2f7;
    box-shadow: 0 12px 32px rgba(0,0,0,0.05);
}

.title {
    font-size: 28px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 6px;
}

.subtitle {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 20px;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead {
    background: #f1f5f9;
}

.table-modern th {
    text-align: left;
    padding: 14px;
    font-size: 13px;
    color: #475569;
    font-weight: 800;
}

.table-modern td {
    padding: 16px 14px;
    border-bottom: 1px solid #eef2f7;
    vertical-align: top;
}

.judul {
    font-weight: 800;
    color: #0f172a;
    line-height: 1.5;
}

.badge {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.badge-success {
    background: #dcfce7;
    color: #15803d;
}

.btn-open {
    background: linear-gradient(135deg, #2563eb, #1e40af);
    color: #fff;
    border-radius: 12px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
}

.btn-open:hover {
    opacity: 0.9;
}

.empty {
    text-align: center;
    padding: 30px;
    color: #94a3b8;
}
</style>

<div class="sk-container">

    <!-- HEADER -->
    <div class="card-premium">
        <div class="title">Surat Keputusan</div>
        <div class="subtitle">
            Lihat dan buka SK tugas akhir yang telah diterbitkan.
        </div>
    </div>

    <!-- TABLE -->
    <div class="card-premium">
        <div class="title" style="font-size:20px;">Daftar SK Saya</div>
        <div class="subtitle">
            File SK bisa langsung dibuka dari tombol di bawah.
        </div>

        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width:20%">Nomor SK</th>
                    <th>Judul</th>
                    <th style="width:15%">Tanggal</th>
                    <th style="width:10%">Status</th>
                    <th style="width:12%">Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (! empty($sk_list)): ?>
                    <?php foreach ($sk_list as $row): ?>
                        <tr>
                            <td>
                                <strong><?= esc((string) ($row['nomor_sk'] ?? '-')) ?></strong>
                            </td>

                            <td>
                                <div class="judul">
                                    <?= esc((string) ($row['judul'] ?? '-')) ?>
                                </div>
                            </td>

                            <td>
                                <?= date('d M Y', strtotime($row['tanggal_terbit'])) ?>
                            </td>

                            <td>
                                <span class="badge badge-success">
                                    Terbit
                                </span>
                            </td>

                            <td>
                                <?php if (! empty($row['file_sk'])): ?>
                                    <a href="<?= base_url($row['file_sk']) ?>" 
                                       target="_blank"
                                       class="btn-open">
                                        📄 Buka
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty">
                            Belum ada SK tersedia.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

</div>

<?= $this->endSection() ?>