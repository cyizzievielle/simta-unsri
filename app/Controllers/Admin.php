<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Dompdf\Dompdf;
use Dompdf\Options;

class Admin extends BaseController
{
    private function guardAdmin()
    {
        if (! session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses admin ditolak.');
        }

        return null;
    }

public function users()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $keyword = trim((string) $this->request->getGet('keyword'));
    $role    = trim((string) $this->request->getGet('role'));
    $status  = trim((string) $this->request->getGet('status'));
    $sort    = trim((string) $this->request->getGet('sort'));

    $allowedSorts = [
        'newest',
        'oldest',
        'name_asc',
        'name_desc',
        'email_asc',
        'email_desc',
    ];

    if (! in_array($sort, $allowedSorts, true)) {
        $sort = 'newest';
    }

    $builder = $db->table('users u')
        ->select('u.*, m.nim, d.nidn')
        ->join('mahasiswa m', 'm.user_id = u.id', 'left')
        ->join('dosen d', 'd.user_id = u.id', 'left');

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('u.name', $keyword)
            ->orLike('u.email', $keyword)
            ->groupEnd();
    }

    if (in_array($role, ['admin', 'mahasiswa', 'dosen'], true)) {
        $builder->where('u.role', $role);
    }

    if ($status !== '') {
        if ($status === 'aktif') {
            $builder->where('u.is_active', 1);
        } elseif ($status === 'nonaktif') {
            $builder->where('u.is_active', 0);
        }
    }

    switch ($sort) {
        case 'oldest':
            $builder->orderBy('u.id', 'ASC');
            break;
        case 'name_asc':
            $builder->orderBy('u.name', 'ASC');
            break;
        case 'name_desc':
            $builder->orderBy('u.name', 'DESC');
            break;
        case 'email_asc':
            $builder->orderBy('u.email', 'ASC');
            break;
        case 'email_desc':
            $builder->orderBy('u.email', 'DESC');
            break;
        case 'newest':
        default:
            $builder->orderBy('u.id', 'DESC');
            break;
    }

    $perPage = 8;
    $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
    $offset  = ($page - 1) * $perPage;

    $countBuilder = clone $builder;
    $totalRows = count($countBuilder->get()->getResultArray());

    $users = $builder
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $totalPages = (int) ceil($totalRows / $perPage);

    return view('dashboard/admin_users', [
        'title'        => 'Kelola Users',
        'pageTitle'    => 'Kelola Users',
        'pageSubtitle' => 'Tambah, edit, dan hapus data admin, mahasiswa, dan dosen',
        'activeMenu'   => 'admin_users',
        'users'        => $users,
        'keyword'      => $keyword,
        'roleFilter'   => $role,
        'statusFilter' => $status,
        'sortFilter'   => $sort,
        'currentPage'  => $page,
        'totalPages'   => $totalPages,
        'totalRows'    => $totalRows,
        'perPage'      => $perPage,
    ]);
}

public function createUser()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $programStudi = $db->table('program_studi')
        ->orderBy('nama_prodi', 'ASC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_user_form', [
        'title'        => 'Tambah User',
        'pageTitle'    => 'Tambah User',
        'pageSubtitle' => 'Buat akun baru untuk admin, mahasiswa, atau dosen',
        'activeMenu'   => 'admin_users',
        'mode'         => 'create',
        'user'         => [],
        'detail'       => [],
        'programStudi' => $programStudi,
    ]);
}

private function pdfResponse(string $html, string $filename = 'laporan.pdf')
{
    $options = new Options();
    $options->set('isRemoteEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    return $this->response
        ->setHeader('Content-Type', 'application/pdf')
        ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
        ->setBody($dompdf->output());
}

private function getPeriodeLaporan()
{
    $db = \Config\Database::connect();

    $periodeId = (int) ($this->request->getGet('periode_id') ?: 0);

    if ($periodeId <= 0) {
        $periodeAktif = $db->table('periode_akademik')
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if (! $periodeAktif) {
            $periodeAktif = $db->table('periode_akademik')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();
        }

        if (! $periodeAktif) {
            return null;
        }

        return $periodeAktif;
    }

    return $db->table('periode_akademik')
        ->where('id', $periodeId)
        ->get()
        ->getRowArray();
}

public function exportRekapPdf()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();
    $periode = $this->getPeriodeLaporan();

    if (! $periode) {
        return redirect()->to('/admin/laporan')->with('error', 'Periode akademik tidak ditemukan.');
    }

    $periodeId = (int) $periode['id'];

    $summary = [
        'permohonan_total' => $db->table('permohonan_pembimbing')->countAllResults(),

        'permohonan_disetujui' => $db->table('permohonan_pembimbing')
            ->where('status', 'disetujui')
            ->countAllResults(),

        'judul_total' => $db->table('pengajuan_judul')
            ->where('periode_akademik_id', $periodeId)
            ->countAllResults(),

        'judul_disetujui' => $db->table('pengajuan_judul')
            ->where('periode_akademik_id', $periodeId)
            ->where('status', 'disetujui')
            ->countAllResults(),

        'proposal_total' => $db->table('proposal_ta')
            ->where('periode_akademik_id', $periodeId)
            ->countAllResults(),

        'proposal_disetujui' => $db->table('proposal_ta')
            ->where('periode_akademik_id', $periodeId)
            ->where('status', 'disetujui')
            ->countAllResults(),

        'sk_total' => $db->table('surat_keputusan sk')
            ->join('pengajuan_judul pj', 'pj.id = sk.pengajuan_judul_id', 'left')
            ->where('pj.periode_akademik_id', $periodeId)
            ->countAllResults(),
    ];

    $html = view('pdf/laporan_rekap_semester', [
        'periode' => $periode,
        'summary' => $summary,
    ]);

    return $this->pdfResponse(
        $html,
        'rekap-semester-' . str_replace('/', '-', (string) $periode['tahun_ajaran']) . '-' . $periode['semester'] . '.pdf'
    );
}

