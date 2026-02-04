<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
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
        <h2>LAPORAN PEMINJAMAN SARANA PRASARANA</h2>
        <p>Periode: <?= $filter['tgl_awal'] ? date('d/m/Y', strtotime($filter['tgl_awal'])) : 'Awal' ?> s/d <?= $filter['tgl_akhir'] ? date('d/m/Y', strtotime($filter['tgl_akhir'])) : 'Sekarang' ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pinjam</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali (Rencana)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($peminjaman as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($p['kode_peminjaman']) ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['nama_barang']) ?></td>
                <td><?= esc($p['jumlah']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_kembali_rencana'])) ?></td>
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
