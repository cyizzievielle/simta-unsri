<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$permohonanMenunggu = $permohonanMenunggu ?? [];
$riwayatKeputusan   = $riwayatKeputusan ?? [];
$keyword            = $keyword ?? '';
$jenis              = $jenis ?? '';
$status             = $status ?? '';
$perPage            = $perPage ?? 10;
$page               = $page ?? 1;
$totalRows          = $totalRows ?? 0;
$totalPages         = $totalPages ?? 1;
$startRow           = $startRow ?? 0;
$endRow             = $endRow ?? 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$statusLabel = static function (mixed $value) use ($safe): string {
    return match (strtolower($safe($value, ''))) {
        'menunggu'    => 'Menunggu',
        'disetujui'   => 'Disetujui',
        'ditolak'     => 'Ditolak',
        'kuota_penuh' => 'Kuota Penuh',
        default       => $safe($value, '-'),
    };
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui'   => 'badge-success',
        'ditolak'     => 'badge-danger',
        'kuota_penuh' => 'badge-warning',
        default       => 'badge-info',
    };
};

$badgeJenis = static function (mixed $jenis) use ($safe): string {
    return match (strtolower($safe($jenis, ''))) {
        'pembimbing_2', '2' => 'badge-purple',
        default             => 'badge-info',
    };
};

$labelJenis = static function (mixed $jenis) use ($safe): string {
    return match (strtolower($safe($jenis, ''))) {
        'pembimbing_2', '2' => 'Pembimbing 2',
        default             => 'Pembimbing 1',
    };
};

$queryParams = [
    'q'        => $keyword,
    'jenis'    => $jenis,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    return base_url('/dosen/permohonan?' . http_build_query(array_merge($queryParams, [
        'page' => $targetPage,
    ])));
};
?>

