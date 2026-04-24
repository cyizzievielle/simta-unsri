<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::login');
$routes->get('/login', 'Auth::login');
$routes->post('/login', 'Auth::attemptLogin');
$routes->get('/logout', 'Auth::logout');

$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/dashboard', 'Dashboard::index');

    $routes->get('admin/surat-keputusan', 'Admin::suratKeputusan');
    $routes->get('admin/surat-keputusan/create', 'Admin::createSK');
    $routes->post('admin/surat-keputusan/store', 'Admin::storeSK');
    $routes->get('admin/surat-keputusan/delete/(:num)', 'Admin::deleteSK/$1');

    $routes->get('/surat-keputusan', 'Dashboard::suratKeputusan');

    $routes->get('/pembimbing', 'Dashboard::pembimbing');
    $routes->post('/pembimbing/ajukan', 'Pembimbing::ajukan');
    $routes->post('/pembimbing/permohonan/(:num)/setujui', 'Pembimbing::setujui/$1');
    $routes->post('/pembimbing/permohonan/(:num)/tolak', 'Pembimbing::tolak/$1');

    $routes->get('/pengajuan-judul', 'Dashboard::pengajuanJudul');
    $routes->post('/pengajuan-judul/simpan', 'Judul::simpan');
    $routes->get('/pengajuan-judul/detail/(:num)', 'Judul::detailMahasiswa/$1');
    $routes->get('/pengajuan-judul/revisi/(:num)', 'Judul::formRevisi/$1');
    $routes->post('/pengajuan-judul/revisi/(:num)', 'Judul::simpanRevisi/$1');

    $routes->get('/proposal-ta', 'Dashboard::proposalTa');
    $routes->get('/proposal-ta/upload', 'Proposal::formUpload');
    $routes->post('/proposal-ta/upload', 'Proposal::upload');
    $routes->get('/proposal-ta/detail/(:num)', 'Proposal::detailMahasiswa/$1');
    $routes->get('/proposal-ta/revisi/(:num)', 'Proposal::formRevisi/$1');
    $routes->post('/proposal-ta/revisi/(:num)', 'Proposal::simpanRevisi/$1');

    $routes->get('/surat-keputusan', 'Dashboard::suratKeputusan');

    $routes->get('/profile', 'Profile::index');
    $routes->get('/profile/edit', 'Profile::edit');
    $routes->post('/profile/update', 'Profile::update');

    // dosen pembimbing
    $routes->get('/dosen/permohonan', 'Pembimbing::permohonanDosen');
    $routes->get('/dosen/permohonan/detail/(:num)', 'Pembimbing::detailPermohonan/$1');
    $routes->post('/dosen/permohonan/(:num)/respon', 'Pembimbing::responPermohonan/$1');

    // dosen judul
    $routes->get('/dosen/pengajuan-judul', 'Judul::indexDosen');
    $routes->get('/dosen/pengajuan-judul/detail/(:num)', 'Judul::detailDosen/$1');
    $routes->get('/dosen/pengajuan-judul/riwayat', 'Judul::riwayatDosen');
    $routes->post('/dosen/pengajuan-judul/(:num)/review', 'Judul::review/$1');
    $routes->get('/dosen/pengajuan-judul/review/edit/(:num)', 'Judul::editReview/$1');
    $routes->post('/dosen/pengajuan-judul/review/update/(:num)', 'Judul::updateReview/$1');
    $routes->post('/dosen/pengajuan-judul/review/delete/(:num)', 'Judul::deleteReview/$1');

    // dosen proposal
    $routes->get('/dosen/proposal-ta', 'Proposal::indexDosen');
    $routes->get('/dosen/proposal-ta/detail/(:num)', 'Proposal::detailDosen/$1');
    $routes->get('/dosen/proposal-ta/riwayat', 'Proposal::riwayatDosen');
    $routes->post('/dosen/proposal-ta/(:num)/review', 'Proposal::review/$1');
    $routes->get('/dosen/proposal-ta/review/edit/(:num)', 'Proposal::editReview/$1');
    $routes->post('/dosen/proposal-ta/review/update/(:num)', 'Proposal::updateReview/$1');
    $routes->post('/dosen/proposal-ta/review/delete/(:num)', 'Proposal::deleteReview/$1');

    // admin
    $routes->group('admin', static function ($routes) {
        $routes->get('users', 'Admin::users');
        $routes->get('users/create', 'Admin::createUser');
        $routes->post('users/store', 'Admin::storeUser');
        $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
        $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
        $routes->post('users/delete/(:num)', 'Admin::deleteUser/$1');

        $routes->get('periode-akademik', 'Admin::periodeAkademik');
        $routes->get('periode-akademik/create', 'Admin::createPeriodeAkademik');
        $routes->post('periode-akademik/store', 'Admin::storePeriodeAkademik');
        $routes->get('periode-akademik/edit/(:num)', 'Admin::editPeriodeAkademik/$1');
        $routes->post('periode-akademik/update/(:num)', 'Admin::updatePeriodeAkademik/$1');
        $routes->post('periode-akademik/delete/(:num)', 'Admin::deletePeriodeAkademik/$1');

        $routes->get('program-studi', 'Admin::programStudi');
        $routes->get('program-studi/create', 'Admin::createProgramStudi');
        $routes->post('program-studi/store', 'Admin::storeProgramStudi');
        $routes->get('program-studi/edit/(:num)', 'Admin::editProgramStudi/$1');
        $routes->post('program-studi/update/(:num)', 'Admin::updateProgramStudi/$1');
        $routes->post('program-studi/delete/(:num)', 'Admin::deleteProgramStudi/$1');

        $routes->get('monitoring-pembimbing', 'Admin::monitoringPembimbing');
        $routes->get('monitoring-judul', 'Admin::monitoringJudul');
        $routes->get('monitoring-proposal', 'Admin::monitoringProposal');

        $routes->get('surat-keputusan', 'Admin::suratKeputusan');
        $routes->get('surat-keputusan/create', 'Admin::createSK');
        $routes->post('surat-keputusan/store', 'Admin::storeSK');
        $routes->get('surat-keputusan/delete/(:num)', 'Admin::deleteSK/$1');

        $routes->get('laporan', 'Admin::laporan');
        $routes->get('audit-log', 'Admin::auditLog');

        $routes->get('laporan/export/rekap', 'Admin::exportRekapPdf');
        $routes->get('laporan/export/judul', 'Admin::exportJudulPdf');
        $routes->get('laporan/export/proposal', 'Admin::exportProposalPdf');
        $routes->get('laporan/export/sk', 'Admin::exportSkPdf');
    });
});