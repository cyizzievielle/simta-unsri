<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Pembimbing extends BaseController
{
    public function ajukan()
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'mahasiswa') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'dosen_id'         => 'required|integer',
            'jenis_pembimbing' => 'required|in_list[pembimbing_1,pembimbing_2]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Data permohonan tidak valid.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $dosenId         = (int) $this->request->getPost('dosen_id');
        $jenisPembimbing = $this->request->getPost('jenis_pembimbing');
        $catatan         = trim((string) $this->request->getPost('catatan'));
        $periodeId       = 1;

        $cekAktif = $db->table('pembimbing_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->where('jenis_pembimbing', $jenisPembimbing)
            ->where('status_aktif', 1)
            ->countAllResults();

        if ($cekAktif > 0) {
            return redirect()->back()->with('error', 'Pembimbing untuk jenis ini sudah ditetapkan.');
        }

        $cekPending = $db->table('permohonan_pembimbing')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->where('jenis_pembimbing', $jenisPembimbing)
            ->where('status', 'menunggu')
            ->countAllResults();

        if ($cekPending > 0) {
            return redirect()->back()->with('error', 'Masih ada permohonan yang menunggu untuk jenis pembimbing ini.');
        }

        $dosen = $db->table('dosen')
            ->where('id', $dosenId)
            ->get()
            ->getRowArray();

        if (! $dosen) {
            return redirect()->back()->with('error', 'Dosen tidak ditemukan.');
        }

        $kuotaAktif = $db->table('pembimbing_mahasiswa')
            ->where('dosen_id', $dosenId)
            ->where('status_aktif', 1)
            ->countAllResults();

        $status = 'menunggu';
        if ($kuotaAktif >= (int) $dosen['kuota_maksimal']) {
            $status = 'kuota_penuh';
        }

        $db->table('permohonan_pembimbing')->insert([
            'mahasiswa_id'        => $mahasiswa['id'],
            'dosen_id'            => $dosenId,
            'periode_akademik_id' => $periodeId,
            'jenis_pembimbing'    => $jenisPembimbing,
            'status'              => $status,
            'catatan'             => $catatan ?: null,
            'tanggal_pengajuan'   => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        if ($status === 'kuota_penuh') {
            return redirect()->back()->with('error', 'Kuota dosen penuh. Silakan pilih dosen lain.');
        }

        return redirect()->to('/pembimbing')->with('success', 'Permohonan pembimbing berhasil diajukan.');
    }

public function responPermohonan($id)
{
    $userId = (int) session()->get('user_id');
    $role   = session()->get('role');

    if ($role !== 'dosen') {
        return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
    }

    $status  = trim((string) $this->request->getPost('status'));
    $catatan = trim((string) $this->request->getPost('catatan'));

    if (! in_array($status, ['disetujui', 'ditolak'], true)) {
        return redirect()->back()->with('error', 'Status keputusan tidak valid.');
    }

    $db = \Config\Database::connect();

    $dosen = $db->table('dosen')
        ->where('user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $dosen) {
        return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
    }

    $permohonan = $db->table('permohonan_pembimbing')
        ->where('id', $id)
        ->where('dosen_id', $dosen['id'])
        ->get()
        ->getRowArray();

    if (! $permohonan) {
        return redirect()->to('/dosen/permohonan')->with('error', 'Permohonan tidak ditemukan.');
    }

    if (($permohonan['status'] ?? '') !== 'menunggu') {
        return redirect()->to('/dosen/permohonan')->with('error', 'Permohonan ini sudah diproses sebelumnya.');
    }

    // ambil periode akademik aktif
    $periodeAktif = $db->table('periode_akademik')
        ->where('is_active', 1)
        ->get()
        ->getRowArray();

    if (! $periodeAktif) {
        return redirect()->to('/dosen/permohonan')->with('error', 'Periode akademik aktif tidak ditemukan.');
    }

    $db->transStart();

    $db->table('permohonan_pembimbing')
        ->where('id', $id)
        ->update([
            'status'         => $status,
            'catatan'        => $catatan !== '' ? $catatan : null,
            'tanggal_respon' => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

    if ($status === 'disetujui') {
        $sudahAda = $db->table('pembimbing_mahasiswa')
            ->where('mahasiswa_id', $permohonan['mahasiswa_id'])
            ->where('jenis_pembimbing', $permohonan['jenis_pembimbing'])
            ->where('status_aktif', 1)
            ->countAllResults();

        if ($sudahAda === 0) {
            $db->table('pembimbing_mahasiswa')->insert([
                'mahasiswa_id'        => $permohonan['mahasiswa_id'],
                'dosen_id'            => $permohonan['dosen_id'],
                'periode_akademik_id' => $periodeAktif['id'],
                'jenis_pembimbing'    => $permohonan['jenis_pembimbing'],
                'status_aktif'        => 1,
                'tanggal_penetapan'   => date('Y-m-d H:i:s'),
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);
        }

        $db->table('permohonan_pembimbing')
            ->where('mahasiswa_id', $permohonan['mahasiswa_id'])
            ->where('jenis_pembimbing', $permohonan['jenis_pembimbing'])
            ->where('id !=', $permohonan['id'])
            ->where('status', 'menunggu')
            ->update([
                'status'         => 'ditolak',
                'catatan'        => 'Permohonan otomatis ditutup karena mahasiswa sudah memiliki pembimbing untuk jenis ini.',
                'tanggal_respon' => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);
    }

    $db->transComplete();

    if (! $db->transStatus()) {
        $error = $db->error();
        return redirect()->to('/dosen/permohonan')->with('error', 'Gagal memproses permohonan. ' . ($error['message'] ?? ''));
    }

    return redirect()->to('/dosen/permohonan')->with('success', 'Permohonan berhasil diproses.');
}

public function detailPermohonan($id)
{
    $userId = (int) session()->get('user_id');
    $role   = session()->get('role');

    if ($role !== 'dosen') {
        return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();

    $dosen = $db->table('dosen')
        ->where('user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $dosen) {
        return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
    }

    $permohonan = $db->table('permohonan_pembimbing pp')
        ->select('pp.*, um.name AS nama_mahasiswa, m.nim')
        ->join('mahasiswa m', 'm.id = pp.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('pp.id', $id)
        ->where('pp.dosen_id', $dosen['id'])
        ->get()
        ->getRowArray();

    if (! $permohonan) {
        return redirect()->to('/dosen/permohonan')->with('error', 'Permohonan tidak ditemukan.');
    }

    return view('dashboard/permohonan_dosen_detail', [
        'title'        => 'Detail Permohonan Pembimbing',
        'pageTitle'    => 'Detail Permohonan Pembimbing',
        'pageSubtitle' => 'Lihat detail permohonan dan ambil keputusan',
        'activeMenu'   => 'permohonan_dosen',
        'permohonan'   => $permohonan,
    ]);
}

public function permohonanDosen()
{
    $userId = (int) session()->get('user_id');
    $role   = session()->get('role');

    if ($role !== 'dosen') {
        return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();

    $dosen = $db->table('dosen')
        ->where('user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $dosen) {
        return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
    }

    $keyword = trim((string) $this->request->getGet('q'));
    $jenis   = trim((string) $this->request->getGet('jenis'));
    $status  = trim((string) $this->request->getGet('status'));
    $perPage = (int) ($this->request->getGet('per_page') ?: 10);
    $page    = max(1, (int) ($this->request->getGet('page') ?: 1));

    if (! in_array($perPage, [10, 50, 100], true)) {
        $perPage = 10;
    }

    $builder = $db->table('permohonan_pembimbing pp')
        ->select('pp.*, um.name AS nama_mahasiswa, m.nim')
        ->join('mahasiswa m', 'm.id = pp.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('pp.dosen_id', $dosen['id']);

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('um.name', $keyword)
            ->orLike('m.nim', $keyword)
            ->groupEnd();
    }

    if ($jenis !== '' && in_array($jenis, ['pembimbing_1', 'pembimbing_2'], true)) {
        $builder->where('pp.jenis_pembimbing', $jenis);
    }

    if ($status !== '' && in_array($status, ['menunggu', 'disetujui', 'ditolak', 'kuota_penuh'], true)) {
        $builder->where('pp.status', $status);
    }

    $totalRows = (clone $builder)->countAllResults();

    $offset = ($page - 1) * $perPage;

    $rows = (clone $builder)
        ->orderBy('pp.tanggal_pengajuan', 'DESC')
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $permohonanMenunggu = [];
    $riwayatKeputusan = [];

    foreach ($rows as $row) {
        if (($row['status'] ?? '') === 'menunggu') {
            $permohonanMenunggu[] = $row;
        } else {
            $riwayatKeputusan[] = $row;
        }
    }

    $jumlahMenungguSidebar = $db->table('permohonan_pembimbing')
        ->where('dosen_id', $dosen['id'])
        ->where('status', 'menunggu')
        ->countAllResults();

    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $startRow   = $totalRows > 0 ? $offset + 1 : 0;
    $endRow     = min($offset + $perPage, $totalRows);

    return view('dashboard/permohonan_dosen', [
        'title'                 => 'Permohonan Pembimbing',
        'pageTitle'             => 'Permohonan Pembimbing',
        'pageSubtitle'          => 'Tinjau dan putuskan permohonan mahasiswa',
        'activeMenu'            => 'permohonan_dosen',

        'permohonanMenunggu'    => $permohonanMenunggu,
        'riwayatKeputusan'      => $riwayatKeputusan,

        'jumlahMenunggu'        => $jumlahMenungguSidebar,
        'jumlahMenungguSidebar' => $jumlahMenungguSidebar,

        'keyword'               => $keyword,
        'jenis'                 => $jenis,
        'status'                => $status,

        'perPage'               => $perPage,
        'page'                  => $page,
        'totalRows'             => $totalRows,
        'totalPages'            => $totalPages,
        'startRow'              => $startRow,
        'endRow'                => $endRow,
    ]);
}

    public function setujui($id)
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'dosen') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $db = Database::connect();

        $dosen = $db->table('dosen')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $permohonan = $db->table('permohonan_pembimbing')
            ->where('id', $id)
            ->where('dosen_id', $dosen['id'])
            ->get()
            ->getRowArray();

        if (! $permohonan) {
            return redirect()->back()->with('error', 'Permohonan tidak ditemukan.');
        }

        if ($permohonan['status'] !== 'menunggu') {
            return redirect()->back()->with('error', 'Permohonan ini sudah diproses.');
        }

        $kuotaAktif = $db->table('pembimbing_mahasiswa')
            ->where('dosen_id', $dosen['id'])
            ->where('status_aktif', 1)
            ->countAllResults();

        if ($kuotaAktif >= (int) $dosen['kuota_maksimal']) {
            $db->table('permohonan_pembimbing')
                ->where('id', $id)
                ->update([
                    'status'         => 'kuota_penuh',
                    'tanggal_respon' => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);

            return redirect()->back()->with('error', 'Kuota bimbingan sudah penuh.');
        }

        $cekSudahAda = $db->table('pembimbing_mahasiswa')
            ->where('mahasiswa_id', $permohonan['mahasiswa_id'])
            ->where('jenis_pembimbing', $permohonan['jenis_pembimbing'])
            ->where('status_aktif', 1)
            ->countAllResults();

        if ($cekSudahAda > 0) {
            return redirect()->back()->with('error', 'Mahasiswa sudah memiliki pembimbing aktif untuk jenis ini.');
        }

        $db->transStart();

        $db->table('permohonan_pembimbing')
            ->where('id', $id)
            ->update([
                'status'         => 'disetujui',
                'tanggal_respon' => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);

        $db->table('pembimbing_mahasiswa')->insert([
            'mahasiswa_id'        => $permohonan['mahasiswa_id'],
            'dosen_id'            => $permohonan['dosen_id'],
            'periode_akademik_id' => $permohonan['periode_akademik_id'],
            'jenis_pembimbing'    => $permohonan['jenis_pembimbing'],
            'status_aktif'        => 1,
            'tanggal_penetapan'   => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        $db->table('permohonan_pembimbing')
            ->where('mahasiswa_id', $permohonan['mahasiswa_id'])
            ->where('jenis_pembimbing', $permohonan['jenis_pembimbing'])
            ->where('status', 'menunggu')
            ->where('id !=', $permohonan['id'])
            ->update([
                'status'         => 'ditolak',
                'catatan'        => 'Ditutup otomatis karena mahasiswa sudah mendapatkan pembimbing.',
                'tanggal_respon' => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);

        $db->transComplete();

        return redirect()->back()->with('success', 'Permohonan berhasil disetujui.');
    }

    public function tolak($id)
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'dosen') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $db = Database::connect();

        $dosen = $db->table('dosen')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $permohonan = $db->table('permohonan_pembimbing')
            ->where('id', $id)
            ->where('dosen_id', $dosen['id'])
            ->get()
            ->getRowArray();

        if (! $permohonan) {
            return redirect()->back()->with('error', 'Permohonan tidak ditemukan.');
        }

        if ($permohonan['status'] !== 'menunggu') {
            return redirect()->back()->with('error', 'Permohonan ini sudah diproses.');
        }

        $catatan = trim((string) $this->request->getPost('catatan'));

        $db->table('permohonan_pembimbing')
            ->where('id', $id)
            ->update([
                'status'         => 'ditolak',
                'catatan'        => $catatan ?: 'Permohonan ditolak oleh dosen.',
                'tanggal_respon' => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s'),
            ]);

        return redirect()->back()->with('success', 'Permohonan berhasil ditolak.');
    }
}