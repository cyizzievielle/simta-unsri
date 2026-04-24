<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('content') ?>

<style>
    .page-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        color: #fff;
        border-radius: 28px;
        padding: 28px 30px;
        margin-bottom: 22px;
        box-shadow: 0 18px 40px rgba(37, 99, 235, 0.18);
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
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .page-hero h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 800;
        position: relative;
        z-index: 1;
    }

    .page-hero p {
        margin: 0;
        color: rgba(255,255,255,0.92);
        line-height: 1.7;
        position: relative;
        z-index: 1;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 22px;
        margin-bottom: 22px;
    }

    .card-premium {
        background: #fff;
        border-radius: 26px;
        padding: 24px;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2f7;
        min-width: 0;
    }

    .section-title-premium {
        margin: 0 0 6px;
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
    }

    .section-subtitle-premium {
        margin: 0 0 16px;
        color: #64748b;
        line-height: 1.7;
    }

    .detail-box {
        border: 1px solid #e2e8f0;
        background: linear-gradient(135deg, #ffffff, #f8fbff);
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .detail-box-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .detail-box-value {
        color: #0f172a;
        line-height: 1.75;
        font-size: 15px;
        word-break: break-word;
    }

    .detail-meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        line-height: 1;
        white-space: nowrap;
    }

    .badge-menunggu { background: #dbeafe; color: #1d4ed8; }
    .badge-disetujui { background: #dcfce7; color: #166534; }
    .badge-ditolak { background: #fee2e2; color: #b91c1c; }
    .badge-kuota { background: #fef3c7; color: #92400e; }
    .badge-p1 { background: #ede9fe; color: #6d28d9; }
    .badge-p2 { background: #e0f2fe; color: #0369a1; }

    .badge-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .form-group-premium {
        margin-bottom: 16px;
    }

    .form-group-premium label {
        display: block;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-group-premium select,
    .form-group-premium textarea {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 13px 15px;
        font-size: 14px;
        background: #fff;
        outline: none;
        box-sizing: border-box;
    }

    .form-group-premium select:focus,
    .form-group-premium textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10);
    }

    .form-group-premium textarea {
        min-height: 130px;
        resize: vertical;
    }

    .premium-note {
        border: 1px dashed #cbd5e1;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-radius: 18px;
        padding: 16px;
        color: #64748b;
        line-height: 1.7;
    }

    @media (max-width: 1100px) {
        .detail-grid,
        .detail-meta-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
    $permohonan = $permohonan ?? [];

    $badgeStatus = static function (?string $status): string {
        $status = strtolower((string) $status);
        return match ($status) {
            'menunggu'    => 'badge-menunggu',
            'disetujui'   => 'badge-disetujui',
            'ditolak'     => 'badge-ditolak',
            'kuota_penuh' => 'badge-kuota',
            default       => 'badge-menunggu',
        };
    };

    $badgeJenis = static function (?string $jenis): string {
        $jenis = strtolower((string) $jenis);
        return match ($jenis) {
            '1', 'pembimbing_1' => 'badge-p1',
            '2', 'pembimbing_2' => 'badge-p2',
            default             => 'badge-p1',
        };
    };

    $labelJenis = static function (?string $jenis): string {
        $jenis = strtolower((string) $jenis);
        return match ($jenis) {
            '1', 'pembimbing_1' => 'Pembimbing 1',
            '2', 'pembimbing_2' => 'Pembimbing 2',
            default             => (string) $jenis,
        };
    };

    $statusSekarang = strtolower((string) ($permohonan['status'] ?? ''));
?>

<div class="page-hero">
    <h2>Detail Permohonan Pembimbing</h2>
    <p>Lihat informasi lengkap permohonan mahasiswa dan ambil keputusan dari halaman ini.</p>
</div>

<div class="detail-grid">
    <div class="card-premium">
        <h3 class="section-title-premium">Informasi Permohonan</h3>
        <p class="section-subtitle-premium">Detail mahasiswa, jenis pembimbing, status, dan catatan permohonan.</p>

        <div class="detail-box">
            <div class="detail-box-title">Mahasiswa</div>
            <div class="detail-box-value">
                <?= esc((string) ($permohonan['nama_mahasiswa'] ?? '-')) ?><br>
                NIM: <?= esc((string) ($permohonan['nim'] ?? '-')) ?>
            </div>
        </div>

        <div class="detail-box">
            <div class="detail-box-title">Status Permohonan</div>
            <div class="badge-row">
                <span class="status-badge <?= $badgeJenis($permohonan['jenis_pembimbing'] ?? '') ?>">
                    <?= esc($labelJenis($permohonan['jenis_pembimbing'] ?? '')) ?>
                </span>

                <span class="status-badge <?= $badgeStatus($permohonan['status'] ?? '') ?>">
                    <?= esc((string) ($permohonan['status'] ?? '-')) ?>
                </span>
            </div>
        </div>

        <div class="detail-meta-grid">
            <div class="detail-box">
                <div class="detail-box-title">Tanggal Pengajuan</div>
                <div class="detail-box-value">
                    <?= esc((string) ($permohonan['tanggal_pengajuan'] ?? '-')) ?>
                </div>
            </div>

            <div class="detail-box">
                <div class="detail-box-title">Tanggal Respon</div>
                <div class="detail-box-value">
                    <?= esc((string) (($permohonan['tanggal_respon'] ?? '') !== '' ? $permohonan['tanggal_respon'] : '-')) ?>
                </div>
            </div>
        </div>

        <div class="detail-box">
            <div class="detail-box-title">Catatan Mahasiswa</div>
            <div class="detail-box-value">
                <?= nl2br(esc((string) (($permohonan['catatan'] ?? '') !== '' ? $permohonan['catatan'] : '-'))) ?>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <h3 class="section-title-premium">Ambil Keputusan</h3>
        <p class="section-subtitle-premium">Setujui atau tolak permohonan pembimbing dari mahasiswa ini.</p>

        <?php if ($statusSekarang === 'menunggu'): ?>
            <form action="<?= base_url('/dosen/permohonan/' . (string) ($permohonan['id'] ?? '') . '/respon') ?>" method="post">
                <?= csrf_field() ?>

                <div class="form-group-premium">
                    <label>Status Keputusan</label>
                    <select name="status" required>
                        <option value="">-- Pilih Keputusan --</option>
                        <option value="disetujui">Setujui</option>
                        <option value="ditolak">Tolak</option>
                    </select>
                </div>

                <div class="form-group-premium">
                    <label>Catatan Dosen</label>
                    <textarea name="catatan" placeholder="Tulis alasan penolakan atau catatan persetujuan..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;">Simpan Keputusan</button>
            </form>
        <?php else: ?>
            <div class="premium-note">
                Permohonan ini sudah diproses sebelumnya, jadi tidak bisa diubah lagi dari halaman ini.
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>