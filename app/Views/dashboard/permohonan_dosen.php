<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$permohonanMenunggu = $permohonanMenunggu ?? [];
$riwayatKeputusan   = $riwayatKeputusan ?? [];
$keyword            = $keyword ?? '';
$jenis              = $jenis ?? '';
$status             = $status ?? '';
$perPage            = $perPage ?? 10;
$page               = $page ?? 1;
$totalRows          = $totalRows ?? 0;
$totalPages         = $totalPages ?? 1;
$startRow           = $startRow ?? 0;
$endRow             = $endRow ?? 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$jenisLabel = static function (?string $jenis): string {
    return match ((string) $jenis) {
        '1', 'pembimbing_1' => 'Pembimbing 1',
        '2', 'pembimbing_2' => 'Pembimbing 2',
        default => '-',
    };
};
$statusLabel = static function (mixed $value) use ($safe): string {
    return match (strtolower($safe($value, ''))) {
        'menunggu'    => 'Menunggu',
        'disetujui'   => 'Disetujui',
        'ditolak'     => 'Ditolak',
        'kuota_penuh' => 'Kuota Penuh',
        default       => $safe($value, '-'),
    };
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui'   => 'badge-success',
        'ditolak'     => 'badge-danger',
        'kuota_penuh' => 'badge-warning',
        default       => 'badge-info',
    };
};

$badgeJenis = static function (mixed $jenis) use ($safe): string {
    return match (strtolower($safe($jenis, ''))) {
        'pembimbing_2', '2' => 'badge-purple',
        default             => 'badge-info',
    };
};

$labelJenis = static function (mixed $jenis) use ($safe): string {
    return match (strtolower($safe($jenis, ''))) {
        'pembimbing_2', '2' => 'Pembimbing 2',
        default             => 'Pembimbing 1',
    };
};

$detailJson = static function (array $row) use ($safe, $labelJenis, $statusLabel): string {
    $payload = [
        'nama'      => $safe($row['nama_mahasiswa'] ?? '-'),
        'nim'       => $safe($row['nim'] ?? '-'),
        'jenis'     => $labelJenis($row['jenis_pembimbing'] ?? ''),
        'status'    => $statusLabel($row['status'] ?? '-'),
        'pengajuan' => $safe($row['tanggal_pengajuan'] ?? '-'),
        'respon'    => $safe($row['tanggal_respon'] ?? '-'),
        'catatan'   => $safe($row['catatan'] ?? 'Tidak ada catatan.'),
    ];

    $json = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    return is_string($json) ? $json : '{}';
};

$queryParams = [
    'q'        => $keyword,
    'jenis'    => $jenis,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    return base_url('/dosen/permohonan?' . http_build_query(array_merge($queryParams, [
        'page' => $targetPage,
    ])));
};
?>
<div class="proposal-dosen-page dosen-title-page">
<section class="page-hero request-hero">
    <span class="page-kicker">
        Permohonan Pembimbing
    </span>

    <h2>Kelola Permohonan Mahasiswa</h2>

    <p>
        Tinjau permohonan pembimbing tugas akhir mahasiswa, berikan keputusan,
        dan pantau riwayat persetujuan secara terstruktur.
    </p>
</section>

