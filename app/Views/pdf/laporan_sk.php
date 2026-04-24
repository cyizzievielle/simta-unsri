<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Arsip SK</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        h1 { margin: 0 0 10px; }
        .meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px; vertical-align: top; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Arsip Surat Keputusan</h1>
    <div class="meta">
        <strong>Tahun Ajaran:</strong> <?= esc((string) ($periode['tahun_ajaran'] ?? '-')) ?><br>
        <strong>Semester:</strong> <?= esc((string) ($periode['semester'] ?? '-')) ?>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Mahasiswa</th>
                <th>NIM</th>
                <th>Judul</th>
                <th>Nomor SK</th>
                <th>Tanggal Terbit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc((string) ($row['nama_mahasiswa'] ?? '-')) ?></td>
                    <td><?= esc((string) ($row['nim'] ?? '-')) ?></td>
                    <td><?= esc((string) ($row['judul'] ?? '-')) ?></td>
                    <td><?= esc((string) ($row['nomor_sk'] ?? '-')) ?></td>
                    <td><?= esc((string) ($row['tanggal_terbit'] ?? '-')) ?></td>
                    <td><?= esc((string) ($row['status'] ?? '-')) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>