public function exportJudulPdf()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();
    $periode = $this->getPeriodeLaporan();

    if (! $periode) {
        return redirect()->to('/admin/laporan')->with('error', 'Periode akademik tidak ditemukan.');
    }

    $rows = $db->table('pengajuan_judul pj')
        ->select('pj.*, m.nim, um.name as nama_mahasiswa')
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('pj.periode_akademik_id', $periode['id'])
        ->orderBy('pj.tanggal_pengajuan', 'DESC')
        ->get()
        ->getResultArray();

    $html = view('pdf/laporan_judul', [
        'periode' => $periode,
        'rows'    => $rows,
    ]);

    return $this->pdfResponse(
        $html,
        'arsip-judul-' . str_replace('/', '-', (string) $periode['tahun_ajaran']) . '-' . $periode['semester'] . '.pdf'
    );
}

public function exportProposalPdf()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();
    $periode = $this->getPeriodeLaporan();

    if (! $periode) {
        return redirect()->to('/admin/laporan')->with('error', 'Periode akademik tidak ditemukan.');
    }

    $rows = $db->table('proposal_ta pt')
        ->select('pt.*, pj.judul, m.nim, um.name as nama_mahasiswa')
        ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('pt.periode_akademik_id', $periode['id'])
        ->orderBy('pt.tanggal_upload', 'DESC')
        ->get()
        ->getResultArray();

    $html = view('pdf/laporan_proposal', [
        'periode' => $periode,
        'rows'    => $rows,
    ]);

    return $this->pdfResponse(
        $html,
        'arsip-proposal-' . str_replace('/', '-', (string) $periode['tahun_ajaran']) . '-' . $periode['semester'] . '.pdf'
    );
}

public function exportSkPdf()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();
    $periode = $this->getPeriodeLaporan();

    if (! $periode) {
        return redirect()->to('/admin/laporan')->with('error', 'Periode akademik tidak ditemukan.');
    }

    $rows = $db->table('surat_keputusan sk')
        ->select('sk.*, pj.judul, m.nim, um.name as nama_mahasiswa')
        ->join('pengajuan_judul pj', 'pj.id = sk.pengajuan_judul_id', 'left')
        ->join('mahasiswa m', 'm.id = sk.mahasiswa_id', 'left')
        ->join('users um', 'um.id = m.user_id', 'left')
        ->where('pj.periode_akademik_id', $periode['id'])
        ->orderBy('sk.tanggal_terbit', 'DESC')
        ->get()
        ->getResultArray();

    $html = view('pdf/laporan_sk', [
        'periode' => $periode,
        'rows'    => $rows,
    ]);

    return $this->pdfResponse(
        $html,
        'arsip-sk-' . str_replace('/', '-', (string) $periode['tahun_ajaran']) . '-' . $periode['semester'] . '.pdf'
    );
}

