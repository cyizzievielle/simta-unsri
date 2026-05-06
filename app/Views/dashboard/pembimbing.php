<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$pembimbingAktif = $pembimbingAktif ?? [];
$permohonan      = $permohonan ?? [];
$dosenList       = $dosenList ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match (strtolower($safe($status, ''))) {
        'disetujui'   => 'badge-success',
        'ditolak'     => 'badge-danger',
        'kuota_penuh' => 'badge-muted',
        default       => 'badge-warning',
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
?>

<div class="pembimbing-page">

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Pembimbing Aktif</h3>
                <p>Dosen pembimbing yang sudah disetujui dan aktif membimbing kamu.</p>
            </div>
        </div>

        <?php if (! empty($pembimbingAktif) && is_array($pembimbingAktif)): ?>
            <div class="pembimbing-grid">
                <?php foreach ($pembimbingAktif as $row): ?>
                    <?php $jenisPembimbing = $row['jenis_pembimbing'] ?? ''; ?>

                    <div class="dosen-card">
                        <div class="dosen-card-top">
                            <div>
                                <div class="dosen-name">
                                    <?= esc($safe($row['nama_dosen'] ?? $row['nama'] ?? '-')) ?>
                                </div>
                                <div class="dosen-small">
                                    NIDN: <?= esc($safe($row['nidn'] ?? '-')) ?>
                                </div>
                            </div>

                            <span class="badge <?= esc($badgeJenis($jenisPembimbing)) ?>">
                                <?= esc($labelJenis($jenisPembimbing)) ?>
                            </span>
                        </div>

                        <div class="dosen-info">
                            <div><strong>No. HP:</strong> <?= esc($safe($row['no_hp'] ?? '-')) ?></div>
                            <div><strong>Bidang:</strong> <?= esc($safe($row['bidang_keahlian'] ?? '-')) ?></div>
                            <div><strong>Status:</strong> <span class="badge badge-success">Aktif</span></div>
                            <div><strong>Tanggal Penetapan:</strong> <?= esc($safe($row['tanggal_penetapan'] ?? '-')) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-box">
                Belum ada pembimbing aktif. Ajukan pembimbing terlebih dahulu, lalu tunggu persetujuan dosen.
            </div>
        <?php endif; ?>
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Riwayat Permohonan Pembimbing</h3>
                <p>Pantau seluruh pengajuan pembimbing yang pernah kamu kirim.</p>
            </div>
        </div>

        <?php if (! empty($permohonan) && is_array($permohonan)): ?>
            <div class="table-wrap">
                <table class="admin-table">
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
                            <?php $catatan = $safe(($p['catatan'] ?? '') !== '' ? $p['catatan'] : '-', '-'); ?>

                            <tr>
                                <td>
                                    <strong><?= esc($safe($p['nama_dosen'] ?? '-')) ?></strong>
                                </td>

                                <td>
                                    <span class="badge <?= esc($badgeJenis($p['jenis_pembimbing'] ?? '')) ?>">
                                        <?= esc($labelJenis($p['jenis_pembimbing'] ?? '')) ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge <?= esc($badgeStatus($p['status'] ?? '')) ?>">
                                        <?= esc($safe($p['status'] ?? '-')) ?>
                                    </span>
                                </td>

                                <td>
                                    <?php if ($catatan !== '-'): ?>
                                        <button type="button" class="icon-btn icon-open" onclick="openNoteModal(this)" data-note="<?= esc($catatan) ?>">
                                            👁
                                        </button>
                                    <?php else: ?>
                                        <span class="muted">-</span>
                                    <?php endif; ?>
                                </td>

                                <td><?= esc($safe($p['tanggal_pengajuan'] ?? $p['created_at'] ?? '-')) ?></td>
                                <td><?= esc($safe(($p['tanggal_respon'] ?? '') !== '' ? $p['tanggal_respon'] : '-')) ?></td>
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
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Ajukan Pembimbing</h3>
                <p>Klik tombol untuk memilih dosen pembimbing 1 atau pembimbing 2.</p>
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
                            <option value="">-- Pilih Jenis --</option>
                            <option value="pembimbing_1">Pembimbing 1</option>
                            <option value="pembimbing_2">Pembimbing 2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Pilih Dosen</label>
                        <select name="dosen_id" class="input" required>
                            <option value="">-- Pilih Dosen --</option>

                            <?php foreach ($dosenList as $dosen): ?>
                                <option value="<?= esc($safe($dosen['id'] ?? '')) ?>">
                                    <?= esc($safe($dosen['nama_dosen'] ?? $dosen['nama'] ?? '-')) ?>
                                    <?php if (($dosen['nidn'] ?? '') !== ''): ?>
                                        - <?= esc($safe($dosen['nidn'])) ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                    <button type="button" class="btn btn-outline" onclick="toggleFormPembimbing()">Batal</button>
                </div>
            </form>
        </div>
    </section>

</div>

<div class="note-modal-overlay" id="noteModal" onclick="closeNoteModal(event)">
    <div class="note-modal">
        <h3>Catatan</h3>
        <p id="noteModalText">-</p>
        <button type="button" class="note-modal-close" onclick="closeNoteModal()">Tutup</button>
    </div>
</div>

<script>
function toggleFormPembimbing() {
    const form = document.getElementById('formPembimbing');
    if (!form) return;

    form.classList.toggle('show');
}

function openNoteModal(button) {
    const modal = document.getElementById('noteModal');
    const text = document.getElementById('noteModalText');

    if (!modal || !text) return;

    text.textContent = button.dataset.note || '-';
    modal.classList.add('show');
}

function closeNoteModal(event) {
    const modal = document.getElementById('noteModal');

    if (!modal) return;

    if (!event || event.target === modal) {
        modal.classList.remove('show');
    }
}
</script>

<?= $this->endSection() ?>