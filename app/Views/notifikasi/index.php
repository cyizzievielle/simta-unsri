<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$notifikasi = $notifikasi ?? [];

$formatDate = static function (mixed $date): string {
    $value = is_scalar($date) ? (string) $date : '';
    $time = strtotime($value);

    return $time ? date('d M Y, H:i', $time) : '-';
};

$notifIcon = static function (mixed $tipe): string {
    $type = is_scalar($tipe) ? strtolower((string) $tipe) : '';

    return match ($type) {
        'chat'    => 'ri-message-3-fill',
        'success' => 'ri-checkbox-circle-fill',
        'danger'  => 'ri-error-warning-fill',
        'warning' => 'ri-alert-fill',
        default   => 'ri-notification-4-fill',
    };
};

$notifClass = static function (mixed $tipe): string {
    $type = is_scalar($tipe) ? strtolower((string) $tipe) : '';

    return match ($type) {
        'chat'    => 'notif-chat',
        'success' => 'notif-success',
        'danger'  => 'notif-danger',
        'warning' => 'notif-warning',
        default   => 'notif-info',
    };
};

$totalNotif = is_array($notifikasi) ? count($notifikasi) : 0;
$totalUnread = 0;

foreach ($notifikasi as $item) {
    if ((int) ($item['is_read'] ?? 0) === 0) {
        $totalUnread++;
    }
}
?>

<style>
.notif-page {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.notif-hero {
    position: relative;
    overflow: hidden;
    border-radius: 30px;
    padding: 28px;
    color: #fff;
    background:
        radial-gradient(circle at 88% 28%, rgba(255,255,255,.18), transparent 24%),
        linear-gradient(135deg, #0f172a 0%, #2563eb 56%, #7c3aed 100%);
    box-shadow: 0 18px 42px rgba(37,99,235,.18);
}

.notif-hero::after {
    content: "";
    position: absolute;
    width: 190px;
    height: 190px;
    right: -58px;
    bottom: -70px;
    border-radius: 999px;
    background: rgba(255,255,255,.12);
}

.notif-kicker {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    width: fit-content;
    padding: 7px 11px;
    margin-bottom: 12px;
    border-radius: 999px;
    background: rgba(255,255,255,.16);
    color: #e0f2fe;
    border: 1px solid rgba(255,255,255,.18);
    font-size: 12px;
    font-weight: 900;
}

.notif-hero-main {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 18px;
    align-items: end;
}

.notif-hero h2 {
    margin: 0 0 8px;
    font-size: 32px;
    font-weight: 950;
    letter-spacing: -.04em;
}

.notif-hero p {
    margin: 0;
    max-width: 760px;
    color: rgba(255,255,255,.9);
    font-size: 14px;
    line-height: 1.65;
}

.notif-stats {
    display: grid;
    grid-template-columns: repeat(2, minmax(110px, 1fr));
    gap: 10px;
}

.notif-stat {
    min-width: 118px;
    padding: 14px;
    border-radius: 20px;
    background: rgba(255,255,255,.16);
    border: 1px solid rgba(255,255,255,.20);
    backdrop-filter: blur(12px);
}

.notif-stat span {
    display: block;
    font-size: 11px;
    font-weight: 850;
    color: rgba(255,255,255,.78);
    margin-bottom: 5px;
}

.notif-stat strong {
    display: block;
    font-size: 25px;
    font-weight: 950;
    line-height: 1;
    color: #fff;
}

.notif-panel {
    background: rgba(255,255,255,.94);
    border: 1px solid rgba(226,232,240,.95);
    border-radius: 28px;
    padding: 20px;
    box-shadow: 0 14px 38px rgba(15,23,42,.06);
}

.notif-panel-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 16px;
}

.notif-panel-head h3 {
    margin: 0;
    color: #0f172a;
    font-size: 21px;
    font-weight: 950;
    letter-spacing: -.03em;
}

.notif-panel-head p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.55;
}

