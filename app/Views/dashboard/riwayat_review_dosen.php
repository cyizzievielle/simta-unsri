<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$riwayat          = $riwayat ?? [];
$keyword          = $keyword ?? '';
$status           = $status ?? '';
$perPage          = $perPage ?? 10;
$page             = $page ?? 1;
$totalRows        = $totalRows ?? 0;
$totalPages       = $totalPages ?? 1;
$startRow         = $startRow ?? 0;
$endRow           = $endRow ?? 0;
$totalDisetujui   = $totalDisetujui ?? 0;
$totalTolakRevisi = $totalTolakRevisi ?? 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
};

$statusClass = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'badge-success',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        default     => 'badge-info',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'Disetujui',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        'direview'  => 'Direview',
        default     => $safe($status, '-'),
    };
};

$reviewDetailJson = static function (array $row) use ($safe, $statusLabel): string {
    $payload = [
        'mahasiswa' => $safe($row['nama_mahasiswa'] ?? '-'),
        'nim'       => $safe($row['nim'] ?? '-'),
        'judul'     => $safe($row['judul'] ?? '-'),
        'status'    => $statusLabel($row['status_review'] ?? '-'),
        'catatan'   => $safe($row['catatan'] ?? 'Tidak ada catatan.'),
        'waktu'     => $safe($row['created_at'] ?? '-'),
    ];

    $json = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

    return is_string($json) ? $json : '{}';
};

$queryParams = [
    'q'        => $keyword,
    'status'   => $status,
    'per_page' => $perPage,
];

$buildPageUrl = static function (int $targetPage) use ($queryParams): string {
    return base_url('/dosen/pengajuan-judul/riwayat?' . http_build_query(array_merge($queryParams, [
        'page' => $targetPage,
    ])));
};
?>

