<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 22px;
}

.stat-card {
    border-radius: 20px;
    padding: 18px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 14px 28px rgba(15,23,42,.09);
    min-height: 118px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.stat-card::after {
    content: "";
    position: absolute;
    right: -22px;
    bottom: -22px;
    width: 86px;
    height: 86px;
    border-radius: 50%;
    background: rgba(255,255,255,.14);
}

.stat-blue { background: linear-gradient(135deg,#2563eb,#1d4ed8); }
.stat-orange { background: linear-gradient(135deg,#f59e0b,#ea580c); }
.stat-green { background: linear-gradient(135deg,#10b981,#059669); }

.stat-label,
.stat-value,
.stat-desc {
    position: relative;
    z-index: 1;
}

.stat-label {
    font-weight: 900;
    font-size: 13px;
    line-height: 1.25;
}

.stat-value {
    font-size: 34px;
    font-weight: 900;
    line-height: 1;
}

.stat-desc {
    font-size: 12px;
    line-height: 1.35;
    opacity: .95;
}

.card-premium {
    background: #fff;
    border-radius: 24px;
    padding: 24px;
    border: 1px solid #edf2f7;
    box-shadow: 0 14px 34px rgba(15,23,42,.06);
    margin-bottom: 22px;
}

.section-head {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: flex-start;
    flex-wrap: wrap;
    margin-bottom: 18px;
}

.section-title {
    margin: 0 0 6px;
    font-size: 24px;
    font-weight: 900;
    color: #0f172a;
}

.section-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.55;
}

.pembimbing-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.dosen-card {
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 18px;
    background: linear-gradient(135deg,#ffffff,#f8fbff);
    transition: .2s ease;
}

.dosen-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(15,23,42,.07);
}

.dosen-top {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: flex-start;
    margin-bottom: 12px;
}

.dosen-name {
    font-size: 18px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.35;
}

.dosen-meta {
    color: #475569;
    margin-top: 7px;
    line-height: 1.55;
    font-size: 13px;
}

.dosen-meta strong {
    color: #334155;
}

.divider {
    height: 1px;
    background: #eef2f7;
    margin: 13px 0;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 9px;
    border-radius: 999px;
    font-size: 11px;
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
    border-radius: 18px;
    padding: 20px;
    color: #64748b;
    background: linear-gradient(135deg,#f8fafc,#f1f5f9);
    text-align: center;
    line-height: 1.55;
    font-size: 13px;
}

.table-wrap {
    width: 100%;
    overflow-x: auto;
    border-radius: 18px;
    border: 1px solid #eef2f7;
}

.table-premium {
    width: 100%;
    min-width: 820px;
    border-collapse: collapse;
    background: #fff;
}

.table-premium thead {
    background: linear-gradient(135deg,#f8fbff,#eff6ff);
}

.table-premium th,
.table-premium td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eef2f7;
    vertical-align: top;
}

.table-premium th {
    font-size: 11px;
    font-weight: 900;
    color: #334155;
    white-space: nowrap;
}

.table-premium td {
    color: #0f172a;
    font-size: 12px;
    line-height: 1.45;
}

.form-panel {
    display: none;
    margin-top: 18px;
    border: 1px solid #e2e8f0;
    border-radius: 22px;
    padding: 18px;
    background: linear-gradient(135deg,#f8fbff,#f1f5f9);
}

.form-panel.show {
    display: block;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

.form-group.full {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 800;
    color: #334155;
    font-size: 13px;
}

.input {
    width: 100%;
    border: 1px solid #dbe3ef;
    border-radius: 14px;
    padding: 12px 13px;
    font-size: 13px;
    outline: none;
    background: #fff;
    box-sizing: border-box;
}

.input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37,99,235,.10);
}

textarea.input {
    min-height: 100px;
    resize: vertical;
}

.btn-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
}

/* TABLET: 3 CARD TETAP SEJAJAR */
@media (max-width: 1000px) {
    .stats-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .stat-card {
        min-height: 100px;
        padding: 13px;
        border-radius: 17px;
    }

    .stat-label {
        font-size: 10.5px;
    }

    .stat-value {
        font-size: 25px;
    }

    .stat-desc {
        font-size: 9.5px;
    }

    .pembimbing-grid,
    .form-grid {
        grid-template-columns: 1fr;
    }
}

/* MOBILE: 3 CARD KECIL KESAMPING */
@media(max-width: 760px) {
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 7px;
        margin-bottom: 14px;
    }

    .stat-card {
        min-height: 82px;
        padding: 9px;
        border-radius: 14px;
        box-shadow: 0 8px 18px rgba(15,23,42,.08);
    }

    .stat-card::after {
        width: 58px;
        height: 58px;
        right: -20px;
        bottom: -20px;
    }

    .stat-label {
        font-size: 8.4px;
        line-height: 1.15;
    }

    .stat-value {
        font-size: 18px;
        line-height: 1;
    }

    .stat-desc {
        font-size: 7.4px;
        line-height: 1.15;
    }

    .card-premium {
        padding: 13px;
        border-radius: 18px;
        margin-bottom: 13px;
    }

    .section-head {
        margin-bottom: 12px;
        gap: 9px;
    }

    .section-title {
        font-size: 17px;
        margin-bottom: 4px;
    }

    .section-subtitle {
        font-size: 10.5px;
        line-height: 1.35;
    }

    .section-head .btn {
        padding: 8px 10px;
        font-size: 10.5px;
        border-radius: 10px;
    }

    .pembimbing-grid {
        grid-template-columns: 1fr;
        gap: 9px;
    }

    .dosen-card {
        padding: 11px;
        border-radius: 16px;
    }

    .dosen-top {
        gap: 8px;
        margin-bottom: 8px;
    }

    .dosen-name {
        font-size: 12px;
        line-height: 1.3;
    }

    .dosen-meta {
        font-size: 10px;
        line-height: 1.35;
        margin-top: 5px;
    }

    .divider {
        margin: 9px 0;
    }

    .badge {
        padding: 4px 6px;
        font-size: 8.5px;
    }

    .empty-box {
        padding: 13px;
        border-radius: 15px;
        font-size: 10px;
        line-height: 1.35;
    }

    .table-wrap {
        border-radius: 15px;
    }

    .table-premium {
        min-width: 540px;
    }

    .table-premium th,
    .table-premium td {
        padding: 7px 7px;
        font-size: 9.8px;
        line-height: 1.25;
        white-space: nowrap;
    }

    .table-premium th {
        font-size: 8.8px;
    }

    .table-premium td:nth-child(1) strong {
        display: inline-block;
        max-width: 125px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .form-panel {
        padding: 11px;
        border-radius: 16px;
        margin-top: 12px;
    }

    .form-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }

    .form-group label {
        font-size: 10px;
        margin-bottom: 5px;
    }

    .input {
        padding: 8px 9px;
        border-radius: 10px;
        font-size: 10px;
    }

    textarea.input {
        min-height: 76px;
    }

    .btn-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 7px;
        margin-top: 11px;
    }

    .btn-row .btn {
        padding: 8px 9px;
        font-size: 10px;
        border-radius: 10px;
    }
}

/* HP KECIL: TETAP 3 CARD, FONT LEBIH KECIL */
@media(max-width: 390px) {
    .stats-grid {
        gap: 6px;
    }

    .stat-card {
        padding: 8px;
        min-height: 78px;
    }

    .stat-label {
        font-size: 7.8px;
    }

    .stat-value {
        font-size: 17px;
    }

    .stat-desc {
        font-size: 7px;
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