<div class="request-page">
    <section class="stat-grid request-stats">
        <div class="stat-card stat-blue">
            <div class="stat-label">Total Riwayat</div>
            <div class="stat-value"><?= esc($safe($totalRows, '0')) ?></div>
            <div class="stat-desc">Sesuai hasil filter</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Menunggu Keputusan</div>
            <div class="stat-value"><?= esc($safe(count($permohonanMenunggu), '0')) ?></div>
            <div class="stat-desc">Perlu ditinjau dosen</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Riwayat Keputusan</div>
            <div class="stat-value"><?= esc($safe(count($riwayatKeputusan), '0')) ?></div>
            <div class="stat-desc">Data pada halaman ini</div>
        </div>
    </section>

    <section class="card-main request-card-main">
        <div class="page-head">
            <div>
                <h3>Permohonan Aktif</h3>
                <p>Daftar mahasiswa yang sedang menunggu keputusan persetujuan pembimbing.</p>
            </div>
        </div>

        <?php if (! empty($permohonanMenunggu) && is_array($permohonanMenunggu)): ?>
            <div class="table-wrap request-table-wrap">
                <table class="admin-table request-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Pembimbing</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($permohonanMenunggu as $row): ?>
                            <tr>
                                <td><strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong></td>
                                <td><?= esc($safe($row['nim'] ?? '-')) ?></td>
                                <td>
                                    <span class="badge <?= esc($badgeJenis($row['jenis_pembimbing'] ?? '')) ?>">
                                        <?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>
                                <td><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></td>
                                <td>
                                    <a href="<?= base_url('/dosen/permohonan/detail/' . $safe($row['id'] ?? '')) ?>" class="icon-btn icon-open" title="Lihat Detail">👁</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="request-mobile-list">
                <?php foreach ($permohonanMenunggu as $row): ?>
                    <article class="request-mobile-card">
                        <div class="request-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>
                            <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="request-mobile-info">
                            <div>
                                <small>Jenis Pembimbing</small>
                                <b><?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?></b>
                            </div>
                            <div>
                                <small>Tanggal Pengajuan</small>
                                <b><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></b>
                            </div>
                        </div>

                        <a href="<?= base_url('/dosen/permohonan/detail/' . $safe($row['id'] ?? '')) ?>" class="btn btn-primary request-mobile-btn">
                            Lihat Detail
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum terdapat permohonan pembimbing yang memerlukan keputusan saat ini.
            </div>
        <?php endif; ?>
    </section>

    <section class="card-main request-card-main">
        <div class="page-head">
            <div>
                <h3>Riwayat Keputusan Pembimbing</h3>
                <p>Telusuri kembali keputusan persetujuan pembimbing berdasarkan mahasiswa, jenis pembimbing, dan status pengajuan.</p>
            </div>
        </div>

        <div class="filter-box request-filter-mini">
            <form method="get" action="<?= base_url('/dosen/permohonan') ?>" class="filter-form request-filter-mini-form">
                <div class="filter-field">
                    <label for="q">Cari Mahasiswa / NIM</label>
                    <input type="text" id="q" name="q" value="<?= esc($safe($keyword, '')) ?>" placeholder="Nama atau NIM">
                </div>

                <div class="filter-field">
                    <label for="jenis">Jenis</label>
                    <select id="jenis" name="jenis">
                        <option value="">Semua</option>
                        <option value="pembimbing_1" <?= $jenis === 'pembimbing_1' ? 'selected' : '' ?>>Pembimbing 1</option>
                        <option value="pembimbing_2" <?= $jenis === 'pembimbing_2' ? 'selected' : '' ?>>Pembimbing 2</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="menunggu" <?= $status === 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                        <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        <option value="kuota_penuh" <?= $status === 'kuota_penuh' ? 'selected' : '' ?>>Kuota Penuh</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                    <a href="<?= base_url('/dosen/permohonan') ?>" class="btn-filter btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <?php if (! empty($riwayatKeputusan) && is_array($riwayatKeputusan)): ?>
            <div class="table-wrap request-table-wrap">
                <table class="admin-table request-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Jenis Pembimbing</th>
                            <th>Status</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Tanggal Respon</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayatKeputusan as $row): ?>
                            <tr>
                                <td><strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong></td>
                                <td><?= esc($safe($row['nim'] ?? '-')) ?></td>
                                <td>
                                    <span class="badge <?= esc($badgeJenis($row['jenis_pembimbing'] ?? '')) ?>">
                                        <?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>
                                <td><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></td>
                                <td><?= esc($safe($row['tanggal_respon'] ?? '-')) ?></td>
                                <td>
                                    <div class="request-note">
                                        <?= esc($safe($row['catatan'] ?? '-')) ?>
                                    </div>
                                </td>
                            <td>
                                <div class="table-action-icons">
                                    <button
                                        type="button"
                                        class="table-icon-btn btn-view table-detail-btn"
                                        title="Lihat Detail"
                                        data-detail='<?= esc(json_encode([
                                            "nama" => $safe($row["nama_mahasiswa"] ?? "-"),
                                            "nim" => $safe($row["nim"] ?? "-"),
                                            "jenis" => $jenisLabel($row["jenis_pembimbing"] ?? ""),
                                            "status" => $statusLabel($row["status"] ?? ""),
                                            "tanggal" => $safe($row["created_at"] ?? "-"),
                                            "respon" => $safe($row["updated_at"] ?? "-"),
                                            "catatan" => $safe($row["catatan"] ?? "Tidak ada catatan"),
                                        ]), "attr") ?>'
                                    >
                                        <i class="ri-eye-line"></i>
                                    </button>

                                    <a
                                        href="<?= base_url('/dosen/permohonan/edit/' . ($row['id'] ?? 0)) ?>"
                                        class="table-icon-btn btn-edit"
                                        title="Edit Keputusan"
                                    >
                                        <i class="ri-edit-2-line"></i>
                                    </a>
                                </div>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="request-mobile-list">
                <?php foreach ($riwayatKeputusan as $row): ?>
                    <article class="request-mobile-card">
                        <div class="request-mobile-top">
                            <div>
                                <strong><?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <span>NIM: <?= esc($safe($row['nim'] ?? '-')) ?></span>
                            </div>
                            <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                <?= esc($statusLabel($row['status'] ?? '-')) ?>
                            </span>
                        </div>

                        <div class="request-mobile-info">
                            <div>
                                <small>Jenis Pembimbing</small>
                                <b><?= esc($labelJenis($row['jenis_pembimbing'] ?? '')) ?></b>
                            </div>
                            <div>
                                <small>Pengajuan</small>
                                <b><?= esc($safe($row['tanggal_pengajuan'] ?? '-')) ?></b>
                            </div>
                            <div>
                                <small>Respon</small>
                                <b><?= esc($safe($row['tanggal_respon'] ?? '-')) ?></b>
                            </div>
                            <div>
                                <small>Catatan</small>
                                <b><?= esc($safe($row['catatan'] ?? '-')) ?></b>
                            </div>
                        </div>

                        <button type="button" class="btn btn-primary request-mobile-btn table-detail-btn" data-detail="<?= esc($detailJson($row), 'attr') ?>">
                            Lihat Detail Keputusan
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Tidak ada riwayat keputusan yang sesuai dengan filter.
            </div>
        <?php endif; ?>

        <div class="pagination-bar">
            <div class="pagination-info">
                Menampilkan <?= esc($safe($startRow, '0')) ?> - <?= esc($safe($endRow, '0')) ?> dari <?= esc($safe($totalRows, '0')) ?> data
            </div>

            <div class="pagination-links">
                <?php if ((int) $page > 1): ?>
                    <a class="page-link" href="<?= $buildPageUrl((int) $page - 1) ?>">&laquo;</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= (int) $totalPages; $i++): ?>
                    <a class="page-link <?= $i === (int) $page ? 'active' : '' ?>" href="<?= $buildPageUrl($i) ?>">
                        <?= esc((string) $i) ?>
                    </a>
                <?php endfor; ?>

                <?php if ((int) $page < (int) $totalPages): ?>
                    <a class="page-link" href="<?= $buildPageUrl((int) $page + 1) ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

