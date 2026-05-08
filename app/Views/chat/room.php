<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$room     = $room ?? [];
$messages = $messages ?? [];
$userId   = (int) session()->get('user_id');
$lawanNama = $lawanNama ?? 'Room Chat Bimbingan';
$lawanFoto = $lawanFoto ?? '';
$lawanRole = $lawanRole ?? 'Diskusi akademik';

$safe = static function (mixed $value, string $default = ''): string {
    if ($value === null || $value === '') return $default;
    return is_scalar($value) ? (string) $value : $default;
};

$formatDate = static function (mixed $date) use ($safe): string {
    $value = $safe($date, '');
    if ($value === '') return '';

    $time = strtotime($value);
    if (! $time) return $value;

    return date('d M Y, H:i', $time);
};

$avatar = static function (?string $foto, string $nama): string {
    $nama = trim($nama) !== '' ? $nama : 'U';
    $initial = strtoupper(substr($nama, 0, 1));

    if ($foto && trim($foto) !== '') {
        return '<img src="' . esc(base_url('uploads/profile/' . $foto), 'attr') . '" alt="Foto Profil">';
    }

    return '<span>' . esc($initial) . '</span>';
};
?>

<style>
.chat-room-page {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.chat-room-shell {
    height: calc(100vh - 150px);
    min-height: 560px;
    display: grid;
    grid-template-rows: auto 1fr auto;
    overflow: hidden;
    border-radius: 28px;
    background: rgba(255, 255, 255, .96);
    border: 1px solid rgba(226, 232, 240, .96);
    box-shadow: 0 18px 45px rgba(15, 23, 42, .08);
}

.chat-room-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    padding: 16px 18px;
    border-bottom: 1px solid #eef2f7;
    background:
        radial-gradient(circle at top right, rgba(37,99,235,.08), transparent 32%),
        linear-gradient(135deg, #ffffff, #f8fbff);
}

.chat-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.chat-back {
    width: 40px;
    height: 40px;
    border-radius: 15px;
    display: grid;
    place-items: center;
    background: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
    flex: 0 0 auto;
    transition: .16s ease;
}

.chat-back:hover {
    transform: translateX(-2px);
}

.room-avatar {
    width: 48px;
    height: 48px;
    border-radius: 18px;
    overflow: hidden;
    display: grid;
    place-items: center;
    flex: 0 0 auto;
    color: #fff;
    font-weight: 950;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    box-shadow: 0 12px 24px rgba(37,99,235,.18);
}

.room-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.room-title {
    min-width: 0;
}

.room-title h3 {
    margin: 0;
    color: #0f172a;
    font-size: 17px;
    font-weight: 950;
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.room-title p {
    margin: 5px 0 0;
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
}

.chat-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 10px;
    border-radius: 999px;
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #bbf7d0;
    font-size: 11px;
    font-weight: 900;
    white-space: nowrap;
}

.chat-status-pill::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: #22c55e;
}

