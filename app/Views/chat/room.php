<?= $this->extend('layouts/dashboard') ?>
<?= $this->section('content') ?>

<?php
$room       = $room ?? [];
$messages   = $messages ?? [];
$userId     = (int) session()->get('user_id');
$lawanNama  = $lawanNama ?? 'Room Chat Bimbingan';
$lawanFoto  = $lawanFoto ?? '';
$lawanRole  = $lawanRole ?? 'Diskusi akademik';

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

.chat-status-pill.offline {
    background: #f1f5f9;
    color: #64748b;
    border-color: #e2e8f0;
}

.chat-status-pill.offline::before {
    background: #94a3b8;
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
    flex-wrap: wrap;
}
.message-row.me .message-meta span {
    color: rgba(255,255,255,.86);
}

.message-row.me .message-meta {
    justify-content: flex-end;
    color: rgba(255,255,255,.86);
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

.message-reply-box {
    margin-bottom: 8px;
    padding: 8px 10px;
    border-radius: 14px;
    background: rgba(37, 99, 235, .08);
    border-left: 3px solid #2563eb;
}

.message-row.me .message-reply-box {
    background: rgba(255,255,255,.16);
    border-left-color: rgba(255,255,255,.8);
}

.message-reply-box strong {
    display: block;
    font-size: 11px;
    font-weight: 950;
    color: #2563eb;
    margin-bottom: 3px;
}

.message-row.me .message-reply-box strong {
    color: #fff;
}

.message-reply-box p {
    margin: 0;
    font-size: 11px;
    color: #64748b;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.message-row.me .message-reply-box p {
    color: rgba(255,255,255,.84);
}

.message-actions {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    margin-left: 6px;
}

.chat-action-btn {
    border: 0;
    background: transparent;
    padding: 0;
    font-size: 11px;
    font-weight: 900;
    cursor: pointer;
    transition: .15s ease;
}
.chat-action-btn:hover {
    color: #2563eb;
}

.chat-action-btn.danger:hover {
    color: #ef4444;
}

.message-row.me .chat-action-btn {
    color: #dbeafe;
}

.message-row.me .chat-action-btn:hover {
    color: #bfdbfe;
}

.deleted-message {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-style: italic;
    color: #94a3b8;
    background: #f8fafc;
}

.message-row.me .deleted-message {
    background: rgba(255,255,255,.16);
    color: rgba(255,255,255,.82);
}

.chat-image-preview {
    margin-top: 9px;
    overflow: hidden;
    border-radius: 16px;
    border: 1px solid rgba(226, 232, 240, .9);
    background: #fff;
}

.message-row.me .chat-image-preview {
    border-color: rgba(255,255,255,.26);
    background: rgba(255,255,255,.12);
}

.chat-image-preview img {
    display: block;
    width: min(260px, 100%);
    max-height: 220px;
    object-fit: cover;
}

.chat-file-caption {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 10px;
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.message-row.me .chat-file-caption {
    color: rgba(255,255,255,.9);
}

.chat-doc-card {
    margin-top: 9px;
    width: min(360px, 100%);
    display: grid;
    grid-template-columns: 46px minmax(0, 1fr) 38px;
    align-items: center;
    gap: 10px;
    padding: 11px;
    border-radius: 18px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    box-shadow: 0 10px 24px rgba(15,23,42,.06);
}

.message-row.me .chat-doc-card {
    background: rgba(255,255,255,.16);
    border-color: rgba(255,255,255,.24);
    box-shadow: none;
}

.chat-doc-icon {
    width: 46px;
    height: 46px;
    border-radius: 16px;
    display: grid;
    place-items: center;
    background: #fee2e2;
    color: #dc2626;
    font-size: 23px;
}

.chat-doc-icon.word {
    background: #dbeafe;
    color: #2563eb;
}

.chat-doc-info {
    min-width: 0;
}

.chat-doc-info strong {
    display: block;
    color: #0f172a;
    font-size: 12.5px;
    font-weight: 900;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.message-row.me .chat-doc-info strong {
    color: #fff;
}

.chat-doc-info span {
    display: block;
    margin-top: 4px;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 800;
}

.message-row.me .chat-doc-info span {
    color: rgba(255,255,255,.82);
}

.chat-doc-action {
    width: 38px;
    height: 38px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    background: #eff6ff;
    color: #2563eb;
    text-decoration: none;
    font-size: 18px;
}

.message-row.me .chat-doc-action {
    background: rgba(255,255,255,.18);
    color: #fff;
}
.message-row.other .chat-action-btn {
    color: #64748b;
}

.message-row.other .chat-action-btn:hover {
    color: #2563eb;
}

.chat-action-btn.danger:hover {
    color: #ef4444 !important;
}
.message-meta {
    opacity: .92;
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

.reply-preview {
    display: none;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 8px;
    padding: 10px 12px;
    border-radius: 16px;
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    border-left: 4px solid #2563eb;
}

.reply-preview.show {
    display: flex;
}

.reply-preview strong {
    display: block;
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 950;
    margin-bottom: 3px;
}

.reply-preview p {
    margin: 0;
    color: #475569;
    font-size: 11px;
    line-height: 1.45;
    max-width: 520px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.reply-preview button {
    width: 30px;
    height: 30px;
    border: 0;
    border-radius: 11px;
    background: #ffffff;
    color: #2563eb;
    cursor: pointer;
    display: grid;
    place-items: center;
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
        max-width: 84%;
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

    .message-actions {
        width: 100%;
        margin-left: 0;
        margin-top: 2px;
    }

    .chat-doc-card {
        grid-template-columns: 40px minmax(0, 1fr) 34px;
        padding: 9px;
        border-radius: 15px;
    }

    .chat-doc-icon {
        width: 40px;
        height: 40px;
        border-radius: 14px;
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
.message-row.me .message-meta,
.message-row.me .message-meta span {
    color: #56585b !important;
}

.message-row.me .chat-action-btn {
    color: #404142 !important;
    font-weight: 800;
}

.message-row.me .chat-action-btn:hover {
    color: #c46e6e !important;
}
.message-row.me .chat-action-btn {
    color: #677d8b !important;
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

            <span class="chat-status-pill" id="chatStatusPill">Memuat status...</span>
        </header>

        <main class="chat-messages" id="chatMessages">
            <div class="chat-empty-message">
                <div>
                    <i class="ri-chat-smile-3-line"></i>
                    Memuat pesan...
                </div>
            </div>
        </main>

        <footer class="chat-composer">
            <form action="<?= base_url('/chat/send') ?>" method="post" enctype="multipart/form-data" class="composer-form">
                <?= csrf_field() ?>

                <input type="hidden" name="room_id" value="<?= esc((string) ($room['id'] ?? '')) ?>">
                <input type="hidden" name="reply_to_id" id="replyToId" value="">

                <label for="chatFile" class="file-trigger" title="Lampirkan file">
                    <i class="ri-attachment-2"></i>
                </label>

                <input
                    type="file"
                    name="file"
                    id="chatFile"
                    class="file-input-hidden"
                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp"
                >

                <div class="composer-input-wrap">
                    <div class="reply-preview" id="replyPreview">
                        <div>
                            <strong id="replyPreviewName">Membalas pesan</strong>
                            <p id="replyPreviewText">-</p>
                        </div>

                        <button type="button" onclick="clearReply()" aria-label="Batalkan reply">
                            <i class="ri-close-line"></i>
                        </button>
                    </div>

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

            <form action="<?= base_url('/chat/edit') ?>" method="post" id="editMessageForm" style="display:none;">
                <?= csrf_field() ?>
                <input type="hidden" name="message_id" id="editMessageId">
                <input type="hidden" name="message" id="editMessageText">
            </form>

            <form action="<?= base_url('/chat/delete') ?>" method="post" id="deleteMessageForm" style="display:none;">
                <?= csrf_field() ?>
                <input type="hidden" name="message_id" id="deleteMessageId">
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

function escapeJs(value) {
    return String(value ?? '')
        .replace(/\\/g, '\\\\')
        .replace(/'/g, "\\'")
        .replace(/"/g, '&quot;')
        .replace(/\n/g, ' ');
}

function formatMessageDate(value) {
    return value || '';
}

function avatarHtml(foto, nama) {
    const name = nama || 'U';
    const initial = name.substring(0, 1).toUpperCase();

    if (foto) {
        return `<img src="<?= base_url('uploads/profile') ?>/${escapeHtml(foto)}" alt="Foto Profil">`;
    }

    return `<span>${escapeHtml(initial)}</span>`;
}

function fileHtml(msg) {
    if (!msg.file_path) return '';

    const fileName = msg.file_original_name || 'File Lampiran';
    const fileUrl = `<?= base_url() ?>${msg.file_path}`;
    const ext = fileName.split('.').pop().toLowerCase();

    const isImage = ['jpg', 'jpeg', 'png', 'webp'].includes(ext);
    const isPdf = ext === 'pdf';
    const isWord = ['doc', 'docx'].includes(ext);

    if (isImage) {
        return `
            <div class="chat-image-preview">
                <a href="${fileUrl}" target="_blank">
                    <img src="${fileUrl}" alt="${escapeHtml(fileName)}">
                </a>

                <div class="chat-file-caption">
                    <i class="ri-image-line"></i>
                    ${escapeHtml(fileName)}
                </div>
            </div>
        `;
    }

    if (isPdf) {
        return `
            <div class="chat-doc-card pdf-card">
                <div class="chat-doc-icon">
                    <i class="ri-file-pdf-2-fill"></i>
                </div>

                <div class="chat-doc-info">
                    <strong>${escapeHtml(fileName)}</strong>
                    <span>Dokumen PDF</span>
                </div>

                <a href="${fileUrl}" target="_blank" class="chat-doc-action">
                    <i class="ri-external-link-line"></i>
                </a>
            </div>
        `;
    }

    return `
        <div class="chat-doc-card">
            <div class="chat-doc-icon ${isWord ? 'word' : ''}">
                <i class="${isWord ? 'ri-file-word-2-fill' : 'ri-file-3-fill'}"></i>
            </div>

            <div class="chat-doc-info">
                <strong>${escapeHtml(fileName)}</strong>
                <span>Dokumen Lampiran</span>
            </div>

            <a href="${fileUrl}" target="_blank" class="chat-doc-action">
                <i class="ri-external-link-line"></i>
            </a>
        </div>
    `;
}

function messageHtml(msg) {
    const isMe = parseInt(msg.sender_user_id) === currentUserId;
    const isDeleted = parseInt(msg.is_deleted || 0) === 1;
    const senderName = msg.sender_name || 'User';
    const senderFoto = msg.sender_foto || '';

    if (isDeleted) {
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

                    <div class="message-bubble deleted-message">
                        <i class="ri-delete-bin-line"></i>
                        Pesan ini telah dihapus
                    </div>
                </div>
            </div>
        `;
    }

    const text = msg.message ? escapeHtml(msg.message).replace(/\n/g, '<br>') : '';
    const replyText = msg.reply_message || msg.reply_file_name || '';
    const replySender = msg.reply_sender_name || 'Pesan';

    const replyBox = replyText
        ? `
            <div class="message-reply-box">
                <strong>${escapeHtml(replySender)}</strong>
                <p>${escapeHtml(replyText)}</p>
            </div>
        `
        : '';

    const actionButtons = isMe
        ? `
            <button type="button" class="chat-action-btn"
                onclick="setReply(${parseInt(msg.id)}, '${escapeJs(senderName)}', '${escapeJs(msg.message || msg.file_original_name || 'File lampiran')}')">
                Reply
            </button>

            <button type="button" class="chat-action-btn"
                onclick="openEditMessage(${parseInt(msg.id)}, '${escapeJs(msg.message || '')}')">
                Edit
            </button>

            <button type="button" class="chat-action-btn danger"
                onclick="deleteMessage(${parseInt(msg.id)})">
                Hapus
            </button>
        `
        : `
            <button type="button" class="chat-action-btn"
                onclick="setReply(${parseInt(msg.id)}, '${escapeJs(senderName)}', '${escapeJs(msg.message || msg.file_original_name || 'File lampiran')}')">
                Reply
            </button>
        `;

    const metaHtml = `
        <div class="message-meta">
            <span>${isMe ? 'Saya' : escapeHtml(senderName)}</span>
            <span>•</span>
            <span>${escapeHtml(formatMessageDate(msg.created_at))}</span>
            ${msg.edited_at ? '<span>• diedit</span>' : ''}
            <span class="message-actions">${actionButtons}</span>
        </div>
    `;

    return `
        <div class="message-row ${isMe ? 'me' : 'other'}">
            <div class="message-avatar">
                ${avatarHtml(senderFoto, senderName)}
            </div>

            <div class="message-bubble-wrap">
                ${metaHtml}

                <div class="message-bubble">
                    ${replyBox}
                    ${text ? `<div>${text}</div>` : ''}
                    ${fileHtml(msg)}
                </div>
            </div>
        </div>
    `;
}

let lastMessageSignature = '';

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

        const signature = JSON.stringify(data.messages.map(msg => [
            msg.id,
            msg.message,
            msg.file_path,
            msg.is_deleted,
            msg.edited_at,
            msg.reply_to_id
        ]));

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
            lastMessageSignature = signature;
            return;
        }

        if (signature !== lastMessageSignature) {
            chatMessages.innerHTML = data.messages.map(messageHtml).join('');
            lastMessageSignature = signature;

            if (shouldScroll) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
    } catch (error) {
        console.error(error);
    }
}

async function loadChatStatus() {
    const statusPill = document.getElementById('chatStatusPill');
    if (!statusPill || !roomId) return;

    try {
        const response = await fetch(`<?= base_url('/chat/status') ?>/${roomId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (!data.success) return;

        statusPill.textContent = data.is_online ? 'Online' : 'Terakhir aktif';
        statusPill.classList.toggle('offline', !data.is_online);
        statusPill.title = data.last_seen ? 'Terakhir aktif: ' + data.last_seen : '';
    } catch (error) {
        console.error(error);
    }
}

const replyToId = document.getElementById('replyToId');
const replyPreview = document.getElementById('replyPreview');
const replyPreviewName = document.getElementById('replyPreviewName');
const replyPreviewText = document.getElementById('replyPreviewText');

function setReply(id, name, text) {
    if (!replyToId || !replyPreview || !replyPreviewName || !replyPreviewText) return;

    replyToId.value = id;
    replyPreviewName.textContent = 'Membalas ' + name;
    replyPreviewText.textContent = text || 'File lampiran';
    replyPreview.classList.add('show');

    const textarea = document.querySelector('.composer-textarea');
    if (textarea) textarea.focus();
}

function clearReply() {
    if (!replyToId || !replyPreview) return;

    replyToId.value = '';
    replyPreview.classList.remove('show');
}

function openEditMessage(id, currentText) {
    const newText = prompt('Edit pesan:', currentText || '');

    if (newText === null) return;

    const cleanText = newText.trim();

    if (cleanText === '') {
        alert('Pesan tidak boleh kosong.');
        return;
    }

    document.getElementById('editMessageId').value = id;
    document.getElementById('editMessageText').value = cleanText;
    document.getElementById('editMessageForm').submit();
}

function deleteMessage(id) {
    if (!confirm('Hapus pesan ini?')) return;

    document.getElementById('deleteMessageId').value = id;
    document.getElementById('deleteMessageForm').submit();
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
loadChatStatus();

setInterval(loadMessagesRealtime, 3000);
setInterval(loadChatStatus, 15000);
</script>

<?= $this->endSection() ?>