</div>

<style>
.table-detail-btn {
    height: 32px;
    padding: 0 12px;
    border: 1px solid #bfdbfe;
    border-radius: 10px;
    background: #eff6ff;
    color: #2563eb;
    font-size: 11px;
    font-weight: 900;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: .18s ease;
}

.table-detail-btn:hover {
    background: #dbeafe;
    transform: translateY(-1px);
}

.detail-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    padding: 18px;
    background: rgba(15,23,42,.46);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: .2s ease;
}

.detail-modal.show {
    opacity: 1;
    visibility: visible;
}

.detail-card {
    width: min(460px, 100%);
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 24px;
    padding: 20px;
    box-shadow: 0 24px 70px rgba(15,23,42,.24);
    transform: translateY(10px) scale(.98);
    transition: .2s ease;
}

.detail-modal.show .detail-card {
    transform: translateY(0) scale(1);
}

.detail-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 16px;
}

.detail-head h4 {
    margin: 0;
    color: #0f172a;
    font-size: 20px;
    font-weight: 950;
    letter-spacing: -.03em;
}

.detail-head p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 12px;
    line-height: 1.5;
}

.detail-close {
    width: 34px;
    height: 34px;
    border: 0;
    border-radius: 12px;
    background: #f1f5f9;
    color: #334155;
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
}

