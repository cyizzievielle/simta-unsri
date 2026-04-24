<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table            = 'dosen';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nidn',
        'kuota_maksimal',
        'no_hp',
        'bidang_keahlian',
        'created_at',
        'updated_at',
    ];

    public function getByUserId(int $userId): ?array
    {
        return $this->select('dosen.*, users.name, users.email')
            ->join('users', 'users.id = dosen.user_id')
            ->where('dosen.user_id', $userId)
            ->first();
    }
}