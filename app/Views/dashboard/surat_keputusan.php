<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$skList = $skList ?? $sk_list ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$formatDate = static function (mixed $date) use ($safe): string {
    $value = $safe($date, '');

    if ($value === '') return '-';

    $timestamp = strtotime($value);
    return $timestamp ? date('d M Y', $timestamp) : '-';
};
?>

<div class="sk-page">
    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Daftar SK Saya</h3>
                <p>File SK bisa dibuka langsung melalui browser atau diunduh untuk arsip pribadi.</p>
            </div>
        </div>

        <?php if (! empty($skList) && is_array($skList)): ?>
            <div class="table-wrap">
                <table class="admin-table sk-table">
                    <thead>
                        <tr>
                            <th>Nomor SK</th>
                            <th>Judul</th>
                            <th>Tanggal Terbit</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($skList as $row): ?>
                            <?php
                                $fileRaw = $row['file_sk'] ?? '';
                                $filePath = '';
                                if (! empty($fileRaw)) {
                                    $decoded = json_decode($fileRaw, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $filePath = $decoded[0]['path'] ?? '';
                                    } else {
                                        $filePath = (string) $fileRaw;
                                    }
                                }
                                $fileUrl = $filePath !== '' ? base_url($filePath) : '';
                            ?>

                            <tr>
                                <td>
                                    <strong><?= esc($safe($row['nomor_sk'] ?? '-')) ?></strong>
                                </td>

                                <td>
                                    <div class="title-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <?= esc($formatDate($row['tanggal_terbit'] ?? '')) ?>
                                </td>

                                <td>
                                    <span class="badge badge-success">Terbit</span>
                                </td>

                                <td>
                                    <?php if ($fileUrl !== ''): ?>
                                        <div class="action-group">
                                            <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener" class="icon-btn icon-open" title="Buka SK">
                                                📄
                                            </a>

                                            <a href="<?= esc($fileUrl) ?>" download class="icon-btn icon-download" title="Download SK">
                                                ⬇
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada SK tersedia. SK akan muncul setelah diterbitkan oleh admin.
            </div>
        <?php endif; ?>
    </section>

</div>

<?= $this->endSection() ?>