<div class="request-page">

    <section class="request-hero">
        <div>
            <span class="request-kicker">Bimbingan Akademik</span>
            <h2>Permohonan Pembimbing</h2>
            <p>
                Tinjau permohonan mahasiswa, berikan keputusan akademik, dan pantau riwayat persetujuan pembimbing secara terstruktur.
            </p>
        </div>
    </section>

    <section class="stat-grid request-stats">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Riwayat</div>
            <div class="stat-value"><?= esc($safe($totalRows, '0')) ?></div>
            <div class="stat-desc">Sesuai hasil filter</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Menunggu Keputusan</div>
            <div class="stat-value"><?= esc($safe(count($permohonanMenunggu), '0')) ?></div>
            <div class="stat-desc">Perlu ditinjau dosen</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Riwayat Keputusan</div>
            <div class="stat-value"><?= esc($safe(count($riwayatKeputusan), '0')) ?></div>
            <div class="stat-desc">Data pada halaman ini</div>
        </div>
    </section>

    <section class="card-main request-card-main">
        <div class="page-head">
            <div>
                <h3>Permohonan Aktif</h3>
                <p>Daftar mahasiswa yang sedang menunggu keputusan persetujuan pembimbing.</p>
            </div>
        </div>

        <?php if (! empty($permohonanMenunggu) && is_array($permohonanMenunggu)): ?>
            <div class="table-wrap request-table-wrap">
                <table class="admin-table request-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Pembimbing</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($permohonanMenunggu as $row): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                </td>

                                <td><?= esc($safe($row['nim'] ?? '-')) ?></td>

                                <td>
                                    <span class="badge <?= esc($badgeJenis($row['jenis_pembimbing'] ?? '')) ?>">
                                        <?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></td>

                                <td>
                                    <a href="<?= base_url('/dosen/permohonan/detail/' . $safe($row['id'] ?? '')) ?>" class="icon-btn icon-open" title="Lihat Detail">
                                        👁
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="request-mobile-list">
                <?php foreach ($permohonanMenunggu as $row): ?>
                    <article class="request-mobile-card">
                        <div class="request-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>

                            <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="request-mobile-info">
                            <div>
                                <small>Jenis Pembimbing</small>
                                <b><?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?></b>
                            </div>

                            <div>
                                <small>Tanggal Pengajuan</small>
                                <b><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></b>
                            </div>
                        </div>

                        <a href="<?= base_url('/dosen/permohonan/detail/' . $safe($row['id'] ?? '')) ?>" class="btn btn-primary request-mobile-btn">
                            Lihat Detail
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Tidak ada permohonan pembimbing yang menunggu keputusan saat ini.
            </div>
        <?php endif; ?>
    </section>

    <section class="card-main request-card-main">
        <div class="page-head">
            <div>
                <h3>Riwayat Keputusan Akademik</h3>
                <p>Gunakan pencarian dan filter untuk meninjau keputusan pembimbing yang telah diberikan.</p>
            </div>
        </div>

        <div class="filter-box request-filter">
            <form method="get" action="<?= base_url('/dosen/permohonan') ?>">
                <div class="filter-form request-filter-form">
                    <div class="filter-field">
                        <label for="q">Cari Mahasiswa / NIM</label>
                        <input type="text" id="q" name="q" value="<?= esc($safe($keyword, '')) ?>" placeholder="Nama atau NIM">
                    </div>

                    <div class="filter-field">
                        <label for="jenis">Jenis</label>
                        <select id="jenis" name="jenis">
                            <option value="">Semua</option>
                            <option value="pembimbing_1" <?= $jenis === 'pembimbing_1' ? 'selected' : '' ?>>Pembimbing 1</option>
                            <option value="pembimbing_2" <?= $jenis === 'pembimbing_2' ? 'selected' : '' ?>>Pembimbing 2</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="">Semua</option>
                            <option value="menunggu" <?= $status === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                            <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                            <option value="kuota_penuh" <?= $status === 'kuota_penuh' ? 'selected' : '' ?>>Kuota Penuh</option>
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="per_page">Tampilkan</label>
                        <select id="per_page" name="per_page">
                            <option value="10" <?= (int) $perPage === 10 ? 'selected' : '' ?>>10</option>
                            <option value="50" <?= (int) $perPage === 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= (int) $perPage === 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                        <a href="<?= base_url('/dosen/permohonan') ?>" class="btn-filter btn-reset">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <?php if (! empty($riwayatKeputusan) && is_array($riwayatKeputusan)): ?>
            <div class="table-wrap request-table-wrap">
                <table class="admin-table request-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Pembimbing</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Tanggal Respon</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayatKeputusan as $row): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                </td>

                                <td><?= esc($safe($row['nim'] ?? '-')) ?></td>

                                <td>
                                    <span class="badge <?= esc($badgeJenis($row['jenis_pembimbing'] ?? '')) ?>">
                                        <?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></td>
                                <td><?= esc($safe($row['tanggal_respon'] ?? '-')) ?></td>
                                <td>
                                    <div class="request-note">
                                        <?= esc($safe($row['catatan'] ?? '-')) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="request-mobile-list">
                <?php foreach ($riwayatKeputusan as $row): ?>
                    <article class="request-mobile-card">
                        <div class="request-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>

                            <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="request-mobile-info">
                            <div>
                                <small>Jenis Pembimbing</small>
                                <b><?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?></b>
                            </div>

                            <div>
                                <small>Pengajuan</small>
                                <b><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></b>
                            </div>

                            <div>
                                <small>Respon</small>
                                <b><?= esc($safe($row['tanggal_respon'] ?? '-')) ?></b>
                            </div>

                            <div>
                                <small>Catatan</small>
                                <b><?= esc($safe($row['catatan'] ?? '-')) ?></b>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Tidak ada riwayat keputusan yang sesuai dengan filter.
            </div>
        <?php endif; ?>

        <div class="pagination-bar">
            <div class="pagination-info">
                Menampilkan <?= esc($safe($startRow, '0')) ?> - <?= esc($safe($endRow, '0')) ?> dari <?= esc($safe($totalRows, '0')) ?> data
            </div>

            <div class="pagination-links">
                <?php if ((int) $page > 1): ?>
                    <a class="page-link" href="<?= $buildPageUrl((int) $page - 1) ?>">&laquo;</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= (int) $totalPages; $i++): ?>
                    <a class="page-link <?= $i === (int) $page ? 'active' : '' ?>" href="<?= $buildPageUrl($i) ?>">
                        <?= esc((string) $i) ?>
                    </a>
                <?php endfor; ?>

                <?php if ((int) $page < (int) $totalPages): ?>
                    <a class="page-link" href="<?= $buildPageUrl((int) $page + 1) ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<?= $this->endSection() ?>