.notif-read-btn {
    min-height: 40px;
    padding: 0 14px;
    border-radius: 14px;
    border: 0;
    cursor: pointer;
    color: #fff;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    box-shadow: 0 12px 24px rgba(37,99,235,.18);
    font-size: 12px;
    font-weight: 900;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.notif-page-list {
    display: flex;
    flex-direction: column;
    gap: 11px;
}

.notif-page-item {
    position: relative;
    display: grid;
    grid-template-columns: 48px 1fr auto;
    align-items: center;
    gap: 13px;
    padding: 14px;
    border-radius: 22px;
    background:
        radial-gradient(circle at right bottom, rgba(37,99,235,.045), transparent 30%),
        linear-gradient(135deg, #ffffff, #f8fbff);
    border: 1px solid #e2e8f0;
    color: inherit;
    text-decoration: none;
    transition: .18s ease;
}

.notif-page-item:hover {
    transform: translateY(-2px);
    border-color: #bfdbfe;
    box-shadow: 0 16px 34px rgba(37,99,235,.10);
}

.notif-page-item.unread {
    background:
        radial-gradient(circle at right bottom, rgba(37,99,235,.08), transparent 32%),
        linear-gradient(135deg, #eff6ff, #ffffff);
    border-color: #bfdbfe;
}

.notif-page-item.unread::before {
    content: "";
    position: absolute;
    left: 0;
    top: 18px;
    bottom: 18px;
    width: 4px;
    border-radius: 999px;
    background: linear-gradient(180deg, #2563eb, #7c3aed);
}

.notif-page-icon {
    width: 48px;
    height: 48px;
    border-radius: 17px;
    display: grid;
    place-items: center;
    background: #eff6ff;
    color: #2563eb;
    font-size: 21px;
    flex: 0 0 auto;
    border: 1px solid #dbeafe;
}

.notif-page-item.notif-chat .notif-page-icon {
    background: #eef2ff;
    color: #4f46e5;
    border-color: #c7d2fe;
}

.notif-page-item.notif-success .notif-page-icon {
    background: #ecfdf5;
    color: #059669;
    border-color: #bbf7d0;
}

.notif-page-item.notif-danger .notif-page-icon {
    background: #fff1f2;
    color: #e11d48;
    border-color: #fecdd3;
}

.notif-page-item.notif-warning .notif-page-icon {
    background: #fffbeb;
    color: #d97706;
    border-color: #fde68a;
}

.notif-page-content {
    min-width: 0;
}

.notif-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.notif-title-row strong {
    display: block;
    color: #0f172a;
    font-size: 14px;
    font-weight: 950;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.unread-pill {
    display: none;
    padding: 4px 7px;
    border-radius: 999px;
    background: #dbeafe;
    color: #2563eb;
    font-size: 10px;
    font-weight: 950;
    white-space: nowrap;
}

.notif-page-item.unread .unread-pill {
    display: inline-flex;
}

.notif-page-content p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

.notif-page-content span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 750;
}

.notif-open-icon {
    width: 34px;
    height: 34px;
    border-radius: 13px;
    display: grid;
    place-items: center;
    background: #fff;
    border: 1px solid #dbeafe;
    color: #2563eb;
    font-size: 18px;
}

.notif-empty-state {
    min-height: 230px;
    display: grid;
    place-items: center;
    text-align: center;
    padding: 30px;
    border-radius: 22px;
    background:
        radial-gradient(circle at top right, rgba(37,99,235,.07), transparent 32%),
        #f8fafc;
    border: 1px dashed #cbd5e1;
}

.notif-empty-icon {
    width: 62px;
    height: 62px;
    margin: 0 auto 12px;
    border-radius: 22px;
    display: grid;
    place-items: center;
    background: #eff6ff;
    color: #2563eb;
    font-size: 30px;
}

.notif-empty-state h4 {
    margin: 0 0 6px;
    color: #0f172a;
    font-size: 16px;
    font-weight: 950;
}

.notif-empty-state p {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

@media (max-width: 768px) {
    .notif-page {
        gap: 14px;
    }

    .notif-hero {
        padding: 18px;
        border-radius: 22px;
    }

    .notif-hero-main {
        grid-template-columns: 1fr;
    }

    .notif-hero h2 {
        font-size: 23px;
    }

    .notif-hero p {
        font-size: 11.5px;
        line-height: 1.55;
    }

    .notif-stats {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .notif-stat {
        min-width: 0;
        padding: 12px;
        border-radius: 17px;
    }

    .notif-stat strong {
        font-size: 22px;
    }

    .notif-panel {
        padding: 14px;
        border-radius: 22px;
    }

    .notif-panel-head {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }

    .notif-read-btn {
        width: 100%;
        justify-content: center;
    }

    .notif-page-item {
        grid-template-columns: 42px 1fr;
        gap: 11px;
        padding: 12px;
        border-radius: 18px;
    }

    .notif-page-icon {
        width: 42px;
        height: 42px;
        border-radius: 15px;
        font-size: 19px;
    }

    .notif-open-icon {
        display: none;
    }

    .notif-title-row strong {
        font-size: 13px;
    }

    .notif-page-content p {
        font-size: 11.5px;
    }
}
</style>

<div class="notif-page">
    <section class="notif-hero">
        <div class="notif-hero-main">
            <div>
                <span class="notif-kicker">
                    <i class="ri-notification-4-fill"></i>
                    Pusat Notifikasi
                </span>

                <h2>Semua Notifikasi</h2>
                <p>
                    Pantau seluruh update akademik, pesan chat bimbingan, status pengajuan,
                    dan aktivitas sistem secara rapi dalam satu halaman.
                </p>
            </div>
        </div>
    </section>

    <section class="notif-panel">
        <div class="notif-panel-head">
            <div>
                <h3>Daftar Notifikasi</h3>
                <p>Notifikasi terbaru akan tampil di bagian atas.</p>
            </div>

            <?php if ($totalUnread > 0): ?>
                <form action="<?= base_url('/notifikasi/read-all') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="notif-read-btn">
                        <i class="ri-check-double-line"></i>
                        Tandai Semua Dibaca
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (! empty($notifikasi) && is_array($notifikasi)): ?>
            <div class="notif-page-list">
                <?php foreach ($notifikasi as $n): ?>
                    <?php
                    $judulNotif = isset($n['judul']) && is_scalar($n['judul'])
                        ? (string) $n['judul']
                        : 'Notifikasi';

                    $pesanNotif = isset($n['pesan']) && is_scalar($n['pesan'])
                        ? (string) $n['pesan']
                        : '-';

                    $tanggalNotif = isset($n['created_at']) && is_scalar($n['created_at'])
                        ? (string) $n['created_at']
                        : '';

                    $tipeNotif = isset($n['tipe']) && is_scalar($n['tipe'])
                        ? (string) $n['tipe']
                        : 'info';

                    $isUnread = (int) ($n['is_read'] ?? 0) === 0;
                    $idNotif = (int) ($n['id'] ?? 0);
                    ?>

                    <a href="<?= base_url('/notifikasi/read/' . $idNotif) ?>"
                       class="notif-page-item <?= esc($notifClass($tipeNotif), 'attr') ?> <?= $isUnread ? 'unread' : '' ?>">
                        <div class="notif-page-icon">
                            <i class="<?= esc($notifIcon($tipeNotif), 'attr') ?>"></i>
                        </div>

                        <div class="notif-page-content">
                            <div class="notif-title-row">
                                <strong><?= esc($judulNotif) ?></strong>
                                <span class="unread-pill">Baru</span>
                            </div>

                            <p><?= esc($pesanNotif) ?></p>

                            <span>
                                <i class="ri-time-line"></i>
                                <?= esc($formatDate($tanggalNotif)) ?>
                            </span>
                        </div>

                        <div class="notif-open-icon">
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="notif-empty-state">
                <div>
                    <div class="notif-empty-icon">
                        <i class="ri-notification-off-line"></i>
                    </div>
                    <h4>Belum Ada Notifikasi</h4>
                    <p>Update akademik, chat, dan aktivitas sistem akan muncul di sini.</p>
                </div>
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>