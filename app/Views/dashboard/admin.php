<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$totalUsers       = (int) ($totalUsers ?? 0);
$totalMahasiswa  = (int) ($totalMahasiswa ?? 0);
$totalDosen      = (int) ($totalDosen ?? 0);
$totalJudul      = (int) ($totalJudul ?? 0);
$totalProposal   = (int) ($totalProposal ?? 0);
$totalSk         = (int) ($totalSk ?? 0);
$totalSkTerbit   = (int) ($totalSkTerbit ?? $totalSk);

$permohonanMenunggu = (int) ($permohonanMenunggu ?? 0);
$permohonanPending  = $permohonanPending ?? 0;
$judulDipantau      = (int) ($judulDipantau ?? 0);
$proposalDiproses   = (int) ($proposalDiproses ?? 0);

$judulTerbaru    = $judulTerbaru ?? [];
$proposalTerbaru = $proposalTerbaru ?? [];

$judulDisetujui = (int) ($judulDisetujui ?? 0);
$judulRevisi    = (int) ($judulRevisi ?? 0);
$judulDitolak   = (int) ($judulDitolak ?? 0);
$judulDiproses  = (int) ($judulDiproses ?? 0);

$proposalDisetujui = (int) ($proposalDisetujui ?? 0);
$proposalRevisi    = (int) ($proposalRevisi ?? 0);
$proposalDitolak   = (int) ($proposalDitolak ?? 0);
$proposalDiproses  = (int) ($proposalDiproses ?? 0);

$totalJudulChart = max(
    1,
    $judulDisetujui + $judulRevisi + $judulDitolak + $judulDiproses
);

$totalProposalChart = max(
    1,
    $proposalDisetujui + $proposalRevisi + $proposalDitolak + $proposalDiproses
);

$totalUserChart = max(
    1,
    $totalMahasiswa + $totalDosen
);

$percentage = static function (int|float $value, int|float $total): string {
    $result = ((float) $value / max(1, (float) $total)) * 100;

    return number_format($result, 2, '.', '');
};

$ring = static function (int|float $value, int|float $total): string {
    $circumference = 314;
    $result = (((float) $value / max(1, (float) $total)) * $circumference);

    return number_format($result, 2, '.', '');
};

$maxBar = max(
    1,
    $totalJudul,
    $totalProposal,
    $totalSk,
    $permohonanMenunggu
);
?>

<div class="admin-dashboard-page admin-dashboard-pro">

    <section class="page-hero admin-dashboard-hero">
        <span class="page-kicker">
            Dashboard Admin
        </span>

        <h2>Monitoring Sistem Tugas Akhir</h2>

        <p>
            Pantau pengguna, pembimbing, pengajuan judul, proposal, dan penerbitan SK
            dalam satu dashboard operasional.
        </p>

        <div class="admin-hero-actions">
            <a href="<?= base_url('/admin/users/create') ?>" class="hero-btn">
                Tambah User
            </a>

            <a href="<?= base_url('/admin/surat-keputusan/create') ?>" class="hero-btn">
                Terbitkan SK
            </a>

            <a href="<?= base_url('/admin/laporan') ?>" class="hero-btn">
                Lihat Laporan
            </a>
        </div>
    </section>

