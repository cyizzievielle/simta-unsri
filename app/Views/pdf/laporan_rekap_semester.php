<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekap Semester</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
        h1, h2 { margin: 0 0 10px; }
        .meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Laporan Rekap Semester</h1>
    <div class="meta">
        <strong>Tahun Ajaran:</strong> <?= esc((string) ($periode['tahun_ajaran'] ?? '-')) ?><br>
        <strong>Semester:</strong> <?= esc((string) ($periode['semester'] ?? '-')) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>Komponen</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Total Permohonan Pembimbing</td><td><?= esc((string) ($summary['permohonan_total'] ?? 0)) ?></td></tr>
            <tr><td>Pembimbing Disetujui</td><td><?= esc((string) ($summary['permohonan_disetujui'] ?? 0)) ?></td></tr>
            <tr><td>Total Pengajuan Judul</td><td><?= esc((string) ($summary['judul_total'] ?? 0)) ?></td></tr>
            <tr><td>Judul Disetujui</td><td><?= esc((string) ($summary['judul_disetujui'] ?? 0)) ?></td></tr>
            <tr><td>Total Proposal</td><td><?= esc((string) ($summary['proposal_total'] ?? 0)) ?></td></tr>
            <tr><td>Proposal Disetujui</td><td><?= esc((string) ($summary['proposal_disetujui'] ?? 0)) ?></td></tr>
            <tr><td>Total SK Terbit</td><td><?= esc((string) ($summary['sk_total'] ?? 0)) ?></td></tr>
        </tbody>
    </table>
</body>
</html>