<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'nim',
        'angkatan',
        'program_studi_id',
        'no_hp',
        'alamat',
        'created_at',
        'updated_at',
    ];

    public function getByUserId(int $userId): ?array
    {
        return $this->select('mahasiswa.*, users.name, users.email, program_studi.nama_prodi')
            ->join('users', 'users.id = mahasiswa.user_id')
            ->join('program_studi', 'program_studi.id = mahasiswa.program_studi_id')
            ->where('mahasiswa.user_id', $userId)
            ->first();
    }
}