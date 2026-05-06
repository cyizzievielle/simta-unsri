<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$nama = session()->get('name') ?? ($mahasiswa['nama'] ?? 'Mahasiswa');

$jumlahPembimbing = $jumlahPembimbing ?? ($totalPembimbing ?? 0);
$jumlahJudul      = $jumlahJudul ?? ($totalJudul ?? 0);
$jumlahProposal   = $jumlahProposal ?? ($totalProposal ?? 0);
$jumlahSk         = $jumlahSk ?? ($totalSk ?? 0);

$judulAktif       = $judulAktif ?? null;
$proposalAktif    = $proposalAktif ?? null;
$skTerbaru        = $skTerbaru ?? null;

$statusJudul      = $judulAktif['status'] ?? null;
$statusProposal   = $proposalAktif['status'] ?? null;

$pembimbingDone = (int) $jumlahPembimbing >= 2;
$judulDone      = $statusJudul === 'disetujui';
$proposalDone   = $statusProposal === 'disetujui';
$skDone         = ! empty($skTerbaru) || (int) $jumlahSk > 0;

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    if (is_array($value)) return implode(', ', array_map('strval', $value));
    return (string) $value;
};

$badgeStatus = static function (mixed $status) use ($safe): string {
    return match ($safe($status, '')) {
        'disetujui' => 'badge-success',
        'revisi' => 'badge-warning',
        'ditolak' => 'badge-danger',
        'diajukan', 'direview', 'menunggu' => 'badge-info',
        default => 'badge-muted',
    };
};

$statusText = static function (mixed $value, string $fallback = 'Belum ada data') use ($safe): string {
    return $safe($value, $fallback);
};
?>


<div class="mhs-page">

    <section class="mhs-hero">
        <div class="mhs-kicker">Sistem Tugas Akhir Mahasiswa</div>
        <h2>Halo, <?= esc($safe($nama)) ?> 👋</h2>
        <p>Pantau perkembangan tugas akhir kamu dari satu halaman: pembimbing, judul, proposal, sampai Surat Keputusan.</p>

        <div class="mhs-actions">
            <a href="<?= base_url('/pembimbing') ?>">Ajukan Pembimbing</a>
            <a href="<?= base_url('/pengajuan-judul') ?>">Ajukan Judul</a>
            <a href="<?= base_url('/proposal-ta') ?>">Upload Proposal</a>
        </div>
    </section>

    <section class="stat-grid mhs-stat-grid">
        <div class="stat-card stat-blue">
            <div class="stat-label">Pembimbing Aktif</div>
            <div class="stat-value"><?= esc($safe($jumlahPembimbing, '0')) ?></div>
            <div class="stat-desc">Target: pembimbing 1 dan pembimbing 2</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-label">Pengajuan Judul</div>
            <div class="stat-value"><?= esc($safe($jumlahJudul, '0')) ?></div>
            <div class="stat-desc">Total judul yang pernah kamu ajukan</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-label">Proposal TA</div>
            <div class="stat-value"><?= esc($safe($jumlahProposal, '0')) ?></div>
            <div class="stat-desc">Proposal yang sudah kamu unggah</div>
        </div>

        <div class="stat-card stat-purple">
            <div class="stat-label">Surat Keputusan</div>
            <div class="stat-value"><?= esc($safe($jumlahSk, '0')) ?></div>
            <div class="stat-desc">SK yang sudah diterbitkan admin</div>
        </div>
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Progress Tugas Akhir</h3>
                <p>Tahapan utama yang perlu diselesaikan sampai SK terbit.</p>
            </div>
        </div>

        <div class="progress-list">

            <div class="progress-row <?= $pembimbingDone ? 'done' : ((int) $jumlahPembimbing > 0 ? 'process' : '') ?>">
                <div class="progress-check">✓</div>
                <div class="progress-body">
                    <div class="progress-title">Pembimbing</div>
                    <div class="progress-desc">
                        <?= $pembimbingDone ? 'Pembimbing 1 dan 2 sudah aktif.' : ((int) $jumlahPembimbing > 0 ? 'Sebagian pembimbing sudah aktif.' : 'Belum mengajukan pembimbing.') ?>
                    </div>
                </div>
                <span class="progress-pill"><?= $pembimbingDone ? 'Selesai' : 'Proses' ?></span>
            </div>

            <div class="progress-row <?= $judulDone ? 'done' : (! empty($statusJudul) ? 'process' : '') ?>">
                <div class="progress-check">✓</div>
                <div class="progress-body">
                    <div class="progress-title">Judul TA</div>
                    <div class="progress-desc">
                        <?= $judulDone ? 'Judul tugas akhir sudah disetujui.' : (! empty($statusJudul) ? 'Judul sedang dalam proses review.' : 'Belum ada judul aktif.') ?>
                    </div>
                </div>
                <span class="progress-pill"><?= $judulDone ? 'Selesai' : 'Proses' ?></span>
            </div>

            <div class="progress-row <?= $proposalDone ? 'done' : (! empty($statusProposal) ? 'process' : '') ?>">
                <div class="progress-check">✓</div>
                <div class="progress-body">
                    <div class="progress-title">Proposal</div>
                    <div class="progress-desc">
                        <?= $proposalDone ? 'Proposal sudah disetujui dosen.' : (! empty($statusProposal) ? 'Proposal sedang diproses.' : 'Belum upload proposal.') ?>
                    </div>
                </div>
                <span class="progress-pill"><?= $proposalDone ? 'Selesai' : 'Belum' ?></span>
            </div>

            <div class="progress-row <?= $skDone ? 'done' : '' ?>">
                <div class="progress-check">✓</div>
                <div class="progress-body">
                    <div class="progress-title">Surat Keputusan</div>
                    <div class="progress-desc">
                        <?= $skDone ? 'SK tugas akhir sudah tersedia.' : 'Menunggu SK diterbitkan admin.' ?>
                    </div>
                </div>
                <span class="progress-pill"><?= $skDone ? 'Selesai' : 'Belum' ?></span>
            </div>

        </div>

        </div>
    </section>

    <div class="mhs-grid">
        <section class="card-main">
            <div class="page-head">
                <div>
                    <h3>Status Terbaru</h3>
                    <p>Ringkasan kondisi terbaru proses tugas akhir kamu.</p>
                </div>
            </div>
