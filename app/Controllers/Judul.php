<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Judul extends BaseController
{
    public function simpan()
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'mahasiswa') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'judul'          => 'required|min_length[10]|max_length[255]',
            'latar_belakang' => 'required|min_length[20]',
            'bidang_topik'   => 'required|max_length[100]',
            'kata_kunci'     => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data pengajuan judul belum lengkap atau tidak valid.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $judulAktif = $db->table('pengajuan_judul')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->where('status', 'disetujui')
            ->countAllResults();

        if ($judulAktif > 0) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Judul sudah disetujui. Anda tidak dapat mengajukan judul baru.');
        }

        $masihProses = $db->table('pengajuan_judul')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->whereIn('status', ['diajukan', 'direview'])
            ->countAllResults();

        if ($masihProses > 0) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Masih ada pengajuan judul yang sedang diproses.');
        }

        $judul         = trim((string) $this->request->getPost('judul'));
        $latarBelakang = trim((string) $this->request->getPost('latar_belakang'));
        $bidangTopik   = trim((string) $this->request->getPost('bidang_topik'));
        $kataKunci     = trim((string) $this->request->getPost('kata_kunci'));

        $judulNormalized = $this->normalizeJudul($judul);

        [$similarityScore, $similarityFlag] = $this->hitungSimilarity($db, $judulNormalized);

        $db->table('pengajuan_judul')->insert([
            'mahasiswa_id'        => $mahasiswa['id'],
            'periode_akademik_id' => 1,
            'judul'               => $judul,
            'judul_normalized'    => $judulNormalized,
            'latar_belakang'      => $latarBelakang,
            'bidang_topik'        => $bidangTopik,
            'kata_kunci'          => $kataKunci,
            'similarity_score'    => $similarityScore,
            'similarity_flag'     => $similarityFlag,
            'status'              => 'diajukan',
            'versi_ke'            => 1,
            'parent_id'           => null,
            'tanggal_pengajuan'   => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        $pengajuanId = $db->insertID();

        $db->table('judul_similarity_logs')->insert([
            'pengajuan_judul_id'     => $pengajuanId,
            'compared_with_judul_id' => null,
            'judul_pembanding'       => 'Pemeriksaan otomatis sistem',
            'score'                  => $similarityScore,
            'hasil'                  => $similarityFlag ? 'terindikasi_mirip' : 'aman',
            'created_at'             => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/pengajuan-judul')->with('success', 'Judul berhasil diajukan.');
    }

    public function formRevisi($id)
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'mahasiswa') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengajuan = $db->table('pengajuan_judul')
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->get()
            ->getRowArray();

        if (! $pengajuan) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Data pengajuan tidak ditemukan.');
        }

        if (! in_array($pengajuan['status'], ['revisi', 'ditolak'], true)) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Pengajuan ini tidak dapat direvisi.');
        }

        return view('dashboard/revisi_judul', [
            'title'        => 'Revisi Judul',
            'pageTitle'    => $pengajuan['status'] === 'revisi' ? 'Revisi Judul' : 'Ajukan Judul Baru',
            'pageSubtitle' => $pengajuan['status'] === 'revisi'
                ? 'Perbaiki judul berdasarkan catatan reviewer'
                : 'Ajukan judul baru setelah penolakan',
            'activeMenu'   => 'pengajuan_judul',
            'pengajuan'    => $pengajuan,
        ]);
    }

    public function simpanRevisi($id)
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'mahasiswa') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'judul'          => 'required|min_length[10]|max_length[255]',
            'latar_belakang' => 'required|min_length[20]',
            'bidang_topik'   => 'required|max_length[100]',
            'kata_kunci'     => 'required|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data revisi belum lengkap atau tidak valid.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengajuanLama = $db->table('pengajuan_judul')
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->get()
            ->getRowArray();

        if (! $pengajuanLama) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Pengajuan lama tidak ditemukan.');
        }

        if (! in_array($pengajuanLama['status'], ['revisi', 'ditolak'], true)) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Pengajuan ini tidak bisa dikirim ulang.');
        }

        $masihProses = $db->table('pengajuan_judul')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->whereIn('status', ['diajukan', 'direview'])
            ->countAllResults();

        if ($masihProses > 0) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Masih ada pengajuan judul lain yang sedang diproses.');
        }

        $judul         = trim((string) $this->request->getPost('judul'));
        $latarBelakang = trim((string) $this->request->getPost('latar_belakang'));
        $bidangTopik   = trim((string) $this->request->getPost('bidang_topik'));
        $kataKunci     = trim((string) $this->request->getPost('kata_kunci'));

        $judulNormalized = $this->normalizeJudul($judul);

        [$similarityScore, $similarityFlag] = $this->hitungSimilarity($db, $judulNormalized, (int) $pengajuanLama['id']);

        $parentId = $pengajuanLama['status'] === 'revisi' ? (int) $pengajuanLama['id'] : null;
        $versiKe  = $pengajuanLama['status'] === 'revisi' ? ((int) $pengajuanLama['versi_ke']) + 1 : 1;

        $db->table('pengajuan_judul')->insert([
            'mahasiswa_id'        => $mahasiswa['id'],
            'periode_akademik_id' => 1,
            'judul'               => $judul,
            'judul_normalized'    => $judulNormalized,
            'latar_belakang'      => $latarBelakang,
            'bidang_topik'        => $bidangTopik,
            'kata_kunci'          => $kataKunci,
            'similarity_score'    => $similarityScore,
            'similarity_flag'     => $similarityFlag,
            'status'              => 'diajukan',
            'catatan_reviewer'    => null,
            'versi_ke'            => $versiKe,
            'parent_id'           => $parentId,
            'tanggal_pengajuan'   => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        $pengajuanBaruId = $db->insertID();

        $db->table('judul_similarity_logs')->insert([
            'pengajuan_judul_id'     => $pengajuanBaruId,
            'compared_with_judul_id' => null,
            'judul_pembanding'       => 'Pemeriksaan otomatis sistem',
            'score'                  => $similarityScore,
            'hasil'                  => $similarityFlag ? 'terindikasi_mirip' : 'aman',
            'created_at'             => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/pengajuan-judul')->with('success', 'Pengajuan berhasil dikirim ulang.');
    }

    public function indexDosen()
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'dosen') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $db = Database::connect();

        $dosen = $db->table('dosen')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $dosen) {
            return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
        }

        $pengajuanAktif = $db->table('pengajuan_judul pj')
            ->select('pj.id, pj.judul, pj.status, pj.versi_ke, pj.tanggal_pengajuan, u.name AS nama_mahasiswa, m.nim')
            ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
            ->join('users u', 'u.id = m.user_id')
            ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = m.id')
            ->where('pm.dosen_id', $dosen['id'])
            ->where('pm.status_aktif', 1)
            ->whereIn('pj.status', ['diajukan', 'direview'])
            ->orderBy('pj.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/pengajuan_judul_dosen_list', [
            'title'          => 'Pengajuan Judul Dosen',
            'pageTitle'      => 'Pengajuan Judul Mahasiswa',
            'pageSubtitle'   => 'Daftar judul aktif yang perlu ditinjau',
            'activeMenu'     => 'pengajuan_judul_dosen',
            'pengajuanAktif' => $pengajuanAktif,
        ]);
    }