.detail-body {
    display: grid;
    gap: 10px;
}

.detail-item {
    padding: 12px 13px;
    border-radius: 15px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.detail-item span {
    display: block;
    margin-bottom: 5px;
    color: #64748b;
    font-size: 11px;
    font-weight: 900;
}

.detail-item strong {
    display: block;
    color: #0f172a;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
}

.detail-item.detail-note {
    background: #ffffff;
}

@media (max-width: 520px) {
    .detail-card {
        border-radius: 20px;
        padding: 16px;
    }

    .detail-head h4 {
        font-size: 18px;
    }

    .table-detail-btn {
        width: 100%;
    }
}
</style>

<div class="detail-modal" id="detailModal" aria-hidden="true">
    <div class="detail-card" role="dialog" aria-modal="true" aria-labelledby="detailTitle">
        <div class="detail-head">
            <div>
                <h4 id="detailTitle">Detail Keputusan Pembimbing</h4>
                <p>Informasi ringkas mengenai keputusan persetujuan pembimbing mahasiswa.</p>
            </div>

            <button type="button" class="detail-close" id="closeDetail" aria-label="Tutup detail">×</button>
        </div>

        <div class="detail-body" id="detailBody"></div>
    </div>
</div>

<script>
(function () {
    const modal = document.getElementById('detailModal');
    const body = document.getElementById('detailBody');
    const closeBtn = document.getElementById('closeDetail');

    if (!modal || !body) return;

    const escapeHtml = (value) => {
        return String(value ?? '-').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    };

    const openModal = (data) => {
        body.innerHTML = `
            <div class="detail-item"><span>Mahasiswa</span><strong>${escapeHtml(data.nama)}</strong></div>
            <div class="detail-item"><span>NIM</span><strong>${escapeHtml(data.nim)}</strong></div>
            <div class="detail-item"><span>Jenis Pembimbing</span><strong>${escapeHtml(data.jenis)}</strong></div>
            <div class="detail-item"><span>Status Keputusan</span><strong>${escapeHtml(data.status)}</strong></div>
            <div class="detail-item"><span>Tanggal Pengajuan</span><strong>${escapeHtml(data.pengajuan)}</strong></div>
            <div class="detail-item"><span>Tanggal Respon</span><strong>${escapeHtml(data.respon)}</strong></div>
            <div class="detail-item detail-note"><span>Catatan Keputusan</span><strong>${escapeHtml(data.catatan)}</strong></div>
        `;

        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
    };

    const closeModal = () => {
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
    };

    document.querySelectorAll('.table-detail-btn').forEach((button) => {
        button.addEventListener('click', () => {
            try {
                openModal(JSON.parse(button.dataset.detail || '{}'));
            } catch (error) {
                openModal({});
            }
        });
    });

    closeBtn?.addEventListener('click', closeModal);

    modal.addEventListener('click', (event) => {
        if (event.target === modal) closeModal();
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') closeModal();
    });
})();
</script>

<?= $this->endSection() ?>
