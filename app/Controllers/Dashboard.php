<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        if (! session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role   = session()->get('role');
        $userId = (int) session()->get('user_id');

        if ($role === 'admin') {
            $db = \Config\Database::connect();

            $totalUsers = $db->table('users')->countAllResults();
            $totalMahasiswa = $db->table('mahasiswa')->countAllResults();
            $totalDosen = $db->table('dosen')->countAllResults();
            $totalJudul = $db->table('pengajuan_judul')->countAllResults();
            $totalProposal = $db->table('proposal_ta')->countAllResults();
            $totalSk = $db->table('surat_keputusan')->countAllResults();

            $permohonanPending = $db->table('permohonan_pembimbing')
                ->where('status', 'menunggu')
                ->countAllResults();

            $judulPending = $db->table('pengajuan_judul')
                ->whereIn('status', ['diajukan', 'direview', 'revisi'])
                ->countAllResults();

            $proposalPending = $db->table('proposal_ta')
                ->whereIn('status', ['diajukan', 'direview', 'revisi'])
                ->countAllResults();

            $skTerbit = $db->table('surat_keputusan')
                ->where('status', 'terbit')
                ->countAllResults();

            $judulTerbaru = $db->table('pengajuan_judul pj')
                ->select('pj.judul, pj.status, pj.tanggal_pengajuan, u.name AS nama_mahasiswa')
                ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
                ->join('users u', 'u.id = m.user_id')
                ->orderBy('pj.id', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            $proposalTerbaru = $db->table('proposal_ta pt')
                ->select('pt.nama_file_asli, pt.status, pt.tanggal_upload, u.name AS nama_mahasiswa, pj.judul')
                ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
                ->join('users u', 'u.id = m.user_id')
                ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
                ->orderBy('pt.id', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            $skTerbaru = $db->table('surat_keputusan sk')
                ->select('sk.nomor_sk, sk.status, sk.tanggal_terbit, u.name AS nama_mahasiswa, pj.judul')
                ->join('mahasiswa m', 'm.id = sk.mahasiswa_id')
                ->join('users u', 'u.id = m.user_id')
                ->join('proposal_ta pt', 'pt.id = sk.proposal_ta_id', 'left')
                ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id', 'left')
                ->orderBy('sk.id', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            $auditTerbaru = $db->table('audit_logs al')
                ->select('al.aktivitas, al.entitas, al.deskripsi, al.created_at, u.name')
                ->join('users u', 'u.id = al.user_id', 'left')
                ->orderBy('al.id', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            return view('dashboard/admin', [
                'title'              => 'Dashboard Admin',
                'pageTitle'          => 'Dashboard Admin',
                'pageSubtitle'       => 'Panel kontrol utama sistem tugas akhir',
                'activeMenu'         => 'dashboard',
                'totalUsers'         => $totalUsers,
                'totalMahasiswa'     => $totalMahasiswa,
                'totalDosen'         => $totalDosen,
                'totalJudul'         => $totalJudul,
                'totalProposal'      => $totalProposal,
                'totalSk'            => $totalSk,
                'permohonanPending'  => $permohonanPending,
                'judulPending'       => $judulPending,
                'proposalPending'    => $proposalPending,
                'skTerbit'           => $skTerbit,
                'judulTerbaru'       => $judulTerbaru,
                'proposalTerbaru'    => $proposalTerbaru,
                'skTerbaru'          => $skTerbaru,
                'auditTerbaru'       => $auditTerbaru,
            ]);
        }

if ($role === 'mahasiswa') {
    $db = \Config\Database::connect();

    $mahasiswa = $db->table('mahasiswa m')
        ->select('m.*, ps.nama_prodi, u.name AS user_nama, u.email')
        ->join('users u', 'u.id = m.user_id')
        ->join('program_studi ps', 'ps.id = m.program_studi_id', 'left')
        ->where('m.user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $mahasiswa) {
        return redirect()->to('/dashboard')->with('error', 'Data mahasiswa tidak ditemukan.');
    }

    $mahasiswaId = (int) $mahasiswa['id'];

    $pembimbingAktif = $db->table('pembimbing_mahasiswa pm')
        ->select('pm.jenis_pembimbing, u.name AS nama_dosen')
        ->join('dosen d', 'd.id = pm.dosen_id')
        ->join('users u', 'u.id = d.user_id')
        ->where('pm.mahasiswa_id', $mahasiswaId)
        ->where('pm.status_aktif', 1)
        ->orderBy('pm.jenis_pembimbing', 'ASC')
        ->get()
        ->getResultArray();

    $jumlahPembimbing = count($pembimbingAktif);

    $jumlahJudul = $db->table('pengajuan_judul')
        ->where('mahasiswa_id', $mahasiswaId)
        ->countAllResults();

    $judulAktif = $db->table('pengajuan_judul')
        ->where('mahasiswa_id', $mahasiswaId)
        ->orderBy('id', 'DESC')
        ->get()
        ->getRowArray();

    $jumlahProposal = $db->table('proposal_ta')
        ->where('mahasiswa_id', $mahasiswaId)
        ->countAllResults();

    $proposalAktif = $db->table('proposal_ta')
        ->where('mahasiswa_id', $mahasiswaId)
        ->orderBy('id', 'DESC')
        ->get()
        ->getRowArray();

    $jumlahSk = $db->table('surat_keputusan')
        ->where('mahasiswa_id', $mahasiswaId)
        ->countAllResults();

    $skTerbaru = $db->table('surat_keputusan')
        ->where('mahasiswa_id', $mahasiswaId)
        ->orderBy('id', 'DESC')
        ->get()
        ->getRowArray();


    return view('dashboard/mahasiswa', [
        'title'              => 'Dashboard Mahasiswa',
        'pageTitle'          => 'Dashboard Mahasiswa',
        'pageSubtitle'       => 'Pantau progres tugas akhir kamu secara ringkas',
        'activeMenu'         => 'dashboard',

        'mahasiswa'          => $mahasiswa,
        'pembimbingAktif'    => $pembimbingAktif,

        'jumlahPembimbing'   => $jumlahPembimbing,
        'jumlahJudul'        => $jumlahJudul,
        'jumlahProposal'     => $jumlahProposal,
        'jumlahSk'           => $jumlahSk,

        'totalPembimbing'    => $jumlahPembimbing,
        'totalJudul'         => $jumlahJudul,
        'totalProposal'      => $jumlahProposal,
        'totalSk'            => $jumlahSk,

        'judulAktif'         => $judulAktif,
        'proposalAktif'      => $proposalAktif,
        'skTerbaru'          => $skTerbaru,
    ]);
}

if ($role === 'dosen') {
    $db = \Config\Database::connect();

    $dosen = $db->table('dosen d')
        ->select('d.*, u.name AS nama_dosen, u.email')
        ->join('users u', 'u.id = d.user_id')
        ->where('d.user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $dosen) {
        return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
    }

    $dosenId = (int) $dosen['id'];

    $totalBimbingan = $db->table('pembimbing_mahasiswa')
        ->where('dosen_id', $dosenId)
        ->where('status_aktif', 1)
        ->countAllResults();

    $totalPermohonan = $db->table('permohonan_pembimbing')
        ->where('dosen_id', $dosenId)
        ->where('status', 'menunggu')
        ->countAllResults();

    $totalJudul = $db->table('pengajuan_judul pj')
        ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = pj.mahasiswa_id')
        ->where('pm.dosen_id', $dosenId)
        ->where('pm.status_aktif', 1)
        ->countAllResults();

    $totalProposal = $db->table('proposal_ta pt')
        ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = pt.mahasiswa_id')
        ->where('pm.dosen_id', $dosenId)
        ->where('pm.status_aktif', 1)
        ->countAllResults();

    $mahasiswaBimbingan = $db->table('pembimbing_mahasiswa pm')
        ->select('m.nim, u.name AS nama, pm.jenis_pembimbing, pm.tanggal_penetapan')
        ->join('mahasiswa m', 'm.id = pm.mahasiswa_id')
        ->join('users u', 'u.id = m.user_id')
        ->where('pm.dosen_id', $dosenId)
        ->where('pm.status_aktif', 1)
        ->orderBy('pm.tanggal_penetapan', 'DESC')
        ->limit(5)
        ->get()
        ->getResultArray();

    $aktivitasPermohonan = $db->table('permohonan_pembimbing pp')
        ->select("
            u.name AS nama_mahasiswa,
            CONCAT('Mengajukan permohonan ', pp.jenis_pembimbing) AS keterangan,
            pp.tanggal_pengajuan AS tanggal
        ")
        ->join('mahasiswa m', 'm.id = pp.mahasiswa_id')
        ->join('users u', 'u.id = m.user_id')
        ->where('pp.dosen_id', $dosenId)
        ->orderBy('pp.tanggal_pengajuan', 'DESC')
        ->limit(3)
        ->get()
        ->getResultArray();

    $aktivitasJudul = $db->table('pengajuan_judul pj')
        ->select("
            u.name AS nama_mahasiswa,
            CONCAT('Mengajukan judul: ', pj.judul) AS keterangan,
            pj.tanggal_pengajuan AS tanggal
        ")
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users u', 'u.id = m.user_id')
        ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = m.id')
        ->where('pm.dosen_id', $dosenId)
        ->where('pm.status_aktif', 1)
        ->orderBy('pj.tanggal_pengajuan', 'DESC')
        ->limit(3)
        ->get()
        ->getResultArray();

    $aktivitasProposal = $db->table('proposal_ta pt')
        ->select("
            u.name AS nama_mahasiswa,
            CONCAT('Upload proposal: ', pt.nama_file_asli) AS keterangan,
            pt.tanggal_upload AS tanggal
        ")
        ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
        ->join('users u', 'u.id = m.user_id')
        ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = m.id')
        ->where('pm.dosen_id', $dosenId)
        ->where('pm.status_aktif', 1)
        ->orderBy('pt.tanggal_upload', 'DESC')
        ->limit(3)
        ->get()
        ->getResultArray();

    $aktivitas = array_merge($aktivitasPermohonan, $aktivitasJudul, $aktivitasProposal);

    usort($aktivitas, function ($a, $b) {
        return strtotime($b['tanggal'] ?? '1970-01-01') <=> strtotime($a['tanggal'] ?? '1970-01-01');
    });

    $aktivitas = array_slice($aktivitas, 0, 5);
    return view('dashboard/dosen', [
        'title'              => 'Dashboard Dosen',
        'pageTitle'          => 'Dashboard Dosen',
        'pageSubtitle'       => 'Kelola bimbingan mahasiswa dan tinjau pengajuan',
        'activeMenu'         => 'dashboard',

        'dosen'              => $dosen,
        'totalBimbingan'     => $totalBimbingan,
        'totalPermohonan'    => $totalPermohonan,
        'totalJudul'         => $totalJudul,
        'totalProposal'      => $totalProposal,

        'aktivitas'          => $aktivitas,
        'mahasiswaBimbingan' => $mahasiswaBimbingan,
    ]);
}}

public function pembimbing()
{
    if (! session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    $db = \Config\Database::connect();

    // ambil mahasiswa login
    $mahasiswa = $db->table('mahasiswa')
        ->where('user_id', session()->get('user_id'))
        ->get()
        ->getRowArray();

    if (! $mahasiswa) {
        return redirect()->to('/dashboard');
    }

    // ✅ PEMBIMBING AKTIF (dari tabel final)
    $pembimbingAktif = $db->table('pembimbing_mahasiswa pm')
        ->select('pm.*, d.nidn, d.no_hp, d.bidang_keahlian, u.name AS nama_dosen')
        ->join('dosen d', 'd.id = pm.dosen_id')
        ->join('users u', 'u.id = d.user_id')
        ->where('pm.mahasiswa_id', $mahasiswa['id'])
        ->where('pm.status_aktif', 1)
        ->orderBy('pm.jenis_pembimbing', 'ASC')
        ->get()
        ->getResultArray();

    // ✅ RIWAYAT PERMOHONAN
    $permohonan = $db->table('permohonan_pembimbing pp')
        ->select('pp.*, u.name AS nama_dosen')
        ->join('dosen d', 'd.id = pp.dosen_id')
        ->join('users u', 'u.id = d.user_id')
        ->where('pp.mahasiswa_id', $mahasiswa['id'])
        ->orderBy('pp.created_at', 'DESC')
        ->get()
        ->getResultArray();

    // ✅ LIST DOSEN UNTUK DIPILIH
    $dosenList = $db->table('dosen d')
        ->select('d.*, u.name')
        ->join('users u', 'u.id = d.user_id')
        ->get()
        ->getResultArray();

    return view('dashboard/pembimbing', [
        'title' => 'Pembimbing',
        'activeMenu' => 'pembimbing',
        'pembimbingAktif' => $pembimbingAktif,
        'permohonan' => $permohonan,
        'dosenList' => $dosenList,
    ]);
}
    public function pengajuanJudul()
    {
        $role   = session()->get('role');
        $userId = (int) session()->get('user_id');

        $db = Database::connect();

        if ($role === 'mahasiswa') {
            $mahasiswa = $db->table('mahasiswa')
                ->where('user_id', $userId)
                ->get()
                ->getRowArray();

            $riwayatJudul        = [];
            $judulAktifDisetujui = null;
            $masihDiproses       = false;

            if ($mahasiswa) {
                $riwayatJudul = $db->table('pengajuan_judul')
                    ->where('mahasiswa_id', $mahasiswa['id'])
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getResultArray();

                $judulAktifDisetujui = $db->table('pengajuan_judul')
                    ->where('mahasiswa_id', $mahasiswa['id'])
                    ->where('status', 'disetujui')
                    ->orderBy('id', 'DESC')
                    ->get()
                    ->getRowArray();

                $masihDiproses = $db->table('pengajuan_judul')
                    ->where('mahasiswa_id', $mahasiswa['id'])
                    ->whereIn('status', ['diajukan', 'direview'])
                    ->countAllResults() > 0;
            }

            return view('dashboard/pengajuan_judul', [
                'title'               => 'Pengajuan Judul',
                'pageTitle'           => 'Pengajuan Judul',
                'pageSubtitle'        => 'Kelola pengajuan judul tugas akhir, pantau hasil review dosen, dan lihat riwayat revisi.',
                'activeMenu'          => 'pengajuan_judul',
                'riwayatJudul'        => $riwayatJudul,
                'judulAktifDisetujui' => $judulAktifDisetujui,
                'masihDiproses'       => $masihDiproses,
            ]);
        }

        return view('dashboard/pengajuan_judul', [
            'title'               => 'Pengajuan Judul',
            'pageTitle'           => 'Pengajuan Judul',
            'pageSubtitle'        => 'Kelola pengajuan judul tugas akhir, pantau hasil review dosen, dan lihat riwayat revisi',
            'activeMenu'          => 'pengajuan_judul',
            'riwayatJudul'        => [],
            'judulAktifDisetujui' => null,
            'masihDiproses'       => false,
        ]);
    }

public function proposalTa()
{
    $role   = session()->get('role');
    $userId = (int) session()->get('user_id');

    $db = Database::connect();

    if ($role === 'mahasiswa') {
        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        $judulDisetujui  = null;
        $riwayatProposal = [];
        $masihDiproses   = false;

        if ($mahasiswa) {
            $judulDisetujui = $db->table('pengajuan_judul')
                ->where('mahasiswa_id', $mahasiswa['id'])
                ->where('status', 'disetujui')
                ->orderBy('id', 'DESC')
                ->get()
                ->getRowArray();

            $riwayatProposal = $db->table('proposal_ta pt')
                ->select('pt.*, pj.judul')
                ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
                ->where('pt.mahasiswa_id', $mahasiswa['id'])
                ->orderBy('pt.id', 'DESC')
                ->get()
                ->getResultArray();

            $masihDiproses = $db->table('proposal_ta')
                ->where('mahasiswa_id', $mahasiswa['id'])
                ->whereIn('status', ['diajukan', 'direview'])
                ->countAllResults() > 0;
        }

        $totalProposal = count($riwayatProposal);
        $totalDisetujui = 0;
        $totalRevisi = 0;

        foreach ($riwayatProposal as $row) {
            $status = strtolower((string) ($row['status'] ?? ''));
            if ($status === 'disetujui') {
                $totalDisetujui++;
            }
            if ($status === 'revisi') {
                $totalRevisi++;
            }
        }

        return view('dashboard/proposal_ta', [
            'title'           => 'Proposal Tugas Akhir',
            'pageTitle'       => 'Proposal Tugas Akhir',
            'pageSubtitle'    => 'Kelola upload, revisi, dan histori proposal tugas akhir',
            'activeMenu'      => 'proposal_ta',
            'judulDisetujui'  => $judulDisetujui,
            'riwayatProposal' => $riwayatProposal,
            'masihDiproses'   => $masihDiproses,
            'totalProposal'   => $totalProposal,
            'totalDisetujui'  => $totalDisetujui,
            'totalRevisi'     => $totalRevisi,
        ]);
    }

    return view('dashboard/proposal_ta', [
        'title'           => 'Proposal Tugas Akhir',
        'pageTitle'       => 'Proposal Tugas Akhir',
        'pageSubtitle'    => 'Kelola upload, revisi, dan histori proposal tugas akhir',
        'activeMenu'      => 'proposal_ta',
        'judulDisetujui'  => null,
        'riwayatProposal' => [],
        'masihDiproses'   => false,
        'totalProposal'   => 0,
        'totalDisetujui'  => 0,
        'totalRevisi'     => 0,
    ]);
}

public function suratKeputusan()
{
    if (! session()->get('isLoggedIn')) {
        return redirect()->to('/login');
    }

    if (session()->get('role') !== 'mahasiswa') {
        return redirect()->to('/dashboard')->with('error', 'Halaman ini hanya untuk mahasiswa.');
    }

    $db = \Config\Database::connect();

    $userId = (int) session()->get('user_id');

    $mahasiswa = $db->table('mahasiswa')
        ->where('user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $mahasiswa) {
        return view('dashboard/surat_keputusan', [
            'title'        => 'Surat Keputusan',
            'pageTitle'    => 'Surat Keputusan',
            'pageSubtitle' => 'Lihat dan unduh SK tugas akhir',
            'activeMenu'   => 'surat_keputusan',
            'rows'         => [],
        ]);
    }

    $rows = $db->table('surat_keputusan sk')
        ->select('sk.*, pj.judul, pt.nama_file_asli')
        ->join('pengajuan_judul pj', 'pj.id = sk.pengajuan_judul_id', 'left')
        ->join('proposal_ta pt', 'pt.id = sk.proposal_ta_id', 'left')
        ->where('sk.mahasiswa_id', $mahasiswa['id'])
        ->orderBy('sk.tanggal_terbit', 'DESC')
        ->get()
        ->getResultArray();

    return view('dashboard/surat_keputusan', [
        'title'        => 'Surat Keputusan',
        'pageTitle'    => 'Surat Keputusan',
        'pageSubtitle' => 'Lihat dan unduh SK tugas akhir',
        'activeMenu'   => 'surat_keputusan',
        'rows'         => $rows,
    ]);
}
}