<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$auditLogs  = $auditLogs ?? [];
$notifikasi = $notifikasi ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$activityClass = static function (mixed $value) use ($safe): string {
    return match (strtolower($safe($value, ''))) {
        'create', 'created', 'tambah', 'insert' => 'log-create',
        'update', 'edit', 'ubah'               => 'log-update',
        'delete', 'hapus'                      => 'log-delete',
        'login'                                => 'log-login',
        default                                => 'log-default',
    };
};

$auditValue = static function (array $row, array $keys, string $default = '-') use ($safe): string {
    foreach ($keys as $key) {
        if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
            return $safe($row[$key], $default);
        }
    }

    return $default;
};
?>

<div class="admin-logs-page">

    <section class="page-hero logs-page-hero">
        <span class="page-kicker">System Activity</span>

        <h2>Notifikasi & Audit Log</h2>

        <p>
            Pantau aktivitas sistem, perubahan data, dan notifikasi pengguna secara real-time.
        </p>
    </section>

    <section class="logs-summary-grid">
        <div class="logs-summary-card">
            <span>Total Audit</span>
            <strong id="auditCount"><?= esc((string) count($auditLogs)) ?></strong>
            <small>Aktivitas sistem tercatat</small>
        </div>

        <div class="logs-summary-card">
            <span>Total Notifikasi</span>
            <strong id="notifCount"><?= esc((string) count($notifikasi)) ?></strong>
            <small>Notifikasi pengguna</small>
        </div>

        <div class="logs-summary-card">
            <span>Status Realtime</span>
            <strong class="realtime-status">
                <i></i> Aktif
            </strong>
            <small>Refresh otomatis 10 detik</small>
        </div>
    </section>

    <section class="logs-layout">

        <div class="card-main logs-main-card">
            <div class="page-head">
                <div>
                    <h3>Audit Log</h3>
                    <p>Riwayat aktivitas user dan perubahan data di dalam sistem.</p>
                </div>
            </div>

            <div class="table-wrap logs-table-wrap">
                <table class="admin-table logs-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Entitas</th>
                            <th>ID</th>
                            <th>Deskripsi</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody id="auditLogBody">
                        <?php if (! empty($auditLogs)): ?>
                            <?php foreach ($auditLogs as $row): ?>
                                <tr>
                                    <td>
                                        <strong><?= esc($safe($row['name'] ?? '-')) ?></strong>
                                    </td>

                                    <td>
                                        <?php $activity = $auditValue($row, ['aktivitas', 'action']); ?>
                                        <span class="log-pill <?= esc($activityClass($activity)) ?>">
                                            <?= esc($activity) ?>
                                        </span>
                                    </td>

                                    <td><?= esc($auditValue($row, ['entitas', 'target_type', 'module'])) ?></td>
                                    <td><?= esc($auditValue($row, ['entitas_id', 'target_id'])) ?></td>

                                    <td>
                                        <div class="log-desc">
                                            <?= esc($auditValue($row, ['deskripsi', 'description'])) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="log-time">
                                            <?= esc($safe($row['created_at'] ?? '-')) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-box">Belum ada audit log.</div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="card-main logs-side-card">
            <div class="page-head">
                <div>
                    <h3>Notifikasi Terbaru</h3>
                    <p>Daftar notifikasi yang masuk ke user.</p>
                </div>
            </div>

            <div class="notif-list" id="notifList">
                <?php if (! empty($notifikasi)): ?>
                    <?php foreach ($notifikasi as $row): ?>
                        <article class="notif-card <?= (int) ($row['is_read'] ?? 0) === 1 ? 'is-read' : 'is-unread' ?>">
                            <div class="notif-icon">
                                <i class="ri-notification-3-line"></i>
                            </div>

                            <div class="notif-content">
                                <div class="notif-title">
                                    <?= esc($safe($row['judul'] ?? '-')) ?>
                                </div>

                                <p>
                                    <?= esc($safe($row['pesan'] ?? '-')) ?>
                                </p>

                                <div class="notif-meta">
                                    <?= esc($safe($row['name'] ?? '-')) ?> &middot;
                                    <?= esc($safe($row['created_at'] ?? '-')) ?>
                                </div>

                                <span class="notif-status <?= (int) ($row['is_read'] ?? 0) === 1 ? 'read' : 'unread' ?>">
                                    <?= (int) ($row['is_read'] ?? 0) === 1 ? 'Sudah dibaca' : 'Belum dibaca' ?>
                                </span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-box">Belum ada notifikasi.</div>
                <?php endif; ?>
            </div>
        </aside>

    </section>

