<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$pageType = (string) ($pageType ?? '');
$rows = $rows ?? [];
$summary = $summary ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$badgeClass = static function (?string $status): string {
    return match (strtolower((string) $status)) {
        'draft'  => 'badge-info',
        'terbit' => 'badge-success',
        'arsip'  => 'badge-muted',
        default  => 'badge-info',
    };
};

$totalRows = is_array($rows) ? count($rows) : 0;
$countDraft = 0;
$countTerbit = 0;
$countArsip = 0;

foreach ($rows as $row) {
    $status = strtolower((string) ($row['status'] ?? ''));

    if ($status === 'draft') {
        $countDraft++;
    }

    if ($status === 'terbit') {
        $countTerbit++;
    }

    if ($status === 'arsip') {
        $countArsip++;
    }
}

$isSkPage = $pageType === 'surat_keputusan';

$heroTitle = $isSkPage ? 'Surat Keputusan' : 'Laporan & Arsip';
$heroKicker = $isSkPage ? 'Arsip SK' : 'Rekap Sistem';
$heroDesc = $isSkPage
    ? 'Kelola penerbitan, penyimpanan, dan arsip Surat Keputusan tugas akhir mahasiswa.'
    : 'Pantau ringkasan data pengguna, pengajuan judul, proposal, dan arsip SK dalam satu halaman.';
?>

