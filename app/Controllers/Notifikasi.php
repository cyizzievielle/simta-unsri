<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Notifikasi extends BaseController
{
    public function realtime()
    {
        $userId = (int) session()->get('user_id');

        if (! $userId) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $db = Database::connect();

        $notif = $db->table('notifikasi')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $unread = $db->table('notifikasi')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();

        return $this->response->setJSON([
            'success' => true,
            'unread' => $unread,
            'items' => $notif
        ]);
    }

    public function index()
{
    $userId = (int) session()->get('user_id');

    if (! $userId) {
        return redirect()->to('/login');
    }

    $db = \Config\Database::connect();

    $notifikasi = $db->table('notifikasi')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'DESC')
        ->get()
        ->getResultArray();

    return view('notifikasi/index', [
        'title'        => 'Notifikasi',
        'pageTitle'    => 'Notifikasi',
        'pageSubtitle' => 'Pantau semua update akademik dan chat terbaru.',
        'activeMenu'   => 'notifikasi',
        'notifikasi'   => $notifikasi,
    ]);
}

public function readAll()
{
    $userId = (int) session()->get('user_id');

    if (! $userId) {
        return redirect()->to('/login');
    }

    $db = \Config\Database::connect();

    $db->table('notifikasi')
        ->where('user_id', $userId)
        ->update([
            'is_read' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    return redirect()->to('/notifikasi')->with('success', 'Semua notifikasi ditandai sudah dibaca.');
}

    public function read(int $id)
    {
        $userId = (int) session()->get('user_id');

        $db = Database::connect();

        $notif = $db->table('notifikasi')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $notif) {
            return redirect()->back();
        }

        $db->table('notifikasi')
            ->where('id', $id)
            ->update([
                'is_read' => 1
            ]);

        return redirect()->to($notif['link'] ?: '/dashboard');
    }
}