<?php

use Config\Database;

if (! function_exists('buat_notifikasi')) {
    function buat_notifikasi(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?string $link = null
    ): bool {
        $db = Database::connect();

        return $db->table('notifikasi')->insert([
            'user_id'    => $userId,
            'judul'      => $judul,
            'pesan'      => $pesan,
            'tipe'       => $tipe,
            'link'       => $link,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

if (! function_exists('catat_audit')) {
    function catat_audit(
        string $module,
        string $action,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): bool {
        $db = Database::connect();

        return $db->table('audit_logs')->insert([
            'user_id'     => session()->get('user_id') ?: null,
            'role'        => session()->get('role') ?: null,
            'module'      => $module,
            'action'      => $action,
            'description' => $description,
            'old_values'  => $oldValues ? json_encode($oldValues, JSON_UNESCAPED_UNICODE) : null,
            'new_values'  => $newValues ? json_encode($newValues, JSON_UNESCAPED_UNICODE) : null,
            'ip_address'  => service('request')->getIPAddress(),
            'user_agent'  => service('request')->getUserAgent()->getAgentString(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}