<div class="admin-doc-page">
    <section class="page-hero admin-doc-hero">
        <span class="page-kicker">
            <?= esc($heroKicker) ?>
        </span>

        <h2><?= esc($heroTitle) ?></h2>

        <p><?= esc($heroDesc) ?></p>
    </section>

    <?php if ($isSkPage): ?>
        <section class="admin-doc-overview">
            <div class="stat-card stat-blue">
                <div class="stat-label">Total SK</div>
                <div class="stat-value"><?= esc((string) $totalRows) ?></div>
                <div class="stat-desc">Seluruh data SK tersimpan</div>
            </div>

            <div class="stat-card stat-green">
                <div class="stat-label">Terbit</div>
                <div class="stat-value"><?= esc((string) $countTerbit) ?></div>
                <div class="stat-desc">Sudah resmi diterbitkan</div>
            </div>

            <div class="stat-card stat-slate">
                <div class="stat-label">Arsip</div>
                <div class="stat-value"><?= esc((string) $countArsip) ?></div>
                <div class="stat-desc">Sudah masuk arsip sistem</div>
            </div>

            <div class="admin-doc-action-card">
                <div>
                    <span>Quick Action</span>
                    <strong>Terbitkan SK Baru</strong>
                    <p>Buat SK dari proposal yang sudah disetujui.</p>
                </div>

                <a href="<?= site_url('admin/surat-keputusan/create') ?>" class="btn btn-primary">
                    <i class="ri-add-line"></i>
                    Tambah SK
                </a>
            </div>
        </section>

        <section class="card-main admin-doc-card">
            <div class="page-head">
                <div>
                    <h3><?= esc($safe($pageTitle ?? 'Daftar Surat Keputusan')) ?></h3>
                    <p><?= esc($safe($pageSubtitle ?? 'Daftar SK yang telah dibuat dan tersimpan di sistem.', '')) ?></p>
                </div>
            </div>

            <?php if (! empty($rows) && is_array($rows)): ?>
                <div class="table-wrap admin-doc-table-wrap">
                    <table class="admin-table admin-doc-table">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Judul</th>
                                <th>Proposal</th>
                                <th>Nomor SK</th>
                                <th>Tanggal Terbit</th>
                                <th>File SK</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <?php
                                $files = [];

                                if (! empty($row['file_sk'])) {
                                    $decoded = json_decode((string) $row['file_sk'], true);

                                    if (is_array($decoded)) {
                                        $files = $decoded;
                                    } else {
                                        $files[] = [
                                            'nama_asli' => basename((string) $row['file_sk']),
                                            'path'      => (string) $row['file_sk'],
                                        ];
                                    }
                                }
                                ?>

                                <tr>
                                    <td>
                                        <div class="cell-title">
                                            <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                        </div>
                                    </td>

                                    <td class="cell-nowrap">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </td>

                                    <td>
                                        <div class="admin-doc-title-cell">
                                            <?= esc($safe($row['judul'] ?? '-')) ?>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="admin-doc-file-name">
                                            <?= esc($safe($row['nama_file_asli'] ?? '-')) ?>
                                        </div>
                                    </td>

                                    <td class="cell-nowrap">
                                        <strong><?= esc($safe($row['nomor_sk'] ?? '-')) ?></strong>
                                    </td>

                                    <td class="cell-nowrap">
                                        <?= esc($safe($row['tanggal_terbit'] ?? '-')) ?>
                                    </td>

                                    <td>
                                        <?php if (! empty($files)): ?>
                                            <div class="table-action-icons">
                                                <?php foreach ($files as $file): ?>
                                                    <?php
                                                    $filePath = $safe($file['path'] ?? '', '');
                                                    $fileUrl = $filePath !== '' ? base_url($filePath) : '';
                                                    ?>

                                                    <?php if ($fileUrl !== ''): ?>
                                                        <a
                                                            href="<?= esc($fileUrl) ?>"
                                                            target="_blank"
                                                            rel="noopener"
                                                            class="table-icon-btn btn-view"
                                                            title="Lihat File SK"
                                                        >
                                                            <i class="ri-eye-line"></i>
                                                        </a>

                                                        <a
                                                            href="<?= esc($fileUrl) ?>"
                                                            download
                                                            class="table-icon-btn btn-download"
                                                            title="Download File SK"
                                                        >
                                                            <i class="ri-download-2-line"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge <?= esc($badgeClass($row['status'] ?? '')) ?>">
                                            <?= esc($safe($row['status'] ?? '-')) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <a
                                            href="<?= site_url('admin/surat-keputusan/delete/' . $safe($row['id'] ?? '')) ?>"
                                            class="table-icon-btn btn-delete"
                                            title="Hapus SK"
                                            onclick="return confirm('Hapus SK ini?')"
                                        >
                                            <i class="ri-delete-bin-6-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-box">
                    Belum ada data Surat Keputusan.
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if ($pageType === 'laporan'): ?>
        <section class="card-main admin-doc-card">
            <div class="page-head">
                <div>
                    <h3><?= esc($safe($pageTitle ?? 'Laporan & Arsip')) ?></h3>
                    <p><?= esc($safe($pageSubtitle ?? 'Ringkasan data utama Sistem Tugas Akhir.', '')) ?></p>
                </div>
            </div>

            <div class="admin-report-grid">
                <div class="admin-report-card">
                    <span>Total Users</span>
                    <strong><?= esc((string) ($summary['users'] ?? 0)) ?></strong>
                    <p>Semua akun yang tersimpan di sistem.</p>
                </div>

                <div class="admin-report-card">
                    <span>Mahasiswa</span>
                    <strong><?= esc((string) ($summary['mahasiswa'] ?? 0)) ?></strong>
                    <p>Mahasiswa aktif yang terdaftar.</p>
                </div>

                <div class="admin-report-card">
                    <span>Dosen</span>
                    <strong><?= esc((string) ($summary['dosen'] ?? 0)) ?></strong>
                    <p>Dosen pembimbing di sistem.</p>
                </div>

                <div class="admin-report-card">
                    <span>Pengajuan Judul</span>
                    <strong><?= esc((string) ($summary['judul'] ?? 0)) ?></strong>
                    <p>Judul yang pernah masuk.</p>
                </div>

                <div class="admin-report-card">
                    <span>Proposal</span>
                    <strong><?= esc((string) ($summary['proposal'] ?? 0)) ?></strong>
                    <p>Proposal yang sudah diunggah.</p>
                </div>

                <div class="admin-report-card">
                    <span>Surat Keputusan</span>
                    <strong><?= esc((string) ($summary['sk'] ?? 0)) ?></strong>
                    <p>SK yang sudah dibuat.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
