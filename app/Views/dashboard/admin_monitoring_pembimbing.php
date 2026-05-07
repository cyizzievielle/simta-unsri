<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$rows      = $rows ?? [];
$summary   = $summary ?? [];
$keyword   = $keyword ?? '';
$status    = $status ?? '';
$jenis     = $jenis ?? '';
$perPage   = $perPage ?? 10;


$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};?>

    <!-- ================= STAT ================= -->
    <div class="stat-grid">

        <div class="stat-card stat-blue">
            <div class="stat-label">Total Data</div>
            <div class="stat-value"><?= esc($summary['total'] ?? 0) ?></div>
            <div class="stat-desc">Semua permohonan</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Menunggu</div>
            <div class="stat-value"><?= esc($summary['menunggu'] ?? 0) ?></div>
            <div class="stat-desc">Belum diproses</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Disetujui</div>
            <div class="stat-value"><?= esc($summary['disetujui'] ?? 0) ?></div>
            <div class="stat-desc">Permohonan telah disetujui</div>
        </div>

        <div class="stat-card stat-rose">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value"><?= esc($summary['ditolak'] ?? 0) ?></div>
            <div class="stat-desc">Permohonan yang ditolak</div>
        </div>

    </div>

    <!-- ================= MAIN ================= -->
    <div class="card-main">

        <!-- HEADER -->
        <div class="page-head">
            <div>
                <h3>Monitoring Pembimbing</h3>
                <p>Kelola dan pantau permohonan pembimbing mahasiswa</p>
            </div>
        </div>

        <!-- ================= FILTER ================= -->
        <div class="filter-box">
            <form method="get" class="filter-form">

                <div class="filter-field">
                    <label>Cari</label>
                    <input type="text" name="q" value="<?= esc($keyword) ?>" placeholder="Nama / NIM">
                </div>

                <div class="filter-field">
                    <label>Status</label>
                    <select name="status">
                        <option value="">Semua</option>
                        <option value="menunggu" <?= $status=='menunggu'?'selected':'' ?>>Menunggu</option>
                        <option value="disetujui" <?= $status=='disetujui'?'selected':'' ?>>Disetujui</option>
                        <option value="ditolak" <?= $status=='ditolak'?'selected':'' ?>>Ditolak</option>
                        <option value="kuota_penuh" <?= $status=='kuota_penuh'?'selected':'' ?>>Kuota</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label>Jenis</label>
                    <select name="jenis">
                        <option value="">Semua</option>
                        <option value="1" <?= $jenis=='1'?'selected':'' ?>>Pembimbing 1</option>
                        <option value="2" <?= $jenis=='2'?'selected':'' ?>>Pembimbing 2</option>
                    </select>
                </div>

                <div class="filter-field">
                    <label>Tampil</label>
                    <select name="per_page">
                        <option value="10" <?= $perPage==10?'selected':'' ?>>10</option>
                        <option value="25" <?= $perPage==25?'selected':'' ?>>25</option>
                        <option value="50" <?= $perPage==50?'selected':'' ?>>50</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button class="btn-filter btn-apply">Terapkan</button>
                    <a href="<?= base_url('/admin/monitoring-pembimbing') ?>" class="btn-filter btn-reset">Reset</a>
                </div>

            </form>
        </div>

        <!-- ================= TABLE ================= -->
        <div class="table-wrap admin-monitoring-table-wrap">
    <table class="admin-table admin-monitoring-table">

                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Dosen</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Respon</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $r): ?>

                        <tr>
                            <td>
                                <b><?= esc($safe($r['nama_mahasiswa'] ?? '-')) ?></b><br>
                                <small class="muted"><?= esc($safe($r['nim'] ?? '-')) ?></small>
                            </td>

                            <td>
                                <b><?= esc($safe($r['nama_dosen'] ?? '-')) ?></b>
                            </td>
                            <td>
                                <span class="badge badge-info">
                                    <?php
                                        $jenisPembimbing = (string) ($r['jenis_pembimbing'] ?? '');

                                        $jenisLabel = in_array($jenisPembimbing, ['2', 'pembimbing_2'], true)
                                            ? 'P2'
                                            : 'P1';
                                        ?>

                                        <span class="badge badge-info">
                                            <?= esc($jenisLabel) ?>
                                        </span>
                                </span>
                            </td>

                            <td>
                            <?php
                            $st = $safe($r['status'] ?? '-');
                            $class = match ($st) {
                                'disetujui' => 'badge-success',
                                'ditolak' => 'badge-danger',
                                'menunggu' => 'badge-warning',
                                default => 'badge-muted'
                            };
                            ?>

                            <span class="badge <?= esc($class) ?>">
                                <?= esc($st) ?>
                            </span>
                            </td>

                                <td><?= esc($safe($r['tanggal_pengajuan'] ?? '-')) ?></td>
                                <td><?= esc($safe($r['tanggal_respon'] ?? '-')) ?></td>

                            <td>
                                <?php $catatan = $safe($r['catatan'] ?? '', ''); ?>

                                <?php if ($catatan !== ''): ?>
                                    <button
                                        type="button"
                                        class="icon-btn icon-open"
                                        onclick="showNote(this)"
                                        data-note="<?= esc($catatan) ?>"
                                    >
                                        👁
                                    </button>
                                <?php else: ?>
                                    <span class="muted">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px;">
                            Tidak ada data
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>
</div>

<!-- ================= POPUP CATATAN ================= -->
<div id="noteModal" class="note-modal-overlay">
    <div class="note-modal">
        <h3>Catatan Permohonan</h3>
        <p id="noteText"></p>

        <button
            type="button"
            onclick="closeNote()"
            class="note-modal-close"
        >
            Tutup
        </button>
    </div>
</div>

<script>
function showNote(button) {
    const modal = document.getElementById('noteModal');
    const text = document.getElementById('noteText');

    if (!modal || !text) {
        return;
    }

    text.innerText = button.dataset.note || '-';
    modal.classList.add('show');
}

function closeNote() {
    const modal = document.getElementById('noteModal');

    if (modal) {
        modal.classList.remove('show');
    }
}
</script>
<?= $this->endSection() ?>