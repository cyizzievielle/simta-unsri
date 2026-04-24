<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Profile extends BaseController
{
    private function getProfileData()
    {
        $db = Database::connect();

        $userId = (int) session()->get('user_id');
        $role   = (string) session()->get('role');

        $user = $db->table('users')
            ->where('id', $userId)
            ->get()
            ->getRowArray();

        $detail = [];

        if ($role === 'mahasiswa') {
            $detail = $db->table('mahasiswa m')
                ->select('m.*, ps.nama_prodi')
                ->join('program_studi ps', 'ps.id = m.program_studi_id', 'left')
                ->where('m.user_id', $userId)
                ->get()
                ->getRowArray() ?? [];
        }

        if ($role === 'dosen') {
            $detail = $db->table('dosen')
                ->where('user_id', $userId)
                ->get()
                ->getRowArray() ?? [];
        }

        return [$user, $detail];
    }

    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        [$user, $detail] = $this->getProfileData();

        return view('dashboard/index', [
            'title'        => 'Profil Saya',
            'pageTitle'    => 'Profil Saya',
            'pageSubtitle' => 'Kelola informasi akun dan data pribadi',
            'activeMenu'   => 'profile',
            'mode'         => 'show',
            'user'         => $user,
            'detail'       => $detail,
        ]);
    }

    public function edit()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        [$user, $detail] = $this->getProfileData();

        return view('dashboard/index', [
            'title'        => 'Edit Profil',
            'pageTitle'    => 'Edit Profil',
            'pageSubtitle' => 'Perbarui informasi akun dan data pribadi',
            'activeMenu'   => 'profile',
            'mode'         => 'edit',
            'user'         => $user,
            'detail'       => $detail,
        ]);
    }

    public function update()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $db = Database::connect();

        $userId = (int) session()->get('user_id');
        $role   = (string) session()->get('role');

        $name  = trim((string) $this->request->getPost('name'));
        $email = trim((string) $this->request->getPost('email'));
        $noHp  = trim((string) $this->request->getPost('no_hp'));

        if ($name === '' || $email === '') {
            return redirect()->back()->withInput()->with('error', 'Nama dan email wajib diisi.');
        }

        $cekEmail = $db->table('users')
            ->where('email', $email)
            ->where('id !=', $userId)
            ->countAllResults();

        if ($cekEmail > 0) {
            return redirect()->back()->withInput()->with('error', 'Email sudah digunakan user lain.');
        }

        $db->transStart();

        $db->table('users')
            ->where('id', $userId)
            ->update([
                'name'       => $name,
                'email'      => $email,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        if ($role === 'mahasiswa') {
            $alamat = trim((string) $this->request->getPost('alamat'));

            $db->table('mahasiswa')
                ->where('user_id', $userId)
                ->update([
                    'no_hp'      => $noHp !== '' ? $noHp : null,
                    'alamat'     => $alamat !== '' ? $alamat : null,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
        }

        if ($role === 'dosen') {
            $bidangKeahlian = trim((string) $this->request->getPost('bidang_keahlian'));

            $db->table('dosen')
                ->where('user_id', $userId)
                ->update([
                    'no_hp'           => $noHp !== '' ? $noHp : null,
                    'bidang_keahlian' => $bidangKeahlian !== '' ? $bidangKeahlian : null,
                    'updated_at'      => date('Y-m-d H:i:s'),
                ]);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil.');
        }

        session()->set([
            'name'  => $name,
            'email' => $email,
        ]);

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }
}