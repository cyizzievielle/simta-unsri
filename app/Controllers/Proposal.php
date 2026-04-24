<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Proposal extends BaseController
{
    public function upload()
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'mahasiswa') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'file_proposal' => 'uploaded[file_proposal]|max_size[file_proposal,5120]|ext_in[file_proposal,pdf,doc,docx]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'File proposal tidak valid. Gunakan PDF/DOC/DOCX maksimal 5 MB.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $judulDisetujui = $db->table('pengajuan_judul')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->where('status', 'disetujui')
            ->orderBy('id', 'DESC')
            ->get()
            ->getRowArray();

        if (! $judulDisetujui) {
            return redirect()->back()->with('error', 'Proposal hanya bisa diunggah jika judul sudah disetujui.');
        }

        $masihDiproses = $db->table('proposal_ta')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->whereIn('status', ['diajukan', 'direview'])
            ->countAllResults();

        if ($masihDiproses > 0) {
            return redirect()->back()->with('error', 'Masih ada proposal yang sedang diproses.');
        }

        $file = $this->request->getFile('file_proposal');

        if (! $file->isValid()) {
            return redirect()->back()->with('error', 'File gagal diupload.');
        }

        $namaAsli = $file->getClientName();
        $namaBaru = $file->getRandomName();

        $uploadPath = FCPATH . 'uploads/proposal';

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $namaBaru);

        $versiKe = 1;

        $proposalTerakhir = $db->table('proposal_ta')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->where('pengajuan_judul_id', $judulDisetujui['id'])
            ->orderBy('versi_ke', 'DESC')
            ->get()
            ->getRowArray();

        if ($proposalTerakhir) {
            $versiKe = ((int) $proposalTerakhir['versi_ke']) + 1;
        }

        $db->table('proposal_ta')->insert([
            'mahasiswa_id'        => $mahasiswa['id'],
            'pengajuan_judul_id'  => $judulDisetujui['id'],
            'periode_akademik_id' => 1,
            'file_proposal'       => $namaBaru,
            'nama_file_asli'      => $namaAsli,
            'versi_ke'            => $versiKe,
            'status'              => 'diajukan',
            'tanggal_upload'      => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/proposal-ta')->with('success', 'Proposal berhasil diunggah.');
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
            return redirect()->to('/proposal-ta')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $proposal = $db->table('proposal_ta')
            ->select('proposal_ta.*, pengajuan_judul.judul')
            ->join('pengajuan_judul', 'pengajuan_judul.id = proposal_ta.pengajuan_judul_id')
            ->where('proposal_ta.id', $id)
            ->where('proposal_ta.mahasiswa_id', $mahasiswa['id'])
            ->get()
            ->getRowArray();

        if (! $proposal) {
            return redirect()->to('/proposal-ta')->with('error', 'Data proposal tidak ditemukan.');
        }

        if (! in_array($proposal['status'], ['revisi', 'ditolak'], true)) {
            return redirect()->to('/proposal-ta')->with('error', 'Proposal ini tidak dapat dikirim ulang.');
        }

        return view('dashboard/revisi_proposal', [
            'title'        => 'Revisi Proposal',
            'pageTitle'    => $proposal['status'] === 'revisi' ? 'Revisi Proposal' : 'Upload Proposal Baru',
            'pageSubtitle' => $proposal['status'] === 'revisi'
                ? 'Unggah revisi proposal berdasarkan catatan reviewer'
                : 'Unggah proposal baru setelah penolakan',
            'activeMenu'   => 'proposal_ta',
            'proposal'     => $proposal,
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
            'file_proposal' => 'uploaded[file_proposal]|max_size[file_proposal,5120]|ext_in[file_proposal,pdf,doc,docx]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'File proposal tidak valid. Gunakan PDF/DOC/DOCX maksimal 5 MB.');
        }

        $db = Database::connect();

        $mahasiswa = $db->table('mahasiswa')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $mahasiswa) {
            return redirect()->to('/proposal-ta')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $proposalLama = $db->table('proposal_ta')
            ->where('id', $id)
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->get()
            ->getRowArray();

        if (! $proposalLama) {
            return redirect()->to('/proposal-ta')->with('error', 'Proposal lama tidak ditemukan.');
        }

        if (! in_array($proposalLama['status'], ['revisi', 'ditolak'], true)) {
            return redirect()->to('/proposal-ta')->with('error', 'Proposal ini tidak bisa dikirim ulang.');
        }

        $masihDiproses = $db->table('proposal_ta')
            ->where('mahasiswa_id', $mahasiswa['id'])
            ->whereIn('status', ['diajukan', 'direview'])
            ->countAllResults();

        if ($masihDiproses > 0) {
            return redirect()->to('/proposal-ta')->with('error', 'Masih ada proposal lain yang sedang diproses.');
        }

        $file = $this->request->getFile('file_proposal');

        if (! $file->isValid()) {
            return redirect()->back()->with('error', 'File gagal diupload.');
        }

        $namaAsli = $file->getClientName();
        $namaBaru = $file->getRandomName();

        $uploadPath = FCPATH . 'uploads/proposal';

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $file->move($uploadPath, $namaBaru);

        $versiKe = $proposalLama['status'] === 'revisi'
            ? ((int) $proposalLama['versi_ke']) + 1
            : 1;

        $db->table('proposal_ta')->insert([
            'mahasiswa_id'        => $mahasiswa['id'],
            'pengajuan_judul_id'  => $proposalLama['pengajuan_judul_id'],
            'periode_akademik_id' => 1,
            'file_proposal'       => $namaBaru,
            'nama_file_asli'      => $namaAsli,
            'versi_ke'            => $versiKe,
            'status'              => 'diajukan',
            'catatan_reviewer'    => null,
            'tanggal_upload'      => date('Y-m-d H:i:s'),
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/proposal-ta')->with('success', 'Proposal berhasil dikirim ulang.');
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

        $proposalAktif = $db->table('proposal_ta pt')
            ->select('pt.id, pt.nama_file_asli, pt.status, pt.versi_ke, pt.tanggal_upload, u.name AS nama_mahasiswa, m.nim, pj.judul')
            ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
            ->join('users u', 'u.id = m.user_id')
            ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
            ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = m.id')
            ->where('pm.dosen_id', $dosen['id'])
            ->where('pm.status_aktif', 1)
            ->whereIn('pt.status', ['diajukan', 'direview'])
            ->orderBy('pt.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/proposal_dosen_list', [
            'title'         => 'Proposal Dosen',
            'pageTitle'     => 'Proposal Mahasiswa',
            'pageSubtitle'  => 'Daftar proposal aktif yang perlu ditinjau',
            'activeMenu'    => 'proposal_dosen',
            'proposalAktif' => $proposalAktif,
        ]);
    }

    public function detailDosen($id)
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

        $proposal = $db->table('proposal_ta pt')
            ->select('pt.*, u.name AS nama_mahasiswa, m.nim, pj.judul')
            ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
            ->join('users u', 'u.id = m.user_id')
            ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
            ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = m.id')
            ->where('pt.id', $id)
            ->where('pm.dosen_id', $dosen['id'])
            ->where('pm.status_aktif', 1)
            ->get()
            ->getRowArray();

        if (! $proposal) {
            return redirect()->to('/dosen/proposal-ta')->with('error', 'Detail proposal tidak ditemukan.');
        }

        $pembimbingList = $db->table('pembimbing_mahasiswa pm')
            ->select('pm.jenis_pembimbing, u.name AS nama_dosen')
            ->join('dosen d', 'd.id = pm.dosen_id')
            ->join('users u', 'u.id = d.user_id')
            ->where('pm.mahasiswa_id', $proposal['mahasiswa_id'])
            ->where('pm.status_aktif', 1)
            ->orderBy('pm.jenis_pembimbing', 'ASC')
            ->get()
            ->getResultArray();

        $riwayatReview = $db->table('review_proposal rp')
            ->select('rp.*, u.name AS nama_reviewer')
            ->join('users u', 'u.id = rp.reviewer_user_id')
            ->where('rp.proposal_ta_id', $id)
            ->orderBy('rp.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/proposal_dosen_detail', [
            'title'          => 'Detail Proposal',
            'pageTitle'      => 'Detail Proposal Mahasiswa',
            'pageSubtitle'   => 'Tinjau file proposal dan beri keputusan review',
            'activeMenu'     => 'proposal_dosen',
            'proposal'       => $proposal,
            'pembimbingList' => $pembimbingList,
            'riwayatReview'  => $riwayatReview,
        ]);
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

    $builder = $db->table('review_proposal rp')
    ->select('rp.*, pj.judul, pt.file_proposal, pt.nama_file_asli, um.name AS nama_mahasiswa, m.nim')
    ->join('proposal_ta pt', 'pt.id = rp.proposal_ta_id')
    ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
    ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
    ->join('users um', 'um.id = m.user_id')
    ->where('rp.reviewer_user_id', $userId);

    if ($keyword !== '') {
        $builder->groupStart()
            ->like('um.name', $keyword)
            ->orLike('m.nim', $keyword)
            ->orLike('pj.judul', $keyword)
            ->orLike('pt.nama_file_asli', $keyword)
            ->groupEnd();
    }

    if ($status !== '' && in_array($status, ['direview', 'disetujui', 'revisi', 'ditolak'], true)) {
        $builder->where('rp.status_review', $status);
    }

    $totalRows = (clone $builder)->countAllResults();

    $offset = ($page - 1) * $perPage;

    $rows = (clone $builder)
        ->orderBy('rp.created_at', 'DESC')
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

    return view('dashboard/riwayat_proposal_dosen', [
        'title'            => 'Riwayat Review Proposal',
        'pageTitle'        => 'Riwayat Review Proposal',
        'pageSubtitle'     => 'Daftar review proposal yang pernah Anda berikan',
        'activeMenu'       => 'riwayat_proposal_dosen',

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
            return redirect()->to('/proposal-ta')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $proposal = $db->table('proposal_ta pt')
            ->select('pt.*, pj.judul')
            ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
            ->where('pt.id', $id)
            ->where('pt.mahasiswa_id', $mahasiswa['id'])
            ->get()
            ->getRowArray();

        if (! $proposal) {
            return redirect()->to('/proposal-ta')->with('error', 'Detail proposal tidak ditemukan.');
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

        $riwayatReview = $db->table('review_proposal rp')
            ->select('rp.*, u.name AS nama_reviewer')
            ->join('users u', 'u.id = rp.reviewer_user_id')
            ->where('rp.proposal_ta_id', $id)
            ->orderBy('rp.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('dashboard/detail_proposal_mahasiswa', [
            'title'          => 'Detail Proposal',
            'pageTitle'      => 'Detail Proposal',
            'pageSubtitle'   => 'Lihat pembimbing dan histori review proposal',
            'activeMenu'     => 'proposal_ta',
            'proposal'       => $proposal,
            'pembimbingList' => $pembimbingList,
            'riwayatReview'  => $riwayatReview,
        ]);
    }

    public function editReview($id)
{
    $userId = (int) session()->get('user_id');

    $db = \Config\Database::connect();

    $review = $db->table('review_proposal rp')
        ->select('rp.*, pj.judul, pt.nama_file_asli, um.name AS nama_mahasiswa, m.nim')
        ->join('proposal_ta pt', 'pt.id = rp.proposal_ta_id')
        ->join('pengajuan_judul pj', 'pj.id = pt.pengajuan_judul_id')
        ->join('mahasiswa m', 'm.id = pt.mahasiswa_id')
        ->join('users um', 'um.id = m.user_id')
        ->where('rp.id', $id)
        ->where('rp.reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $review) {
        return redirect()->to('/dosen/proposal-ta/riwayat')->with('error', 'Review proposal tidak ditemukan.');
    }

    return view('dashboard/edit_review_proposal_dosen', [
        'title'      => 'Edit Review Proposal',
        'pageTitle'  => 'Edit Review Proposal',
        'activeMenu' => 'riwayat_proposal_dosen',
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

    $review = $db->table('review_proposal')
        ->where('id', $id)
        ->where('reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $review) {
        return redirect()->to('/dosen/proposal-ta/riwayat')->with('error', 'Review proposal tidak ditemukan.');
    }

    $db->table('review_proposal')
        ->where('id', $id)
        ->update([
            'status_review' => $status,
            'catatan'       => $catatan !== '' ? $catatan : null,
        ]);

    return redirect()->to('/dosen/proposal-ta/riwayat')->with('success', 'Review proposal berhasil diupdate.');
}

public function deleteReview($id)
{
    $userId = (int) session()->get('user_id');

    $db = \Config\Database::connect();

    $review = $db->table('review_proposal')
        ->where('id', $id)
        ->where('reviewer_user_id', $userId)
        ->get()
        ->getRowArray();

    if (! $review) {
        return redirect()->to('/dosen/proposal-ta/riwayat')->with('error', 'Review proposal tidak ditemukan.');
    }

    $db->table('review_proposal')
        ->where('id', $id)
        ->delete();

    return redirect()->to('/dosen/proposal-ta/riwayat')->with('success', 'Review proposal berhasil dihapus.');
}

    public function review($id)
    {
        $userId = (int) session()->get('user_id');
        $role   = session()->get('role');

        if ($role !== 'dosen') {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $rules = [
            'status'           => 'required|in_list[disetujui,revisi,ditolak,direview]',
            'catatan_reviewer' => 'permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->with('error', 'Data review tidak valid.');
        }

        $db = Database::connect();

        $dosen = $db->table('dosen')
            ->where('user_id', $userId)
            ->get()
            ->getRowArray();

        if (! $dosen) {
            return redirect()->back()->with('error', 'Data dosen tidak ditemukan.');
        }

        $proposal = $db->table('proposal_ta pt')
            ->select('pt.*')
            ->join('pembimbing_mahasiswa pm', 'pm.mahasiswa_id = pt.mahasiswa_id')
            ->where('pt.id', $id)
            ->where('pm.dosen_id', $dosen['id'])
            ->where('pm.status_aktif', 1)
            ->get()
            ->getRowArray();

        if (! $proposal) {
            return redirect()->back()->with('error', 'Proposal tidak ditemukan atau bukan mahasiswa bimbingan Anda.');
        }

        $status  = $this->request->getPost('status');
        $catatan = trim((string) $this->request->getPost('catatan_reviewer'));

        $db->table('proposal_ta')
            ->where('id', $id)
            ->update([
                'status'            => $status,
                'catatan_reviewer'  => $catatan ?: null,
                'tanggal_review'    => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);

        $db->table('review_proposal')->insert([
            'proposal_ta_id'    => $id,
            'reviewer_user_id'  => $userId,
            'status_review'     => $status,
            'catatan'           => $catatan ?: null,
            'created_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/dosen/proposal-ta/detail/' . $id)->with('success', 'Review proposal berhasil disimpan.');
    }
}