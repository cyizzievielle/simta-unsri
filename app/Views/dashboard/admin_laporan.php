<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$periodeList     = $periodeList ?? [];
$periodeAktif    = $periodeAktif ?? null;
$summary         = $summary ?? [];
$arsipPembimbing = $arsipPembimbing ?? [];
$arsipJudul      = $arsipJudul ?? [];
$arsipProposal   = $arsipProposal ?? [];
$arsipSk         = $arsipSk ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$statusBadge = static function (mixed $status): string {
    $status = strtolower((string) $status);

    return match ($status) {
        'disetujui', 'diterima', 'terbit' => 'badge-success',
        'revisi', 'direview', 'diajukan', 'menunggu' => 'badge-warning',
        'ditolak' => 'badge-danger',
        'arsip' => 'badge-muted',
        default => 'badge-info',
    };
};

$jenisLabel = static function (mixed $jenis): string {
    $jenis = strtolower((string) $jenis);

    return in_array($jenis, ['2', 'pembimbing_2'], true)
        ? 'Pembimbing 2'
        : 'Pembimbing 1';
};

$jenisBadge = static function (mixed $jenis): string {
    $jenis = strtolower((string) $jenis);

    return in_array($jenis, ['2', 'pembimbing_2'], true)
        ? 'badge-purple'
        : 'badge-info';
};

$periodeId = (string) ($periodeAktif['id'] ?? '');

$reportCards = [
    [
        'label' => 'Permohonan',
        'value' => $summary['permohonan_total'] ?? 0,
        'desc'  => 'Total permohonan pembimbing',
        'class' => 'stat-blue',
    ],
    [
        'label' => 'Pembimbing Disetujui',
        'value' => $summary['permohonan_disetujui'] ?? 0,
        'desc'  => 'Permohonan telah diterima',
        'class' => 'stat-green',
    ],
    [
        'label' => 'Judul Diajukan',
        'value' => $summary['judul_total'] ?? 0,
        'desc'  => 'Total judul semester ini',
        'class' => 'stat-purple',
    ],
    [
        'label' => 'Judul Disetujui',
        'value' => $summary['judul_disetujui'] ?? 0,
        'desc'  => 'Judul telah disetujui',
        'class' => 'stat-amber',
    ],
    [
        'label' => 'Proposal',
        'value' => $summary['proposal_total'] ?? 0,
        'desc'  => 'Proposal yang masuk',
        'class' => 'stat-blue',
    ],
    [
        'label' => 'Proposal Disetujui',
        'value' => $summary['proposal_disetujui'] ?? 0,
        'desc'  => 'Proposal sudah diterima',
        'class' => 'stat-green',
    ],
    [
        'label' => 'SK Terbit',
        'value' => $summary['sk_total'] ?? 0,
        'desc'  => 'SK diterbitkan admin',
        'class' => 'stat-slate',
    ],
];
?>

<div class="admin-report-page">
    <section class="page-hero report-hero">
        <span class="page-kicker">Laporan / Arsip</span>

        <h2>Rekap Akademik Per Semester</h2>

        <p>
            Pilih periode akademik untuk melihat ringkasan data pembimbing,
            judul, proposal, dan Surat Keputusan secara terpusat.
        </p>
    </section>

<section class="report-control-grid">
    <div class="card-main report-period-card">
        <div class="page-head">
            <div>
                <h3>Periode Laporan</h3>
                <p>Pilih periode akademik untuk menampilkan rekap data.</p>
            </div>
        </div>

        <form method="get" class="report-period-form">
            <div class="filter-field">
                <label>Periode Akademik</label>

                <select name="periode_id">
                    <?php foreach ($periodeList as $periode): ?>
                        <option
                            value="<?= esc((string) ($periode['id'] ?? '')) ?>"
                            <?= (string) ($periode['id'] ?? '') === (string) $periodeId ? 'selected' : '' ?>
                        >
                            <?= esc($safe($periode['nama_periode'] ?? '-')) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="card-main report-export-card">
        <span class="report-export-kicker">Export PDF</span>

        <h3>Unduh Laporan</h3>

        <p>Pilih jenis laporan yang ingin diunduh.</p>

        <div class="report-export-actions">
            <a href="<?= base_url('/admin/laporan/export/rekap?periode_id=' . $periodeId) ?>" class="report-export-btn">Rekap</a>
            <a href="<?= base_url('/admin/laporan/export/judul?periode_id=' . $periodeId) ?>" class="report-export-btn">Judul</a>
            <a href="<?= base_url('/admin/laporan/export/proposal?periode_id=' . $periodeId) ?>" class="report-export-btn">Proposal</a>
            <a href="<?= base_url('/admin/laporan/export/sk?periode_id=' . $periodeId) ?>" class="report-export-btn">SK</a>
        </div>
    </div>