<div class="review-history-page">
    <section class="card-main review-history-card">
        <div class="page-head review-history-head">
            <div>
                <h3>Riwayat Review Judul</h3>
                <p>Telusuri hasil review judul tugas akhir berdasarkan mahasiswa, NIM, judul, atau status review.</p>
            </div>
        </div>

        <div class="review-summary-grid">
            <div class="review-summary-card">
                <span>Total Review</span>
                <strong><?= esc($safe($totalRows, '0')) ?></strong>
                <small>Sesuai hasil filter</small>
            </div>

            <div class="review-summary-card is-success">
                <span>Disetujui</span>
                <strong><?= esc($safe($totalDisetujui, '0')) ?></strong>
                <small>Judul telah disetujui</small>
            </div>

            <div class="review-summary-card is-warning">
                <span>Revisi / Ditolak</span>
                <strong><?= esc($safe($totalTolakRevisi, '0')) ?></strong>
                <small>Perlu tindak lanjut mahasiswa</small>
            </div>
        </div>

        <div class="filter-box review-filter">
            <form method="get" action="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>" class="filter-form review-filter-form">
                <div class="filter-field">
                    <label for="q">Cari Mahasiswa / NIM / Judul</label>
                    <input
                        type="text"
                        id="q"
                        name="q"
                        value="<?= esc($safe($keyword, '')) ?>"
                        placeholder="Masukkan kata kunci"
                    >
                </div>

                <div class="filter-field">
                    <label for="status">Status Review</label>
                    <select id="status" name="status">
                        <option value="">Semua</option>
                        <option value="direview" <?= $status === 'direview' ? 'selected' : '' ?>>Direview</option>
                        <option value="disetujui" <?= $status === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                        <option value="revisi" <?= $status === 'revisi' ? 'selected' : '' ?>>Revisi</option>
                        <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label for="per_page">Jumlah Data</label>
                    <select id="per_page" name="per_page">
                        <option value="10" <?= (int) $perPage === 10 ? 'selected' : '' ?>>10</option>
                        <option value="50" <?= (int) $perPage === 50 ? 'selected' : '' ?>>50</option>
                        <option value="100" <?= (int) $perPage === 100 ? 'selected' : '' ?>>100</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter btn-apply">Terapkan</button>
                    <a href="<?= base_url('/dosen/pengajuan-judul/riwayat') ?>" class="btn-filter btn-reset">Reset</a>
                </div>
            </form>
        </div>

        <?php if (! empty($riwayat) && is_array($riwayat)): ?>
            <div class="table-wrap review-table-wrap">
                <table class="admin-table review-table">
                    <thead>
                        <tr>
                            <th>Mahasiswa</th>
                            <th>NIM</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Waktu Review</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($riwayat as $row): ?>
                            <?php
                                $catatanText = $safe(($row['catatan'] ?? '') !== '' ? $row['catatan'] : '-', '-');
                                $detailJson = $reviewDetailJson($row);
                            ?>

                            <tr>
                                <td>
                                    <strong class="review-student-name">
                                        <?= esc($safe($row['nama_mahasiswa'] ?? '-')) ?>
                                    </strong>
                                </td>

                                <td>
                                    <span class="review-nim">
                                        <?= esc($safe($row['nim'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="review-title-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($statusClass($row['status_review'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status_review'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="review-note-cell">
                                        <?= esc($catatanText) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="review-date">
                                        <?= esc($safe($row['created_at'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="table-action-icons">
                                        <button
                                            type="button"
                                            class="table-icon-btn btn-view review-detail-btn"
                                            title="Lihat Detail"
                                            data-detail="<?= esc($detailJson, 'attr') ?>"
                                        >
                                            <i class="ri-eye-line"></i>
                                            <span class="sr-only">Lihat Detail</span>
                                        </button>

                                        <a
                                            href="<?= base_url('/dosen/pengajuan-judul/review/edit/' . $safe($row['id'] ?? '')) ?>"
                                            class="table-icon-btn btn-edit"
                                            title="Edit Review"
                                        >
                                            <i class="ri-edit-2-line"></i>
                                            <span class="sr-only">Edit Review</span>
                                        </a>

                                        <form
                                            action="<?= base_url('/dosen/pengajuan-judul/review/delete/' . $safe($row['id'] ?? '')) ?>"
                                            method="post"
                                            onsubmit="return confirm('Hapus data review ini?');"
                                        >
                                            <?= csrf_field() ?>
                                            <button type="submit" class="table-icon-btn btn-delete" title="Hapus Review">
                                                <i class="ri-delete-bin-6-line"></i>
                                                <span class="sr-only">Hapus Review</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada riwayat review judul yang sesuai dengan filter.
            </div>
        <?php endif; ?>

        <div class="pagination-bar review-pagination">
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

<script>
document.querySelectorAll('.review-detail-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
        const modal = document.getElementById('reviewDetailModal');
        const body = document.getElementById('reviewDetailBody');

        if (!modal || !body) return;

        let data = {};

        try {
            data = JSON.parse(btn.dataset.detail || '{}');
        } catch (error) {
            data = {};
        }

        body.innerHTML = `
            <div class="detail-item"><span>Mahasiswa</span><strong>${data.mahasiswa || '-'}</strong></div>
            <div class="detail-item"><span>NIM</span><strong>${data.nim || '-'}</strong></div>
            <div class="detail-item"><span>Judul</span><strong>${data.judul || '-'}</strong></div>
            <div class="detail-item"><span>Status Review</span><strong>${data.status || '-'}</strong></div>
            <div class="detail-item"><span>Waktu Review</span><strong>${data.waktu || '-'}</strong></div>
            <div class="detail-item"><span>Catatan</span><strong>${data.catatan || '-'}</strong></div>
        `;

        modal.classList.add('show');
    });
});

const reviewDetailModal = document.getElementById('reviewDetailModal');
const closeReviewDetail = document.getElementById('closeReviewDetail');

closeReviewDetail?.addEventListener('click', () => {
    reviewDetailModal?.classList.remove('show');
});

reviewDetailModal?.addEventListener('click', (event) => {
    if (event.target === reviewDetailModal) {
        reviewDetailModal.classList.remove('show');
    }
});
</script>

<?= $this->endSection() ?>
