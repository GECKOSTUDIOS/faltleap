<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <div class="card border-0 shadow text-center">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    <i class="bi bi-qr-code me-2"></i>
                    QR-Code für <?php echo htmlspecialchars($this->data->name); ?>
                </h4>
            </div>
            <div class="card-body py-5">
                <div id="qrcode" class="mb-4 d-flex justify-content-center"></div>

                <div class="alert alert-info">
                    <strong>QR-Code ID:</strong>
                    <code class="fs-5"><?php echo htmlspecialchars($this->data->qr_code); ?></code>
                </div>

                <p class="text-muted">
                    Scannen Sie diesen QR-Code mit der Reinigungserfassungs-App, um eine Reinigung zu protokollieren.
                </p>

                <div class="d-flex justify-content-center gap-2 mt-4">
                    <button onclick="printQR()" class="btn btn-primary">
                        <i class="bi bi-printer me-2"></i>
                        Drucken
                    </button>
                    <a href="/cleaning/checkpoint" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Zurück
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
// Generate QR code - only encode the QR code identifier
new QRCode(document.getElementById("qrcode"), {
    text: "<?php echo htmlspecialchars($this->data->qr_code); ?>",
    width: 300,
    height: 300,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
});

function printQR() {
    window.print();
}
</script>

<style>
@media print {
    .card-header, .btn, nav, .navbar, .sidebar {
        display: none !important;
    }
    #qrcode {
        margin: 50px auto;
    }
}
</style>
