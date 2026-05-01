<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.dosen-hero {
    background: linear-gradient(135deg,#0f1f46,#2563eb);
    color:#fff;
    border-radius:30px;
    padding:34px;
    margin-bottom:24px;
    box-shadow:0 20px 48px rgba(37,99,235,.20);
}
.dosen-hero h2 {margin:0 0 10px;font-size:34px;font-weight:900}
.dosen-hero p {margin:0;color:rgba(255,255,255,.88);font-size:16px;line-height:1.7}

.stat-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:18px;
    margin-bottom:24px;
}
.stat-card {
    border-radius:24px;
    padding:24px;
    color:#fff;
    min-height:145px;
    position:relative;
    overflow:hidden;
    box-shadow:0 16px 36px rgba(15,23,42,.10);
}
.stat-card:after {
    content:"";
    position:absolute;
    right:-30px;
    bottom:-30px;
    width:115px;
    height:115px;
    border-radius:999px;
    background:rgba(255,255,255,.15);
}
.stat-blue{background:linear-gradient(135deg,#2563eb,#1d4ed8)}
.stat-orange{background:linear-gradient(135deg,#f59e0b,#ea580c)}
.stat-green{background:linear-gradient(135deg,#10b981,#059669)}
.stat-purple{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
.stat-label,.stat-number,.stat-desc{position:relative;z-index:1}
.stat-label{font-weight:800;margin-bottom:14px}
.stat-number{font-size:42px;font-weight:900;margin-bottom:8px}
.stat-desc{font-size:13px;opacity:.95;line-height:1.5}

.dash-grid {
    display:grid;
    grid-template-columns:1.1fr .9fr;
    gap:22px;
    align-items:start;
}
.card-premium {
    background:#fff;
    border-radius:28px;
    padding:28px;
    border:1px solid #edf2f7;
    box-shadow:0 18px 42px rgba(15,23,42,.06);
    margin-bottom:24px;
}
.section-title {
    margin:0 0 8px;
    font-size:26px;
    font-weight:900;
    color:#0f172a;
}
.section-subtitle {
    margin:0 0 22px;
    color:#64748b;
    font-size:15px;
    line-height:1.7;
}
.info-row {
    display:grid;
    grid-template-columns:190px 1fr;
    gap:14px;
    padding:15px 0;
    border-bottom:1px solid #eef2f7;
}
.info-row:last-child {border-bottom:none}
.info-label {color:#64748b;font-weight:800}
.info-value {color:#0f172a;font-weight:900}

.quick-grid {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:14px;
}
.quick-card {
    display:block;
    padding:20px;
    border-radius:22px;
    background:linear-gradient(135deg,#f8fbff,#eff6ff);
    border:1px solid #dbeafe;
    text-decoration:none;
    transition:.2s ease;
}
.quick-card:hover {
    transform:translateY(-2px);
    box-shadow:0 16px 32px rgba(37,99,235,.12);
}
.quick-icon {
    width:42px;
    height:42px;
    border-radius:16px;
    background:#2563eb;
    color:white;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
    margin-bottom:14px;
}
.quick-title {font-size:17px;font-weight:900;color:#0f172a;margin-bottom:8px}
.quick-desc {color:#64748b;font-size:14px;line-height:1.55}

.timeline {
    display:flex;
    flex-direction:column;
    gap:14px;
}
.timeline-item {
    display:grid;
    grid-template-columns:42px 1fr;
    gap:14px;
    padding:16px;
    border-radius:20px;
    background:#f8fafc;
    border:1px solid #edf2f7;
}
.timeline-dot {
    width:42px;
    height:42px;
    border-radius:16px;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:900;
}
.timeline-title {font-weight:900;color:#0f172a;margin-bottom:5px}
.timeline-text {color:#64748b;font-size:14px;line-height:1.55}

@media(max-width:1200px){
    .stat-grid{grid-template-columns:repeat(2,1fr)}
    .dash-grid{grid-template-columns:1fr}
}
@media(max-width:700px){
    .stat-grid,.quick-grid{grid-template-columns:1fr}
    .info-row{grid-template-columns:1fr;gap:6px}
    .dosen-hero h2{font-size:27px}
}
</style>

<?php
$namaDosen = $dosen['nama'] ?? session()->get('name') ?? 'Dosen';

$totalBimbingan = $totalBimbingan ?? 0;
$totalPermohonan = $totalPermohonan ?? 0;
$totalJudul = $totalJudul ?? 0;
$totalProposal = $totalProposal ?? 0;
?>

<div class="dosen-hero">
    <h2>Halo, <?= esc((string) $namaDosen) ?> 🎓</h2>
    <p>
        Kelola mahasiswa bimbingan, tinjau pengajuan judul, beri catatan revisi,
        dan pantau proposal tugas akhir dari satu dashboard dosen.
    </p>
</div>

<div class="stat-grid">
    <div class="stat-card stat-blue">
        <div class="stat-label">Mahasiswa Bimbingan</div>
        <div class="stat-number"><?= esc((string) $totalBimbingan) ?></div>
        <div class="stat-desc">Mahasiswa yang sudah aktif dibimbing</div>
    </div>

    <div class="stat-card stat-orange">
        <div class="stat-label">Permohonan Masuk</div>
        <div class="stat-number"><?= esc((string) $totalPermohonan) ?></div>
        <div class="stat-desc">Permohonan pembimbing yang perlu ditinjau</div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-label">Pengajuan Judul</div>
        <div class="stat-number"><?= esc((string) $totalJudul) ?></div>
        <div class="stat-desc">Judul mahasiswa yang masuk review</div>
    </div>

    <div class="stat-card stat-purple">
        <div class="stat-label">Proposal TA</div>
        <div class="stat-number"><?= esc((string) $totalProposal) ?></div>
        <div class="stat-desc">Proposal yang perlu dipantau/review</div>
    </div>
</div>

<div class="dash-grid">

    <!-- LEFT: Aktivitas -->
    <div class="card-premium">
        <h3 class="section-title">Aktivitas Terbaru</h3>
        <p class="section-subtitle">Update terbaru dari mahasiswa bimbingan.</p>

        <div class="timeline">

            <?php if (!empty($aktivitas)): ?>
                <?php foreach ($aktivitas as $row): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot">📌</div>
                        <div>
                            <div class="timeline-title">
                                <?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?>
                            </div>
                            <div class="timeline-text">
                                <?= esc((string) ($row['keterangan'] ?? '-')) ?><br>
                                <small><?= esc((string) ($row['tanggal'] ?? '-')) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="color:#64748b;">Belum ada aktivitas.</div>
            <?php endif; ?>

        </div>
    </div>

    <!-- RIGHT: Mahasiswa Bimbingan -->
    <div class="card-premium">
        <h3 class="section-title">Mahasiswa Bimbingan</h3>
        <p class="section-subtitle">Daftar mahasiswa yang sedang kamu bimbing.</p>

        <?php if (!empty($mahasiswaBimbingan)): ?>
            <?php foreach ($mahasiswaBimbingan as $mhs): ?>
                <div style="
                    padding:14px;
                    border:1px solid #e2e8f0;
                    border-radius:16px;
                    margin-bottom:10px;
                    background:#f8fafc;
                ">
                    <strong><?= esc((string) ($mhs['nama'] ?? '-')) ?></strong><br>
                    <small><?= esc((string) ($mhs['nim'] ?? '-')) ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="color:#64748b;">Belum ada mahasiswa bimbingan.</div>
        <?php endif; ?>

    </div>

</div>

    <div class="card-premium">
        <h3 class="section-title">Aksi Cepat</h3>
        <p class="section-subtitle">Akses cepat ke modul utama dosen.</p>

        <div class="quick-grid">
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
                <div class="quick-desc">Perbarui data kontak dan keahlian.</div>
            </a>
        </div>
    </div>
</div>

<div class="card-premium">
    <h3 class="section-title">Alur Kerja Dosen</h3>
    <p class="section-subtitle">Tahapan kerja utama dalam proses bimbingan tugas akhir.</p>

    <div class="timeline">
        <div class="timeline-item">
            <div class="timeline-dot">A</div>
            <div>
                <div class="timeline-title">Tinjau Permohonan Pembimbing</div>
                <div class="timeline-text">Cek mahasiswa yang mengajukan Anda sebagai pembimbing 1 atau pembimbing 2.</div>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-dot">B</div>
            <div>
                <div class="timeline-title">Review Judul Tugas Akhir</div>
                <div class="timeline-text">Berikan keputusan disetujui, revisi, atau ditolak beserta catatan.</div>
            </div>
        </div>

        <div class="timeline-item">
            <div class="timeline-dot">C</div>
            <div>
                <div class="timeline-title">Review Proposal</div>
                <div class="timeline-text">Pantau proposal mahasiswa dan simpan hasil review final.</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>