.chat-messages {
    padding: 18px;
    overflow-y: auto;
    background:
        radial-gradient(circle at top left, rgba(37,99,235,.06), transparent 28%),
        linear-gradient(180deg, #f8fbff, #f6f8fc);
}

.message-row {
    display: flex;
    gap: 10px;
    align-items: flex-end;
    margin-bottom: 14px;
}

.message-row.me {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 34px;
    height: 34px;
    border-radius: 13px;
    overflow: hidden;
    flex: 0 0 auto;
    display: grid;
    place-items: center;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff;
    font-size: 12px;
    font-weight: 950;
}

.message-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-bubble-wrap {
    max-width: min(640px, 72%);
}

.message-meta {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 5px;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
}

.message-row.me .message-meta {
    justify-content: flex-end;
}

.message-bubble {
    border-radius: 20px 20px 20px 6px;
    padding: 12px 14px;
    background: #fff;
    color: #334155;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 24px rgba(15, 23, 42, .045);
    font-size: 13px;
    line-height: 1.65;
    word-break: break-word;
}

.message-row.me .message-bubble {
    border-radius: 20px 20px 6px 20px;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 14px 28px rgba(37,99,235,.18);
}

.file-card-chat {
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    border-radius: 16px;
    background: rgba(248, 250, 252, .92);
    border: 1px solid #e2e8f0;
}

.message-row.me .file-card-chat {
    background: rgba(255, 255, 255, .14);
    border-color: rgba(255, 255, 255, .24);
}

.file-icon-chat {
    width: 38px;
    height: 38px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    background: #eff6ff;
    color: #2563eb;
    flex: 0 0 auto;
    font-size: 18px;
}

.message-row.me .file-icon-chat {
    background: rgba(255,255,255,.18);
    color: #fff;
}

.file-info-chat {
    min-width: 0;
    flex: 1;
}

.file-info-chat strong {
    display: block;
    font-size: 12px;
    font-weight: 900;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-info-chat a {
    display: inline-flex;
    margin-top: 4px;
    color: #2563eb;
    font-size: 11px;
    font-weight: 900;
}

.message-row.me .file-info-chat a {
    color: #fff;
    text-decoration: underline;
}

.chat-empty-message {
    height: 100%;
    display: grid;
    place-items: center;
    text-align: center;
    color: #64748b;
    font-size: 13px;
    line-height: 1.6;
}

.chat-empty-message i {
    display: block;
    font-size: 38px;
    color: #2563eb;
    margin-bottom: 10px;
}

.chat-composer {
    padding: 14px;
    border-top: 1px solid #eef2f7;
    background: rgba(255,255,255,.96);
}

.composer-form {
    display: grid;
    grid-template-columns: auto 1fr auto;
    gap: 10px;
    align-items: end;
}

.file-trigger {
    width: 44px;
    height: 44px;
    border-radius: 16px;
    border: 1px solid #dbeafe;
    background: #eff6ff;
    color: #2563eb;
    display: grid;
    place-items: center;
    cursor: pointer;
    font-size: 20px;
    transition: .16s ease;
}

.file-trigger:hover {
    background: #dbeafe;
}

.file-input-hidden {
    display: none;
}

.composer-input-wrap {
    min-width: 0;
}

.composer-textarea {
    width: 100%;
    min-height: 44px;
    max-height: 120px;
    resize: vertical;
    border-radius: 18px;
    border: 1px solid #dbe3ef;
    padding: 12px 14px;
    outline: none;
    background: #f8fafc;
    color: #0f172a;
    font-size: 13px;
    line-height: 1.5;
}

.composer-textarea:focus {
    border-color: #93c5fd;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(37,99,235,.10);
}

.file-preview-name {
    display: none;
    margin-top: 7px;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
}

.file-preview-name.show {
    display: block;
}

.send-btn {
    width: 48px;
    height: 44px;
    border: 0;
    border-radius: 16px;
    display: grid;
    place-items: center;
    cursor: pointer;
    color: #fff;
    background: linear-gradient(135deg, #2563eb, #7c3aed);
    box-shadow: 0 12px 24px rgba(37,99,235,.20);
    font-size: 19px;
    transition: .16s ease;
}

.send-btn:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .chat-room-shell {
        height: calc(100vh - 104px);
        min-height: 560px;
        border-radius: 22px;
    }

    .chat-room-header {
        padding: 12px;
    }

    .chat-back {
        width: 36px;
        height: 36px;
        border-radius: 13px;
    }

    .room-avatar {
        width: 42px;
        height: 42px;
        border-radius: 15px;
    }

    .room-title h3 {
        font-size: 14px;
    }

    .room-title p {
        font-size: 10.5px;
    }

    .chat-status-pill {
        display: none;
    }

    .chat-messages {
        padding: 13px;
    }

    .message-bubble-wrap {
        max-width: 82%;
    }

    .message-bubble {
        font-size: 12px;
        padding: 10px 12px;
    }

    .message-avatar {
        width: 30px;
        height: 30px;
        border-radius: 11px;
    }

    .composer-form {
        grid-template-columns: auto 1fr auto;
        gap: 7px;
    }

    .file-trigger,
    .send-btn {
        width: 40px;
        height: 40px;
        border-radius: 14px;
    }

    .composer-textarea {
        min-height: 40px;
        border-radius: 15px;
        font-size: 12px;
        padding: 10px 12px;
    }
}
</style>

<div class="chat-room-page">
    <section class="chat-room-shell">
        <header class="chat-room-header">
            <div class="chat-header-left">
                <a href="<?= base_url('/chat') ?>" class="chat-back">
                    <i class="ri-arrow-left-line"></i>
                </a>

                <div class="room-avatar">
                    <?= $avatar($lawanFoto, $lawanNama) ?>
                </div>

                <div class="room-title">
                    <h3><?= esc($lawanNama) ?></h3>
                    <p><?= esc($lawanRole) ?> • Diskusi akademik</p>
                </div>
            </div>

            <span class="chat-status-pill">Aktif</span>
        </header>

        <main class="chat-messages" id="chatMessages">
            <?php if (! empty($messages) && is_array($messages)): ?>
                <?php foreach ($messages as $msg): ?>
                    <?php
                    $isMe = (int) ($msg['sender_user_id'] ?? 0) === $userId;
                    $senderName = $safe($msg['sender_name'] ?? 'User', 'User');
                    $senderFoto = $safe($msg['sender_foto'] ?? '', '');
                    $messageText = $safe($msg['message'] ?? '', '');
                    $filePath = $safe($msg['file_path'] ?? '', '');
                    $fileName = $safe($msg['file_original_name'] ?? '', 'File Lampiran');
                    ?>

                    <div class="message-row <?= $isMe ? 'me' : 'other' ?>">
                        <div class="message-avatar">
                            <?= $avatar($senderFoto, $senderName) ?>
                        </div>

                        <div class="message-bubble-wrap">
                            <div class="message-meta">
                                <span><?= $isMe ? 'Saya' : esc($senderName) ?></span>
                                <span>•</span>
                                <span><?= esc($formatDate($msg['created_at'] ?? '')) ?></span>
                            </div>

                            <div class="message-bubble">
                                <?php if ($messageText !== ''): ?>
                                    <div><?= nl2br(esc($messageText)) ?></div>
                                <?php endif; ?>

                                <?php if ($filePath !== ''): ?>
                                    <div class="file-card-chat">
                                        <div class="file-icon-chat">
                                            <i class="ri-file-3-fill"></i>
                                        </div>

                                        <div class="file-info-chat">
                                            <strong><?= esc($fileName) ?></strong>
                                            <a href="<?= base_url($filePath) ?>" target="_blank">
                                                Buka file
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="chat-empty-message">
                    <div>
                        <i class="ri-chat-smile-3-line"></i>
                        Belum ada pesan. Mulai diskusi bimbingan dengan mengirim pesan pertama.
                    </div>
                </div>
            <?php endif; ?>
        </main>

        <footer class="chat-composer">
            <form action="<?= base_url('/chat/send') ?>" method="post" enctype="multipart/form-data" class="composer-form">
                <?= csrf_field() ?>

                <input type="hidden" name="room_id" value="<?= esc((string) ($room['id'] ?? '')) ?>">

                <label for="chatFile" class="file-trigger" title="Lampirkan file">
                    <i class="ri-attachment-2"></i>
                </label>

                <input
                    type="file"
                    name="file"
                    id="chatFile"
                    class="file-input-hidden"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                >

                <div class="composer-input-wrap">
                    <textarea
                        name="message"
                        class="composer-textarea"
                        placeholder="Tulis pesan atau catatan revisi..."
                        rows="1"
                    ></textarea>

                    <div class="file-preview-name" id="filePreviewName"></div>
                </div>

                <button type="submit" class="send-btn" title="Kirim pesan">
                    <i class="ri-send-plane-fill"></i>
                </button>
            </form>
        </footer>
    </section>
</div>

<script>
const roomId = <?= (int) ($room['id'] ?? 0) ?>;
const currentUserId = <?= (int) session()->get('user_id') ?>;
const chatMessages = document.getElementById('chatMessages');

function escapeHtml(value) {
    return String(value ?? '').replace(/[&<>"']/g, function (m) {
        return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[m];
    });
}

function formatMessageDate(value) {
    if (!value) return '';
    return value;
}

function avatarHtml(foto, nama) {
    const name = nama || 'U';
    const initial = name.substring(0, 1).toUpperCase();

    if (foto) {
        return `<img src="<?= base_url('uploads/profile') ?>/${escapeHtml(foto)}" alt="Foto Profil">`;
    }

    return `<span>${escapeHtml(initial)}</span>`;
}

function fileHtml(msg, isMe) {
    if (!msg.file_path) return '';

    const fileName = msg.file_original_name || 'File Lampiran';
    const fileUrl = `<?= base_url() ?>${msg.file_path}`;

    return `
        <div class="file-card-chat">
            <div class="file-icon-chat">
                <i class="ri-file-3-fill"></i>
            </div>
            <div class="file-info-chat">
                <strong>${escapeHtml(fileName)}</strong>
                <a href="${fileUrl}" target="_blank">Buka file</a>
            </div>
        </div>
    `;
}

function messageHtml(msg) {
    const isMe = parseInt(msg.sender_user_id) === currentUserId;
    const senderName = msg.sender_name || 'User';
    const senderFoto = msg.sender_foto || '';
    const text = msg.message ? escapeHtml(msg.message).replace(/\n/g, '<br>') : '';

    return `
        <div class="message-row ${isMe ? 'me' : 'other'}">
            <div class="message-avatar">
                ${avatarHtml(senderFoto, senderName)}
            </div>

            <div class="message-bubble-wrap">
                <div class="message-meta">
                    <span>${isMe ? 'Saya' : escapeHtml(senderName)}</span>
                    <span>•</span>
                    <span>${escapeHtml(formatMessageDate(msg.created_at))}</span>
                </div>

                <div class="message-bubble">
                    ${text ? `<div>${text}</div>` : ''}
                    ${fileHtml(msg, isMe)}
                </div>
            </div>
        </div>
    `;
}

let lastMessageCount = 0;

async function loadMessagesRealtime() {
    if (!roomId || !chatMessages) return;

    try {
        const response = await fetch(`<?= base_url('/chat/messages') ?>/${roomId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (!data.success || !Array.isArray(data.messages)) return;

        const shouldScroll = chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight - 80;

        if (data.messages.length === 0) {
            chatMessages.innerHTML = `
                <div class="chat-empty-message">
                    <div>
                        <i class="ri-chat-smile-3-line"></i>
                        Belum ada pesan. Mulai diskusi bimbingan dengan mengirim pesan pertama.
                    </div>
                </div>
            `;
            return;
        }

        if (data.messages.length !== lastMessageCount) {
            chatMessages.innerHTML = data.messages.map(messageHtml).join('');
            lastMessageCount = data.messages.length;

            if (shouldScroll) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
    } catch (error) {
        console.error(error);
    }
}

const chatFile = document.getElementById('chatFile');
const filePreviewName = document.getElementById('filePreviewName');

if (chatFile && filePreviewName) {
    chatFile.addEventListener('change', function () {
        const file = this.files && this.files[0];

        if (!file) {
            filePreviewName.classList.remove('show');
            filePreviewName.textContent = '';
            return;
        }

        filePreviewName.textContent = 'File dipilih: ' + file.name;
        filePreviewName.classList.add('show');
    });
}

loadMessagesRealtime();
setInterval(loadMessagesRealtime, 3000);
</script>

<?= $this->endSection() ?>