<div class="status-grid status-grid-clean">
    <div class="status-card status-clean">
        <div class="status-label">Pembimbing Aktif</div>
        <div class="status-main">
            <strong><?= esc($safe($jumlahPembimbing, '0')) ?> Dosen</strong>
            <span class="badge <?= $pembimbingDone ? 'badge-success' : 'badge-warning' ?>">
                <?= $pembimbingDone ? 'Lengkap' : 'Belum lengkap' ?>
            </span>
        </div>
        <div class="status-hint">Pembimbing 1 & 2</div>
    </div>

    <div class="status-card status-clean">
        <div class="status-label">Status Judul</div>
        <div class="status-main">
            <span class="badge <?= esc($badgeStatus($statusJudul)) ?>">
                <?= esc(ucfirst(strtolower((string) $statusJudul))) ?>
            </span>
        </div>
        <div class="status-hint">Pengajuan judul terbaru</div>
    </div>

    <div class="status-card status-clean">
        <div class="status-label">Status Proposal</div>
        <div class="status-main">
            <span class="badge <?= esc($badgeStatus($statusProposal)) ?>">
                <?= esc(ucfirst(strtolower((string) $statusProposal))) ?>
            </span>
        </div>
        <div class="status-hint">Progress proposal TA</div>
    </div>

    <div class="status-card status-clean">
        <div class="status-label">Surat Keputusan</div>
        <div class="status-main">
            <span class="badge <?= $skDone ? 'badge-success' : 'badge-muted' ?>">
                <?= $skDone ? 'Sudah terbit' : 'Belum tersedia' ?>
            </span>
        </div>
        <div class="status-hint">Dokumen SK TA</div>
    </div>
</div>
        </section>

        <section class="card-main">
            <div class="page-head">
                <div>
                    <h3>Quick Actions</h3>
                    <p>Akses cepat ke modul utama mahasiswa.</p>
                </div>
            </div>

            <div class="quick-grid">
                <a href="<?= base_url('/pembimbing') ?>" class="quick-card">
                    <div class="quick-icon">1</div>
                    <div class="quick-title">Pembimbing</div>
                    <div class="quick-desc">Pilih dan pantau dosen pembimbing.</div>
                </a>

                <a href="<?= base_url('/pengajuan-judul') ?>" class="quick-card">
                    <div class="quick-icon">2</div>
                    <div class="quick-title">Pengajuan Judul</div>
                    <div class="quick-desc">Ajukan judul dan lihat review.</div>
                </a>

                <a href="<?= base_url('/proposal-ta') ?>" class="quick-card">
                    <div class="quick-icon">3</div>
                    <div class="quick-title">Proposal TA</div>
                    <div class="quick-desc">Upload proposal dan pantau status.</div>
                </a>

                <a href="<?= base_url('/surat-keputusan') ?>" class="quick-card">
                    <div class="quick-icon">4</div>
                    <div class="quick-title">Surat Keputusan</div>
                    <div class="quick-desc">Lihat file SK yang diterbitkan.</div>
                </a>
            </div>
        </section>
    </div>

    <div class="mhs-grid">
        <section class="card-main">
            <div class="page-head">
                <div>
                    <h3>Timeline Proses</h3>
                    <p>Urutan kerja yang bisa kamu ikuti.</p>
                </div>
            </div>

            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-dot">A</div>
                    <div>
                        <div class="timeline-title">Ajukan Pembimbing</div>
                        <div class="timeline-text">Pilih dosen pembimbing 1 dan pembimbing 2.</div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot">B</div>
                    <div>
                        <div class="timeline-title">Ajukan Judul</div>
                        <div class="timeline-text">Masukkan judul, topik, kata kunci, dan latar belakang.</div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot">C</div>
                    <div>
                        <div class="timeline-title">Upload Proposal</div>
                        <div class="timeline-text">Proposal bisa diunggah setelah judul disetujui.</div>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-dot">D</div>
                    <div>
                        <div class="timeline-title">Terima SK</div>
                        <div class="timeline-text">Admin menerbitkan SK setelah proposal disetujui.</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="card-main">
            <div class="page-head">
                <div>
                    <h3>Catatan Penting</h3>
                    <p>Hal yang perlu kamu perhatikan selama proses tugas akhir.</p>
                </div>
            </div>

            <div class="tips-box">
                <div class="tips-title">Tips agar proses lancar</div>
                <ul class="tips-list">
                    <li>Pastikan pembimbing 1 dan 2 sudah aktif sebelum mengajukan judul.</li>
                    <li>Gunakan judul yang spesifik supaya tidak terlalu mirip dengan judul sebelumnya.</li>
                    <li>Periksa catatan revisi dari dosen sebelum mengirim ulang pengajuan.</li>
                    <li>Upload proposal dengan file yang jelas dan sesuai format.</li>
                    <li>Cek menu Surat Keputusan secara berkala setelah proposal disetujui.</li>
                </ul>
            </div>
        </section>
    </div>

</div>

<?= $this->endSection() ?>