public function detailDosen($id)
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

    $pengajuan = $db->table('pengajuan_judul pj')
        ->select('pj.*, um.name as nama_mahasiswa, m.nim')
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = pj.mahasiswa_id AND pm.status_aktif = 1')
        ->where('pj.id', $id)
        ->where('pm.dosen_id', $dosen['id'])
        ->groupBy('pj.id')
        ->get()
        ->getRowArray();

    if (! $pengajuan) {
        return redirect()->to('/dosen/pengajuan-judul')->with('error', 'Pengajuan tidak ditemukan.');
    }

    $pembimbingList = $db->table('pembimbing_mahasiswa pm')
        ->select('pm.*, d.nidn, u.name as nama_dosen')
        ->join('dosen d', 'd.id = pm.dosen_id')
        ->join('users u', 'u.id = d.user_id')
        ->where('pm.mahasiswa_id', $pengajuan['mahasiswa_id'])
        ->where('pm.status_aktif', 1)
        ->orderBy('pm.jenis_pembimbing', 'ASC')
        ->get()
        ->getResultArray();

    $reviewPembimbing = $db->table('review_judul rj')
        ->select('rj.*, u.name as nama_dosen, pm.jenis_pembimbing')
        ->join('users u', 'u.id = rj.reviewer_user_id')
        ->join('dosen d', 'd.user_id = rj.reviewer_user_id')
        ->join('pembimbing_mahasiswa pm', 'pm.dosen_id = d.id AND pm.mahasiswa_id = ' . (int) $pengajuan['mahasiswa_id'] . ' AND pm.status_aktif = 1')
        ->where('rj.pengajuan_judul_id', $pengajuan['id'])
        ->orderBy('pm.jenis_pembimbing', 'ASC')
        ->get()
        ->getResultArray();

    return view('dashboard/review_judul_dosen_detail', [
        'title'            => 'Detail Pengajuan Judul',
        'pageTitle'        => 'Detail Pengajuan Judul',
        'pageSubtitle'     => 'Tinjau detail judul dan beri keputusan review',
        'activeMenu'       => 'judul_dosen',
        'pengajuan'        => $pengajuan,
        'pembimbingList'   => $pembimbingList,
        'reviewPembimbing' => $reviewPembimbing,
    ]);
}

