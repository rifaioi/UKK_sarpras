<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - <?= $loan_code ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
        }
        .receipt-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border: 2px solid #333;
        }
        .receipt-header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        .receipt-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .loan-code {
            font-size: 1.2rem;
            font-weight: bold;
            color: #0066cc;
            margin: 1rem 0;
        }
        .info-row {
            display: flex;
            margin-bottom: 0.5rem;
        }
        .info-label {
            font-weight: bold;
            width: 200px;
        }
        .qr-section {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px dashed #999;
        }
        .signature-section {
            margin-top: 3rem;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="receipt-title">BUKTI PEMINJAMAN SARPRAS</div>
            <div>Sistem Manajemen Sarana dan Prasarana</div>
            <div class="loan-code"><?= $loan_code ?></div>
        </div>

        <div class="receipt-body">
            <h5 class="mb-3">Informasi Peminjaman</h5>
            
            <div class="info-row">
                <div class="info-label">Nama Peminjam:</div>
                <div><?= esc($peminjaman['nama_lengkap']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Nama Barang:</div>
                <div><?= esc($peminjaman['nama_barang']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Kode Barang:</div>
                <div><?= esc($peminjaman['kode']) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Jumlah:</div>
                <div><?= $peminjaman['jumlah'] ?> unit</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Tanggal Pinjam:</div>
                <div><?= date('d F Y', strtotime($peminjaman['tgl_pinjam'])) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Rencana Kembali:</div>
                <div><?= date('d F Y', strtotime($peminjaman['tgl_kembali_rencana'])) ?></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div><strong><?= esc($peminjaman['nama_status']) ?></strong></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Tanggal Cetak:</div>
                <div><?= date('d F Y H:i:s') ?></div>
            </div>
        </div>

        <div class="qr-section">
            <p><strong>Scan QR Code untuk verifikasi:</strong></p>
            <?php 
                // Format PJID:[DATABASE_ID] for the scanner to easily parse
                $qrData = "PJID:" . $peminjaman['id'];
            ?>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qrData) ?>" alt="QR Code" style="max-width: 200px;">
            <p class="mt-2 text-muted small"><?= $loan_code ?></p>
        </div>

        <div class="text-center mt-4 text-muted small">
            <p>Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan basah.</p>
            <p>Harap membawa bukti ini saat pengembalian barang.</p>
        </div>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg">
                <i class="bi bi-printer"></i> Cetak Bukti
            </button>
            <a href="<?= base_url('admin/peminjaman') ?>" class="btn btn-secondary btn-lg">
                Kembali
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