<section class="admin-stat-grid stat-count-8">

    <div class="stat-card stat-blue">
        <div class="stat-label">Total Users</div>
        <div class="stat-value"><?= esc((string) $totalUsers) ?></div>
        <div class="stat-desc">
            Akun admin, mahasiswa, dan dosen
        </div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-label">Mahasiswa</div>
        <div class="stat-value"><?= esc((string) $totalMahasiswa) ?></div>
        <div class="stat-desc">
            Data mahasiswa terdaftar
        </div>
    </div>

    <div class="stat-card stat-purple">
        <div class="stat-label">Dosen</div>
        <div class="stat-value"><?= esc((string) $totalDosen) ?></div>
        <div class="stat-desc">
            Dosen pembimbing aktif
        </div>
    </div>

    <div class="stat-card stat-indigo">
        <div class="stat-label">Pengajuan Judul</div>
        <div class="stat-value"><?= esc((string) $totalJudul) ?></div>
        <div class="stat-desc">
            <?= esc((string) ($judulPerluDipantau ?? 0)) ?> perlu dipantau
        </div>
    </div>

    <div class="stat-card stat-cyan">
        <div class="stat-label">Proposal</div>
        <div class="stat-value"><?= esc((string) $totalProposal) ?></div>
        <div class="stat-desc">
            <?= esc((string) ($proposalDiproses ?? 0)) ?> sedang diproses
        </div>
    </div>

    <div class="stat-card stat-rose">
        <div class="stat-label">Total SK</div>
        <div class="stat-value"><?= esc((string) $totalSk) ?></div>
        <div class="stat-desc">
            SK yang sudah masuk arsip
        </div>
    </div>

    <div class="stat-card stat-orange">
        <div class="stat-label">Permohonan Pembimbing</div>
        <div class="stat-value"><?= esc((string) $permohonanPending) ?></div>
        <div class="stat-desc">Menunggu keputusan dosen</div>
    </div>

    <div class="stat-card stat-amber">
        <div class="stat-label">SK Terbit</div>
        <div class="stat-value"><?= esc((string) $totalSkTerbit) ?></div>
        <div class="stat-desc">
            Sudah diterbitkan admin
        </div>
    </div>