public function editReview($id)
{
    $userId = (int) session()->get('user_id');

    $db = \Config\Database::connect();

    $review = $db->table('review_judul rj')
        ->select('rj.*, pj.judul, um.name AS nama_mahasiswa, m.nim')
        ->join('pengajuan_judul pj', 'pj.id = rj.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('rj.id', $id)
        ->where('rj.reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $review) {
        return redirect()->to('/dosen/pengajuan-judul/riwayat')->with('error', 'Review tidak ditemukan.');
    }

    return view('dashboard/edit_review_judul_dosen', [
        'title'      => 'Edit Review Judul',
        'pageTitle'  => 'Edit Review Judul',
        'activeMenu' => 'riwayat_review_dosen',
        'review'     => $review,
    ]);
}

public function updateReview($id)
{
    $userId = (int) session()->get('user_id');
    $status = trim((string) $this->request->getPost('status_review'));
    $catatan = trim((string) $this->request->getPost('catatan'));

    if (! in_array($status, ['direview', 'disetujui', 'revisi', 'ditolak'], true)) {
        return redirect()->back()->withInput()->with('error', 'Status review tidak valid.');
    }

    $db = \Config\Database::connect();

    $review = $db->table('review_judul')
        ->where('id', $id)
        ->where('reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $review) {
        return redirect()->to('/dosen/pengajuan-judul/riwayat')->with('error', 'Review tidak ditemukan.');
    }

    $db->table('review_judul')
        ->where('id', $id)
        ->update([
            'status_review' => $status,
            'catatan'       => $catatan !== '' ? $catatan : null,
        ]);

    return redirect()->to('/dosen/pengajuan-judul/riwayat')->with('success', 'Review judul berhasil diupdate.');
}

public function deleteReview($id)
{
    $userId = (int) session()->get('user_id');

    $db = \Config\Database::connect();

    $review = $db->table('review_judul')
        ->where('id', $id)
        ->where('reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $review) {
        return redirect()->to('/dosen/pengajuan-judul/riwayat')->with('error', 'Review tidak ditemukan.');
    }

    $db->table('review_judul')
        ->where('id', $id)
        ->delete();

    return redirect()->to('/dosen/pengajuan-judul/riwayat')->with('success', 'Review judul berhasil dihapus.');
}

public function riwayatDosen()
{
    $userId = (int) session()->get('user_id');
    $role   = session()->get('role');

    if ($role !== 'dosen') {
        return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
    }

    $db = \Config\Database::connect();

    $keyword = trim((string) $this->request->getGet('q'));
    $status  = trim((string) $this->request->getGet('status'));
    $perPage = (int) ($this->request->getGet('per_page') ?: 10);
    $page    = max(1, (int) ($this->request->getGet('page') ?: 1));

    if (! in_array($perPage, [10, 50, 100], true)) {
        $perPage = 10;
    }

    $builder = $db->table('review_judul rj')
        ->select('rj.*, pj.judul, um.name AS nama_mahasiswa, m.nim')
        ->join('pengajuan_judul pj', 'pj.id = rj.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pj.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('rj.reviewer_user_id', $userId);

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('um.name', $keyword)
            ->orLike('m.nim', $keyword)
            ->orLike('pj.judul', $keyword)
            ->groupEnd();
    }

    if ($status !== '' && in_array($status, ['direview', 'disetujui', 'revisi', 'ditolak'], true)) {
        $builder->where('rj.status_review', $status);
    }

    $totalRows = (clone $builder)->countAllResults();

    $offset = ($page - 1) * $perPage;

    $rows = (clone $builder)
        ->orderBy('rj.created_at', 'DESC')
        ->limit($perPage, $offset)
        ->get()
        ->getResultArray();

    $totalDisetujui = 0;
    $totalTolakRevisi = 0;
    foreach ($rows as $row) {
        if (($row['status_review'] ?? '') === 'disetujui') {
            $totalDisetujui++;
        }
        if (in_array(($row['status_review'] ?? ''), ['ditolak', 'revisi'], true)) {
            $totalTolakRevisi++;
        }
    }

    $totalPages = max(1, (int) ceil($totalRows / $perPage));
    $startRow   = $totalRows > 0 ? $offset + 1 : 0;
    $endRow     = min($offset + $perPage, $totalRows);

    return view('dashboard/riwayat_review_dosen', [
        'title'            => 'Riwayat Review Judul',
        'pageTitle'        => 'Riwayat Review Judul',
        'pageSubtitle'     => 'Daftar review final yang pernah Anda berikan',
        'activeMenu'       => 'riwayat_review_dosen',

        'riwayat'          => $rows,
        'keyword'          => $keyword,
        'status'           => $status,
        'perPage'          => $perPage,
        'page'             => $page,
        'totalRows'        => $totalRows,
        'totalPages'       => $totalPages,
        'startRow'         => $startRow,
        'endRow'           => $endRow,

        'totalDisetujui'   => $totalDisetujui,
        'totalTolakRevisi' => $totalTolakRevisi,
    ]);
}

    public function detailMahasiswa($id)
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'mahasiswa') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $pengajuan = $db->table('pengajuan_judul')
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->get()
            ->getRowArray();

        if (! $pengajuan) {
            return redirect()->to('/pengajuan-judul')->with('error', 'Detail pengajuan tidak ditemukan.');
        }

        $pembimbingList = $db->table('pembimbing_mahasiswa pm')
            ->select('pm.jenis_pembimbing, u.name AS nama_dosen')
            ->join('dosen d', 'd.id = pm.dosen_id')
            ->join('users u', 'u.id = d.user_id')
            ->where('pm.mahasiswa_id', $mahasiswa['id'])
            ->where('pm.status_aktif', 1)
            ->orderBy('pm.jenis_pembimbing', 'ASC')
            ->get()
            ->getResultArray();

        $riwayatReview = $db->table('review_judul rj')
            ->select('rj.*, u.name AS nama_reviewer')
            ->join('users u', 'u.id = rj.reviewer_user_id')
            ->where('rj.pengajuan_judul_id', $id)
            ->orderBy('rj.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/detail_judul_mahasiswa', [
            'title'         => 'Detail Judul',
            'pageTitle'     => 'Detail Pengajuan Judul',
            'pageSubtitle'  => 'Lihat pembimbing dan histori review judul',
            'activeMenu'    => 'pengajuan_judul',
            'pengajuan'     => $pengajuan,
            'pembimbingList'=> $pembimbingList,
            'riwayatReview' => $riwayatReview,
        ]);
    }

