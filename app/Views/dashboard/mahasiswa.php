<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.dashboard-hero{background:linear-gradient(135deg,#0f1f46,#2563eb);color:#fff;border-radius:30px;padding:34px;margin-bottom:24px;box-shadow:0 20px 48px rgba(37,99,235,.20);position:relative;overflow:hidden}
.dashboard-hero:after{content:"";position:absolute;right:-60px;top:-70px;width:220px;height:220px;border-radius:999px;background:rgba(255,255,255,.12)}
.hero-content{position:relative;z-index:1}
.hero-kicker{display:inline-flex;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.14);color:#e0f2fe;font-size:13px;font-weight:800;margin-bottom:14px}
.dashboard-hero h2{margin:0 0 10px;font-size:34px;font-weight:900}
.dashboard-hero p{margin:0;max-width:850px;color:rgba(255,255,255,.88);font-size:16px;line-height:1.7}
.mini-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:20px}
.btn-soft{display:inline-flex;padding:12px 16px;border-radius:15px;border:1px solid #dbeafe;background:#eff6ff;color:#1d4ed8;font-weight:900;text-decoration:none}

.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px}
.stat-card{border-radius:24px;padding:24px;color:#fff;position:relative;overflow:hidden;box-shadow:0 16px 36px rgba(15,23,42,.10);min-height:150px}
.stat-card:after{content:"";position:absolute;right:-28px;bottom:-28px;width:115px;height:115px;border-radius:999px;background:rgba(255,255,255,.15)}
.stat-blue{background:linear-gradient(135deg,#2563eb,#1d4ed8)}
.stat-orange{background:linear-gradient(135deg,#f59e0b,#ea580c)}
.stat-green{background:linear-gradient(135deg,#10b981,#059669)}
.stat-purple{background:linear-gradient(135deg,#8b5cf6,#6d28d9)}
.stat-label,.stat-number,.stat-desc{position:relative;z-index:1}
.stat-label{font-size:14px;font-weight:800;margin-bottom:14px}
.stat-number{font-size:42px;font-weight:900;margin-bottom:8px}
.stat-desc{font-size:13px;line-height:1.5;opacity:.95}

.card-premium{background:#fff;border-radius:28px;padding:28px;border:1px solid #edf2f7;box-shadow:0 18px 42px rgba(15,23,42,.06);margin-bottom:24px}
.section-head{display:flex;justify-content:space-between;align-items:flex-start;gap:16px;flex-wrap:wrap;margin-bottom:22px}
.section-title{margin:0 0 8px;font-size:26px;font-weight:900;color:#0f172a}
.section-subtitle{margin:0;color:#64748b;font-size:15px;line-height:1.7}

.progress-steps{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}
.step-card{border-radius:22px;padding:18px;border:1px solid #e2e8f0;background:linear-gradient(135deg,#fff,#f8fafc)}
.step-card.done{border-color:#bbf7d0;background:linear-gradient(135deg,#ecfdf5,#f0fdf4)}
.step-card.process{border-color:#fde68a;background:linear-gradient(135deg,#fffbeb,#fff7ed)}
.step-number{width:36px;height:36px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:#e2e8f0;color:#475569;font-weight:900;margin-bottom:14px}
.step-card.done .step-number{background:#22c55e;color:#fff}
.step-card.process .step-number{background:#f59e0b;color:#fff}
.step-title{font-size:15px;font-weight:900;color:#0f172a;margin-bottom:8px}
.step-desc{color:#64748b;font-size:13px;line-height:1.55}

.dashboard-grid{display:grid;grid-template-columns:1.1fr .9fr;gap:22px;align-items:start;margin-bottom:24px}
.dashboard-grid-2{display:grid;grid-template-columns:.9fr 1.1fr;gap:22px;align-items:start}

.badge{display:inline-flex;align-items:center;padding:8px 12px;border-radius:999px;font-size:12px;font-weight:900}
.badge-blue{background:#dbeafe;color:#1d4ed8}
.badge-green{background:#dcfce7;color:#166534}
.badge-orange{background:#fef3c7;color:#92400e}
.badge-red{background:#fee2e2;color:#b91c1c}
.badge-gray{background:#e2e8f0;color:#475569}

.status-modern-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.status-modern-card{border:1px solid #e2e8f0;border-radius:22px;padding:18px;background:linear-gradient(135deg,#fff,#f8fbff)}
.status-modern-label{color:#64748b;font-size:14px;font-weight:900;margin-bottom:12px}
.status-modern-main{display:flex;align-items:center;gap:10px;flex-wrap:wrap;color:#0f172a;font-size:16px}
.status-modern-main strong{font-size:22px;font-weight:900}
.status-modern-desc{margin-top:12px;padding-top:12px;border-top:1px solid #eef2f7;color:#0f172a;font-size:14px;font-weight:800;line-height:1.55}

.quick-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.quick-card{display:block;padding:20px;border-radius:22px;background:linear-gradient(135deg,#f8fbff,#eff6ff);border:1px solid #dbeafe;transition:.2s ease;min-height:132px;text-decoration:none}
.quick-card:hover{transform:translateY(-2px);box-shadow:0 16px 32px rgba(37,99,235,.12)}
.quick-icon{width:42px;height:42px;border-radius:16px;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900;margin-bottom:14px}
.quick-title{font-size:17px;font-weight:900;color:#0f172a;margin-bottom:8px}
.quick-desc{color:#64748b;font-size:14px;line-height:1.55}

.timeline{display:flex;flex-direction:column;gap:14px}
.timeline-item{display:grid;grid-template-columns:42px 1fr;gap:14px;align-items:start;padding:16px;border-radius:20px;background:#f8fafc;border:1px solid #edf2f7}
.timeline-dot{width:42px;height:42px;border-radius:16px;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:900}
.timeline-title{font-weight:900;color:#0f172a;margin-bottom:5px}
.timeline-text{color:#64748b;font-size:14px;line-height:1.55}
.tips-box{border-radius:24px;padding:22px;background:linear-gradient(135deg,#fff7ed,#fffbeb);border:1px solid #fed7aa}
.tips-title{font-weight:900;color:#9a3412;margin-bottom:10px;font-size:18px}
.tips-list{margin:0;padding-left:20px;color:#7c2d12;line-height:1.8}

@media(max-width:1200px){
    .stat-grid,.progress-steps{grid-template-columns:repeat(2,1fr)}
    .dashboard-grid,.dashboard-grid-2{grid-template-columns:1fr}
}
@media(max-width:700px){
    .stat-grid,.progress-steps,.quick-grid,.status-modern-grid{grid-template-columns:1fr}
    .dashboard-hero h2{font-size:27px}
    .card-premium{padding:22px}
}
</style>

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

$badgeStatus = static function (?string $status): string {
    return match ($status) {
        'disetujui' => 'badge-green',
        'revisi' => 'badge-orange',
        'ditolak' => 'badge-red',
        'diajukan', 'direview', 'menunggu' => 'badge-blue',
        default => 'badge-gray',
    };
};

$statusText = static function ($value, $fallback = 'Belum ada data') {
    return ($value !== null && $value !== '') ? $value : $fallback;
};
?>

<div class="dashboard-hero">
    <div class="hero-content">
        <div class="hero-kicker">Sistem Tugas Akhir Mahasiswa</div>
        <h2>Halo, <?= esc((string) $nama) ?> 👋</h2>
        <p>Pantau perkembangan tugas akhir kamu dari satu halaman: pembimbing, judul, proposal, sampai Surat Keputusan.</p>

        <div class="mini-actions">
            <a href="<?= base_url('/pembimbing') ?>" class="btn-soft">Ajukan Pembimbing</a>
            <a href="<?= base_url('/pengajuan-judul') ?>" class="btn-soft">Ajukan Judul</a>
            <a href="<?= base_url('/proposal-ta') ?>" class="btn-soft">Upload Proposal</a>
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card stat-blue">
        <div class="stat-label">Pembimbing Aktif</div>
        <div class="stat-number"><?= esc((string) $jumlahPembimbing) ?></div>
        <div class="stat-desc">Target: pembimbing 1 dan pembimbing 2</div>
    </div>

    <div class="stat-card stat-orange">
        <div class="stat-label">Pengajuan Judul</div>
        <div class="stat-number"><?= esc((string) $jumlahJudul) ?></div>
        <div class="stat-desc">Total judul yang pernah kamu ajukan</div>
    </div>

    <div class="stat-card stat-green">
        <div class="stat-label">Proposal TA</div>
        <div class="stat-number"><?= esc((string) $jumlahProposal) ?></div>
        <div class="stat-desc">Proposal yang sudah kamu unggah</div>
    </div>

    <div class="stat-card stat-purple">
        <div class="stat-label">Surat Keputusan</div>
        <div class="stat-number"><?= esc((string) $jumlahSk) ?></div>
        <div class="stat-desc">SK yang sudah diterbitkan admin</div>
    </div>
</div>

<div class="card-premium">
    <div class="section-head">
        <div>
            <h3 class="section-title">Progress Tugas Akhir</h3>
            <p class="section-subtitle">Tahapan utama yang perlu diselesaikan sampai SK terbit.</p>
        </div>
    </div>

    <div class="progress-steps">
        <div class="step-card <?= $pembimbingDone ? 'done' : ((int) $jumlahPembimbing > 0 ? 'process' : '') ?>">
            <div class="step-number">1</div>
            <div class="step-title">Pembimbing</div>
            <div class="step-desc"><?= $pembimbingDone ? 'Pembimbing lengkap.' : ((int) $jumlahPembimbing > 0 ? 'Sebagian pembimbing sudah aktif.' : 'Ajukan pembimbing terlebih dahulu.') ?></div>
        </div>

        <div class="step-card <?= $judulDone ? 'done' : (! empty($statusJudul) ? 'process' : '') ?>">
            <div class="step-number">2</div>
            <div class="step-title">Judul TA</div>
            <div class="step-desc"><?= $judulDone ? 'Judul sudah disetujui.' : (! empty($statusJudul) ? 'Judul sedang diproses/review.' : 'Ajukan judul setelah pembimbing tersedia.') ?></div>
        </div>

        <div class="step-card <?= $proposalDone ? 'done' : (! empty($statusProposal) ? 'process' : '') ?>">
            <div class="step-number">3</div>
            <div class="step-title">Proposal</div>
            <div class="step-desc"><?= $proposalDone ? 'Proposal sudah disetujui.' : (! empty($statusProposal) ? 'Proposal masih dalam proses.' : 'Upload proposal setelah judul disetujui.') ?></div>
        </div>

        <div class="step-card <?= $skDone ? 'done' : '' ?>">
            <div class="step-number">4</div>
            <div class="step-title">SK Terbit</div>
            <div class="step-desc"><?= $skDone ? 'SK sudah tersedia.' : 'Menunggu admin menerbitkan SK.' ?></div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Status Terbaru</h3>
                <p class="section-subtitle">Ringkasan kondisi terbaru proses tugas akhir kamu.</p>
            </div>
        </div>

        <div class="status-modern-grid">
            <div class="status-modern-card">
                <div class="status-modern-label">Pembimbing Aktif</div>
                <div class="status-modern-main">
                    <strong><?= esc((string) $jumlahPembimbing) ?> dosen</strong>
                    <span class="badge <?= $pembimbingDone ? 'badge-green' : 'badge-orange' ?>">
                        <?= $pembimbingDone ? 'Lengkap' : 'Belum lengkap' ?>
                    </span>
                </div>
            </div>

            <div class="status-modern-card">
                <div class="status-modern-label">Status Judul</div>
                <div class="status-modern-main">
                    <span class="badge <?= $badgeStatus($statusJudul) ?>">
                        <?= esc((string) $statusText($statusJudul)) ?>
                    </span>
                </div>

                <?php if (! empty($judulAktif['judul'])): ?>
                    <div class="status-modern-desc">
                        <?= esc((string) $judulAktif['judul']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="status-modern-card">
                <div class="status-modern-label">Status Proposal</div>
                <div class="status-modern-main">
                    <span class="badge <?= $badgeStatus($statusProposal) ?>">
                        <?= esc((string) $statusText($statusProposal)) ?>
                    </span>
                </div>
            </div>

            <div class="status-modern-card">
                <div class="status-modern-label">Surat Keputusan</div>
                <div class="status-modern-main">
                    <span class="badge <?= $skDone ? 'badge-green' : 'badge-gray' ?>">
                        <?= $skDone ? 'Sudah terbit' : 'Belum tersedia' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Quick Actions</h3>
                <p class="section-subtitle">Akses cepat ke modul utama mahasiswa.</p>
            </div>
        </div>

        <div class="quick-grid">
            <a href="<?= base_url('/pembimbing') ?>" class="quick-card">
                <div class="quick-icon">1</div>
                <div class="quick-title">Pembimbing</div>
                <div class="quick-desc">Pilih dan pantau dosen pembimbing 1 atau 2.</div>
            </a>

            <a href="<?= base_url('/pengajuan-judul') ?>" class="quick-card">
                <div class="quick-icon">2</div>
                <div class="quick-title">Pengajuan Judul</div>
                <div class="quick-desc">Ajukan judul dan lihat hasil review dosen.</div>
            </a>

            <a href="<?= base_url('/proposal-ta') ?>" class="quick-card">
                <div class="quick-icon">3</div>
                <div class="quick-title">Proposal TA</div>
                <div class="quick-desc">Upload proposal dan pantau revisi atau persetujuan.</div>
            </a>

            <a href="<?= base_url('/surat-keputusan') ?>" class="quick-card">
                <div class="quick-icon">4</div>
                <div class="quick-title">Surat Keputusan</div>
                <div class="quick-desc">Lihat dan buka file SK yang sudah diterbitkan.</div>
            </a>
        </div>
    </div>
</div>

<div class="dashboard-grid-2">
    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Timeline Proses</h3>
                <p class="section-subtitle">Urutan kerja yang bisa kamu ikuti.</p>
            </div>
        </div>

        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-dot">A</div>
                <div>
                    <div class="timeline-title">Ajukan Pembimbing</div>
                    <div class="timeline-text">Pilih dosen pembimbing 1 dan pembimbing 2, lalu tunggu persetujuan.</div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">B</div>
                <div>
                    <div class="timeline-title">Ajukan Judul</div>
                    <div class="timeline-text">Masukkan judul, topik, kata kunci, dan latar belakang untuk direview.</div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">C</div>
                <div>
                    <div class="timeline-title">Upload Proposal</div>
                    <div class="timeline-text">Proposal bisa diunggah setelah judul tugas akhir disetujui.</div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-dot">D</div>
                <div>
                    <div class="timeline-title">Terima SK</div>
                    <div class="timeline-text">Admin menerbitkan SK setelah proposal selesai dan disetujui.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-premium">
        <div class="section-head">
            <div>
                <h3 class="section-title">Catatan Penting</h3>
                <p class="section-subtitle">Hal yang perlu kamu perhatikan selama proses tugas akhir.</p>
            </div>
        </div>

        <div class="tips-box">
            <div class="tips-title">Tips agar proses lancar</div>
            <ul class="tips-list">
                <li>Pastikan pembimbing 1 dan pembimbing 2 sudah aktif sebelum mengajukan judul.</li>
                <li>Gunakan judul yang spesifik supaya tidak terlalu mirip dengan judul sebelumnya.</li>
                <li>Periksa catatan revisi dari dosen sebelum mengirim ulang pengajuan.</li>
                <li>Upload proposal dengan file yang jelas dan sesuai format yang diminta.</li>
                <li>Cek menu Surat Keputusan secara berkala setelah proposal disetujui.</li>
            </ul>
        </div>
    </div>
</div>

<?= $this->endSection() ?>