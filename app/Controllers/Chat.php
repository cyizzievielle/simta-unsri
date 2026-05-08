<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Chat extends BaseController
{
    public function index()
    {
        $userId = (int) session()->get('user_id');
        $role   = (string) session()->get('role');

        if (! $userId) {
            return redirect()->to('/login');
        }

        $db = Database::connect();

        if ($role === 'mahasiswa') {
            $mahasiswa = $db->table('mahasiswa')
                ->where('user_id', $userId)
                ->get()
                ->getRowArray();

            if (! $mahasiswa) {
                return redirect()->to('/dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
            }

            $this->syncMahasiswaRooms((int) $mahasiswa['id']);

            $rooms = $db->table('chat_rooms cr')
                ->select('
                    cr.*,
                    d.id AS dosen_id,
                    ud.name AS nama_lawan,
                    ud.email AS email_lawan,
                    ud.foto AS foto_lawan,
                    pm.jenis_pembimbing,
                    (
                        SELECT cm.message
                        FROM chat_messages cm
                        WHERE cm.room_id = cr.id
                        ORDER BY cm.created_at DESC
                        LIMIT 1
                    ) AS last_message
                ')
                ->join('dosen d', 'd.id = cr.dosen_id', 'left')
                ->join('users ud', 'ud.id = d.user_id', 'left')
                ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = cr.mahasiswa_id AND pm.dosen_id = cr.dosen_id AND pm.status_aktif = 1', 'left')
                ->where('cr.room_type', 'mahasiswa_dosen')
                ->where('cr.mahasiswa_id', $mahasiswa['id'])
                ->orderBy('cr.last_message_at', 'DESC')
                ->get()
                ->getResultArray();

            $this->syncMahasiswaRooms((int) $mahasiswa['id']);

        } elseif ($role === 'dosen') {
            $dosen = $db->table('dosen')
                ->where('user_id', $userId)
                ->get()
                ->getRowArray();

            if (! $dosen) {
                return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
            }

            $rooms = $db->table('chat_rooms cr')
                ->select('
                    cr.*,
                    m.id AS mahasiswa_id,
                    m.nim,
                    um.name AS nama_lawan,
                    um.email AS email_lawan,
                    um.foto AS foto_lawan,
                    pm.jenis_pembimbing,
                    (
                        SELECT cm.message
                        FROM chat_messages cm
                        WHERE cm.room_id = cr.id
                        ORDER BY cm.created_at DESC
                        LIMIT 1
                    ) AS last_message
                ')
                ->join('mahasiswa m', 'm.id = cr.mahasiswa_id', 'left')
                ->join('users um', 'um.id = m.user_id', 'left')
                ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = cr.mahasiswa_id AND pm.dosen_id = cr.dosen_id AND pm.status_aktif = 1', 'left')
                ->where('cr.room_type', 'mahasiswa_dosen')
                ->where('cr.dosen_id', $dosen['id'])
                ->orderBy('cr.last_message_at', 'DESC')
                ->get()
                ->getResultArray();

        } else {
            $rooms = $db->table('chat_rooms cr')
                ->select('
                    cr.*,
                    ud.name AS nama_lawan,
                    ud.email AS email_lawan,
                    ud.foto AS foto_lawan,
                    (
                        SELECT cm.message
                        FROM chat_messages cm
                        WHERE cm.room_id = cr.id
                        ORDER BY cm.created_at DESC
                        LIMIT 1
                    ) AS last_message
                ')
                ->join('dosen d', 'd.id = cr.dosen_id', 'left')
                ->join('users ud', 'ud.id = d.user_id', 'left')
                ->where('cr.room_type', 'dosen_admin')
                ->orderBy('cr.last_message_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        return view('chat/index', [
            'title'        => 'Chat Bimbingan',
            'pageTitle'    => 'Chat Bimbingan',
            'pageSubtitle' => 'Diskusi akademik dengan pembimbing, mahasiswa, dan admin.',
            'activeMenu'   => 'chat',
            'rooms'        => $rooms ?? [],
        ]);
    }

    public function messages(int $roomId)
{
    $userId = (int) session()->get('user_id');

    if (! $userId) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Unauthorized',
        ]);
    }

    $db = \Config\Database::connect();

    $messages = $db->table('chat_messages cm')
        ->select('cm.*, u.name AS sender_name, u.role AS sender_role, u.foto AS sender_foto')
        ->join('users u', 'u.id = cm.sender_user_id', 'left')
        ->where('cm.room_id', $roomId)
        ->orderBy('cm.created_at', 'ASC')
        ->get()
        ->getResultArray();

    $db->table('chat_messages')
        ->where('room_id', $roomId)
        ->where('sender_user_id !=', $userId)
        ->update(['is_read' => 1]);

    return $this->response->setJSON([
        'success' => true,
        'user_id' => $userId,
        'messages' => $messages,
    ]);
}

    public function room(int $roomId)
    {
        $userId = (int) session()->get('user_id');

        if (! $userId) {
            return redirect()->to('/login');
        }

        $db = Database::connect();

        $room = $db->table('chat_rooms')
            ->where('id', $roomId)
            ->get()
            ->getRowArray();

        if (! $room) {
            return redirect()->to('/chat')->with('error', 'Room chat tidak ditemukan.');
        }
        $role = (string) session()->get('role');

$roomDetail = $db->table('chat_rooms cr')
    ->select('
        cr.*,
        um.name AS nama_mahasiswa,
        um.foto AS foto_mahasiswa,
        ud.name AS nama_dosen,
        ud.foto AS foto_dosen,
        pm.jenis_pembimbing
    ')
    ->join('mahasiswa m', 'm.id = cr.mahasiswa_id', 'left')
    ->join('users um', 'um.id = m.user_id', 'left')
    ->join('dosen d', 'd.id = cr.dosen_id', 'left')
    ->join('users ud', 'ud.id = d.user_id', 'left')
    ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = cr.mahasiswa_id AND pm.dosen_id = cr.dosen_id AND pm.status_aktif = 1', 'left')
    ->where('cr.id', $roomId)
    ->get()
    ->getRowArray();

$lawanNama = 'User';
$lawanFoto = '';
$lawanRole = 'Chat Akademik';

if ($roomDetail) {
    if ($role === 'mahasiswa') {
        $lawanNama = $roomDetail['nama_dosen'] ?? 'Dosen';
        $lawanFoto = $roomDetail['foto_dosen'] ?? '';
        $lawanRole = ($roomDetail['jenis_pembimbing'] ?? '') === 'pembimbing_2'
            ? 'Pembimbing 2'
            : 'Pembimbing 1';
    } else {
        $lawanNama = $roomDetail['nama_mahasiswa'] ?? 'Mahasiswa';
        $lawanFoto = $roomDetail['foto_mahasiswa'] ?? '';
        $lawanRole = 'Mahasiswa Bimbingan';
    }
}

        $messages = $db->table('chat_messages cm')
            ->select('cm.*, u.name AS sender_name, u.role AS sender_role, u.foto AS sender_foto')
            ->join('users u', 'u.id = cm.sender_user_id', 'left')
            ->where('cm.room_id', $roomId)
            ->orderBy('cm.created_at', 'ASC')
            ->get()
            ->getResultArray();

        $db->table('chat_messages')
            ->where('room_id', $roomId)
            ->where('sender_user_id !=', $userId)
            ->update(['is_read' => 1]);

        return view('chat/room', [
            'title'        => 'Room Chat',
            'pageTitle'    => 'Room Chat',
            'pageSubtitle' => 'Kirim pesan, catatan revisi, dan file bimbingan.',
            'activeMenu'   => 'chat',
            'room'         => $room,
            'messages'     => $messages,
            'roomDetail' => $roomDetail,
            'lawanNama'  => $lawanNama,
            'lawanFoto'  => $lawanFoto,
            'lawanRole'  => $lawanRole,
        ]);
    }

    public function send()
    {
        $userId = (int) session()->get('user_id');
        $roomId = (int) $this->request->getPost('room_id');
        $message = trim((string) $this->request->getPost('message'));

        if (! $userId || ! $roomId) {
            return redirect()->back()->with('error', 'Data chat tidak valid.');
        }

        if ($message === '' && ! $this->request->getFile('file')) {
            return redirect()->back()->with('error', 'Pesan atau file wajib diisi.');
        }

        $db = Database::connect();

        $filePath = null;
        $fileOriginalName = null;
        $fileType = null;
        $fileSize = null;

        $file = $this->request->getFile('file');

        if ($file && $file->isValid() && ! $file->hasMoved()) {
            $allowed = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
            $ext = strtolower($file->getExtension());

            if (! in_array($ext, $allowed, true)) {
                return redirect()->back()->with('error', 'Format file harus PDF, DOC, DOCX, JPG, atau PNG.');
            }

            if ($file->getSizeByUnit('mb') > 5) {
                return redirect()->back()->with('error', 'Ukuran file maksimal 5MB.');
            }

            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/chat', $newName);

            $filePath = 'uploads/chat/' . $newName;
            $fileOriginalName = $file->getClientName();
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();
        }

        $now = date('Y-m-d H:i:s');

        $db->table('chat_messages')->insert([
            'room_id'            => $roomId,
            'sender_user_id'     => $userId,
            'message'            => $message !== '' ? $message : null,
            'file_path'          => $filePath,
            'file_original_name' => $fileOriginalName,
            'file_type'          => $fileType,
            'file_size'          => $fileSize,
            'is_read'            => 0,
            'created_at'         => $now,
            'updated_at'         => $now,
        ]);

        $db->table('chat_rooms')
            ->where('id', $roomId)
            ->update([
                'last_message_at' => $now,
                'updated_at'      => $now,
            ]);

        $room = $db->table('chat_rooms')
            ->where('id', $roomId)
            ->get()
            ->getRowArray();

        $penerimaUserId = null;

        if ($room) {
            $senderRole = (string) session()->get('role');

            if ($senderRole === 'mahasiswa') {
                $dosen = $db->table('dosen')
                    ->where('id', $room['dosen_id'])
                    ->get()
                    ->getRowArray();

                if ($dosen) {
                    $penerimaUserId = (int) $dosen['user_id'];
                }
            } elseif ($senderRole === 'dosen') {
                $mahasiswa = $db->table('mahasiswa')
                    ->where('id', $room['mahasiswa_id'])
                    ->get()
                    ->getRowArray();

                if ($mahasiswa) {
                    $penerimaUserId = (int) $mahasiswa['user_id'];
                }
            }
        }

        if ($penerimaUserId) {
            buat_notifikasi(
                $penerimaUserId,
                'Pesan Chat Baru',
                'Ada pesan baru di ruang chat bimbingan.',
                'chat',
                base_url('/chat/room/' . $roomId)
            );
        }
        catat_audit(
            'chat',
            'send_message',
            'User mengirim pesan chat bimbingan.',
            null,
            [
                'room_id' => $roomId,
                'message' => $message,
                'file' => $fileOriginalName,
            ]
        );

        return redirect()->to('/chat/room/' . $roomId);
    }

    private function syncMahasiswaRooms(int $mahasiswaId): void
    {
        $db = Database::connect();

        $pembimbing = $db->table('pembimbing_mahasiswa')
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status_aktif', 1)
            ->get()
            ->getResultArray();

        foreach ($pembimbing as $p) {
            $exists = $db->table('chat_rooms')
                ->where('room_type', 'mahasiswa_dosen')
                ->where('mahasiswa_id', $mahasiswaId)
                ->where('dosen_id', $p['dosen_id'])
                ->countAllResults();

            if ($exists === 0) {
                $db->table('chat_rooms')->insert([
                    'room_type'      => 'mahasiswa_dosen',
                    'mahasiswa_id'   => $mahasiswaId,
                    'dosen_id'       => $p['dosen_id'],
                    'last_message_at'=> date('Y-m-d H:i:s'),
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}