<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        margin-bottom: 22px;
    }

    .section-title-premium {
        margin: 0 0 8px;
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-subtitle-premium {
        margin: 0 0 16px;
        color: #64748b;
        line-height: 1.7;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr auto;
        gap: 16px;
        align-items: end;
    }

    .form-group-premium label {
        display: block;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-group-premium select {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 13px 15px;
        font-size: 14px;
        background: #fff;
        box-sizing: border-box;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-top: 18px;
    }

    .summary-card {
        border-radius: 22px;
        padding: 22px;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .summary-label {
        font-size: 13px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 10px;
    }

    .summary-value {
        font-size: 34px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .summary-desc {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .arsip-title {
        margin: 0 0 14px;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    .premium-table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid #eef2f7;
    }

    .premium-table {
        width: 100%;
        min-width: 1000px;
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
        font-size: 13px;
        font-weight: 800;
        color: #334155;
        white-space: nowrap;
    }

    .premium-table td {
        color: #0f172a;
        font-size: 14px;
        line-height: 1.65;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .badge-diajukan { background: #dbeafe; color: #1d4ed8; }
    .badge-disetujui { background: #dcfce7; color: #166534; }
    .badge-revisi { background: #fef3c7; color: #92400e; }
    .badge-ditolak { background: #fee2e2; color: #b91c1c; }
    .badge-p1 { background: #ede9fe; color: #6d28d9; }
    .badge-p2 { background: #e0f2fe; color: #0369a1; }

    .premium-empty {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 22px;
        padding: 24px;
        text-align: center;
        color: #64748b;
        line-height: 1.8;
    }

    @media (max-width: 1100px) {
        .summary-grid {
            grid-template-columns: 1fr 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $periodeList = $periodeList ?? [];
    $periodeAktif = $periodeAktif ?? null;
    $summary = $summary ?? [];
    $arsipPembimbing = $arsipPembimbing ?? [];
    $arsipJudul = $arsipJudul ?? [];
    $arsipProposal = $arsipProposal ?? [];
    $arsipSk = $arsipSk ?? [];

    $badgeStatus = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'diajukan'   => 'badge-diajukan',
            'disetujui'  => 'badge-disetujui',
            'revisi'     => 'badge-revisi',
            'ditolak'    => 'badge-ditolak',
            'menunggu'   => 'badge-diajukan',
            default      => 'badge-diajukan',
        };
    };

    $badgeJenis = static function (?string $jenis): string {
        $jenis = strtolower((string) $jenis);
        return $jenis === 'pembimbing_2' ? 'badge-p2' : 'badge-p1';
    };

    $labelJenis = static function (?string $jenis): string {
        $jenis = strtolower((string) $jenis);
        return $jenis === 'pembimbing_2' ? 'Pembimbing 2' : 'Pembimbing 1';
    };
?>

<div class="card-premium">
    <h3 class="section-title-premium">Laporan / Arsip Per Semester</h3>
    <p class="section-subtitle-premium">Pilih periode akademik untuk melihat rekap semester dan arsip data terkait.</p>

    <form method="get" action="<?= base_url('/admin/laporan') ?>">
        <div class="filter-grid">
            <div class="form-group-premium">
                <label for="periode_id">Periode Akademik</label>
                <select name="periode_id" id="periode_id">
                    <?php foreach ($periodeList as $periode): ?>
                        <option value="<?= esc((string) ($periode['id'] ?? '')) ?>"
                            <?= (string) ($periodeAktif['id'] ?? '') === (string) ($periode['id'] ?? '') ? 'selected' : '' ?>>
                            <?= esc((string) ($periode['tahun_ajaran'] ?? '-')) ?> - <?= esc((string) ($periode['semester'] ?? '-')) ?>
                            <?= (int) ($periode['is_active'] ?? 0) === 1 ? ' (Aktif)' : '' ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </div>
    </form>
</div>

<div class="card-premium">
    <h4 class="arsip-title">Export PDF</h4>
    <p class="section-subtitle-premium">Unduh laporan berdasarkan periode akademik yang sedang dipilih.</p>

    <div style="display:flex; gap:12px; flex-wrap:wrap;">
        <a href="<?= base_url('/admin/laporan/export/rekap?periode_id=' . (string) ($periodeAktif['id'] ?? '')) ?>" class="btn btn-primary">
            Export Rekap PDF
        </a>
        <a href="<?= base_url('/admin/laporan/export/judul?periode_id=' . (string) ($periodeAktif['id'] ?? '')) ?>" class="btn btn-primary">
            Export Judul PDF
        </a>
        <a href="<?= base_url('/admin/laporan/export/proposal?periode_id=' . (string) ($periodeAktif['id'] ?? '')) ?>" class="btn btn-primary">
            Export Proposal PDF
        </a>
        <a href="<?= base_url('/admin/laporan/export/sk?periode_id=' . (string) ($periodeAktif['id'] ?? '')) ?>" class="btn btn-primary">
            Export SK PDF
        </a>
    </div>
</div>

<?php if (! empty($periodeAktif)): ?>
    <div class="card-premium">
        <h3 class="section-title-premium">
            Rekap Semester <?= esc((string) ($periodeAktif['tahun_ajaran'] ?? '-')) ?> - <?= esc((string) ($periodeAktif['semester'] ?? '-')) ?>
        </h3>
        <p class="section-subtitle-premium">Ringkasan operasional berdasarkan periode akademik yang dipilih.</p>

        <div class="summary-grid">
            <div class="summary-card">
                <div class="summary-label">Permohonan Pembimbing</div>
                <div class="summary-value"><?= esc((string) ($summary['permohonan_total'] ?? 0)) ?></div>
                <div class="summary-desc">Total permohonan pada semester ini</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Pembimbing Disetujui</div>
                <div class="summary-value"><?= esc((string) ($summary['permohonan_disetujui'] ?? 0)) ?></div>
                <div class="summary-desc">Permohonan yang telah diterima</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Judul Diajukan</div>
                <div class="summary-value"><?= esc((string) ($summary['judul_total'] ?? 0)) ?></div>
                <div class="summary-desc">Total pengajuan judul semester ini</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Judul Disetujui</div>
                <div class="summary-value"><?= esc((string) ($summary['judul_disetujui'] ?? 0)) ?></div>
                <div class="summary-desc">Judul yang lolos final</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Proposal Diajukan</div>
                <div class="summary-value"><?= esc((string) ($summary['proposal_total'] ?? 0)) ?></div>
                <div class="summary-desc">Proposal yang masuk semester ini</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">Proposal Disetujui</div>
                <div class="summary-value"><?= esc((string) ($summary['proposal_disetujui'] ?? 0)) ?></div>
                <div class="summary-desc">Proposal yang sudah diterima</div>
            </div>

            <div class="summary-card">
                <div class="summary-label">SK Terbit</div>
                <div class="summary-value"><?= esc((string) ($summary['sk_total'] ?? 0)) ?></div>
                <div class="summary-desc">SK yang sudah diterbitkan</div>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <h4 class="arsip-title">Arsip Pembimbing</h4>

        <?php if (! empty($arsipPembimbing)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
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
                                <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nama_dosen'] ?? '-')) ?></td>
                                <td>
                                    <span class="status-badge <?= $badgeJenis($row['jenis_pembimbing'] ?? '') ?>">
                                        <?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?>
                                    </span>
                                </td>
                                <td><?= esc((string) ($row['tanggal_penetapan'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada arsip pembimbing pada periode ini.</div>
        <?php endif; ?>
    </div>

    <div class="card-premium">
        <h4 class="arsip-title">Arsip Pengajuan Judul</h4>

        <?php if (! empty($arsipJudul)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
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
                                <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                                <td>
                                    <span class="status-badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </td>
                                <td><?= esc((string) ($row['tanggal_pengajuan'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada arsip pengajuan judul pada periode ini.</div>
        <?php endif; ?>
    </div>

    <div class="card-premium">
        <h4 class="arsip-title">Arsip Proposal</h4>

        <?php if (! empty($arsipProposal)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
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
                                <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nama_file_asli'] ?? '-')) ?></td>
                                <td>
                                    <span class="status-badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </td>
                                <td><?= esc((string) ($row['tanggal_upload'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada arsip proposal pada periode ini.</div>
        <?php endif; ?>
    </div>

    <div class="card-premium">
        <h4 class="arsip-title">Arsip Surat Keputusan</h4>

        <?php if (! empty($arsipSk)): ?>
            <div class="premium-table-wrap">
                <table class="premium-table">
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
                                <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['nomor_sk'] ?? '-')) ?></td>
                                <td><?= esc((string) ($row['tanggal_terbit'] ?? '-')) ?></td>
                                <td>
                                    <span class="status-badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="premium-empty">Belum ada arsip SK pada periode ini.</div>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="card-premium">
        <div class="premium-empty">Belum ada periode akademik yang bisa dipilih.</div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>