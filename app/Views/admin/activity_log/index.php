<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
// Ensure all variables have default values to prevent undefined variable errors
$activities    = $activities ?? [];
$totalActivities = $totalActivities ?? 0;
$page          = $page ?? 1;
$totalPages    = $totalPages ?? 1;
$limit         = $limit ?? 50;
$stats         = $stats ?? [];
$modules       = $modules ?? [];
$actions       = $actions ?? [];
$filters       = $filters ?? [];
?>

<style>
.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    gap: 16px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-card.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card.success {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card.info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    margin: 8px 0;
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
}

.filter-form {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    margin-bottom: 24px;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    font-weight: 600;
    font-size: 13px;
    color: #2c3e50;
}

.filter-group input,
.filter-group select {
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-buttons {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-primary {
    background: #667eea;
    color: white;
}

.btn-primary:hover {
    background: #5568d3;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f0f0f0;
    color: #333;
}

.btn-secondary:hover {
    background: #e0e0e0;
}

.btn-export {
    background: #10b981;
    color: white;
}

.btn-export:hover {
    background: #059669;
}

.activity-table {
    background: white;
    border-radius: 12px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
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

.badge-role {
    background: #e8f5e9;
    color: #388e3c;
}

.action-links {
    display: flex;
    gap: 8px;
}

.action-links a {
    color: #667eea;
    text-decoration: none;
    font-size: 12px;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s;
}

.action-links a:hover {
    background: #f0f0f0;
    color: #5568d3;
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

.no-data {
    text-align: center;
    padding: 40px 20px;
    color: #999;
}

.time-format {
    color: #666;
    font-size: 12px;
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

<div class="activity-header">
    <div>
        <h1 style="margin: 0; color: #2c3e50;">Activity Log / Audit Trail</h1>
        <p style="margin: 4px 0 0 0; color: #999; font-size: 14px;">Tracking semua aktivitas pengguna dalam sistem</p>
    </div>
    <a href="<?= base_url('admin/activity-log/export?' . http_build_query(array_filter($filters))) ?>" class="btn btn-export">
        📥 Export CSV
    </a>
</div>

<!-- Statistics -->
<?php if (isset($stats)): ?>
<div class="stats-grid">
    <div class="stat-card primary">
        <div class="stat-label">Total Aktivitas</div>
        <div class="stat-value"><?= number_format($stats['total_activities']) ?></div>
    </div>
    <div class="stat-card success">
        <div class="stat-label">Module Aktif</div>
        <div class="stat-value"><?= count($stats['by_module']) ?></div>
    </div>
    <div class="stat-card info">
        <div class="stat-label">Top User</div>
        <div class="stat-value"><?= isset($stats['by_user'][0]) ? $stats['by_user'][0]['count'] : '0' ?></div>
        <small><?= isset($stats['by_user'][0]) ? $stats['by_user'][0]['username'] : '-' ?></small>
    </div>
</div>
<?php endif; ?>

<!-- Filter Form -->
<form method="GET" class="filter-form">
    <div class="filter-row">
        <div class="filter-group">
            <label for="module">Module</label>
            <select name="module" id="module">
                <option value="">-- Semua Module --</option>
                <?php foreach ($modules as $mod): ?>
                    <?php $modValue = (string) ($mod['module'] ?? ''); $filterValue = (string) ($filters['module'] ?? ''); ?>
                    <option value="<?= esc($modValue) ?>" <?= ($filterValue === $modValue) ? 'selected' : '' ?>>
                        <?= esc(ucfirst($modValue)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="action">Action</label>
            <select name="action" id="action">
                <option value="">-- Semua Action --</option>
                <?php foreach ($actions as $act): ?>
                    <?php $actValue = (string) ($act['action'] ?? ''); $filterAction = (string) ($filters['action'] ?? ''); ?>
                    <option value="<?= esc($actValue) ?>" <?= ($filterAction === $actValue) ? 'selected' : '' ?>>
                        <?= esc(ucfirst($actValue)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="start_date">Dari Tanggal</label>
            <input type="date" name="start_date" id="start_date" value="<?= esc($filters['start_date'] ?? '') ?>">
        </div>

        <div class="filter-group">
            <label for="end_date">Sampai Tanggal</label>
            <input type="date" name="end_date" id="end_date" value="<?= esc($filters['end_date'] ?? '') ?>">
        </div>

        <div class="filter-buttons">
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="<?= base_url('admin/activity-log') ?>" class="btn btn-secondary">↻ Reset</a>
        </div>
    </div>
</form>

<!-- Activity Table -->
<div class="activity-table">
    <?php if (! empty($activities)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Module</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($activities as $activity): ?>
                    <?php
                        $username = (string) ($activity['username'] ?? '-');
                        $role = (string) ($activity['role'] ?? '-');
                        $module = (string) ($activity['module'] ?? '');
                        $action = (string) ($activity['action'] ?? '');
                        $ipAddress = (string) ($activity['ip_address'] ?? '-');
                    ?>
                    <tr>
                        <td>#<?= $activity['id'] ?? '-' ?></td>
                        <td>
                            <div><?= date('d M Y', strtotime($activity['created_at'] ?? '')) ?></div>
                            <div class="time-format"><?= date('H:i:s', strtotime($activity['created_at'] ?? '')) ?></div>
                        </td>
                        <td>
                            <strong><?= esc($username) ?></strong><br>
                            <span class="badge badge-role"><?= esc($role) ?></span>
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
                            <small><?= esc(substr($activity['description'] ?? '-', 0, 50)) ?></small>
                        </td>
                        <td>
                            <code style="background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-size: 11px;">
                                <?= esc($ipAddress) ?>
                            </code>
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="<?= base_url('admin/activity-log/user/' . ($activity['user_id'] ?? 0)) ?>">Detail User</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="no-data">
            <p>📭 Tidak ada activity log yang ditemukan</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=1&<?= http_build_query(array_filter($filters)) ?>">« First</a>
            <a href="?page=<?= $page - 1 ?>&<?= http_build_query(array_filter($filters)) ?>">‹ Previous</a>
        <?php endif; ?>

        <?php 
        $start = max(1, $page - 2);
        $end = min($totalPages, $page + 2);
        
        for ($i = $start; $i <= $end; $i++):
        ?>
            <?php if ($i === $page): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>&<?= http_build_query(array_filter($filters)) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>&<?= http_build_query(array_filter($filters)) ?>">Next ›</a>
            <a href="?page=<?= $totalPages ?>&<?= http_build_query(array_filter($filters)) ?>">Last »</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div style="margin-top: 24px; padding: 16px; background: #f8f9fa; border-radius: 8px; font-size: 12px; color: #666;">
    <strong>📊 Summary:</strong> Menampilkan <?= count($activities ?? []) ?> dari <?= number_format($totalActivities ?? 0) ?> total aktivitas 
    <?php if (!empty(array_filter($filters ?? []))): ?>
        dengan filter yang diterapkan
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
