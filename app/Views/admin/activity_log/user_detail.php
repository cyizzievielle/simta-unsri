<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
// Ensure all variables have default values to prevent undefined variable errors
$user           = $user ?? [];
$activities     = $activities ?? [];
$totalActivities = $totalActivities ?? 0;
$page           = $page ?? 1;
$totalPages     = $totalPages ?? 1;
$limit          = $limit ?? 50;
?>

<style>
.user-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
}

.user-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: bold;
    border: 3px solid rgba(255,255,255,0.4);
}

.user-info {
    flex: 1;
}

.user-info h2 {
    margin: 0 0 8px 0;
    font-size: 24px;
}

.user-info p {
    margin: 4px 0;
    opacity: 0.9;
    font-size: 14px;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: white;
    text-decoration: none;
    padding: 8px 16px;
    background: rgba(0,0,0,0.2);
    border-radius: 6px;
    transition: all 0.2s;
}

.back-link:hover {
    background: rgba(0,0,0,0.3);
}

.activity-table {
    background: white;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    margin-bottom: 24px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

thead {
    background: #f8f9fa;
    border-bottom: 2px solid #e0e0e0;
}

th {
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    color: #2c3e50;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

td {
    padding: 14px 12px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13px;
}

tbody tr:hover {
    background: #f8f9fa;
}

.badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.badge-module {
    background: #e3f2fd;
    color: #1976d2;
}

.badge-action {
    background: #f3e5f5;
    color: #7b1fa2;
}

.time-format {
    color: #666;
    font-size: 12px;
}

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 24px;
    padding: 20px 0;
}

.pagination a,
.pagination span {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination a:hover {
    border-color: #667eea;
    color: #667eea;
}

.pagination .active {
    background: #667eea;
    color: white;
    border-color: #667eea;
}

.module-badge-chat { background: #fef3c7; color: #92400e; }
.module-badge-proposal { background: #dbeafe; color: #0c4a6e; }
.module-badge-profile { background: #f0fdfa; color: #134e4a; }
.module-badge-user { background: #fce7f3; color: #831843; }
.module-badge-admin { background: #fee2e2; color: #7f1d1d; }

.action-badge-create { background: #d1fae5; color: #065f46; }
.action-badge-update { background: #bfdbfe; color: #0c2340; }
.action-badge-delete { background: #fecaca; color: #7f1d1d; }
.action-badge-view { background: #e0e7ff; color: #312e81; }
.action-badge-send { background: #d8b4fe; color: #581c87; }
.action-badge-download { background: #cffafe; color: #164e63; }
</style>

<a href="<?= base_url('admin/activity-log') ?>" class="back-link">← Kembali ke Activity Log</a>

<div class="user-header">
    <div class="user-avatar">
        <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
    </div>
    <div class="user-info">
        <h2><?= esc($user['name'] ?? '-') ?></h2>
        <p>📧 <?= esc($user['email'] ?? '-') ?></p>
        <p>🆔 User ID: #<?= $user['id'] ?? '-' ?></p>
    </div>
</div>

<div class="activity-table">
    <?php if (! empty($activities)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                    <?php
                        $description = (string) ($activity['description'] ?? '-');
                        $module = (string) ($activity['module'] ?? '');
                        $action = (string) ($activity['action'] ?? '');
                    ?>
                    <tr>
                        <td>#<?= $activity['id'] ?? '-' ?></td>
                        <td>
                            <div><?= date('d M Y', strtotime($activity['created_at'] ?? '')) ?></div>
                            <div class="time-format"><?= date('H:i:s', strtotime($activity['created_at'] ?? '')) ?></div>
                        </td>
                        <td>
                            <span class="badge badge-module module-badge-<?= strtolower($module) ?>">
                                <?= esc(ucfirst($module)) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-action action-badge-<?= strtolower($action) ?>">
                                <?= esc(ucfirst($action)) ?>
                            </span>
                        </td>
                        <td>
                            <small><?= esc($description) ?></small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <p>📭 Tidak ada activity untuk user ini</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1">« First</a>
            <a href="?page=<?= $page - 1 ?>">‹ Previous</a>
        <?php endif; ?>

        <?php 
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        
        for ($i = $start; $i <= $end; $i++):
        ?>
            <?php if ($i === $page): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Next ›</a>
            <a href="?page=<?= $totalPages ?>">Last »</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div style="margin-top: 24px; padding: 16px; background: #f8f9fa; border-radius: 8px; font-size: 12px; color: #666;">
    <strong>📊 Summary:</strong> Total <?= number_format($totalActivities ?? 0) ?> aktivitas dari user ini
</div>

<?= $this->endSection() ?>
