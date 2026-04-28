<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<style>
.mhs-hero {
    background: linear-gradient(135deg, #0f1f46, #2563eb);
    color: #fff;
    border-radius: 26px;
    padding: 28px;
    margin-bottom: 20px;
    box-shadow: 0 18px 42px rgba(37,99,235,.18);
    position: relative;
    overflow: hidden;
}

.mhs-hero::after {
    content: "";
    position: absolute;
    right: -55px;
    top: -65px;
    width: 190px;
    height: 190px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
}

.mhs-hero-content {
    position: relative;
    z-index: 1;
}

.mhs-kicker {
    display: inline-flex;
    padding: 7px 11px;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
    color: #e0f2fe;
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 12px;
}

.mhs-hero h2 {
    margin: 0 0 8px;
    font-size: 32px;
    font-weight: 900;
}

.mhs-hero p {
    margin: 0;
    max-width: 760px;
    color: rgba(255,255,255,.9);
    font-size: 14px;
    line-height: 1.6;
}

.mhs-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
}

.mhs-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 11px 14px;
    border-radius: 14px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.18);
    color: #fff;
    font-weight: 900;
    font-size: 13px;
    text-decoration: none;
}

.mhs-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.mhs-stat-card {
    border-radius: 22px;
    padding: 20px;
    color: #fff;
    min-height: 135px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 14px 32px rgba(15,23,42,.1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.mhs-stat-card::after {
    content: "";
    position: absolute;
    right: -26px;
    bottom: -26px;
    width: 105px;
    height: 105px;
    border-radius: 999px;
    background: rgba(255,255,255,.14);
}

.stat-blue { background: linear-gradient(135deg,#2563eb,#1d4ed8); }
.stat-orange { background: linear-gradient(135deg,#f59e0b,#ea580c); }
.stat-green { background: linear-gradient(135deg,#10b981,#059669); }
.stat-purple { background: linear-gradient(135deg,#8b5cf6,#6d28d9); }

.mhs-stat-label,
.mhs-stat-number,
.mhs-stat-desc {
    position: relative;
    z-index: 1;
}

.mhs-stat-label {
    font-size: 12px;
    font-weight: 900;
}

.mhs-stat-number {
    font-size: 36px;
    font-weight: 900;
    line-height: 1;
}

.mhs-stat-desc {
    font-size: 12px;
    line-height: 1.4;
    opacity: .95;
}

.mhs-card {
    background: #fff;
    border-radius: 24px;
    padding: 22px;
    border: 1px solid #edf2f7;
    box-shadow: 0 14px 34px rgba(15,23,42,.06);
    margin-bottom: 20px;
}

.mhs-section-head {
    margin-bottom: 16px;
}

.mhs-section-title {
    margin: 0 0 5px;
    font-size: 22px;
    font-weight: 900;
    color: #0f172a;
}

.mhs-section-subtitle {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.progress-steps {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}

.step-card {
    border-radius: 18px;
    padding: 15px;
    border: 1px solid #e2e8f0;
    background: linear-gradient(135deg,#fff,#f8fafc);
}

.step-card.done {
    border-color: #bbf7d0;
    background: linear-gradient(135deg,#ecfdf5,#f0fdf4);
}

.step-card.process {
    border-color: #fde68a;
    background: linear-gradient(135deg,#fffbeb,#fff7ed);
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e2e8f0;
    color: #475569;
    font-size: 13px;
    font-weight: 900;
    margin-bottom: 11px;
}

.step-card.done .step-number {
    background: #22c55e;
    color: #fff;
}

.step-card.process .step-number {
    background: #f59e0b;
    color: #fff;
}

.step-title {
    font-size: 13px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 6px;
}

.step-desc {
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
}

.mhs-grid {
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 18px;
    align-items: start;
}

.mhs-grid-2 {
    display: grid;
    grid-template-columns: .9fr 1.1fr;
    gap: 18px;
    align-items: start;
}

.badge {
    display: inline-flex;
    align-items: center;
    padding: 7px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.badge-blue { background:#dbeafe; color:#1d4ed8; }
.badge-green { background:#dcfce7; color:#166534; }
.badge-orange { background:#fef3c7; color:#92400e; }
.badge-red { background:#fee2e2; color:#b91c1c; }
.badge-gray { background:#e2e8f0; color:#475569; }

.status-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.status-card {
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 15px;
    background: linear-gradient(135deg,#fff,#f8fbff);
}

.status-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 9px;
}

.status-main {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    color: #0f172a;
    font-size: 13px;
}

.status-main strong {
    font-size: 19px;
    font-weight: 900;
}

.status-desc {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eef2f7;
    color: #0f172a;
    font-size: 12px;
    font-weight: 800;
    line-height: 1.45;
}

.quick-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.quick-card {
    display: block;
    padding: 16px;
    border-radius: 18px;
    background: linear-gradient(135deg,#f8fbff,#eff6ff);
    border: 1px solid #dbeafe;
    text-decoration: none;
    transition: .2s ease;
}

.quick-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(37,99,235,.12);
}

.quick-icon {
    width: 36px;
    height: 36px;
    border-radius: 14px;
    background: #2563eb;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    margin-bottom: 11px;
    font-size: 13px;
}

.quick-title {
    font-size: 14px;
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 5px;
}

.quick-desc {
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
}

.timeline {
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.timeline-item {
    display: grid;
    grid-template-columns: 38px 1fr;
    gap: 12px;
    align-items: start;
    padding: 13px;
    border-radius: 18px;
    background: #f8fafc;
    border: 1px solid #edf2f7;
}

.timeline-dot {
    width: 38px;
    height: 38px;
    border-radius: 14px;
    background: #2563eb;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 12px;
}

.timeline-title {
    font-weight: 900;
    color: #0f172a;
    margin-bottom: 4px;
    font-size: 13px;
}

.timeline-text {
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
}

.tips-box {
    border-radius: 20px;
    padding: 18px;
    background: linear-gradient(135deg,#fff7ed,#fffbeb);
    border: 1px solid #fed7aa;
}

.tips-title {
    font-weight: 900;
    color: #9a3412;
    margin-bottom: 8px;
    font-size: 15px;
}

.tips-list {
    margin: 0;
    padding-left: 18px;
    color: #7c2d12;
    font-size: 13px;
    line-height: 1.7;
}

@media(max-width: 1200px) {
    .mhs-stat-grid,
    .progress-steps {
        grid-template-columns: repeat(2, 1fr);
    }

    .mhs-grid,
    .mhs-grid-2 {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 700px) {
    .mhs-hero {
        padding: 18px;
        border-radius: 22px;
        margin-bottom: 14px;
    }

    .mhs-kicker {
        font-size: 10.5px;
        padding: 6px 9px;
        margin-bottom: 9px;
    }

    .mhs-hero h2 {
        font-size: 22px;
    }

    .mhs-hero p {
        font-size: 11.5px;
        line-height: 1.45;
    }

    .mhs-actions {
        gap: 7px;
        margin-top: 13px;
    }

    .mhs-action {
        padding: 8px 10px;
        border-radius: 11px;
        font-size: 11px;
        flex: 1 1 auto;
    }

    .mhs-stat-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 14px;
    }

    .mhs-stat-card {
        min-height: 110px;
        padding: 13px;
        border-radius: 18px;
    }

    .mhs-stat-label {
        font-size: 10px;
    }

    .mhs-stat-number {
        font-size: 27px;
    }

    .mhs-stat-desc {
        font-size: 9.5px;
        line-height: 1.3;
    }

    .mhs-card {
        padding: 14px;
        border-radius: 20px;
        margin-bottom: 14px;
    }

    .mhs-section-title {
        font-size: 18px;
    }

    .mhs-section-subtitle {
        font-size: 11.5px;
        line-height: 1.35;
    }

    .progress-steps,
    .status-grid,
    .quick-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 9px;
    }

    .step-card,
    .status-card,
    .quick-card {
        padding: 11px;
        border-radius: 15px;
    }

    .step-number,
    .quick-icon {
        width: 30px;
        height: 30px;
        border-radius: 11px;
        font-size: 11px;
        margin-bottom: 8px;
    }

    .step-title,
    .quick-title,
    .timeline-title {
        font-size: 11.5px;
    }

    .step-desc,
    .quick-desc,
    .timeline-text,
    .status-desc {
        font-size: 10px;
        line-height: 1.35;
    }

    .status-label {
        font-size: 10.5px;
        margin-bottom: 7px;
    }

    .status-main strong {
        font-size: 15px;
    }

    .badge {
        padding: 5px 7px;
        font-size: 9.5px;
    }

    .timeline-item {
        grid-template-columns: 32px 1fr;
        gap: 9px;
        padding: 10px;
        border-radius: 15px;
    }

    .timeline-dot {
        width: 32px;
        height: 32px;
        border-radius: 11px;
        font-size: 10px;
    }

    .tips-box {
        padding: 13px;
        border-radius: 17px;
    }

    .tips-title {
        font-size: 12.5px;
    }

    .tips-list {
        font-size: 10.5px;
        line-height: 1.6;
        padding-left: 15px;
    }
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

<div class="mhs-hero">
    <div class="mhs-hero-content">
        <div class="mhs-kicker">Sistem Tugas Akhir Mahasiswa</div>
        <h2>Halo, <?= esc((string) $nama) ?> 👋</h2>
        <p>Pantau perkembangan tugas akhir kamu dari satu halaman: pembimbing, judul, proposal, sampai Surat Keputusan.</p>

        <div class="mhs-actions">
            <a href="<?= base_url('/pembimbing') ?>" class="mhs-action">Ajukan Pembimbing</a>
            <a href="<?= base_url('/pengajuan-judul') ?>" class="mhs-action">Ajukan Judul</a>
            <a href="<?= base_url('/proposal-ta') ?>" class="mhs-action">Upload Proposal</a>
        </div>
    </div>
</div>

<div class="mhs-stat-grid">
    <div class="mhs-stat-card stat-blue">
        <div class="mhs-stat-label">Pembimbing Aktif</div>
        <div class="mhs-stat-number"><?= esc((string) $jumlahPembimbing) ?></div>
        <div class="mhs-stat-desc">Target: pembimbing 1 dan pembimbing 2</div>
    </div>

    <div class="mhs-stat-card stat-orange">
        <div class="mhs-stat-label">Pengajuan Judul</div>
        <div class="mhs-stat-number"><?= esc((string) $jumlahJudul) ?></div>
        <div class="mhs-stat-desc">Total judul yang pernah kamu ajukan</div>
    </div>

    <div class="mhs-stat-card stat-green">
        <div class="mhs-stat-label">Proposal TA</div>
        <div class="mhs-stat-number"><?= esc((string) $jumlahProposal) ?></div>
        <div class="mhs-stat-desc">Proposal yang sudah kamu unggah</div>
    </div>

    <div class="mhs-stat-card stat-purple">
        <div class="mhs-stat-label">Surat Keputusan</div>
        <div class="mhs-stat-number"><?= esc((string) $jumlahSk) ?></div>
        <div class="mhs-stat-desc">SK yang sudah diterbitkan admin</div>
    </div>
</div>

<div class="mhs-card">
    <div class="mhs-section-head">
        <h3 class="mhs-section-title">Progress Tugas Akhir</h3>
        <p class="mhs-section-subtitle">Tahapan utama yang perlu diselesaikan sampai SK terbit.</p>
    </div>

    <div class="progress-steps">
        <div class="step-card <?= $pembimbingDone ? 'done' : ((int) $jumlahPembimbing > 0 ? 'process' : '') ?>">
            <div class="step-number">1</div>
            <div class="step-title">Pembimbing</div>
            <div class="step-desc"><?= $pembimbingDone ? 'Pembimbing lengkap.' : ((int) $jumlahPembimbing > 0 ? 'Sebagian pembimbing aktif.' : 'Ajukan pembimbing dulu.') ?></div>
        </div>

        <div class="step-card <?= $judulDone ? 'done' : (! empty($statusJudul) ? 'process' : '') ?>">
            <div class="step-number">2</div>
            <div class="step-title">Judul TA</div>
            <div class="step-desc"><?= $judulDone ? 'Judul disetujui.' : (! empty($statusJudul) ? 'Judul sedang diproses.' : 'Ajukan judul setelah pembimbing.') ?></div>
        </div>

        <div class="step-card <?= $proposalDone ? 'done' : (! empty($statusProposal) ? 'process' : '') ?>">
            <div class="step-number">3</div>
            <div class="step-title">Proposal</div>
            <div class="step-desc"><?= $proposalDone ? 'Proposal disetujui.' : (! empty($statusProposal) ? 'Proposal diproses.' : 'Upload setelah judul disetujui.') ?></div>
        </div>

        <div class="step-card <?= $skDone ? 'done' : '' ?>">
            <div class="step-number">4</div>
            <div class="step-title">SK Terbit</div>
            <div class="step-desc"><?= $skDone ? 'SK sudah tersedia.' : 'Menunggu admin menerbitkan SK.' ?></div>
        </div>
    </div>
</div>

<div class="mhs-grid">
    <div class="mhs-card">
        <div class="mhs-section-head">
            <h3 class="mhs-section-title">Status Terbaru</h3>
            <p class="mhs-section-subtitle">Ringkasan kondisi terbaru proses tugas akhir kamu.</p>
        </div>

        <div class="status-grid">
            <div class="status-card">
                <div class="status-label">Pembimbing Aktif</div>
                <div class="status-main">
                    <strong><?= esc((string) $jumlahPembimbing) ?> dosen</strong>
                    <span class="badge <?= $pembimbingDone ? 'badge-green' : 'badge-orange' ?>">
                        <?= $pembimbingDone ? 'Lengkap' : 'Belum lengkap' ?>
                    </span>
                </div>
            </div>

            <div class="status-card">
                <div class="status-label">Status Judul</div>
                <div class="status-main">
                    <span class="badge <?= $badgeStatus($statusJudul) ?>">
                        <?= esc((string) $statusText($statusJudul)) ?>
                    </span>
                </div>

                <?php if (! empty($judulAktif['judul'])): ?>
                    <div class="status-desc"><?= esc((string) $judulAktif['judul']) ?></div>
                <?php endif; ?>
            </div>

            <div class="status-card">
                <div class="status-label">Status Proposal</div>
                <div class="status-main">
                    <span class="badge <?= $badgeStatus($statusProposal) ?>">
                        <?= esc((string) $statusText($statusProposal)) ?>
                    </span>
                </div>
            </div>

            <div class="status-card">
                <div class="status-label">Surat Keputusan</div>
                <div class="status-main">
                    <span class="badge <?= $skDone ? 'badge-green' : 'badge-gray' ?>">
                        <?= $skDone ? 'Sudah terbit' : 'Belum tersedia' ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mhs-card">
        <div class="mhs-section-head">
            <h3 class="mhs-section-title">Quick Actions</h3>
            <p class="mhs-section-subtitle">Akses cepat ke modul utama mahasiswa.</p>
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
    </div>
</div>

<div class="mhs-grid-2">
    <div class="mhs-card">
        <div class="mhs-section-head">
            <h3 class="mhs-section-title">Timeline Proses</h3>
            <p class="mhs-section-subtitle">Urutan kerja yang bisa kamu ikuti.</p>
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
    </div>

    <div class="mhs-card">
        <div class="mhs-section-head">
            <h3 class="mhs-section-title">Catatan Penting</h3>
            <p class="mhs-section-subtitle">Hal yang perlu kamu perhatikan selama proses tugas akhir.</p>
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
    </div>
</div>

<?= $this->endSection() ?>