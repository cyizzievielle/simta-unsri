<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$pengajuanJudul = $pengajuanJudul
    ?? $pengajuan
    ?? $riwayat
    ?? $riwayatJudul
    ?? $judulList
    ?? $rows
    ?? [];

$bolehAjukanJudul = $bolehAjukanJudul ?? false;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui' => 'badge-success',
        'revisi'    => 'badge-warning',
        'ditolak'   => 'badge-danger',
        'diajukan',
        'direview'  => 'badge-info',
        default     => 'badge-muted',
    };
};

$statusLabel = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'diajukan'  => 'Diajukan',
        'direview'  => 'Direview',
        'disetujui' => 'Disetujui',
        'revisi'    => 'Revisi',
        'ditolak'   => 'Ditolak',
        default     => $safe($status, '-'),
    };
};
?>

<div class="judul-page">

    <section class="judul-step-grid">
        <div class="judul-step">
            <span>1</span>
            <strong>Pembimbing Disetujui</strong>
            <p>Minimal satu dosen pembimbing harus menyetujui permohonan bimbingan.</p>
        </div>

        <div class="judul-step">
            <span>2</span>
            <strong>Isi Data Judul</strong>
            <p>Masukkan judul, latar belakang, bidang topik, dan kata kunci.</p>
        </div>

        <div class="judul-step">
            <span>3</span>
            <strong>Review Dosen</strong>
            <p>Dosen pembimbing memberi keputusan dan catatan review.</p>
        </div>

        <div class="judul-step">
            <span>4</span>
            <strong>Keputusan</strong>
            <p>Judul dapat disetujui, ditolak, atau diminta revisi.</p>
        </div>
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Pengajuan Judul Tugas Akhir</h3>
                <p>Ajukan judul, pantau status review, dan lihat riwayat pengajuan kamu.</p>
            </div>

            <?php if ($bolehAjukanJudul): ?>
                <button type="button" class="btn btn-primary" onclick="toggleFormJudul()">
                    <i class="ri-add-line"></i>
                    Ajukan Judul
                </button>
            <?php endif; ?>
        </div>

        <?php if ($bolehAjukanJudul): ?>

            <div id="formJudul" class="form-panel">
                <form action="<?= base_url('/pengajuan-judul/simpan') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-grid form-grid-2">
                        <div class="form-group form-full">
                            <label for="judul">Judul Tugas Akhir</label>
                            <input
                                type="text"
                                id="judul"
                                name="judul"
                                class="input"
                                value="<?= esc($safe(old('judul'), '')) ?>"
                                placeholder="Masukkan judul tugas akhir"
                                required
                            >
                        </div>

                        <div class="form-group form-full">
                            <label for="latar_belakang">Latar Belakang</label>
                            <textarea
                                id="latar_belakang"
                                name="latar_belakang"
                                class="input textarea"
                                placeholder="Tuliskan latar belakang singkat"
                                required
                            ><?= esc($safe(old('latar_belakang'), '')) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="bidang_topik">Bidang Topik</label>
                            <input
                                type="text"
                                id="bidang_topik"
                                name="bidang_topik"
                                class="input"
                                value="<?= esc($safe(old('bidang_topik'), '')) ?>"
                                placeholder="Contoh: Web, Database, UI/UX"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="kata_kunci">Kata Kunci</label>
                            <input
                                type="text"
                                id="kata_kunci"
                                name="kata_kunci"
                                class="input"
                                value="<?= esc($safe(old('kata_kunci'), '')) ?>"
                                placeholder="Contoh: sistem informasi, website"
                                required
                            >
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-send-plane-line"></i>
                            Kirim Pengajuan
                        </button>

                        <button type="button" class="btn btn-outline" onclick="toggleFormJudul()">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

        <?php else: ?>

            <div class="proposal-waiting-card">
                <div class="waiting-icon">
                    <i class="ri-user-follow-line"></i>
                </div>

                <div class="waiting-content">
                    <h4>Menunggu Persetujuan Pembimbing</h4>

                    <p>
                        Pengajuan judul hanya dapat dilakukan setelah minimal satu dosen
                        pembimbing menyetujui permohonan bimbingan kamu.
                    </p>

                    <div class="waiting-badge">
                        <i class="ri-time-line"></i>
                        Belum Ada Pembimbing Disetujui
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Riwayat Pengajuan Judul</h3>
                <p>Semua pengajuan judul yang pernah kamu kirim akan tampil di sini.</p>
            </div>
        </div>

        <?php if (! empty($pengajuanJudul) && is_array($pengajuanJudul)): ?>
            <div class="table-wrap">
                <table class="admin-table judul-table">
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
                        <?php foreach ($pengajuanJudul as $row): ?>
                            <tr>
                                <td>
                                    <div class="title-cell">
                                        <?= esc($safe($row['judul'] ?? '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <?= esc($safe(($row['bidang_topik'] ?? '') !== '' ? $row['bidang_topik'] : '-')) ?>
                                </td>

                                <td>
                                    <div class="keyword-cell">
                                        <?= esc($safe(($row['kata_kunci'] ?? '') !== '' ? $row['kata_kunci'] : '-')) ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge <?= esc($badgeStatus($row['status'] ?? '')) ?>">
                                        <?= esc($statusLabel($row['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= esc($safe($row['tanggal_pengajuan'] ?? $row['created_at'] ?? '-')) ?>
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            href="<?= base_url('/pengajuan-judul/detail/' . $safe($row['id'] ?? '0')) ?>"
                                            class="icon-btn icon-open"
                                            title="Detail"
                                        >
                                            <i class="ri-eye-line"></i>
                                        </a>

                                        <?php if (($row['status'] ?? '') === 'revisi'): ?>
                                            <a
                                                href="<?= base_url('/pengajuan-judul/revisi/' . $safe($row['id'] ?? '0')) ?>"
                                                class="icon-btn icon-edit"
                                                title="Revisi"
                                            >
                                                <i class="ri-edit-2-line"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada riwayat pengajuan. Riwayat akan muncul setelah kamu mengirim pengajuan judul.
            </div>
        <?php endif; ?>
    </section>

</div>

<script>
function toggleFormJudul() {
    const form = document.getElementById('formJudul');
    if (!form) return;

    form.classList.toggle('show');
}
</script>

<?= $this->endSection() ?>