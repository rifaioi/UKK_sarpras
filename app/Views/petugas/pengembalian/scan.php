<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Scan QR Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="pt-3 pb-2 mb-3 border-bottom text-center">
    <h1 class="h2">Scan QR Bukti Peminjaman</h1>
    <p class="text-muted">Arahkan kamera ke kode QR di bukti peminjaman untuk proses cepat.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-dark">
            <div class="card-body p-0 overflow-hidden" style="border-radius: 12px;">
                <div id="reader" style="width: 100%;"></div>
            </div>
            <div class="card-footer bg-transparent border-0 text-center py-3">
                <div id="result" class="alert alert-info d-none mb-0">
                    <i class="bi bi-info-circle me-2"></i> Menemukan data: <strong id="scan-val"></strong>
                </div>
                <button id="reset-btn" class="btn btn-sm btn-outline-secondary d-none mt-2">Scan Ulang</button>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="<?= base_url('petugas/pengembalian') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        // Assume QR contains just the numeric ID or a URL with ID
        console.log(`Scan result: ${decodedText}`);
        
        document.getElementById('result').classList.remove('d-none');
        document.getElementById('scan-val').innerText = decodedText;
        
        // If it's a URL, extract ID. If just ID, use it.
        let pjId = decodedText;
        if(decodedText.includes('peminjaman/detail/')) {
             pjId = decodedText.split('/').pop();
        } else if (decodedText.startsWith('PJ-')) {
             // If we had a way to lookup by PJ Code via AJAX, we'd do it here.
             // For now, let's assume QR has the numeric database ID for simplicity matching T1 requirements.
             // Usually UKK systems use IDs for faster lookup.
        }

        if(!isNaN(pjId)) {
            window.location.href = "<?= base_url('petugas/pengembalian/process/') ?>/" + pjId;
        } else {
            alert("QR Code tidak valid atau format salah.");
        }
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
<?= $this->endSection() ?>
