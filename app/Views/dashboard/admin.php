<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.admin-hero {
    background: linear-gradient(135deg, #0f1f46, #2563eb);
    color: #fff;
    border-radius: 30px;
    padding: 34px;
    margin-bottom: 24px;
    box-shadow: 0 20px 48px rgba(37,99,235,.20);
    position: relative;
    overflow: hidden;
}

.admin-hero::after {
    content: "";
    position: absolute;
    right: -70px;
    top: -70px;
    width: 230px;
    height: 230px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
}

.admin-hero h2,
.admin-hero p,
.admin-hero .hero-actions {
    position: relative;
    z-index: 1;
}

.admin-hero h2 {
    margin: 0 0 10px;
    font-size: 34px;
    font-weight: 900;
}

.admin-hero p {
    margin: 0;
    max-width: 900px;
    line-height: 1.7;
    color: rgba(255,255,255,.9);
}

.hero-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.hero-btn {
    display: inline-flex;
    padding: 12px 16px;
    border-radius: 15px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.18);
    color: #fff;
    font-weight: 900;
    text-decoration: none;
}

.admin-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}

.admin-stat {
    border-radius: 24px;
    padding: 24px;
    color: #fff;
    aspect-ratio: unset;
    min-height: 155px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 16px 36px rgba(15,23,42,.10);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.admin-stat::after {
    content: "";
    position: absolute;
    right: -28px;
    bottom: -28px;
    width: 115px;
    height: 115px;
    border-radius: 999px;
    background: rgba(255,255,255,.15);
}

.stat-blue { background: linear-gradient(135deg,#2563eb,#1d4ed8); }
.stat-green { background: linear-gradient(135deg,#10b981,#059669); }
.stat-purple { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }
.stat-orange { background: linear-gradient(135deg,#f59e0b,#ea580c); }
.stat-red { background: linear-gradient(135deg,#ef4444,#be123c); }
.stat-dark { background: linear-gradient(135deg,#1f2937,#0f172a); }
.stat-cyan { background: linear-gradient(135deg,#06b6d4,#0284c7); }
.stat-pink { background: linear-gradient(135deg,#db2777,#be185d); }

.stat-label,
.stat-number,
.stat-desc {
    position: relative;
    z-index: 1;
}

.stat-label {
    font-size: 14px;
    font-weight: 800;
}

.stat-number {
    font-size: 42px;
    font-weight: 900;
    line-height: 1;
}

.stat-desc {
    font-size: 13px;
    line-height: 1.5;
    opacity: .95;
}

.dashboard-section {
    margin-bottom: 24px;
}

.card-premium {
    background: #fff;
    border-radius: 28px;
    padding: 28px;
    border: 1px solid #edf2f7;
    box-shadow: 0 18px 42px rgba(15,23,42,.06);
}

.section-head {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: flex-start;
    flex-wrap: wrap;
    margin-bottom: 22px;
}

.section-title {
    margin: 0 0 8px;
    font-size: 26px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 15px;
    line-height: 1.6;
}

.see-all {
    color: #2563eb;
    font-weight: 900;
    text-decoration: none;
}

.menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
}

.menu-card {
    min-height: 155px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
    padding: 18px;
    border-radius: 22px;
    border: 1px solid #dbeafe;
    background: linear-gradient(135deg,#f8fbff,#eff6ff);
    text-decoration: none;
    transition: .2s ease;
}

.menu-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(37,99,235,.11);
}

.menu-icon {
    width: 44px;
    height: 44px;
    border-radius: 16px;
    background: #2563eb;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
}

.menu-title {
    color: #0f172a;
    font-size: 16px;
    font-weight: 900;
    margin-bottom: 4px;
}

.menu-desc {
    color: #64748b;
    font-size: 13px;
    line-height: 1.4;
}

.menu-arrow {
    color: #2563eb;
    font-weight: 900;
    align-self: flex-end;
}

.chart-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 24px;
}

.bar-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.bar-item {
    padding: 16px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.bar-top {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-weight: 900;
    color: #0f172a;
}

.bar-track {
    width: 100%;
    height: 13px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}

.bar-fill {
    height: 100%;
    border-radius: 999px;
}

.bar-fill.green { background: linear-gradient(135deg, #22c55e, #16a34a); }
.bar-fill.orange { background: linear-gradient(135deg, #f59e0b, #ea580c); }
.bar-fill.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
.bar-fill.blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }

.latest-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
}

.activity-list {
    display: grid;
    grid-template-columns: 1fr;
    gap: 14px;
}

.activity-card {
    display: grid;
    grid-template-columns: 52px 1fr;
    gap: 16px;
    padding: 18px;
    border-radius: 22px;
    border: 1px solid #e2e8f0;
    background: linear-gradient(135deg, #ffffff, #f8fbff);
}

.activity-avatar {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    background: #2563eb;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
}

.activity-top {
    display: flex;
    justify-content: space-between;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 10px;
}

.activity-title {
    font-weight: 900;
    color: #0f172a;
    font-size: 16px;
}

.activity-sub {
    color: #64748b;
    font-size: 13px;
    margin-top: 3px;
}

.activity-desc {
    color: #0f172a;
    font-weight: 800;
    line-height: 1.55;
    margin-bottom: 10px;
}

.activity-date {
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 900;
    white-space: nowrap;
}

.badge-green { background: #dcfce7; color: #166534; }
.badge-orange { background: #fef3c7; color: #92400e; }

.empty-box {
    padding: 24px;
    border-radius: 22px;
    border: 1px dashed #cbd5e1;
    background: #f8fafc;
    color: #64748b;
    text-align: center;
}

@media(max-width: 1200px) {
    .admin-stat-grid,
    .menu-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .admin-stat {
        aspect-ratio: auto;
    }

    .chart-grid,
    .latest-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 700px) {
    .admin-hero {
        padding: 24px;
        border-radius: 24px;
    }

    .admin-hero h2 {
        font-size: 27px;
    }

    .hero-actions {
        flex-direction: column;
    }

    .hero-btn {
        width: 100%;
        justify-content: center;
    }

    .admin-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }

    .admin-stat {
        min-height: 135px;
        padding: 16px;
        border-radius: 20px;
    }

    .stat-label {
        font-size: 12px;
    }

    .stat-number {
        font-size: 30px;
    }

    .stat-desc {
        font-size: 11.5px;
    }

    .card-premium {
        padding: 20px;
        border-radius: 24px;
    }

    .section-title {
        font-size: 22px;
    }

    .menu-grid {
        grid-template-columns: 1fr;
    }

    .menu-card {
        min-height: auto;
        display: grid;
        grid-template-columns: 44px 1fr auto;
        align-items: center;
    }

    .menu-arrow {
        align-self: center;
    }

    .activity-card {
        grid-template-columns: 1fr;
    }

    .activity-top {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<?php
$totalUsers       = $totalUsers ?? 0;
$totalMahasiswa  = $totalMahasiswa ?? 0;
$totalDosen      = $totalDosen ?? 0;
$totalJudul      = $totalJudul ?? 0;
$totalProposal   = $totalProposal ?? 0;
$totalSk         = $totalSk ?? 0;
$totalPembimbing = $totalPembimbing ?? 0;
$totalSkTerbit   = $totalSkTerbit ?? $totalSk;

$permohonanMenunggu = $permohonanMenunggu ?? 0;
$judulDipantau      = $judulDipantau ?? 0;
$proposalDiproses   = $proposalDiproses ?? 0;

$judulTerbaru    = $judulTerbaru ?? [];
$proposalTerbaru = $proposalTerbaru ?? [];

$judulDisetujui = $judulDisetujui ?? 0;
$judulRevisi    = $judulRevisi ?? 0;
$judulDitolak   = $judulDitolak ?? 0;
$judulDiproses  = $judulDiproses ?? 0;

$proposalDisetujui = $proposalDisetujui ?? 0;
$proposalRevisi    = $proposalRevisi ?? 0;
$proposalDitolak   = $proposalDitolak ?? 0;
$proposalDiproses  = $proposalDiproses ?? 0;

$totalGrafikJudul = max(1, $judulDisetujui + $judulRevisi + $judulDitolak + $judulDiproses);
$totalGrafikProposal = max(1, $proposalDisetujui + $proposalRevisi + $proposalDitolak + $proposalDiproses);
?>

<div class="admin-hero">
    <h2>Selamat datang, Admin 👑</h2>
    <p>
        Kelola pengguna, pembimbing, pengajuan judul, proposal, SK, dan arsip sistem
        dari satu dashboard operasional yang rapi dan terpusat.
    </p>

    <div class="hero-actions">
        <a href="<?= base_url('/admin/users/create') ?>" class="hero-btn">+ Tambah User</a>
        <a href="<?= base_url('/admin/surat-keputusan/create') ?>" class="hero-btn">+ Terbitkan SK</a>
        <a href="<?= base_url('/admin/laporan') ?>" class="hero-btn">Lihat Laporan</a>
    </div>
</div>

<div class="admin-stat-grid">
    <div class="admin-stat stat-blue">
        <div class="stat-label">Total Users</div>
        <div class="stat-number"><?= esc((string) $totalUsers) ?></div>
        <div class="stat-desc">Akun admin, mahasiswa, dan dosen</div>
    </div>

    <div class="admin-stat stat-green">
        <div class="stat-label">Mahasiswa</div>
        <div class="stat-number"><?= esc((string) $totalMahasiswa) ?></div>
        <div class="stat-desc">Data mahasiswa terdaftar</div>
    </div>

    <div class="admin-stat stat-purple">
        <div class="stat-label">Dosen</div>
        <div class="stat-number"><?= esc((string) $totalDosen) ?></div>
        <div class="stat-desc">Dosen pembimbing aktif</div>
    </div>

    <div class="admin-stat stat-orange">
        <div class="stat-label">Pengajuan Judul</div>
        <div class="stat-number"><?= esc((string) $totalJudul) ?></div>
        <div class="stat-desc"><?= esc((string) $judulDipantau) ?> perlu dipantau</div>
    </div>

    <div class="admin-stat stat-cyan">
        <div class="stat-label">Proposal</div>
        <div class="stat-number"><?= esc((string) $totalProposal) ?></div>
        <div class="stat-desc"><?= esc((string) $proposalDiproses) ?> sedang diproses</div>
    </div>

    <div class="admin-stat stat-pink">
        <div class="stat-label">Total SK</div>
        <div class="stat-number"><?= esc((string) $totalSk) ?></div>
        <div class="stat-desc">SK yang sudah masuk arsip</div>
    </div>

    <div class="admin-stat stat-dark">
        <div class="stat-label">Permohonan Pembimbing</div>
        <div class="stat-number"><?= esc((string) $permohonanMenunggu) ?></div>
        <div class="stat-desc">Menunggu keputusan dosen</div>
    </div>

    <div class="admin-stat stat-green">
        <div class="stat-label">SK Terbit</div>
        <div class="stat-number"><?= esc((string) $totalSkTerbit) ?></div>
        <div class="stat-desc">Sudah diterbitkan admin</div>
    </div>
</div>

<div class="dashboard-section">
    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Akses Cepat</h3>
                <p class="section-subtitle">Menu operasional utama admin.</p>
            </div>
        </div>

        <div class="menu-grid">
            <a href="<?= base_url('/admin/users') ?>" class="menu-card">
                <div class="menu-icon">U</div>
                <div>
                    <div class="menu-title">Kelola Users</div>
                    <div class="menu-desc">Admin, mahasiswa, dosen</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/periode-akademik') ?>" class="menu-card">
                <div class="menu-icon">P</div>
                <div>
                    <div class="menu-title">Periode Akademik</div>
                    <div class="menu-desc">Semester aktif dan riwayat</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/program-studi') ?>" class="menu-card">
                <div class="menu-icon">S</div>
                <div>
                    <div class="menu-title">Program Studi</div>
                    <div class="menu-desc">Master data prodi</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/monitoring-pembimbing') ?>" class="menu-card">
                <div class="menu-icon">B</div>
                <div>
                    <div class="menu-title">Monitoring Pembimbing</div>
                    <div class="menu-desc">Permohonan dosen pembimbing</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/monitoring-judul') ?>" class="menu-card">
                <div class="menu-icon">J</div>
                <div>
                    <div class="menu-title">Monitoring Judul</div>
                    <div class="menu-desc">Status judul dan revisi</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/monitoring-proposal') ?>" class="menu-card">
                <div class="menu-icon">R</div>
                <div>
                    <div class="menu-title">Monitoring Proposal</div>
                    <div class="menu-desc">Status proposal mahasiswa</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/surat-keputusan') ?>" class="menu-card">
                <div class="menu-icon">SK</div>
                <div>
                    <div class="menu-title">Surat Keputusan</div>
                    <div class="menu-desc">Penerbitan dan arsip SK</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>

            <a href="<?= base_url('/admin/audit-log') ?>" class="menu-card">
                <div class="menu-icon">L</div>
                <div>
                    <div class="menu-title">Audit Log</div>
                    <div class="menu-desc">Aktivitas dan notifikasi</div>
                </div>
                <div class="menu-arrow">→</div>
            </a>
        </div>
    </div>
</div>

<div class="chart-grid">
    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Grafik Status Judul</h3>
                <p class="section-subtitle">Ringkasan status pengajuan judul mahasiswa.</p>
            </div>
        </div>

        <div class="bar-list">
            <div class="bar-item">
                <div class="bar-top">
                    <span>Disetujui</span>
                    <strong><?= esc((string) $judulDisetujui) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill green" style="width: <?= ($judulDisetujui / $totalGrafikJudul) * 100 ?>%;"></div>
                </div>
            </div>

            <div class="bar-item">
                <div class="bar-top">
                    <span>Revisi</span>
                    <strong><?= esc((string) $judulRevisi) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill orange" style="width: <?= ($judulRevisi / $totalGrafikJudul) * 100 ?>%;"></div>
                </div>
            </div>

            <div class="bar-item">
                <div class="bar-top">
                    <span>Ditolak</span>
                    <strong><?= esc((string) $judulDitolak) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill red" style="width: <?= ($judulDitolak / $totalGrafikJudul) * 100 ?>%;"></div>
                </div>
            </div>

            <div class="bar-item">
                <div class="bar-top">
                    <span>Diproses</span>
                    <strong><?= esc((string) $judulDiproses) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill blue" style="width: <?= ($judulDiproses / $totalGrafikJudul) * 100 ?>%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Grafik Status Proposal</h3>
                <p class="section-subtitle">Ringkasan status proposal tugas akhir.</p>
            </div>
        </div>

        <div class="bar-list">
            <div class="bar-item">
                <div class="bar-top">
                    <span>Disetujui</span>
                    <strong><?= esc((string) $proposalDisetujui) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill green" style="width: <?= ($proposalDisetujui / $totalGrafikProposal) * 100 ?>%;"></div>
                </div>
            </div>

            <div class="bar-item">
                <div class="bar-top">
                    <span>Revisi</span>
                    <strong><?= esc((string) $proposalRevisi) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill orange" style="width: <?= ($proposalRevisi / $totalGrafikProposal) * 100 ?>%;"></div>
                </div>
            </div>

            <div class="bar-item">
                <div class="bar-top">
                    <span>Ditolak</span>
                    <strong><?= esc((string) $proposalDitolak) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill red" style="width: <?= ($proposalDitolak / $totalGrafikProposal) * 100 ?>%;"></div>
                </div>
            </div>

            <div class="bar-item">
                <div class="bar-top">
                    <span>Diproses</span>
                    <strong><?= esc((string) $proposalDiproses) ?></strong>
                </div>
                <div class="bar-track">
                    <div class="bar-fill blue" style="width: <?= ($proposalDiproses / $totalGrafikProposal) * 100 ?>%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="latest-grid">
    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Pengajuan Judul Terbaru</h3>
                <p class="section-subtitle">Data pengajuan judul terbaru yang masuk ke sistem.</p>
            </div>
            <a class="see-all" href="<?= base_url('/admin/monitoring-judul') ?>">Lihat semua</a>
        </div>

        <?php if (! empty($judulTerbaru)): ?>
            <div class="activity-list">
                <?php foreach ($judulTerbaru as $row): ?>
                    <div class="activity-card">
                        <div class="activity-avatar">J</div>
                        <div class="activity-body">
                            <div class="activity-top">
                                <div>
                                    <div class="activity-title"><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></div>
                                    <div class="activity-sub"><?= esc((string) ($row['nim'] ?? '-')) ?></div>
                                </div>

                                <span class="badge <?= (($row['status'] ?? '') === 'disetujui') ? 'badge-green' : 'badge-orange' ?>">
                                    <?= esc((string) ($row['status'] ?? '-')) ?>
                                </span>
                            </div>

                            <div class="activity-desc">
                                <?= esc((string) ($row['judul'] ?? '-')) ?>
                            </div>

                            <div class="activity-date">
                                <?= esc((string) ($row['tanggal_pengajuan'] ?? $row['created_at'] ?? '-')) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">Belum ada pengajuan judul terbaru.</div>
        <?php endif; ?>
    </div>

    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Proposal Terbaru</h3>
                <p class="section-subtitle">Data proposal terbaru mahasiswa.</p>
            </div>
            <a class="see-all" href="<?= base_url('/admin/monitoring-proposal') ?>">Lihat semua</a>
        </div>

        <?php if (! empty($proposalTerbaru)): ?>
            <div class="activity-list">
                <?php foreach ($proposalTerbaru as $row): ?>
                    <div class="activity-card">
                        <div class="activity-avatar">P</div>
                        <div class="activity-body">
                            <div class="activity-top">
                                <div>
                                    <div class="activity-title"><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></div>
                                    <div class="activity-sub"><?= esc((string) ($row['nim'] ?? '-')) ?></div>
                                </div>

                                <span class="badge <?= (($row['status'] ?? '') === 'disetujui') ? 'badge-green' : 'badge-orange' ?>">
                                    <?= esc((string) ($row['status'] ?? '-')) ?>
                                </span>
                            </div>

                            <div class="activity-desc">
                                <?= esc((string) ($row['nama_file_asli'] ?? '-')) ?>
                            </div>

                            <div class="activity-date">
                                <?= esc((string) ($row['tanggal_upload'] ?? $row['created_at'] ?? '-')) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">Belum ada proposal terbaru.</div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>