</section>

    <?php if (! empty($periodeAktif)): ?>
        <section class="stat-grid report-stat-grid stat-count-7">
            <?php foreach ($reportCards as $card): ?>
                <div class="stat-card <?= esc($card['class']) ?>">
                    <div class="stat-label"><?= esc($card['label']) ?></div>
                    <div class="stat-value"><?= esc((string) $card['value']) ?></div>
                    <div class="stat-desc"><?= esc($card['desc']) ?></div>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="report-archive-grid">
            <div class="card-main report-table-card">
                <div class="page-head">
                    <div>
                        <h3>Arsip Pembimbing</h3>
                        <p>Data dosen pembimbing yang ditetapkan pada periode ini.</p>
                    </div>
                </div>

                <?php if (! empty($arsipPembimbing)): ?>
                    <div class="table-wrap report-table-wrap">
                        <table class="admin-table report-table">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Dosen</th>
                                    <th>Jenis</th>
                                    <th>Tanggal Penetapan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arsipPembimbing as $row): ?>
                                    <tr>
                                        <td><strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong></td>
                                        <td><?= esc($safe($row['nim'] ?? '-')) ?></td>
                                        <td><?= esc($safe($row['nama_dosen'] ?? '-')) ?></td>
                                        <td>
                                            <span class="badge <?= esc($jenisBadge($row['jenis_pembimbing'] ?? '')) ?>">
                                                <?= esc($jenisLabel($row['jenis_pembimbing'] ?? '')) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($safe($row['tanggal_penetapan'] ?? '-')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-box">Belum ada arsip pembimbing pada periode ini.</div>
                <?php endif; ?>
            </div>

            <div class="card-main report-table-card">
                <div class="page-head">
                    <div>
                        <h3>Arsip Pengajuan Judul</h3>
                        <p>Daftar pengajuan judul mahasiswa pada periode terpilih.</p>
                    </div>
                </div>

                <?php if (! empty($arsipJudul)): ?>
                    <div class="table-wrap report-table-wrap">
                        <table class="admin-table report-table report-wide-table">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Judul</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arsipJudul as $row): ?>
                                    <tr>
                                        <td><strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong></td>
                                        <td><?= esc($safe($row['nim'] ?? '-')) ?></td>
                                        <td class="report-title-cell"><?= esc($safe($row['judul'] ?? '-')) ?></td>
                                        <td>
                                            <span class="badge <?= esc($statusBadge($row['status'] ?? '')) ?>">
                                                <?= esc($safe($row['status'] ?? '-')) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-box">Belum ada arsip pengajuan judul pada periode ini.</div>
                <?php endif; ?>
            </div>

            <div class="card-main report-table-card">
                <div class="page-head">
                    <div>
                        <h3>Arsip Proposal</h3>
                        <p>Daftar proposal tugas akhir yang masuk pada periode ini.</p>
                    </div>
                </div>

                <?php if (! empty($arsipProposal)): ?>
                    <div class="table-wrap report-table-wrap">
                        <table class="admin-table report-table report-wide-table">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Judul</th>
                                    <th>File Proposal</th>
                                    <th>Status</th>
                                    <th>Tanggal Upload</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arsipProposal as $row): ?>
                                    <tr>
                                        <td><strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong></td>
                                        <td><?= esc($safe($row['nim'] ?? '-')) ?></td>
                                        <td class="report-title-cell"><?= esc($safe($row['judul'] ?? '-')) ?></td>
                                        <td class="report-file-cell"><?= esc($safe($row['nama_file_asli'] ?? '-')) ?></td>
                                        <td>
                                            <span class="badge <?= esc($statusBadge($row['status'] ?? '')) ?>">
                                                <?= esc($safe($row['status'] ?? '-')) ?>
                                            </span>
                                        </td>
                                        <td><?= esc($safe($row['tanggal_upload'] ?? '-')) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-box">Belum ada arsip proposal pada periode ini.</div>
                <?php endif; ?>
            </div>

            <div class="card-main report-table-card">
                <div class="page-head">
                    <div>
                        <h3>Arsip Surat Keputusan</h3>
                        <p>Data SK yang diterbitkan pada periode akademik terpilih.</p>
                    </div>
                </div>

                <?php if (! empty($arsipSk)): ?>
                    <div class="table-wrap report-table-wrap">
                        <table class="admin-table report-table report-wide-table">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Judul</th>
                                    <th>Nomor SK</th>
                                    <th>Tanggal Terbit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($arsipSk as $row): ?>
                                    <tr>
                                        <td><strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong></td>
                                        <td><?= esc($safe($row['nim'] ?? '-')) ?></td>
                                        <td class="report-title-cell"><?= esc($safe($row['judul'] ?? '-')) ?></td>
                                        <td><?= esc($safe($row['nomor_sk'] ?? '-')) ?></td>
                                        <td><?= esc($safe($row['tanggal_terbit'] ?? '-')) ?></td>
                                        <td>
                                            <span class="badge <?= esc($statusBadge($row['status'] ?? '')) ?>">
                                                <?= esc($safe($row['status'] ?? '-')) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-box">Belum ada arsip SK pada periode ini.</div>
                <?php endif; ?>
            </div>
        </section>
    <?php else: ?>
        <section class="card-main">
            <div class="empty-box">Belum ada periode akademik yang bisa dipilih.</div>
        </section>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
