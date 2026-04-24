<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
    .logs-hero {
        background: linear-gradient(135deg, #0f172a 0%, #334155 45%, #1d4ed8 100%);
        color: #fff;
        border-radius: 28px;
        padding: 28px 30px;
        margin-bottom: 22px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        position: relative;
        overflow: hidden;
    }

    .logs-hero::after {
        content: "";
        position: absolute;
        right: -45px;
        top: -45px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .logs-hero h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .logs-hero p {
        margin: 0;
        color: rgba(255,255,255,0.92);
        line-height: 1.7;
        position: relative;
        z-index: 1;
    }

    .logs-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 22px;
    }

    .logs-card {
        background: #fff;
        border-radius: 24px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
    }

    .logs-card h3 {
        margin: 0 0 8px;
        font-size: 22px;
        color: #0f172a;
    }

    .logs-card p {
        margin: 0 0 16px;
        color: #64748b;
        line-height: 1.7;
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

    .notif-list {
        display: grid;
        gap: 14px;
    }

    .notif-card {
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border-radius: 20px;
        padding: 16px;
    }

    .notif-card strong {
        display: block;
        margin-bottom: 8px;
        color: #0f172a;
    }

    .notif-meta {
        color: #64748b;
        font-size: 13px;
        line-height: 1.65;
        margin-top: 6px;
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

    .badge-read {
        background: #dcfce7;
        color: #166534;
    }

    .badge-unread {
        background: #fee2e2;
        color: #b91c1c;
    }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 28px;
        text-align: center;
        color: #64748b;
    }

    @media (max-width: 1100px) {
        .logs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="logs-hero">
    <h2>Notifikasi / Audit Log</h2>
    <p>Pantau aktivitas sistem, log perubahan data, dan notifikasi user dari satu halaman admin yang rapi dan mudah dibaca.</p>
</div>

<div class="logs-grid">
    <div class="logs-card">
        <h3>Audit Log</h3>
        <p>Riwayat aktivitas user dan perubahan entitas di dalam sistem.</p>

        <?php if (! empty($auditLogs)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Entitas</th>
                            <th>Entitas ID</th>
                            <th>Deskripsi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($auditLogs as $row): ?>
                            <tr>
                                <td><?= esc((string) ($row['name'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['aktivitas'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['entitas'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['entitas_id'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['deskripsi'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['created_at'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada audit log.</div>
        <?php endif; ?>
    </div>

    <div class="logs-card">
        <h3>Notifikasi</h3>
        <p>Daftar notifikasi yang masuk ke user.</p>

        <?php if (! empty($notifikasi)): ?>
            <div class="notif-list">
                <?php foreach ($notifikasi as $row): ?>
                    <div class="notif-card">
                        <strong><?= esc((string) ($row['judul'] ?? '-')) ?></strong>
                        <div><?= esc((string) ($row['pesan'] ?? '-')) ?></div>

                        <div class="notif-meta">
                            User: <?= esc((string) ($row['name'] ?? '-')) ?><br>
                            Waktu: <?= esc((string) ($row['created_at'] ?? '-')) ?>
                        </div>

                        <div style="margin-top:10px;">
                            <?php if ((int) ($row['is_read'] ?? 0) === 1): ?>
                                <span class="status-badge badge-read">Sudah Dibaca</span>
                            <?php else: ?>
                                <span class="status-badge badge-unread">Belum Dibaca</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada notifikasi.</div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>