public function review($id)
{
    $userId = (int) session()->get('user_id');
    $role   = session()->get('role');

    if ($role !== 'dosen') {
        return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
    }

    $statusReview = trim((string) $this->request->getPost('status'));
    $catatan      = trim((string) $this->request->getPost('catatan_reviewer'));

    if (! in_array($statusReview, ['direview', 'disetujui', 'revisi', 'ditolak'], true)) {
        return redirect()->back()->with('error', 'Status review tidak valid.');
    }

    $db = \Config\Database::connect();

    $dosen = $db->table('dosen')
        ->where('user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $dosen) {
        return redirect()->to('/dashboard')->with('error', 'Data dosen tidak ditemukan.');
    }

    $pengajuan = $db->table('pengajuan_judul')
        ->where('id', $id)
        ->get()
        ->getRowArray();

    if (! $pengajuan) {
        return redirect()->to('/dosen/pengajuan-judul')->with('error', 'Pengajuan judul tidak ditemukan.');
    }

    // cek apakah dosen ini pembimbing aktif mahasiswa tsb
    $pembimbing = $db->table('pembimbing_mahasiswa')
        ->where('mahasiswa_id', $pengajuan['mahasiswa_id'])
        ->where('dosen_id', $dosen['id'])
        ->where('status_aktif', 1)
        ->get()
        ->getRowArray();

    if (! $pembimbing) {
        return redirect()->to('/dosen/pengajuan-judul')->with('error', 'Anda bukan pembimbing aktif mahasiswa ini.');
    }

    $db->transStart();

    // review dosen ini saja
    $reviewLama = $db->table('review_judul')
        ->where('pengajuan_judul_id', $pengajuan['id'])
        ->where('reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if ($reviewLama) {
        $db->table('review_judul')
            ->where('id', $reviewLama['id'])
            ->update([
                'status_review' => $statusReview,
                'catatan'       => $catatan !== '' ? $catatan : null,
            ]);
    } else {
        $db->table('review_judul')->insert([
            'pengajuan_judul_id' => $pengajuan['id'],
            'reviewer_user_id'   => $userId,
            'status_review'      => $statusReview,
            'catatan'            => $catatan !== '' ? $catatan : null,
            'created_at'         => date('Y-m-d H:i:s'),
        ]);
    }

    // ambil semua pembimbing aktif mahasiswa
    $semuaPembimbing = $db->table('pembimbing_mahasiswa pm')
        ->select('pm.*, d.user_id as reviewer_user_id')
        ->join('dosen d', 'd.id = pm.dosen_id')
        ->where('pm.mahasiswa_id', $pengajuan['mahasiswa_id'])
        ->where('pm.status_aktif', 1)
        ->get()
        ->getResultArray();

    // ambil semua review yang sudah masuk untuk pengajuan ini
    $semuaReview = $db->table('review_judul')
        ->where('pengajuan_judul_id', $pengajuan['id'])
        ->get()
        ->getResultArray();

    $reviewMap = [];
    foreach ($semuaReview as $rv) {
        $reviewMap[$rv['reviewer_user_id']] = $rv['status_review'];
    }

    $jumlahPembimbing = count($semuaPembimbing);
    $jumlahFinalMasuk = 0;
    $adaDitolak = false;
    $adaRevisi = false;
    $semuaSetuju = true;

    foreach ($semuaPembimbing as $pb) {
        $statusPb = $reviewMap[$pb['reviewer_user_id']] ?? null;

        if (in_array($statusPb, ['disetujui', 'revisi', 'ditolak'], true)) {
            $jumlahFinalMasuk++;
        }

        if ($statusPb === 'ditolak') {
            $adaDitolak = true;
        }

        if ($statusPb === 'revisi') {
            $adaRevisi = true;
        }

        if ($statusPb !== 'disetujui') {
            $semuaSetuju = false;
        }
    }

    // hitung status akhir pengajuan
    $statusFinal = 'direview';

    if ($adaDitolak) {
        $statusFinal = 'ditolak';
    } elseif ($jumlahFinalMasuk < $jumlahPembimbing) {
        $statusFinal = 'direview';
    } elseif ($adaRevisi) {
        $statusFinal = 'revisi';
    } elseif ($semuaSetuju && $jumlahPembimbing > 0) {
        $statusFinal = 'disetujui';
    }

    $db->table('pengajuan_judul')
        ->where('id', $pengajuan['id'])
        ->update([
            'status'       => $statusFinal,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

    $db->transComplete();

    if (! $db->transStatus()) {
        return redirect()->back()->with('error', 'Gagal menyimpan review judul.');
    }

    return redirect()->to('/dosen/pengajuan-judul/detail/' . $pengajuan['id'])
        ->with('success', 'Review berhasil disimpan.');
}

    private function normalizeJudul(string $judul): string
    {
        $judul = strtolower($judul);
        $judul = preg_replace('/[^a-z0-9\s]/', '', $judul);
        $judul = preg_replace('/\s+/', ' ', $judul);
        return trim($judul);
    }

    private function hitungSimilarity($db, string $judulNormalized, int $excludeId = 0): array
    {
        $builder = $db->table('pengajuan_judul')->select('id, judul_normalized');

        if ($excludeId > 0) {
            $builder->where('id !=', $excludeId);
        }

        $cekMirip = $builder->get()->getResultArray();

        $similarityScore = 0.00;
        $similarityFlag  = 0;

        foreach ($cekMirip as $item) {
            similar_text($judulNormalized, (string) ($item['judul_normalized'] ?? ''), $persen);
            if ($persen > $similarityScore) {
                $similarityScore = round($persen, 2);
            }
        }

        if ($similarityScore >= 70) {
            $similarityFlag = 1;
        }

        return [$similarityScore, $similarityFlag];
    }
}