<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<?php
$proposalAktif = $proposalAktif ?? $proposalList ?? $proposals ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$totalProposal = count($proposalAktif);
$totalDiajukan = 0;
$totalDireview = 0;

foreach ($proposalAktif as $row) {
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
.proposal-page {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.page-hero {
    background:
        radial-gradient(circle at top right, rgba(255,255,255,.16), transparent 32%),
        linear-gradient(135deg, #0f172a 0%, #1d4ed8 54%, #2563eb 100%);
    color: #fff;
    border-radius: 28px;
    padding: 28px 30px;
    box-shadow: 0 18px 40px rgba(37, 99, 235, .18);
    position: relative;
    overflow: hidden;
}

.page-hero::after {
    content: "";
    position: absolute;
    right: -45px;
    top: -45px;
    width: 180px;
    height: 180px;
    background: rgba(255,255,255,.08);
    border-radius: 999px;
}

.page-hero h2 {
    margin: 0 0 8px;
    font-size: clamp(23px, 4vw, 32px);
    font-weight: 900;
    letter-spacing: -.03em;
    position: relative;
    z-index: 1;
}

.page-hero p {
    margin: 0;
    max-width: 760px;
    color: rgba(255,255,255,.92);
    line-height: 1.7;
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
    min-height: 125px;
}

.premium-stat-card::after {
    content: "";
    position: absolute;
    right: -18px;
    bottom: -18px;
    width: 85px;
    height: 85px;
    background: rgba(255,255,255,.10);
    border-radius: 999px;
}

.stat-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.stat-amber { background: linear-gradient(135deg, #d97706, #f59e0b); }
.stat-emerald { background: linear-gradient(135deg, #059669, #10b981); }

.premium-stat-label {
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
}

.premium-stat-value {
    font-size: 34px;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 7px;
    position: relative;
    z-index: 1;
}

.premium-stat-desc {
    font-size: 12px;
    opacity: .95;
    line-height: 1.45;
    position: relative;
    z-index: 1;
}

.card-premium {
    background: #fff;
    border-radius: 26px;
    padding: 22px;
    box-shadow: 0 14px 35px rgba(15, 23, 42, .06);
    border: 1px solid #eef2f7;
}

.section-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.section-title-premium {
    margin: 0 0 6px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
    letter-spacing: -.02em;
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
    min-width: 1120px;
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
    vertical-align: top;
}

.premium-table th {
    font-size: 12px;
    font-weight: 900;
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

.student-name {
    font-weight: 900;
    color: #0f172a;
    white-space: nowrap;
}

.student-nim {
    font-weight: 800;
    color: #64748b;
    white-space: nowrap;
}

.cell-title {
    font-weight: 800;
    color: #0f172a;
    line-height: 1.55;
    max-width: 420px;
    word-break: break-word;

    display: -webkit-box;
    -webkit-line-clamp: 4;
    line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.file-cell {
    max-width: 320px;
}

.file-name {
    font-weight: 800;
    color: #0f172a;
    line-height: 1.45;
    word-break: break-word;
}

.file-system {
    margin-top: 5px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
    word-break: break-word;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
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
        min-width: 1040px;
    }
}

@media (max-width: 768px) {
    .proposal-page {
        gap: 14px;
    }

    .page-hero {
        padding: 21px;
        border-radius: 22px;
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
        padding: 12px 9px;
        border-radius: 18px;
        min-height: 98px;
    }

    .premium-stat-label {
        font-size: 10px;
        margin-bottom: 7px;
    }

    .premium-stat-value {
        font-size: 21px;
        margin-bottom: 4px;
    }

    .premium-stat-desc {
        font-size: 9.5px;
        line-height: 1.25;
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
        min-width: 920px;
    }

    .premium-table th,
    .premium-table td {
        padding: 12px 10px;
        font-size: 12px;
    }

    .premium-table th {
        font-size: 11px;
    }

    .cell-title {
        max-width: 280px;
        font-size: 12px;
        -webkit-line-clamp: 3;
        line-clamp: 3;
    }

    .file-cell {
        max-width: 240px;
    }

    .file-name,
    .file-system,
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
    .premium-stats {
        gap: 7px;
    }

    .premium-stat-card {
        padding: 11px 8px;
    }

    .premium-stat-value {
        font-size: 20px;
    }

    .premium-stat-desc {
        display: none;
    }

    .premium-table {
        min-width: 860px;
    }
}
</style>

<div class="proposal-page">

    <section class="page-hero">
        <h2>Proposal Tugas Akhir</h2>
        <p>Kelola proposal aktif mahasiswa bimbingan dan lanjutkan ke detail untuk proses review.</p>
    </section>

    <section class="premium-stats">
        <div class="premium-stat-card stat-blue">
            <div class="premium-stat-label">Total Proposal</div>
            <div class="premium-stat-value"><?= esc($safe($totalProposal, '0')) ?></div>
            <div class="premium-stat-desc">Proposal aktif yang perlu ditinjau</div>
        </div>

        <div class="premium-stat-card stat-amber">
            <div class="premium-stat-label">Baru Diajukan</div>
            <div class="premium-stat-value"><?= esc($safe($totalDiajukan, '0')) ?></div>
            <div class="premium-stat-desc">Menunggu review awal</div>
        </div>

        <div class="premium-stat-card stat-emerald">
            <div class="premium-stat-label">Direview</div>
            <div class="premium-stat-value"><?= esc($safe($totalDireview, '0')) ?></div>
            <div class="premium-stat-desc">Sedang dalam proses</div>
        </div>
    </section>

    <section class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title-premium">List Proposal Aktif</h3>
                <p class="section-subtitle-premium">
                    Hanya proposal berstatus diajukan atau direview yang tampil di halaman ini.
                </p>
            </div>
        </div>

        <?php if (! empty($proposalAktif) && is_array($proposalAktif)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>File Proposal</th>
                            <th>Status</th>
                            <th>Versi</th>
                            <th>Tanggal Upload</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($proposalAktif as $row): ?>
                            <tr>
                                <td>
                                    <div class="student-name">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="student-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="cell-title">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="file-cell">
                                        <div class="file-name">
                                            <?= esc($safe(($row['nama_file_asli'] ?? '') !== '' ? $row['nama_file_asli'] : '-')) ?>
                                        </div>

                                        <div class="file-system">
                                            File sistem: <?= esc($safe(($row['file_proposal'] ?? '') !== '' ? $row['file_proposal'] : '-')) ?>
                                        </div>
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
                                        <?= esc($safe($row['tanggal_upload'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="<?= base_url('/dosen/proposal-ta/detail/' . $safe($row['id'] ?? '')) ?>" class="btn-detail">
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
                Belum ada proposal aktif yang perlu direview.
            </div>
        <?php endif; ?>
    </section>

</div>

<?= $this->endSection() ?>