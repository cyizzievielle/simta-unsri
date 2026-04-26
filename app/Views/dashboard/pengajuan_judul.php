<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
    .judul-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 22px;
    }

    .card-modern {
        background: #fff;
        border-radius: 28px;
        padding: 28px;
        border: 1px solid #edf2f7;
        box-shadow: 0 18px 42px rgba(15,23,42,.06);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 20px;
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
        line-height: 1.7;
    }

    .judul-active-box {
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(135deg, #ecfdf5, #f0fdf4);
        border: 1px solid #bbf7d0;
    }

    .judul-active-title {
        font-size: 18px;
        font-weight: 900;
        color: #065f46;
        line-height: 1.7;
        margin-bottom: 14px;
    }

    .judul-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .empty-modern {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border: 1px dashed #cbd5e1;
        color: #64748b;
        line-height: 1.7;
    }

    .empty-icon {
        width: 54px;
        height: 54px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e0f2fe;
        color: #0369a1;
        font-size: 24px;
        flex-shrink: 0;
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
    .badge-blue { background: #dbeafe; color: #1d4ed8; }
    .badge-orange { background: #fef3c7; color: #92400e; }
    .badge-red { background: #fee2e2; color: #b91c1c; }
    .badge-gray { background: #e2e8f0; color: #475569; }

    .btn-modern {
        border: none;
        border-radius: 15px;
        padding: 12px 18px;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #fff;
        font-weight: 900;
        cursor: pointer;
        box-shadow: 0 12px 26px rgba(37,99,235,.20);
        transition: .2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-modern:hover {
        transform: translateY(-1px);
    }

    .btn-light {
        background: #f8fafc;
        color: #334155;
        border: 1px solid #dbe3ef;
        box-shadow: none;
    }

    .form-wrapper {
        display: none;
        margin-top: 22px;
        padding: 22px;
        border-radius: 24px;
        background: linear-gradient(135deg, #f8fbff, #f1f5f9);
        border: 1px solid #e2e8f0;
    }

    .form-wrapper.show {
        display: block;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        font-weight: 800;
        color: #334155;
    }

    .input-modern {
        width: 100%;
        border: 1px solid #dbe3ef;
        border-radius: 16px;
        padding: 14px 16px;
        font-size: 15px;
        outline: none;
        background: #fff;
        box-sizing: border-box;
    }

    .input-modern:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37,99,235,.10);
    }

    textarea.input-modern {
        min-height: 130px;
        resize: vertical;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .table-wrap {
        width: 100%;
        overflow-x: auto;
        border-radius: 22px;
        border: 1px solid #eef2f7;
    }

    .table-modern {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
        background: #fff;
    }

    .table-modern thead {
        background: linear-gradient(135deg, #f8fbff, #eff6ff);
    }

    .table-modern th,
    .table-modern td {
        padding: 16px;
        border-bottom: 1px solid #eef2f7;
        text-align: left;
        vertical-align: top;
        font-size: 14px;
        line-height: 1.6;
    }

    .table-modern th {
        color: #334155;
        font-size: 13px;
        font-weight: 900;
        white-space: nowrap;
    }

    .judul-table-title {
        font-weight: 900;
        color: #0f172a;
        line-height: 1.6;
    }

    .muted {
        color: #64748b;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .judul-grid {
            gap: 16px;
        }

        .card-modern {
            padding: 20px;
            border-radius: 22px;
        }

        .card-header {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }

        .section-title {
            font-size: 22px;
        }

        .section-subtitle {
            font-size: 14px;
        }

        .judul-active-box {
            padding: 18px;
            border-radius: 20px;
        }

        .judul-active-title {
            font-size: 15px;
            line-height: 1.6;
        }

        .judul-meta {
            flex-direction: column;
            align-items: flex-start;
        }

        .empty-modern {
            flex-direction: column;
            align-items: flex-start;
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-wrapper {
            padding: 18px;
            border-radius: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-modern {
            width: 100%;
        }

        .table-wrap {
            border: none;
            overflow: visible;
        }

        .mobile-table {
            min-width: 0;
        }

        .mobile-table thead {
            display: none;
        }

        .mobile-table,
        .mobile-table tbody,
        .mobile-table tr,
        .mobile-table td {
            display: block;
            width: 100%;
        }

        .mobile-table tr {
            margin-bottom: 14px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 24px rgba(15,23,42,.04);
        }

        .mobile-table td {
            border-bottom: none;
            padding: 9px 0;
        }

        .mobile-table td::before {
            content: attr(data-label);
            display: block;
            color: #64748b;
            font-size: 12px;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .mobile-table td[data-label="Aksi"] {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<?php
    $judulAktif = $judulAktif ?? null;
    $riwayatJudul = $riwayatJudul ?? ($riwayat ?? []);

    $badgeStatus = static function (?string $status): string {
        return match ($status) {
            'disetujui' => 'badge-green',
            'revisi' => 'badge-orange',
            'ditolak' => 'badge-red',
            'diajukan', 'direview' => 'badge-blue',
            default => 'badge-gray',
        };
    };
?>

<div class="judul-grid">

    <div class="card-modern">
        <div class="card-header">
            <div>
                <h2 class="section-title">Status Judul Aktif</h2>
                <p class="section-subtitle">Judul yang sudah disetujui dan menjadi acuan tugas akhirmu.</p>
            </div>

            <?php if (! empty($judulAktif)): ?>
                <span class="badge badge-green">Aktif</span>
            <?php else: ?>
                <span class="badge badge-gray">Belum Ada</span>
            <?php endif; ?>
        </div>

        <?php if (! empty($judulAktif)): ?>
            <div class="judul-active-box">
                <div class="judul-active-title">
                    <?= esc((string) ($judulAktif['judul'] ?? '-')) ?>
                </div>

                <div class="judul-meta">
                    <span class="badge badge-green">Disetujui</span>

                    <?php if (! empty($judulAktif['bidang_topik'])): ?>
                        <span class="badge badge-blue"><?= esc((string) $judulAktif['bidang_topik']) ?></span>
                    <?php endif; ?>

                    <span class="muted">
                        ID: #<?= esc((string) ($judulAktif['id'] ?? '-')) ?>
                    </span>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-modern">
                <div class="empty-icon">📄</div>
                <div>
                    <strong style="color:#0f172a;">Belum ada judul disetujui.</strong><br>
                    Kamu bisa mengajukan judul baru dan menunggu hasil review dari dosen pembimbing.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-modern">
        <div class="card-header">
            <div>
                <h2 class="section-title">Ajukan Judul</h2>
                <p class="section-subtitle">Klik tombol untuk membuka form pengajuan judul tugas akhir.</p>
            </div>

            <button type="button" class="btn-modern" onclick="toggleFormJudul()">
                + Ajukan Judul
            </button>
        </div>

        <div id="formJudul" class="form-wrapper">
            <form method="post" action="<?= base_url('/pengajuan-judul/simpan') ?>">
                <?= csrf_field() ?>

                <div class="form-grid">
                    <div class="form-group full">
                        <label>Judul Tugas Akhir</label>
                        <input type="text" name="judul" class="input-modern" value="<?= old('judul') ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Bidang Topik</label>
                        <input type="text" name="bidang_topik" class="input-modern" value="<?= old('bidang_topik') ?>">
                    </div>

                    <div class="form-group">
                        <label>Kata Kunci</label>
                        <input type="text" name="kata_kunci" class="input-modern" value="<?= old('kata_kunci') ?>">
                    </div>

                    <div class="form-group full">
                        <label>Latar Belakang</label>
                        <textarea name="latar_belakang" class="input-modern"><?= old('latar_belakang') ?></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-modern">Submit Judul</button>
                    <button type="button" class="btn-modern btn-light" onclick="toggleFormJudul()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card-modern">
        <div class="card-header">
            <div>
                <h2 class="section-title">Riwayat Pengajuan Judul</h2>
                <p class="section-subtitle">Semua pengajuan judul yang pernah kamu kirim.</p>
            </div>
        </div>

        <?php if (! empty($riwayatJudul)): ?>
            <div class="table-wrap">
                <table class="table-modern mobile-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Bidang</th>
                            <th>Kata Kunci</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatJudul as $row): ?>
                            <tr>
                                <td data-label="Judul">
                                    <div class="judul-table-title">
                                        <?= esc((string) ($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td data-label="Bidang">
                                    <?= esc((string) (($row['bidang_topik'] ?? '') !== '' ? $row['bidang_topik'] : '-')) ?>
                                </td>

                                <td data-label="Kata Kunci">
                                    <?= esc((string) (($row['kata_kunci'] ?? '') !== '' ? $row['kata_kunci'] : '-')) ?>
                                </td>

                                <td data-label="Status">
                                    <span class="badge <?= $badgeStatus($row['status'] ?? '') ?>">
                                        <?= esc((string) ($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td data-label="Tanggal">
                                    <?= esc((string) ($row['tanggal_pengajuan'] ?? $row['created_at'] ?? '-')) ?>
                                </td>

                                <td data-label="Aksi">
                                    <a href="<?= base_url('/pengajuan-judul/detail/' . ($row['id'] ?? 0)) ?>" class="btn-modern btn-light">
                                        Detail
                                    </a>

                                    <?php if (($row['status'] ?? '') === 'revisi'): ?>
                                        <a href="<?= base_url('/pengajuan-judul/revisi/' . ($row['id'] ?? 0)) ?>" class="btn-modern">
                                            Revisi
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-modern">
                <div class="empty-icon">📝</div>
                <div>
                    <strong style="color:#0f172a;">Belum ada riwayat pengajuan.</strong><br>
                    Riwayat akan muncul setelah kamu mengirim pengajuan judul.
                </div>
            </div>
        <?php endif; ?>
    </div>

</div>

<script>
function toggleFormJudul() {
    const form = document.getElementById('formJudul');
    form.classList.toggle('show');
}
</script>

<?= $this->endSection() ?>