</div>

<script>
(function () {
    const auditBody = document.getElementById('auditLogBody');
    const notifList = document.getElementById('notifList');
    const auditCount = document.getElementById('auditCount');
    const notifCount = document.getElementById('notifCount');

    function escapeHtml(value) {
        return String(value ?? '-').replace(/[&<>"']/g, function (char) {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            }[char];
        });
    }

    function activityClass(value) {
        value = String(value || '').toLowerCase();

        if (['create', 'created', 'tambah', 'insert'].includes(value)) return 'log-create';
        if (['update', 'edit', 'ubah'].includes(value)) return 'log-update';
        if (['delete', 'hapus'].includes(value)) return 'log-delete';
        if (value === 'login') return 'log-login';

        return 'log-default';
    }

    function firstValue(row, keys, fallback = '-') {
        for (const key of keys) {
            if (row && row[key] !== undefined && row[key] !== null && row[key] !== '') {
                return row[key];
            }
        }

        return fallback;
    }

    async function loadRealtimeLogs() {
        try {
            const response = await fetch('<?= base_url('/admin/audit-log/realtime') ?>', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;

            const data = await response.json();

            const audits = data.auditLogs || [];
            const notifs = data.notifikasi || [];

            auditCount.textContent = audits.length;
            notifCount.textContent = notifs.length;

            auditBody.innerHTML = audits.length
                ? audits.map(row => `
                    <tr>
                        <td><strong>${escapeHtml(row.name)}</strong></td>
                        <td>
                            <span class="log-pill ${activityClass(firstValue(row, ['aktivitas', 'action']))}">
                                ${escapeHtml(firstValue(row, ['aktivitas', 'action']))}
                            </span>
                        </td>
                        <td>${escapeHtml(firstValue(row, ['entitas', 'target_type', 'module']))}</td>
                        <td>${escapeHtml(firstValue(row, ['entitas_id', 'target_id']))}</td>
                        <td><div class="log-desc">${escapeHtml(firstValue(row, ['deskripsi', 'description']))}</div></td>
                        <td><span class="log-time">${escapeHtml(row.created_at)}</span></td>
                    </tr>
                `).join('')
                : `<tr><td colspan="6"><div class="empty-box">Belum ada audit log.</div></td></tr>`;

            notifList.innerHTML = notifs.length
                ? notifs.map(row => {
                    const isRead = Number(row.is_read || 0) === 1;

                    return `
                        <article class="notif-card ${isRead ? 'is-read' : 'is-unread'}">
                            <div class="notif-icon">
                                <i class="ri-notification-3-line"></i>
                            </div>

                            <div class="notif-content">
                                <div class="notif-title">${escapeHtml(row.judul)}</div>
                                <p>${escapeHtml(row.pesan)}</p>

                                <div class="notif-meta">
                                    ${escapeHtml(row.name)} &middot; ${escapeHtml(row.created_at)}
                                </div>

                                <span class="notif-status ${isRead ? 'read' : 'unread'}">
                                    ${isRead ? 'Sudah dibaca' : 'Belum dibaca'}
                                </span>
                            </div>
                        </article>
                    `;
                }).join('')
                : `<div class="empty-box">Belum ada notifikasi.</div>`;
        } catch (error) {
            console.warn('Realtime log gagal dimuat:', error);
        }
    }

    loadRealtimeLogs();
    setInterval(loadRealtimeLogs, 10000);
})();
</script>

<?= $this->endSection() ?>
