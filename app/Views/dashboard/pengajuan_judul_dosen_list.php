<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$pengajuanAktif = $pengajuanAktif ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$totalJudul    = count($pengajuanAktif);
$totalDiajukan = 0;
$totalDireview = 0;

foreach ($pengajuanAktif as $row) {
    $statusRow = strtolower($safe($row['status'] ?? '', ''));

    if ($statusRow === 'diajukan') {
        $totalDiajukan++;
    }

    if ($statusRow === 'direview') {
        $totalDireview++;
    }
}

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'status-diajukan',
        'direview'  => 'status-direview',
        'revisi'    => 'status-revisi',
        'disetujui' => 'status-disetujui',
        'ditolak'   => 'status-ditolak',
        default     => 'status-diajukan',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'Diajukan',
        'direview'  => 'Direview',
        'revisi'    => 'Revisi',
        'disetujui' => 'Disetujui',
        'ditolak'   => 'Ditolak',
        default     => $safe($status, '-'),
    };
};
?>

<style>
    .judul-page {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .page-hero {
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.16), transparent 32%),
            linear-gradient(135deg, #0f172a 0%, #1d4ed8 54%, #7c3aed 100%);
        color: #fff;
        border-radius: 28px;
        padding: 30px;
        box-shadow: 0 20px 45px rgba(37, 99, 235, .18);
        position: relative;
        overflow: hidden;
    }

    .page-hero::after {
        content: "";
        position: absolute;
        right: -52px;
        top: -52px;
        width: 190px;
        height: 190px;
        background: rgba(255,255,255,.08);
        border-radius: 999px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 13px;
        border-radius: 999px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 12px;
        font-weight: 900;
        margin-bottom: 14px;
        position: relative;
        z-index: 1;
    }

    .page-hero h2 {
        margin: 0 0 9px;
        font-size: clamp(24px, 4vw, 34px);
        font-weight: 950;
        letter-spacing: -.035em;
        position: relative;
        z-index: 1;
    }

    .page-hero p {
        margin: 0;
        max-width: 780px;
        color: rgba(255,255,255,.88);
        line-height: 1.75;
        font-size: 14px;
        position: relative;
        z-index: 1;
    }

    .premium-stats {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .premium-stat-card {
        border-radius: 24px;
        padding: 20px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 14px 35px rgba(15, 23, 42, .10);
        min-height: 128px;
    }

    .premium-stat-card::after {
        content: "";
        position: absolute;
        right: -22px;
        bottom: -22px;
        width: 92px;
        height: 92px;
        background: rgba(255,255,255,.12);
        border-radius: 999px;
    }

    .stat-blue {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .stat-amber {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .stat-emerald {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .premium-stat-label {
        font-size: 12px;
        font-weight: 850;
        opacity: .95;
        margin-bottom: 11px;
        position: relative;
        z-index: 1;
    }

    .premium-stat-value {
        font-size: 34px;
        font-weight: 950;
        line-height: 1;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .premium-stat-desc {
        font-size: 12px;
        opacity: .92;
        line-height: 1.45;
        position: relative;
        z-index: 1;
    }

    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, .06);
        border: 1px solid #eef2f7;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .section-title-premium {
        margin: 0 0 6px;
        font-size: 22px;
        font-weight: 950;
        color: #0f172a;
        letter-spacing: -.025em;
    }

    .section-subtitle-premium {
        margin: 0;
        color: #64748b;
        line-height: 1.65;
        font-size: 13px;
    }

    .table-hint {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    .premium-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 18px;
        border: 1px solid #eef2f7;
        background: #fff;
    }

    .premium-table {
        width: 100%;
        min-width: 950px;
        border-collapse: collapse;
        background: #fff;
    }

    .premium-table thead tr {
        background: linear-gradient(135deg, #f8fbff, #eff6ff);
    }

    .premium-table th,
    .premium-table td {
        padding: 15px 14px;
        text-align: left;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
    }

    .premium-table th {
        font-size: 12px;
        font-weight: 950;
        color: #334155;
        white-space: nowrap;
    }

    .premium-table td {
        color: #0f172a;
        font-size: 14px;
    }

    .premium-table tbody tr {
        transition: .18s ease;
    }

    .premium-table tbody tr:hover {
        background: #fafcff;
    }

    .student-name {
        font-weight: 900;
        color: #0f172a;
        line-height: 1.35;
        white-space: nowrap;
    }

    .student-nim {
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .judul-cell-title {
        font-weight: 850;
        color: #0f172a;
        line-height: 1.55;
        max-width: 500px;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 11px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 950;
        white-space: nowrap;
    }

    .status-diajukan {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .status-direview {
        background: #fef3c7;
        color: #92400e;
    }

    .status-revisi {
        background: #fee2e2;
        color: #b91c1c;
    }

    .status-disetujui {
        background: #dcfce7;
        color: #166534;
    }

    .status-ditolak {
        background: #f1f5f9;
        color: #475569;
    }

    .version-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 30px;
        padding: 0 10px;
        border-radius: 999px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-weight: 900;
        font-size: 12px;
        white-space: nowrap;
    }

    .date-text {
        color: #475569;
        font-weight: 750;
        white-space: nowrap;
    }

    .btn-detail {
        border-radius: 14px;
        padding: 10px 13px;
        background: #2563eb;
        color: #fff;
        text-decoration: none;
        font-weight: 900;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        transition: .18s ease;
        box-shadow: 0 10px 20px rgba(37, 99, 235, .18);
    }

    .btn-detail:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 28px;
        text-align: center;
        color: #64748b;
        line-height: 1.8;
        font-size: 14px;
    }

    @media (max-width: 1100px) {
        .premium-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .premium-table {
            min-width: 900px;
        }
    }

    @media (max-width: 768px) {
        .judul-page {
            gap: 14px;
        }

        .page-hero {
            padding: 21px;
            border-radius: 22px;
        }

        .hero-badge {
            font-size: 11px;
            padding: 7px 11px;
            margin-bottom: 12px;
        }

        .page-hero h2 {
            font-size: 22px;
        }

        .page-hero p {
            font-size: 13px;
            line-height: 1.65;
        }

        .premium-stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }

        .premium-stat-card {
            padding: 13px 10px;
            border-radius: 18px;
            min-height: 104px;
        }

        .premium-stat-label {
            font-size: 10.5px;
            margin-bottom: 8px;
        }

        .premium-stat-value {
            font-size: 23px;
            margin-bottom: 6px;
        }

        .premium-stat-desc {
            font-size: 10px;
            line-height: 1.35;
        }

        .card-premium {
            padding: 16px;
            border-radius: 22px;
        }

        .section-head {
            flex-direction: column;
            margin-bottom: 14px;
        }

        .section-title-premium {
            font-size: 18px;
        }

        .section-subtitle-premium {
            font-size: 12.5px;
        }

        .table-hint {
            font-size: 11px;
            padding: 7px 10px;
        }

        .premium-table-wrap {
            border-radius: 16px;
        }

        .premium-table {
            min-width: 820px;
        }

        .premium-table th,
        .premium-table td {
            padding: 12px 10px;
            font-size: 12px;
        }

        .premium-table th {
            font-size: 11px;
        }

        .judul-cell-title {
            max-width: 280px;
            font-size: 12.5px;
        }

        .student-name,
        .student-nim,
        .date-text {
            font-size: 12px;
        }

        .status-badge {
            padding: 6px 9px;
            font-size: 11px;
        }

        .version-pill {
            height: 28px;
            min-width: 30px;
            font-size: 11px;
        }

        .btn-detail {
            padding: 8px 10px;
            font-size: 12px;
            border-radius: 12px;
        }
    }

    @media (max-width: 430px) {
        .page-hero h2 {
            font-size: 21px;
        }

        .premium-stats {
            gap: 7px;
        }

        .premium-stat-card {
            padding: 12px 9px;
        }

        .premium-stat-value {
            font-size: 21px;
        }

        .premium-stat-desc {
            display: none;
        }

        .premium-table {
            min-width: 780px;
        }
    }
</style>

<div class="judul-page">

    <section class="page-hero">
        <div class="hero-badge">Panel Dosen</div>
        <h2>Pengajuan Judul Mahasiswa</h2>
        <p>
            Tinjau judul aktif dari mahasiswa bimbingan dan lanjutkan ke halaman detail untuk memberi review.
        </p>
    </section>

    <section class="premium-stats">
        <div class="premium-stat-card stat-blue">
            <div class="premium-stat-label">Total Judul Aktif</div>
            <div class="premium-stat-value"><?= esc($safe($totalJudul, '0')) ?></div>
            <div class="premium-stat-desc">Judul aktif yang perlu ditinjau</div>
        </div>

        <div class="premium-stat-card stat-amber">
            <div class="premium-stat-label">Baru Diajukan</div>
            <div class="premium-stat-value"><?= esc($safe($totalDiajukan, '0')) ?></div>
            <div class="premium-stat-desc">Menunggu review awal</div>
        </div>

        <div class="premium-stat-card stat-emerald">
            <div class="premium-stat-label">Sedang Direview</div>
            <div class="premium-stat-value"><?= esc($safe($totalDireview, '0')) ?></div>
            <div class="premium-stat-desc">Masih dalam proses peninjauan</div>
        </div>
    </section>

    <section class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title-premium">List Pengajuan Judul Aktif</h3>
                <p class="section-subtitle-premium">
                    Hanya pengajuan berstatus diajukan atau direview yang tampil di halaman ini.
                </p>
            </div>
        </div>

        <?php if (! empty($pengajuanAktif) && is_array($pengajuanAktif)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($pengajuanAktif as $row): ?>
                            <tr>
                                <td>
                                    <div class="student-name">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="student-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="judul-cell-title">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="status-badge <?= esc($statusClass($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="version-pill">
                                        v<?= esc($safe($row['versi_ke'] ?? '1')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="date-text">
                                        <?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <a
                                        href="<?= base_url('/dosen/pengajuan-judul/detail/' . $safe($row['id'] ?? '')) ?>"
                                        class="btn-detail"
                                    >
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">
                Belum ada pengajuan judul aktif dari mahasiswa bimbingan Anda.
            </div>
        <?php endif; ?>
    </section>

</div>

<?= $this->endSection() ?>