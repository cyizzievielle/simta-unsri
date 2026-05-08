<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatRoomModel extends Model
{
    protected $table = 'chat_rooms';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'room_type',
        'mahasiswa_id',
        'dosen_id',
        'admin_user_id',
        'pengajuan_judul_id',
        'proposal_ta_id',
        'last_message_at',
        'created_at',
        'updated_at',
    ];
}