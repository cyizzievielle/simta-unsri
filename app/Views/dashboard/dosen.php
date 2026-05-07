<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
function safeText(mixed $value, string $default = '-'): string
{
    if ($value === null || $value === '') {
        return $default;
    }

    if (is_array($value)) {
        return implode(', ', array_map('strval', $value));
    }

    return (string) $value;
}

$namaDosen           = $dosen['nama'] ?? session()->get('name') ?? 'Dosen';
$totalBimbingan      = $totalBimbingan ?? 0;
$totalPermohonan     = $totalPermohonan ?? 0;
$totalJudul          = $totalJudul ?? 0;
$totalProposal       = $totalProposal ?? 0;
$aktivitas           = $aktivitas ?? [];
$mahasiswaBimbingan  = $mahasiswaBimbingan ?? [];
?>

<div class="dosen-dashboard-page">

    <section class="dosen-hero">
        <div>
            <span class="dosen-kicker">Dashboard Dosen</span>
            <h2>Halo, <?= esc(safeText($namaDosen, 'Dosen')) ?> 🎓</h2>
            <p>
                Kelola mahasiswa bimbingan, tinjau permohonan, review judul,
                pantau proposal, dan berikan keputusan akademik dari satu dashboard.
            </p>
        </div>
    </section>

    <section class="stat-grid dosen-stat-grid">
        <div class="stat-card stat-blue">
            <div class="stat-top">
                <div class="stat-label">Bimbingan</div>
                <div class="stat-icon">MB</div>
            </div>
            <div class="stat-number"><?= esc(safeText($totalBimbingan, '0')) ?></div>
            <div class="stat-desc">Mahasiswa aktif dibimbing</div>
        </div>

        <div class="stat-card stat-amber">
            <div class="stat-top">
                <div class="stat-label">Permohonan</div>
                <div class="stat-icon">PM</div>
            </div>
            <div class="stat-number"><?= esc(safeText($totalPermohonan, '0')) ?></div>
            <div class="stat-desc">Menunggu keputusan</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-top">
                <div class="stat-label">Judul</div>
                <div class="stat-icon">TA</div>
            </div>
            <div class="stat-number"><?= esc(safeText($totalJudul, '0')) ?></div>
            <div class="stat-desc">Masuk proses review</div>
        </div>

        <div class="stat-card stat-purple">
            <div class="stat-top">
                <div class="stat-label">Proposal</div>
                <div class="stat-icon">PR</div>
            </div>
            <div class="stat-number"><?= esc(safeText($totalProposal, '0')) ?></div>
            <div class="stat-desc">Sedang dipantau</div>
        </div>
    </section>

    <section class="dosen-grid">
        <div class="card-main">
            <div class="page-head">
                <div>
                    <h3>Aktivitas Terbaru</h3>
                    <p>Update terbaru dari mahasiswa bimbingan dan proses review.</p>
                </div>
                <span class="badge badge-info">Realtime</span>
            </div>

            <div class="dosen-timeline">
                <?php if (! empty($aktivitas) && is_array($aktivitas)): ?>
                    <?php foreach ($aktivitas as $row): ?>
                        <div class="dosen-timeline-item">
                            <div class="timeline-dot">
                               <i class="ri-history-line"></i>
                            </div>
                            <div>
                                <strong><?= esc(safeText($row['nama_mahasiswa'] ?? '-')) ?></strong>
                                <p>
                                    <?= esc(safeText($row['keterangan'] ?? '-')) ?><br>
                                    <small><?= esc(safeText($row['tanggal'] ?? '-')) ?></small>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-box">Belum ada aktivitas terbaru.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-main">
            <div class="page-head">
                <div>
                    <h3>Mahasiswa Bimbingan</h3>
                    <p>Daftar mahasiswa yang sedang kamu bimbing.</p>
                </div>
            </div>

            <div class="student-list">
                <?php if (! empty($mahasiswaBimbingan) && is_array($mahasiswaBimbingan)): ?>
                    <?php foreach ($mahasiswaBimbingan as $mhs): ?>

                    <?php
                    $namaRaw = $mhs['nama'] ?? 'Mahasiswa';
                    $nama = is_array($namaRaw) ? 'Mahasiswa' : (string) $namaRaw;

                    $nimRaw = $mhs['nim'] ?? '-';
                    $nim = is_array($nimRaw) ? '-' : (string) $nimRaw;

                    $fotoRaw = $mhs['foto'] ?? '';
                    $fotoMhs = is_array($fotoRaw) ? '' : trim((string) $fotoRaw);

                    $fotoMhsUrl = null;

                    if ($fotoMhs !== '' && file_exists(FCPATH . 'uploads/profile/' . $fotoMhs)) {
                        $fotoMhsUrl = base_url('uploads/profile/' . $fotoMhs);
                    }

                    $initial = strtoupper(substr($nama, 0, 1));
                    ?>

                    <div class="student-card">
                        <div class="student-avatar">
                            <?php if ($fotoMhsUrl): ?>
                                <img src="<?= esc($fotoMhsUrl) ?>" alt="<?= esc($nama) ?>">
                            <?php else: ?>
                                <span><?= esc($initial) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="student-info">
                            <strong><?= esc($nama) ?></strong>
                            <small>NIM: <?= esc($nim) ?></small>
                        </div>

                    </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-box">Belum ada mahasiswa bimbingan.</div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Aksi Cepat</h3>
                <p>Akses cepat ke modul utama dosen.</p>
            </div>
        </div>

        <div class="dosen-quick-grid">
            <a href="<?= base_url('/dosen/permohonan') ?>" class="quick-card">
                <div class="quick-icon">1</div>
                <div class="quick-title">Permohonan</div>
                <div class="quick-desc">Setujui atau tolak permohonan pembimbing.</div>
            </a>

            <a href="<?= base_url('/dosen/pengajuan-judul') ?>" class="quick-card">
                <div class="quick-icon">2</div>
                <div class="quick-title">Review Judul</div>
                <div class="quick-desc">Tinjau judul mahasiswa bimbingan.</div>
            </a>

            <a href="<?= base_url('/dosen/proposal-ta') ?>" class="quick-card">
                <div class="quick-icon">3</div>
                <div class="quick-title">Review Proposal</div>
                <div class="quick-desc">Pantau proposal dan beri keputusan.</div>
            </a>

            <a href="<?= base_url('/profile') ?>" class="quick-card">
                <div class="quick-icon">4</div>
                <div class="quick-title">Profil Saya</div>
                <div class="quick-desc">Perbarui data kontak dan bidang keahlian.</div>
            </a>
        </div>
    </section>

    <section class="card-main">
        <div class="page-head">
            <div>
                <h3>Alur Kerja Dosen</h3>
                <p>Tahapan utama dalam proses bimbingan tugas akhir.</p>
            </div>
        </div>

        <div class="workflow-grid">
            <div class="workflow-card">
                <div class="workflow-step">A</div>
                <h4>Tinjau Permohonan</h4>
                <p>Cek mahasiswa yang mengajukan Anda sebagai pembimbing 1 atau pembimbing 2.</p>
            </div>

            <div class="workflow-card">
                <div class="workflow-step">B</div>
                <h4>Review Judul</h4>
                <p>Berikan keputusan disetujui, revisi, atau ditolak beserta catatan.</p>
            </div>

            <div class="workflow-card">
                <div class="workflow-step">C</div>
                <h4>Review Proposal</h4>
                <p>Pantau proposal mahasiswa dan simpan hasil review final.</p>
            </div>
        </div>
    </section>

</div>

<?= $this->endSection() ?>