<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$rooms = $rooms ?? [];

$safe = static function (mixed $value, string $default = '-'): string {
    if ($value === null || $value === '') return $default;
    return is_scalar($value) ? (string) $value : $default;
};

$avatar = static function (?string $foto, string $nama): string {
    $nama = trim($nama) !== '' ? $nama : 'U';
    $initial = strtoupper(substr($nama, 0, 1));

    if ($foto && trim($foto) !== '') {
        return '<img src="' . esc(base_url('uploads/profile/' . $foto), 'attr') . '" alt="Foto Profil">';
    }

    return '<span>' . esc($initial) . '</span>';
};

$labelJenis = static function (mixed $jenis) use ($safe): string {
    return match ($safe($jenis, '')) {
        'pembimbing_2' => 'Pembimbing 2',
        'pembimbing_1' => 'Pembimbing 1',
        default => 'Chat Akademik',
    };
};
?>

<style>
.chat-page {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.chat-hero {
    border-radius: 28px;
    padding: 28px;
    background:
        radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 30%),
        linear-gradient(135deg, #0f172a, #2563eb 58%, #7c3aed);
    color: #fff;
    box-shadow: 0 18px 42px rgba(37,99,235,.18);
}

.chat-kicker {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 11px;
    border-radius: 999px;
    background: rgba(255,255,255,.16);
    color: #e0f2fe;
    font-size: 12px;
    font-weight: 900;
    margin-bottom: 12px;
}

.chat-hero h2 {
    margin: 0 0 8px;
    font-size: 32px;
    font-weight: 950;
    letter-spacing: -.04em;
}

.chat-hero p {
    margin: 0;
    max-width: 760px;
    color: rgba(255,255,255,.9);
    font-size: 14px;
    line-height: 1.65;
}

.chat-panel {
    background: rgba(255,255,255,.94);
    border: 1px solid rgba(226,232,240,.95);
    border-radius: 28px;
    padding: 20px;
    box-shadow: 0 14px 38px rgba(15,23,42,.06);
}

.chat-panel-head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    margin-bottom: 16px;
}

.chat-panel-head h3 {
    margin: 0;
    color: #0f172a;
    font-size: 21px;
    font-weight: 950;
    letter-spacing: -.03em;
}

.chat-panel-head p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 13px;
}

.chat-list {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}

.chat-card {
    display: flex;
    align-items: center;
    gap: 13px;
    padding: 15px;
    border-radius: 22px;
    background:
        radial-gradient(circle at right bottom, rgba(37,99,235,.06), transparent 32%),
        linear-gradient(135deg, #fff, #f8fbff);
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 24px rgba(15,23,42,.04);
    transition: .18s ease;
}

.chat-card:hover {
    transform: translateY(-2px);
    border-color: #bfdbfe;
    box-shadow: 0 16px 34px rgba(37,99,235,.10);
}

.chat-avatar {
    width: 52px;
    height: 52px;
    border-radius: 18px;
    overflow: hidden;
    flex: 0 0 auto;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff;
    display: grid;
    place-items: center;
    font-weight: 950;
    font-size: 18px;
    box-shadow: 0 12px 24px rgba(37,99,235,.18);
}

.chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.chat-info {
    min-width: 0;
    flex: 1;
}

.chat-name-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.chat-name {
    color: #0f172a;
    font-size: 14px;
    font-weight: 950;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-role {
    display: inline-flex;
    width: fit-content;
    margin-top: 5px;
    padding: 5px 8px;
    border-radius: 999px;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    font-size: 10.5px;
    font-weight: 900;
}

.chat-last {
    margin-top: 8px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-arrow {
    width: 36px;
    height: 36px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    background: #fff;
    border: 1px solid #dbeafe;
    color: #2563eb;
    flex: 0 0 auto;
}

.chat-empty {
    border-radius: 22px;
    padding: 22px;
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    text-align: center;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.chat-empty i {
    display: block;
    font-size: 32px;
    color: #2563eb;
    margin-bottom: 8px;
}

@media (max-width: 900px) {
    .chat-list {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .chat-page {
        gap: 14px;
    }

    .chat-hero {
        padding: 18px;
        border-radius: 22px;
    }

    .chat-hero h2 {
        font-size: 23px;
    }

    .chat-hero p {
        font-size: 11.5px;
        line-height: 1.55;
    }

    .chat-panel {
        padding: 14px;
        border-radius: 22px;
    }

    .chat-panel-head h3 {
        font-size: 18px;
    }

    .chat-panel-head p {
        font-size: 11.5px;
    }

    .chat-card {
        padding: 12px;
        border-radius: 18px;
    }

    .chat-avatar {
        width: 46px;
        height: 46px;
        border-radius: 16px;
    }

    .chat-name {
        font-size: 13px;
    }

    .chat-last {
        font-size: 11px;
    }
}
</style>

<div class="chat-page">
    <section class="chat-hero">
        <span class="chat-kicker">
            <i class="ri-message-3-fill"></i>
            Chat Bimbingan
        </span>

        <h2>Ruang Diskusi Akademik</h2>
        <p>
            Kirim pesan, catatan revisi, dan file bimbingan dengan pembimbing secara rapi
            dalam satu ruang komunikasi akademik.
        </p>
    </section>

    <section class="chat-panel">
        <div class="chat-panel-head">
            <div>
                <h3>Daftar Percakapan</h3>
                <p>Pilih ruang chat untuk mulai berdiskusi.</p>
            </div>
        </div>

        <?php if (! empty($rooms) && is_array($rooms)): ?>
            <div class="chat-list">
                <?php foreach ($rooms as $room): ?>
                    <?php
                    $nama = $safe($room['nama_lawan'] ?? 'User');
                    $foto = $safe($room['foto_lawan'] ?? '', '');
                    $last = $safe($room['last_message'] ?? '', 'Belum ada pesan. Mulai percakapan sekarang.');
                    ?>

                    <a href="<?= base_url('/chat/room/' . (int) $room['id']) ?>" class="chat-card">
                        <div class="chat-avatar">
                            <?= $avatar($foto, $nama) ?>
                        </div>

                        <div class="chat-info">
                            <div class="chat-name-row">
                                <div class="chat-name"><?= esc($nama) ?></div>
                            </div>

                            <div class="chat-role">
                                <?= esc($labelJenis($room['jenis_pembimbing'] ?? '')) ?>
                            </div>

                            <div class="chat-last">
                                <?= esc($last) ?>
                            </div>
                        </div>

                        <div class="chat-arrow">
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="chat-empty">
                <i class="ri-chat-3-line"></i>
                Belum ada ruang chat. Room akan muncul setelah pembimbing aktif ditetapkan.
            </div>
        <?php endif; ?>
    </section>
</div>

<?= $this->endSection() ?>