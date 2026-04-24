<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }

    .stat-card {
        border-radius: 24px;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 34px rgba(15,23,42,.10);
    }

    .stat-card::after {
        content: "";
        position: absolute;
        right: -24px;
        bottom: -24px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(255,255,255,.14);
    }

    .stat-blue { background: linear-gradient(135deg,#2563eb,#1d4ed8); }
    .stat-orange { background: linear-gradient(135deg,#f59e0b,#ea580c); }
    .stat-green { background: linear-gradient(135deg,#10b981,#059669); }

    .stat-label {
        font-weight: 800;
        font-size: 15px;
        margin-bottom: 14px;
        position: relative;
        z-index: 1;
    }

    .stat-value {
        font-size: 40px;
        font-weight: 900;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .stat-desc {
        font-size: 14px;
        opacity: .95;
        position: relative;
        z-index: 1;
    }

    .card-premium {
        background: #fff;
        border-radius: 28px;
        padding: 28px;
        border: 1px solid #edf2f7;
        box-shadow: 0 18px 42px rgba(15,23,42,.06);
        margin-bottom: 24px;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: flex-start;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .section-title {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 900;
        color: #0f172a;
    }

    .section-subtitle {
        margin: 0;
        color: #64748b;
        font-size: 15px;
        line-height: 1.7;
    }

    .pembimbing-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .dosen-card {
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 24px;
        background: linear-gradient(135deg,#ffffff,#f8fbff);
        transition: .2s ease;
    }

    .dosen-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 34px rgba(15,23,42,.08);
    }

    .dosen-top {
        display: flex;
        justify-content: space-between;
        gap: 14px;
        align-items: flex-start;
        margin-bottom: 14px;
    }

    .dosen-name {
        font-size: 22px;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.35;
    }

    .dosen-meta {
        color: #475569;
        margin-top: 8px;
        line-height: 1.7;
        font-size: 14px;
    }

    .dosen-meta strong {
        color: #334155;
    }

    .divider {
        height: 1px;
        background: #eef2f7;
        margin: 16px 0;
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

    .badge-p1 { background: #ede9fe; color: #6d28d9; }
    .badge-p2 { background: #e0f2fe; color: #0369a1; }
    .badge-aktif { background: #dcfce7; color: #166534; }
    .badge-menunggu { background: #dbeafe; color: #1d4ed8; }
    .badge-disetujui { background: #dcfce7; color: #166534; }
    .badge-ditolak { background: #fee2e2; color: #b91c1c; }
    .badge-kuota { background: #fef3c7; color: #92400e; }

    .empty-box {
        border: 1px dashed #cbd5e1;
        border-radius: 22px;
        padding: 24px;
        color: #64748b;
        background: linear-gradient(135deg,#f8fafc,#f1f5f9);
        text-align: center;
        line-height: 1.7;
    }

    .table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 20px;
        border: 1px solid #eef2f7;
    }

    .table-premium {
        width: 100%;
        min-width: 900px;
        border-collapse: collapse;
        background: #fff;
    }

    .table-premium thead {
        background: linear-gradient(135deg,#f8fbff,#eff6ff);
    }

    .table-premium th,
    .table-premium td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid #eef2f7;
        vertical-align: top;
    }

    .table-premium th {
        font-size: 13px;
        font-weight: 900;
        color: #334155;
        white-space: nowrap;
    }

    .table-premium td {
        color: #0f172a;
        font-size: 14px;
        line-height: 1.6;
    }

    .form-panel {
        display: none;
        margin-top: 22px;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 22px;
        background: linear-gradient(135deg,#f8fbff,#f1f5f9);
    }

    .form-panel.show {
        display: block;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 800;
        color: #334155;
    }

    .input {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 14px 16px;
        font-size: 15px;
        outline: none;
        background: #fff;
    }

    .input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37,99,235,.10);
    }

    textarea.input {
        min-height: 120px;
        resize: vertical;
    }

    .btn-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    @media (max-width: 1000px) {
        .stats-grid,
        .pembimbing-grid,
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $pembimbingAktif = $pembimbingAktif ?? [];
    $permohonan = $permohonan ?? [];
    $dosenList = $dosenList ?? [];

    $totalAktif = count($pembimbingAktif);
    $totalMenunggu = 0;
    $totalDisetujui = 0;

    foreach ($permohonan as $p) {
        if (($p['status'] ?? '') === 'menunggu') {
            $totalMenunggu++;
        }

        if (($p['status'] ?? '') === 'disetujui') {
            $totalDisetujui++;
        }
    }

    $labelJenis = static function ($jenis) {
        return ($jenis === 'pembimbing_2' || $jenis === '2') ? 'Pembimbing 2' : 'Pembimbing 1';
    };

    $badgeJenis = static function ($jenis) {
        return ($jenis === 'pembimbing_2' || $jenis === '2') ? 'badge-p2' : 'badge-p1';
    };

    $badgeStatus = static function ($status) {
        return match ($status) {
            'disetujui' => 'badge-disetujui',
            'ditolak' => 'badge-ditolak',
            'kuota_penuh' => 'badge-kuota',
            default => 'badge-menunggu',
        };
    };
?>

<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-label">Pembimbing Aktif</div>
        <div class="stat-value"><?= esc((string) $totalAktif) ?></div>
        <div class="stat-desc">Dosen pembimbing yang sudah ditetapkan</div>
    </div>

    <div class="stat-card stat-orange">
        <div class="stat-label">Permohonan Menunggu</div>
        <div class="stat-value"><?= esc((string) $totalMenunggu) ?></div>
        <div class="stat-desc">Masih menunggu keputusan dosen</div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-label">Riwayat Persetujuan</div>
        <div class="stat-value"><?= esc((string) $totalDisetujui) ?></div>
        <div class="stat-desc">Permohonan yang sudah disetujui</div>
    </div>
</div>

<div class="card-premium">
    <div class="section-head">
        <div>
            <h2 class="section-title">Pembimbing Aktif</h2>
            <p class="section-subtitle">
                Dosen pembimbing hanya muncul setelah permohonan disetujui dan ditetapkan.
            </p>
        </div>
    </div>

    <?php if (! empty($pembimbingAktif)): ?>
        <div class="pembimbing-grid">
            <?php foreach ($pembimbingAktif as $row): ?>
                <?php $jenis = $row['jenis_pembimbing'] ?? ''; ?>

                <div class="dosen-card">
                    <div class="dosen-top">
                        <div class="dosen-name">
                            <?= esc((string) ($row['nama_dosen'] ?? $row['nama'] ?? '-')) ?>
                        </div>

                        <span class="badge <?= $badgeJenis($jenis) ?>">
                            <?= esc($labelJenis($jenis)) ?>
                        </span>
                    </div>

                    <div class="dosen-meta"><strong>NIDN:</strong> <?= esc((string) ($row['nidn'] ?? '-')) ?></div>
                    <div class="dosen-meta"><strong>No. HP:</strong> <?= esc((string) ($row['no_hp'] ?? '-')) ?></div>
                    <div class="dosen-meta"><strong>Bidang:</strong> <?= esc((string) ($row['bidang_keahlian'] ?? '-')) ?></div>

                    <div class="divider"></div>

                    <div class="dosen-meta">
                        <strong>Status:</strong> <span class="badge badge-aktif">Aktif</span>
                    </div>

                    <div class="dosen-meta">
                        <strong>Tanggal Penetapan:</strong>
                        <?= esc((string) ($row['tanggal_penetapan'] ?? '-')) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-box">
            Belum ada pembimbing aktif. Ajukan pembimbing terlebih dahulu, lalu tunggu persetujuan dosen.
        </div>
    <?php endif; ?>
</div>

<div class="card-premium">
    <div class="section-head">
        <div>
            <h2 class="section-title">Riwayat Permohonan Pembimbing</h2>
            <p class="section-subtitle">
                Pantau seluruh pengajuan pembimbing yang pernah kamu kirim.
            </p>
        </div>
    </div>

    <?php if (! empty($permohonan)): ?>
        <div class="table-wrap">
            <table class="table-premium">
                <thead>
                    <tr>
                        <th>Dosen</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Respon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($permohonan as $p): ?>
                        <tr>
                            <td><strong><?= esc((string) ($p['nama_dosen'] ?? '-')) ?></strong></td>
                            <td>
                                <span class="badge <?= $badgeJenis($p['jenis_pembimbing'] ?? '') ?>">
                                    <?= esc($labelJenis($p['jenis_pembimbing'] ?? '')) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= $badgeStatus($p['status'] ?? '') ?>">
                                    <?= esc((string) ($p['status'] ?? '-')) ?>
                                </span>
                            </td>
                            <td><?= esc((string) (($p['catatan'] ?? '') !== '' ? $p['catatan'] : '-')) ?></td>
                            <td><?= esc((string) ($p['tanggal_pengajuan'] ?? $p['created_at'] ?? '-')) ?></td>
                            <td><?= esc((string) (($p['tanggal_respon'] ?? '') !== '' ? $p['tanggal_respon'] : '-')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-box">
            Belum ada riwayat permohonan pembimbing.
        </div>
    <?php endif; ?>
</div>

<div class="card-premium">
    <div class="section-head">
        <div>
            <h2 class="section-title">Ajukan Pembimbing</h2>
            <p class="section-subtitle">
                Klik tombol untuk memilih dosen pembimbing 1 atau pembimbing 2.
            </p>
        </div>

        <button type="button" class="btn btn-primary" onclick="toggleFormPembimbing()">
            + Ajukan Pembimbing
        </button>
    </div>

    <div id="formPembimbing" class="form-panel">
        <form action="<?= base_url('/pembimbing/ajukan') ?>" method="post">
            <?= csrf_field() ?>

            <div class="form-grid">
                <div class="form-group">
                    <label>Jenis Pembimbing</label>
                    <select name="jenis_pembimbing" class="input" required>
                        <option value="">-- Pilih Jenis Pembimbing --</option>
                        <option value="pembimbing_1">Pembimbing 1</option>
                        <option value="pembimbing_2">Pembimbing 2</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pilih Dosen</label>
                    <select name="dosen_id" class="input" required>
                        <option value="">-- Pilih Dosen --</option>
                        <?php foreach ($dosenList as $dosen): ?>
                            <option value="<?= esc((string) $dosen['id']) ?>">
                                <?= esc((string) ($dosen['nama'] ?? $dosen['name'] ?? '-')) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Catatan</label>
                    <textarea name="catatan" class="input" placeholder="Tulis catatan jika diperlukan..."><?= old('catatan') ?></textarea>
                </div>
            </div>

            <div class="btn-row">
                <button type="submit" class="btn btn-primary">Kirim Permohonan</button>
                <button type="button" class="btn" style="border:1px solid #dbe3ef;color:#334155;" onclick="toggleFormPembimbing()">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleFormPembimbing() {
    document.getElementById('formPembimbing').classList.toggle('show');
}
</script>

<?= $this->endSection() ?>