public function storeUser()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $name            = trim((string) $this->request->getPost('name'));
    $email           = trim((string) $this->request->getPost('email'));
    $password        = (string) $this->request->getPost('password');
    $role            = trim((string) $this->request->getPost('role'));
    $isActive        = (int) ($this->request->getPost('is_active') ?? 1);

    $nim             = trim((string) $this->request->getPost('nim'));
    $angkatan        = trim((string) $this->request->getPost('angkatan'));
    $programStudiId  = $this->request->getPost('program_studi_id') !== '' ? (int) $this->request->getPost('program_studi_id') : null;
    $noHpMahasiswa   = trim((string) $this->request->getPost('no_hp_mahasiswa'));
    $alamat          = trim((string) $this->request->getPost('alamat'));

    $nidn            = trim((string) $this->request->getPost('nidn'));
    $nip             = trim((string) $this->request->getPost('nip'));
    $kuotaMaksimal   = (int) ($this->request->getPost('kuota_maksimal') ?: 25);
    $noHpDosen       = trim((string) $this->request->getPost('no_hp_dosen'));
    $bidangKeahlian  = trim((string) $this->request->getPost('bidang_keahlian'));

    if ($name === '' || $email === '' || $password === '' || ! in_array($role, ['admin', 'mahasiswa', 'dosen'], true)) {
        return redirect()->back()->withInput()->with('error', 'Data user belum lengkap.');
    }

    $emailExists = $db->table('users')->where('email', $email)->countAllResults();
    if ($emailExists > 0) {
        return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
    }

    if ($role === 'mahasiswa') {
        if ($nim === '' || $angkatan === '' || $programStudiId === null) {
            return redirect()->back()->withInput()->with('error', 'NIM, angkatan, dan program studi wajib diisi untuk mahasiswa.');
        }
    }

    if ($role === 'dosen') {
        if ($nidn === '') {
            return redirect()->back()->withInput()->with('error', 'NIDN wajib diisi untuk dosen.');
        }
    }

    $db->transStart();

    $db->table('users')->insert([
        'name'          => $name,
        'email'         => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'role'          => $role,
        'is_active'     => $isActive,
        'created_at'    => date('Y-m-d H:i:s'),
        'updated_at'    => date('Y-m-d H:i:s'),
    ]);

    $userId = $db->insertID();

    if ($role === 'mahasiswa') {
        $db->table('mahasiswa')->insert([
            'user_id'          => $userId,
            'nim'              => $nim,
            'angkatan'         => $angkatan,
            'program_studi_id' => $programStudiId,
            'no_hp'            => $noHpMahasiswa !== '' ? $noHpMahasiswa : null,
            'alamat'           => $alamat !== '' ? $alamat : null,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);
    }

    if ($role === 'dosen') {
        $db->table('dosen')->insert([
            'user_id'         => $userId,
            'nidn'            => $nidn,
            'nip'             => $nip !== '' ? $nip : null,
            'kuota_maksimal'  => $kuotaMaksimal > 0 ? $kuotaMaksimal : 25,
            'no_hp'           => $noHpDosen !== '' ? $noHpDosen : null,
            'bidang_keahlian' => $bidangKeahlian !== '' ? $bidangKeahlian : null,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    $db->transComplete();

    if (! $db->transStatus()) {
        $error = $db->error();
        dd($error);
        return redirect()->back()->withInput()->with('error', 'Gagal menyimpan user ke database. ' . ($error['message'] ?? ''));
    }

    return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan.');
}

public function editUser($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $user = $db->table('users')
        ->where('id', $id)
        ->get()
        ->getRowArray();

    if (! $user) {
        return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
    }

    $detail = [];

    if ($user['role'] === 'mahasiswa') {
        $detail = $db->table('mahasiswa')
            ->where('user_id', $id)
            ->get()
            ->getRowArray() ?? [];
    }

    if ($user['role'] === 'dosen') {
        $detail = $db->table('dosen')
            ->where('user_id', $id)
            ->get()
            ->getRowArray() ?? [];
    }

    $programStudi = $db->table('program_studi')
        ->orderBy('nama_prodi', 'ASC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_user_form', [
        'title'        => 'Edit User',
        'pageTitle'    => 'Edit User',
        'pageSubtitle' => 'Perbarui akun dan detail user',
        'activeMenu'   => 'admin_users',
        'mode'         => 'edit',
        'user'         => $user,
        'detail'       => $detail,
        'programStudi' => $programStudi,
    ]);
}

public function updateUser($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $user = $db->table('users')->where('id', $id)->get()->getRowArray();
    if (! $user) {
        return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
    }

    $name            = trim((string) $this->request->getPost('name'));
    $email           = trim((string) $this->request->getPost('email'));
    $password        = (string) $this->request->getPost('password');
    $role            = trim((string) $this->request->getPost('role'));
    $isActive        = (int) ($this->request->getPost('is_active') ?? 1);

    $nim             = trim((string) $this->request->getPost('nim'));
    $angkatan        = trim((string) $this->request->getPost('angkatan'));
    $programStudiId  = $this->request->getPost('program_studi_id') !== '' ? (int) $this->request->getPost('program_studi_id') : null;
    $noHpMahasiswa   = trim((string) $this->request->getPost('no_hp_mahasiswa'));
    $alamat          = trim((string) $this->request->getPost('alamat'));

    $nidn            = trim((string) $this->request->getPost('nidn'));
    $nip             = trim((string) $this->request->getPost('nip'));
    $kuotaMaksimal   = (int) ($this->request->getPost('kuota_maksimal') ?: 25);
    $noHpDosen       = trim((string) $this->request->getPost('no_hp_dosen'));
    $bidangKeahlian  = trim((string) $this->request->getPost('bidang_keahlian'));

    if ($name === '' || $email === '' || ! in_array($role, ['admin', 'mahasiswa', 'dosen'], true)) {
        return redirect()->back()->withInput()->with('error', 'Data user belum lengkap.');
    }

    $emailExists = $db->table('users')
        ->where('email', $email)
        ->where('id !=', $id)
        ->countAllResults();

    if ($emailExists > 0) {
        return redirect()->back()->withInput()->with('error', 'Email sudah digunakan.');
    }

    if ($role === 'mahasiswa') {
        if ($nim === '' || $angkatan === '' || $programStudiId === null) {
            return redirect()->back()->withInput()->with('error', 'NIM, angkatan, dan program studi wajib diisi untuk mahasiswa.');
        }
    }

    if ($role === 'dosen') {
        if ($nidn === '') {
            return redirect()->back()->withInput()->with('error', 'NIDN wajib diisi untuk dosen.');
        }
    }

    $db->transStart();

    $userUpdate = [
        'name'       => $name,
        'email'      => $email,
        'role'       => $role,
        'is_active'  => $isActive,
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    if ($password !== '') {
        $userUpdate['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $db->table('users')->where('id', $id)->update($userUpdate);

    $db->table('mahasiswa')->where('user_id', $id)->delete();
    $db->table('dosen')->where('user_id', $id)->delete();

    if ($role === 'mahasiswa') {
        $db->table('mahasiswa')->insert([
            'user_id'          => $id,
            'nim'              => $nim,
            'angkatan'         => $angkatan,
            'program_studi_id' => $programStudiId,
            'no_hp'            => $noHpMahasiswa !== '' ? $noHpMahasiswa : null,
            'alamat'           => $alamat !== '' ? $alamat : null,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s'),
        ]);
    }

    if ($role === 'dosen') {
        $db->table('dosen')->insert([
            'user_id'         => $id,
            'nidn'            => $nidn,
            'nip'             => $nip !== '' ? $nip : null,
            'kuota_maksimal'  => $kuotaMaksimal > 0 ? $kuotaMaksimal : 25,
            'no_hp'           => $noHpDosen !== '' ? $noHpDosen : null,
            'bidang_keahlian' => $bidangKeahlian !== '' ? $bidangKeahlian : null,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);
    }

    $db->transComplete();

    if (! $db->transStatus()) {
        $error = $db->error();
        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate user. ' . ($error['message'] ?? ''));
    }

    return redirect()->to('/admin/users')->with('success', 'User berhasil diupdate.');
}

    public function deleteUser($id)
    {
        if ($redirect = $this->guardAdmin()) {
            return $redirect;
        }

        $db = Database::connect();

        if ((int) $id === (int) session('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Admin tidak bisa menghapus akun sendiri.');
        }

        $db->transStart();
        $db->table('mahasiswa')->where('user_id', $id)->delete();
        $db->table('dosen')->where('user_id', $id)->delete();
        $db->table('users')->where('id', $id)->delete();
        $db->transComplete();

        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus.');
    }

public function periodeAkademik()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $rows = $db->table('periode_akademik')
        ->orderBy('id', 'DESC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_master', [
        'title'        => 'Periode Akademik',
        'pageTitle'    => 'Periode Akademik',
        'pageSubtitle' => 'Kelola data periode akademik aktif dan riwayat semester',
        'activeMenu'   => 'periode_akademik',
        'pageType'     => 'periode_akademik',
        'rows'         => $rows,
    ]);
}

public function programStudi()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $rows = $db->table('program_studi')
        ->orderBy('id', 'DESC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_master', [
        'title'        => 'Program Studi',
        'pageTitle'    => 'Program Studi',
        'pageSubtitle' => 'Kelola daftar program studi',
        'activeMenu'   => 'program_studi',
        'pageType'     => 'program_studi',
        'rows'         => $rows,
    ]);
}

public function createPeriodeAkademik()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    return view('dashboard/admin_periode_form', [
        'title'        => 'Tambah Periode Akademik',
        'pageTitle'    => 'Tambah Periode Akademik',
        'pageSubtitle' => 'Buat data periode akademik baru',
        'activeMenu'   => 'periode_akademik',
        'mode'         => 'create',
        'periode'      => null,
    ]);
}

public function storePeriodeAkademik()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $rules = [
        'tahun_ajaran' => 'required',
        'semester'     => 'required|in_list[ganjil,genap,pendek]',
        'is_active'    => 'required|in_list[0,1]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Data periode akademik tidak valid.');
    }

    $db = Database::connect();

    $isActive = (int) $this->request->getPost('is_active');

    $db->transStart();

    if ($isActive === 1) {
        $db->table('periode_akademik')->update([
            'is_active'   => 0,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);
    }

    $db->table('periode_akademik')->insert([
        'tahun_ajaran' => (string) $this->request->getPost('tahun_ajaran'),
        'semester'     => (string) $this->request->getPost('semester'),
        'is_active'    => $isActive,
        'created_at'   => date('Y-m-d H:i:s'),
        'updated_at'   => date('Y-m-d H:i:s'),
    ]);

    $db->transComplete();

    return redirect()->to('/admin/periode-akademik')->with('success', 'Periode akademik berhasil ditambahkan.');
}

public function editPeriodeAkademik($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $periode = $db->table('periode_akademik')->where('id', $id)->get()->getRowArray();

    if (! $periode) {
        return redirect()->to('/admin/periode-akademik')->with('error', 'Periode akademik tidak ditemukan.');
    }

    return view('dashboard/admin_periode_form', [
        'title'        => 'Edit Periode Akademik',
        'pageTitle'    => 'Edit Periode Akademik',
        'pageSubtitle' => 'Perbarui data periode akademik',
        'activeMenu'   => 'periode_akademik',
        'mode'         => 'edit',
        'periode'      => $periode,
    ]);
}

public function updatePeriodeAkademik($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $rules = [
        'tahun_ajaran' => 'required',
        'semester'     => 'required|in_list[ganjil,genap,pendek]',
        'is_active'    => 'required|in_list[0,1]',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Data periode akademik tidak valid.');
    }

    $db = Database::connect();

    $periode = $db->table('periode_akademik')->where('id', $id)->get()->getRowArray();

    if (! $periode) {
        return redirect()->to('/admin/periode-akademik')->with('error', 'Periode akademik tidak ditemukan.');
    }

    $isActive = (int) $this->request->getPost('is_active');

    $db->transStart();

    if ($isActive === 1) {
        $db->table('periode_akademik')->update([
            'is_active'  => 0,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    $db->table('periode_akademik')
        ->where('id', $id)
        ->update([
            'tahun_ajaran' => (string) $this->request->getPost('tahun_ajaran'),
            'semester'     => (string) $this->request->getPost('semester'),
            'is_active'    => $isActive,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

    $db->transComplete();

    return redirect()->to('/admin/periode-akademik')->with('success', 'Periode akademik berhasil diperbarui.');
}

public function deletePeriodeAkademik($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $db->table('periode_akademik')->where('id', $id)->delete();

    return redirect()->to('/admin/periode-akademik')->with('success', 'Periode akademik berhasil dihapus.');
}

public function createProgramStudi()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    return view('dashboard/admin_program_studi_form', [
        'title'        => 'Tambah Program Studi',
        'pageTitle'    => 'Tambah Program Studi',
        'pageSubtitle' => 'Buat data program studi baru',
        'activeMenu'   => 'program_studi',
        'mode'         => 'create',
        'programStudi' => null,
    ]);
}

public function storeProgramStudi()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $rules = [
        'kode_prodi' => 'required',
        'nama_prodi' => 'required',
        'jenjang'    => 'permit_empty',
        'fakultas'   => 'permit_empty',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Data program studi tidak valid.');
    }

    $db = Database::connect();

    $db->table('program_studi')->insert([
        'kode_prodi'  => (string) $this->request->getPost('kode_prodi'),
        'nama_prodi'  => (string) $this->request->getPost('nama_prodi'),
        'jenjang'     => $this->request->getPost('jenjang') ?: null,
        'fakultas'    => $this->request->getPost('fakultas') ?: null,
        'created_at'  => date('Y-m-d H:i:s'),
        'updated_at'  => date('Y-m-d H:i:s'),
    ]);

    return redirect()->to('/admin/program-studi')->with('success', 'Program studi berhasil ditambahkan.');
}

public function editProgramStudi($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $programStudi = $db->table('program_studi')->where('id', $id)->get()->getRowArray();

    if (! $programStudi) {
        return redirect()->to('/admin/program-studi')->with('error', 'Program studi tidak ditemukan.');
    }

    return view('dashboard/admin_program_studi_form', [
        'title'        => 'Edit Program Studi',
        'pageTitle'    => 'Edit Program Studi',
        'pageSubtitle' => 'Perbarui data program studi',
        'activeMenu'   => 'program_studi',
        'mode'         => 'edit',
        'programStudi' => $programStudi,
    ]);
}

public function updateProgramStudi($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $rules = [
        'kode_prodi' => 'required',
        'nama_prodi' => 'required',
        'jenjang'    => 'permit_empty',
        'fakultas'   => 'permit_empty',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Data program studi tidak valid.');
    }

    $db = Database::connect();

    $db->table('program_studi')
        ->where('id', $id)
        ->update([
            'kode_prodi' => (string) $this->request->getPost('kode_prodi'),
            'nama_prodi' => (string) $this->request->getPost('nama_prodi'),
            'jenjang'    => $this->request->getPost('jenjang') ?: null,
            'fakultas'   => $this->request->getPost('fakultas') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

    return redirect()->to('/admin/program-studi')->with('success', 'Program studi berhasil diperbarui.');
}

public function deleteProgramStudi($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $db->table('program_studi')->where('id', $id)->delete();

    return redirect()->to('/admin/program-studi')->with('success', 'Program studi berhasil dihapus.');
}

public function monitoringPembimbing()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $keyword = trim((string) $this->request->getGet('q'));
    $status  = trim((string) $this->request->getGet('status'));
    $jenis   = trim((string) $this->request->getGet('jenis'));
    $perPage = (int) ($this->request->getGet('per_page') ?: 10);
    $page    = max(1, (int) ($this->request->getGet('page') ?: 1));

    if (! in_array($perPage, [10, 25, 50, 100], true)) {
        $perPage = 10;
    }

    $builder = $db->table('permohonan_pembimbing pp')
        ->select('pp.*, um.name AS nama_mahasiswa, m.nim, ud.name AS nama_dosen')
        ->join('mahasiswa m', 'm.id = pp.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->join('dosen d', 'd.id = pp.dosen_id')
        ->join('users ud', 'ud.id = d.user_id');

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('um.name', $keyword)
            ->orLike('m.nim', $keyword)
            ->orLike('ud.name', $keyword)
            ->groupEnd();
    }

    if ($status !== '' && in_array($status, ['menunggu', 'disetujui', 'ditolak', 'kuota_penuh'], true)) {
        $builder->where('pp.status', $status);
    }

    if ($jenis !== '' && in_array($jenis, ['pembimbing_1', 'pembimbing_2'], true)) {
        $builder->where('pp.jenis_pembimbing', $jenis);
    }

    $totalRows = (clone $builder)->countAllResults();
    $offset = ($page - 1) * $perPage;

    $rows = (clone $builder)
        ->orderBy('pp.tanggal_pengajuan', 'DESC')
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $summary = [
        'total'     => $totalRows,
        'menunggu'  => $db->table('permohonan_pembimbing')->where('status', 'menunggu')->countAllResults(),
        'disetujui' => $db->table('permohonan_pembimbing')->where('status', 'disetujui')->countAllResults(),
        'ditolak'   => $db->table('permohonan_pembimbing')->where('status', 'ditolak')->countAllResults(),
    ];

    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $startRow   = $totalRows > 0 ? $offset + 1 : 0;
    $endRow     = min($offset + $perPage, $totalRows);

    return view('dashboard/admin_monitoring_pembimbing', [
        'title'        => 'Monitoring Pembimbing',
        'pageTitle'    => 'Monitoring Pembimbing',
        'pageSubtitle' => 'Pantau semua permohonan pembimbing mahasiswa',
        'activeMenu'   => 'monitoring_pembimbing',
        'rows'         => $rows,
        'summary'      => $summary,
        'keyword'      => $keyword,
        'status'       => $status,
        'jenis'        => $jenis,
        'perPage'      => $perPage,
        'page'         => $page,
        'totalRows'    => $totalRows,
        'totalPages'   => $totalPages,
        'startRow'     => $startRow,
        'endRow'       => $endRow,
    ]);
}

public function monitoringJudul()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $keyword = trim((string) $this->request->getGet('q'));
    $status  = trim((string) $this->request->getGet('status'));
    $perPage = (int) ($this->request->getGet('per_page') ?: 10);
    $page    = max(1, (int) ($this->request->getGet('page') ?: 1));

    if (! in_array($perPage, [10, 25, 50, 100], true)) {
        $perPage = 10;
    }

    $builder = $db->table('pengajuan_judul pj')
        ->select('pj.*, um.name AS nama_mahasiswa, m.nim')
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id');

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('um.name', $keyword)
            ->orLike('m.nim', $keyword)
            ->orLike('pj.judul', $keyword)
            ->groupEnd();
    }

    if ($status !== '' && in_array($status, ['draft', 'diajukan', 'direview', 'revisi', 'ditolak', 'disetujui'], true)) {
        $builder->where('pj.status', $status);
    }

    $totalRows = (clone $builder)->countAllResults();
    $offset = ($page - 1) * $perPage;

    $rows = (clone $builder)
        ->orderBy('pj.tanggal_pengajuan', 'DESC')
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $summary = [
        'total'     => $totalRows,
        'diajukan'  => $db->table('pengajuan_judul')->where('status', 'diajukan')->countAllResults(),
        'direview'  => $db->table('pengajuan_judul')->where('status', 'direview')->countAllResults(),
        'disetujui' => $db->table('pengajuan_judul')->where('status', 'disetujui')->countAllResults(),
        'revisi'    => $db->table('pengajuan_judul')->where('status', 'revisi')->countAllResults(),
        'ditolak'   => $db->table('pengajuan_judul')->where('status', 'ditolak')->countAllResults(),
    ];

    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $startRow   = $totalRows > 0 ? $offset + 1 : 0;
    $endRow     = min($offset + $perPage, $totalRows);

    return view('dashboard/admin_monitoring_judul', [
        'title'        => 'Monitoring Judul',
        'pageTitle'    => 'Monitoring Judul',
        'pageSubtitle' => 'Pantau semua pengajuan judul mahasiswa',
        'activeMenu'   => 'monitoring_judul',
        'rows'         => $rows,
        'summary'      => $summary,
        'keyword'      => $keyword,
        'status'       => $status,
        'perPage'      => $perPage,
        'page'         => $page,
        'totalRows'    => $totalRows,
        'totalPages'   => $totalPages,
        'startRow'     => $startRow,
        'endRow'       => $endRow,
    ]);
}

public function monitoringProposal()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $keyword = trim((string) $this->request->getGet('q'));
    $status  = trim((string) $this->request->getGet('status'));
    $perPage = (int) ($this->request->getGet('per_page') ?: 10);
    $page    = max(1, (int) ($this->request->getGet('page') ?: 1));

    if (! in_array($perPage, [10, 25, 50, 100], true)) {
        $perPage = 10;
    }

    $builder = $db->table('proposal_ta pt')
        ->select('pt.*, pj.judul, um.name AS nama_mahasiswa, m.nim')
        ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id');

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('um.name', $keyword)
            ->orLike('m.nim', $keyword)
            ->orLike('pj.judul', $keyword)
            ->orLike('pt.nama_file_asli', $keyword)
            ->groupEnd();
    }

    if ($status !== '' && in_array($status, ['diajukan', 'revisi', 'ditolak', 'disetujui'], true)) {
        $builder->where('pt.status', $status);
    }

    $totalRows = (clone $builder)->countAllResults();
    $offset = ($page - 1) * $perPage;

    $rows = (clone $builder)
        ->orderBy('pt.tanggal_upload', 'DESC')
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $summary = [
        'total'     => $totalRows,
        'diajukan'  => $db->table('proposal_ta')->where('status', 'diajukan')->countAllResults(),
        'disetujui' => $db->table('proposal_ta')->where('status', 'disetujui')->countAllResults(),
        'revisi'    => $db->table('proposal_ta')->where('status', 'revisi')->countAllResults(),
        'ditolak'   => $db->table('proposal_ta')->where('status', 'ditolak')->countAllResults(),
    ];

    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $startRow   = $totalRows > 0 ? $offset + 1 : 0;
    $endRow     = min($offset + $perPage, $totalRows);

    return view('dashboard/admin_monitoring_proposal', [
        'title'        => 'Monitoring Proposal',
        'pageTitle'    => 'Monitoring Proposal',
        'pageSubtitle' => 'Pantau semua proposal tugas akhir mahasiswa',
        'activeMenu'   => 'monitoring_proposal',
        'rows'         => $rows,
        'summary'      => $summary,
        'keyword'      => $keyword,
        'status'       => $status,
        'perPage'      => $perPage,
        'page'         => $page,
        'totalRows'    => $totalRows,
        'totalPages'   => $totalPages,
        'startRow'     => $startRow,
        'endRow'       => $endRow,
    ]);
}

public function suratKeputusan()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $rows = $db->table('surat_keputusan sk')
        ->select('sk.*, um.name AS nama_mahasiswa, m.nim, pj.judul, pt.nama_file_asli')
        ->join('mahasiswa m', 'm.id = sk.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->join('proposal_ta pt', 'pt.id = sk.proposal_ta_id', 'left')
        ->join('pengajuan_judul pj', 'pj.id = sk.pengajuan_judul_id', 'left')
        ->orderBy('sk.id', 'DESC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_documents', [
        'title'        => 'Surat Keputusan',
        'pageTitle'    => 'Surat Keputusan',
        'pageSubtitle' => 'Kelola penerbitan dan arsip SK',
        'activeMenu'   => 'surat_keputusan_admin',
        'pageType'     => 'surat_keputusan',
        'rows'         => $rows,
    ]);
}

public function laporan()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $periodeList = $db->table('periode_akademik')
        ->orderBy('is_active', 'DESC')
        ->orderBy('id', 'DESC')
        ->get()
        ->getResultArray();

    if (empty($periodeList)) {
        return view('dashboard/admin_laporan', [
            'title'           => 'Laporan / Arsip',
            'pageTitle'       => 'Laporan / Arsip',
            'pageSubtitle'    => 'Rekap data berdasarkan periode akademik',
            'activeMenu'      => 'laporan_arsip',
            'periodeList'     => [],
            'periodeAktif'    => null,
            'summary'         => [],
            'arsipPembimbing' => [],
            'arsipJudul'      => [],
            'arsipProposal'   => [],
            'arsipSk'         => [],
        ]);
    }

    $periodeId = (int) ($this->request->getGet('periode_id') ?: 0);

    if ($periodeId <= 0) {
        $periodeAktifDefault = null;

        foreach ($periodeList as $periode) {
            if ((int) ($periode['is_active'] ?? 0) === 1) {
                $periodeAktifDefault = $periode;
                break;
            }
        }

        if (! $periodeAktifDefault) {
            $periodeAktifDefault = $periodeList[0];
        }

        $periodeId = (int) $periodeAktifDefault['id'];
    }

    $periodeAktif = $db->table('periode_akademik')
        ->where('id', $periodeId)
        ->get()
        ->getRowArray();

    if (! $periodeAktif) {
        return redirect()->to('/admin/laporan')->with('error', 'Periode akademik tidak ditemukan.');
    }

    $summary = [
        'permohonan_total' => $db->table('permohonan_pembimbing')->countAllResults(),

        'permohonan_disetujui' => $db->table('permohonan_pembimbing')
            ->where('status', 'disetujui')
            ->countAllResults(),

        'judul_total' => $db->table('pengajuan_judul')
            ->where('periode_akademik_id', $periodeId)
            ->countAllResults(),

        'judul_disetujui' => $db->table('pengajuan_judul')
            ->where('periode_akademik_id', $periodeId)
            ->where('status', 'disetujui')
            ->countAllResults(),

        'proposal_total' => $db->table('proposal_ta')
            ->where('periode_akademik_id', $periodeId)
            ->countAllResults(),

        'proposal_disetujui' => $db->table('proposal_ta')
            ->where('periode_akademik_id', $periodeId)
            ->where('status', 'disetujui')
            ->countAllResults(),

        'sk_total' => $db->table('surat_keputusan sk')
            ->join('pengajuan_judul pj', 'pj.id = sk.pengajuan_judul_id', 'left')
            ->where('pj.periode_akademik_id', $periodeId)
            ->countAllResults(),
    ];

    $arsipPembimbing = $db->table('pembimbing_mahasiswa pm')
        ->select('pm.*, m.nim, um.name as nama_mahasiswa, ud.name as nama_dosen')
        ->join('mahasiswa m', 'm.id = pm.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->join('dosen d', 'd.id = pm.dosen_id')
        ->join('users ud', 'ud.id = d.user_id')
        ->where('pm.periode_akademik_id', $periodeId)
        ->orderBy('pm.tanggal_penetapan', 'DESC')
        ->limit(10)
        ->get()
        ->getResultArray();

    $arsipJudul = $db->table('pengajuan_judul pj')
        ->select('pj.*, m.nim, um.name as nama_mahasiswa')
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('pj.periode_akademik_id', $periodeId)
        ->orderBy('pj.tanggal_pengajuan', 'DESC')
        ->limit(10)
        ->get()
        ->getResultArray();

    $arsipProposal = $db->table('proposal_ta pt')
        ->select('pt.*, pj.judul, m.nim, um.name as nama_mahasiswa')
        ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('pt.periode_akademik_id', $periodeId)
        ->orderBy('pt.tanggal_upload', 'DESC')
        ->limit(10)
        ->get()
        ->getResultArray();

    $arsipSk = $db->table('surat_keputusan sk')
        ->select('sk.*, pj.judul, m.nim, um.name as nama_mahasiswa')
        ->join('proposal_ta pt', 'pt.id = sk.proposal_ta_id', 'left')
        ->join('pengajuan_judul pj', 'pj.id = sk.pengajuan_judul_id', 'left')
        ->join('mahasiswa m', 'm.id = sk.mahasiswa_id', 'left')
        ->join('users um', 'um.id = m.user_id', 'left')
        ->where('pj.periode_akademik_id', $periodeId)
        ->orderBy('sk.tanggal_terbit', 'DESC')
        ->limit(10)
        ->get()
        ->getResultArray();

    return view('dashboard/admin_laporan', [
        'title'           => 'Laporan / Arsip',
        'pageTitle'       => 'Laporan / Arsip',
        'pageSubtitle'    => 'Rekap data berdasarkan periode akademik',
        'activeMenu'      => 'laporan_arsip',
        'periodeList'     => $periodeList,
        'periodeAktif'    => $periodeAktif,
        'summary'         => $summary,
        'arsipPembimbing' => $arsipPembimbing,
        'arsipJudul'      => $arsipJudul,
        'arsipProposal'   => $arsipProposal,
        'arsipSk'         => $arsipSk,
    ]);
}

public function createSK()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $data = $db->table('proposal_ta pt')
        ->select('
            pt.id,
            pt.mahasiswa_id,
            pt.pengajuan_judul_id,
            pt.nama_file_asli,
            pj.judul,
            um.name AS nama_mahasiswa,
            m.nim
        ')
        ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->join('surat_keputusan sk', 'sk.proposal_ta_id = pt.id', 'left')
        ->where('pt.status', 'disetujui')
        ->where('sk.id IS NULL', null, false)
        ->orderBy('pt.id', 'DESC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_sk_form', [
        'title'        => 'Tambah SK',
        'pageTitle'    => 'Tambah Surat Keputusan',
        'pageSubtitle' => 'Buat SK dari proposal yang sudah disetujui',
        'activeMenu'   => 'surat_keputusan_admin',
        'data'         => $data,
    ]);
}

public function storeSK()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $rules = [
        'proposal_ta_id'      => 'required|integer',
        'mahasiswa_id'        => 'required|integer',
        'pengajuan_judul_id'  => 'required|integer',
        'nomor_sk'            => 'required',
        'tanggal_terbit'      => 'required',
    ];

    if (! $this->validate($rules)) {
        return redirect()->back()->withInput()->with('error', 'Data SK tidak valid.');
    }

    $db = \Config\Database::connect();

    $proposalId = (int) $this->request->getPost('proposal_ta_id');

    $proposal = $db->table('proposal_ta')
        ->where('id', $proposalId)
        ->where('status', 'disetujui')
        ->get()
        ->getRowArray();

    if (! $proposal) {
        return redirect()->back()->withInput()->with('error', 'Proposal tidak valid atau belum disetujui.');
    }

    $cekSk = $db->table('surat_keputusan')
        ->where('proposal_ta_id', $proposalId)
        ->countAllResults();

    if ($cekSk > 0) {
        return redirect()->to('/admin/surat-keputusan')->with('error', 'SK untuk proposal ini sudah pernah dibuat.');
    }

    $uploadPath = FCPATH . 'uploads/sk';

    if (! is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $uploadedFiles = [];
    $files = $this->request->getFiles();

    if (isset($files['file_sk']) && is_array($files['file_sk'])) {
        foreach ($files['file_sk'] as $file) {
            if (! $file || ! $file->isValid() || $file->hasMoved()) {
                continue;
            }

            $allowedExt = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
            $ext = strtolower($file->getClientExtension());

            if (! in_array($ext, $allowedExt, true)) {
                return redirect()->back()->withInput()->with('error', 'Format file SK tidak didukung.');
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()->withInput()->with('error', 'Ukuran file maksimal 5MB per file.');
            }

            $newName = 'sk_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

            $file->move($uploadPath, $newName);

            $uploadedFiles[] = [
                'nama_asli' => $file->getClientName(),
                'nama_file' => $newName,
                'path'      => 'uploads/sk/' . $newName,
            ];
        }
    }

    $db->table('surat_keputusan')->insert([
        'mahasiswa_id'       => (int) $this->request->getPost('mahasiswa_id'),
        'pengajuan_judul_id' => (int) $this->request->getPost('pengajuan_judul_id'),
        'proposal_ta_id'     => $proposalId,
        'nomor_sk'           => (string) $this->request->getPost('nomor_sk'),
        'tanggal_terbit'     => (string) $this->request->getPost('tanggal_terbit'),
        'file_sk'            => ! empty($uploadedFiles) ? json_encode($uploadedFiles) : null,
        'status'             => 'terbit',
        'created_at'         => date('Y-m-d H:i:s'),
        'updated_at'         => date('Y-m-d H:i:s'),
    ]);

    return redirect()->to('/admin/surat-keputusan')->with('success', 'SK berhasil dibuat.');
}

public function deleteSK($id)
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = \Config\Database::connect();

    $db->table('surat_keputusan')->where('id', $id)->delete();

    return redirect()->to('/admin/surat-keputusan')->with('success', 'SK berhasil dihapus.');
}

public function auditLog()
{
    if ($redirect = $this->guardAdmin()) {
        return $redirect;
    }

    $db = Database::connect();

    $auditLogs = $db->table('audit_logs al')
        ->select('al.*, u.name')
        ->join('users u', 'u.id = al.user_id', 'left')
        ->orderBy('al.id', 'DESC')
        ->get()
        ->getResultArray();

    $notifikasi = $db->table('notifikasi n')
        ->select('n.*, u.name')
        ->join('users u', 'u.id = n.user_id', 'left')
        ->orderBy('n.id', 'DESC')
        ->get()
        ->getResultArray();

    return view('dashboard/admin_logs', [
        'title'        => 'Notifikasi / Audit Log',
        'pageTitle'    => 'Notifikasi / Audit Log',
        'pageSubtitle' => 'Pantau aktivitas sistem dan notifikasi user',
        'activeMenu'   => 'audit_log',
        'auditLogs'    => $auditLogs,
        'notifikasi'   => $notifikasi,
    ]);
}
}