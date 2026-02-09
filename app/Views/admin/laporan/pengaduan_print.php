<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 50px; text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>LAPORAN PENGADUAN SARANA PRASARANA</h2>
        <p>Dicetak pada: <?= date('d/m/Y H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelapor</th>
                <th>Judul Pengaduan</th>
                <th>Lokasi</th>
                <th>Tgl Lapor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pengaduan as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['judul']) ?></td>
                <td><?= esc($p['lokasi']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                <td><?= esc($p['nama_status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: <?= date('d/m/Y H:i') ?></p>
        <br><br><br>
        <p>( _______________________ )</p>
        <p>Admin Sarpras</p>
    </div>

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">Cetak Lagi</button>
        <button onclick="window.history.back()">Kembali</button>
    </div>
</body>
</html>
