<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Scan QR Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="pt-3 pb-2 mb-3 border-bottom text-center">
    <h1 class="h2">Admin: Scan QR Pengembalian</h1>
    <p class="text-muted">Proses pengembalian cepat dengan scan QR Bukti Peminjaman.</p>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-dark">
            <div class="card-body p-0 overflow-hidden" style="border-radius: 12px;">
                <div id="reader" style="width: 100%;"></div>
            </div>
            <div class="card-footer bg-transparent border-0 text-center py-3">
                <div id="result" class="alert alert-info d-none mb-0">
                    <i class="bi bi-info-circle me-2"></i> Menemukan data ID: <strong id="scan-val"></strong>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="<?= base_url('admin/pengembalian') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Scan result: ${decodedText}`);
        let pjId = decodedText;
        if(decodedText.includes('peminjaman/detail/')) {
             pjId = decodedText.split('/').pop();
        }

        if(!isNaN(pjId)) {
            window.location.href = "<?= base_url('admin/pengembalian/process/') ?>/" + pjId;
        } else {
            alert("QR Code tidak valid.");
        }
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
<?= $this->endSection() ?>
