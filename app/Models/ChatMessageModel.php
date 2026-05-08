<?php

namespace App\Models;

use CodeIgniter\Model;

class ChatMessageModel extends Model
{
    protected $table = 'chat_messages';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'room_id',
        'sender_user_id',
        'reply_to_id',
        'message',
        'file_path',
        'file_original_name',
        'file_type',
        'file_size',
        'is_read',
        'created_at',
        'updated_at',
    ];
}