</section>

    <section class="admin-analytics-grid">
        <div class="card-main admin-chart-card admin-wide-chart">
            <div class="page-head">
                <div>
                    <h3>Ringkasan Proses Akademik</h3>
                    <p>Perbandingan data utama yang sedang berjalan di sistem.</p>
                </div>
            </div>

            <div class="admin-column-chart">
                <div class="admin-column-item">
                    <div class="admin-column-track">
                        <span style="height: <?= esc($percentage($totalJudul, $maxBar)) ?>%;"></span>
                    </div>
                    <strong><?= esc((string) $totalJudul) ?></strong>
                    <small>Judul</small>
                </div>

                <div class="admin-column-item is-green">
                    <div class="admin-column-track">
                        <span style="height: <?= esc($percentage($totalProposal, $maxBar)) ?>%;"></span>
                    </div>
                    <strong><?= esc((string) $totalProposal) ?></strong>
                    <small>Proposal</small>
                </div>

                <div class="admin-column-item is-amber">
                    <div class="admin-column-track">
                        <span style="height: <?= esc($percentage($totalSk, $maxBar)) ?>%;"></span>
                    </div>
                    <strong><?= esc((string) $totalSk) ?></strong>
                    <small>SK</small>
                </div>

                <div class="admin-column-item is-purple">
                    <div class="admin-column-track">
                        <span style="height: <?= esc($percentage($permohonanMenunggu, $maxBar)) ?>%;"></span>
                    </div>
                    <strong><?= esc((string) $permohonanMenunggu) ?></strong>
                    <small>Permohonan</small>
                </div>
            </div>
        </div>

        <div class="card-main admin-chart-card">
            <div class="page-head compact-head">
                <div>
                    <h3>Komposisi User</h3>
                    <p>Mahasiswa dan dosen.</p>
                </div>
            </div>

            <div class="admin-donut-wrap">
                <svg class="admin-donut" viewBox="0 0 120 120" aria-hidden="true">
                    <circle cx="60" cy="60" r="50" class="donut-bg"></circle>
                    <circle
                        cx="60"
                        cy="60"
                        r="50"
                        class="donut-ring donut-blue"
                        stroke-dasharray="<?= esc($ring($totalMahasiswa, $totalUserChart)) ?> 314"
                    ></circle>
                    <circle
                        cx="60"
                        cy="60"
                        r="40"
                        class="donut-ring donut-purple"
                        stroke-dasharray="<?= esc($ring($totalDosen, $totalUserChart)) ?> 251"
                    ></circle>
                </svg>

                <div class="donut-center">
                    <strong><?= esc((string) $totalUsers) ?></strong>
                    <span>Total User</span>
                </div>
            </div>

            <div class="chart-legend">
                <span><b class="dot-blue"></b> Mahasiswa <?= esc((string) $totalMahasiswa) ?></span>
                <span><b class="dot-purple"></b> Dosen <?= esc((string) $totalDosen) ?></span>
            </div>
        </div>
    </section>

        <section class="card-main admin-line-chart-card">
            <div class="page-head">
                <div>
                    <h3>Aktivitas Pengajuan Bulanan</h3>
                    <p>Perbandingan jumlah pengajuan judul dan proposal dalam tahun berjalan.</p>
                </div>

                <span class="badge badge-info">
                    <?= date('Y') ?>
                </span>
            </div>

            <div class="chart-wrapper">
                <canvas id="adminLineChart"></canvas>
            </div>
        </section>

    
    <section class="admin-latest-grid">
        <div class ="card-main">
             <div class="page-head">
                <div>
                    <h3>Judul Terbaru</h3>
                    <p>Pengajuan judul terbaru yang masuk.</p>
                </div>

                <a class="see-all-link" href="<?= base_url('/admin/monitoring-judul') ?>">
                    Lihat semua
                </a>
            </div>

            <?php if (! empty($judulTerbaru)): ?>
                <div class="admin-activity-list">
                    <?php foreach ($judulTerbaru as $row): ?>
                        <article class="admin-activity-card">
                            <div class="admin-activity-avatar">J</div>

                            <div class="admin-activity-body">
                                <div class="admin-activity-top">
                                    <div>
                                        <div class="admin-activity-title">
                                            <?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?>
                                        </div>

                                        <div class="admin-activity-sub">
                                            <?= esc((string) ($row['nim'] ?? '-')) ?>
                                        </div>
                                    </div>

                                    <span class="badge <?= (($row['status'] ?? '') === 'disetujui') ? 'badge-success' : 'badge-warning' ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </div>

                                <div class="admin-activity-desc">
                                    <?= esc((string) ($row['judul'] ?? '-')) ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-box">Belum ada pengajuan judul terbaru.</div>
            <?php endif; ?>
        </div>

        <div class="card-main">
            <div class="page-head">
                <div>
                    <h3>Proposal Terbaru</h3>
                    <p>Proposal mahasiswa yang terakhir masuk.</p>
                </div>

                <a class="see-all-link" href="<?= base_url('/admin/monitoring-proposal') ?>">
                    Lihat semua
                </a>
            </div>

            <?php if (! empty($proposalTerbaru)): ?>
                <div class="admin-activity-list">
                    <?php foreach ($proposalTerbaru as $row): ?>
                        <article class="admin-activity-card">
                            <div class="admin-activity-avatar">P</div>

                            <div class="admin-activity-body">
                                <div class="admin-activity-top">
                                    <div>
                                        <div class="admin-activity-title">
                                            <?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?>
                                        </div>

                                        <div class="admin-activity-sub">
                                            <?= esc((string) ($row['nim'] ?? '-')) ?>
                                        </div>
                                    </div>

                                    <span class="badge <?= (($row['status'] ?? '') === 'disetujui') ? 'badge-success' : 'badge-warning' ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </div>

                                <div class="admin-activity-desc">
                                    <?= esc((string) ($row['nama_file_asli'] ?? '-')) ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-box">Belum ada proposal terbaru.</div>
            <?php endif; ?>
        </div>
    </section>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const adminLineChart = document.getElementById('adminLineChart');

if (adminLineChart) {
    new Chart(adminLineChart, {
        type: 'line',

        data: {
            labels: <?= json_encode($chartLabels ?? []) ?>,

            datasets: [
                {
                    label: 'Pengajuan Judul',
                    data: <?= json_encode($chartJudul ?? []) ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, .12)',
                    fill: true,
                    tension: .45,
                    borderWidth: 4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Proposal',
                    data: <?= json_encode($chartProposal ?? []) ?>,
                    borderColor: '#22c3d6',
                    backgroundColor: 'rgba(34, 195, 214, .12)',
                    fill: true,
                    tension: .45,
                    borderWidth: 4,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 18,
                        color: '#475569',
                        font: {
                            size: 13,
                            weight: '700'
                        }
                    }
                }
            },

            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#64748b',
                        font: {
                            weight: '700'
                        }
                    }
                },

                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, .16)'
                    },
                    ticks: {
                        precision: 0,
                        color: '#64748b',
                        font: {
                            weight: '700'
                        }
                    }
                }
            }
        }
    });
}
</script>
<?= $this->endSection() ?>
