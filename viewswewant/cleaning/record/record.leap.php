<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinigung erfassen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .main-card {
            max-width: 600px;
            margin: 0 auto;
        }
        #video {
            width: 100%;
            max-width: 100%;
            border-radius: 10px;
        }
        #signatureCanvas {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            cursor: crosshair;
            background: white;
            touch-action: none;
        }
        .step {
            display: none !important;
        }
        .step.active {
            display: block !important;
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card">
            <!-- Step 1: QR Code Scanner -->
            <div id="step1" class="step active">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-qr-code-scan me-2"></i>
                            Schritt 1: QR-Code scannen
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="qr-reader" style="width: 100%;"></div>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Halten Sie den QR-Code vor die Kamera
                        </div>
                        <div id="scannedInfo" class="alert alert-success mt-3" style="display: none;">
                            <strong>Gescannt:</strong> <span id="scannedName"></span>
                        </div>

                        <!-- Manual QR Code Input -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="toggleManualInput()">
                                <i class="bi bi-keyboard me-1"></i>
                                QR-Code manuell eingeben
                            </button>
                            <div id="manualInput" style="display: none;" class="mt-2">
                                <input type="text" id="manualQrCode" class="form-control" placeholder="QR-Code hier eingeben (z.B. CP001)">
                                <button type="button" class="btn btn-primary btn-sm mt-2" onclick="submitManualQrCode()">
                                    Weiter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Signature -->
            <div id="step2" class="step">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">
                            <i class="bi bi-pen me-2"></i>
                            Schritt 2: Unterschrift
                        </h4>
                    </div>
                    <div class="card-body">
                        <p class="text-center mb-3">
                            <strong>Checkpoint:</strong> <span id="checkpointName"></span>
                        </p>
                        <canvas id="signatureCanvas" width="500" height="200"></canvas>
                        <div class="d-flex justify-content-between mt-3">
                            <button onclick="clearSignature()" class="btn btn-outline-secondary">
                                <i class="bi bi-eraser me-2"></i>
                                Löschen
                            </button>
                            <button onclick="saveRecord()" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>
                                Speichern
                            </button>
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="notes" class="form-label">Notizen (optional)</label>
                            <textarea id="notes" class="form-control" rows="3" placeholder="Besondere Vorkommnisse..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Success -->
            <div id="step3" class="step">
                <div class="card shadow-lg">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <h3 class="mt-3">Reinigung erfolgreich erfasst!</h3>
                        <p class="text-muted">Die Reinigung wurde gespeichert.</p>
                        <button onclick="resetForm()" class="btn btn-primary btn-lg mt-3">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Neue Reinigung erfassen
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let scannedData = null;
        let canvas = document.getElementById('signatureCanvas');
        let ctx = canvas.getContext('2d');
        let isDrawing = false;
        let lastX = 0;
        let lastY = 0;
        let html5QrCode = null;

        // Initialize QR Scanner when page loads
        window.addEventListener('DOMContentLoaded', function() {
            startQrScanner();
        });

        function startQrScanner() {
            html5QrCode = new Html5Qrcode("qr-reader");

            // Try to get camera permissions and start scanner
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    console.log("Cameras found:", cameras.length);
                    // Start with back camera (environment) or first available
                    const cameraId = cameras.length > 1 ? cameras[1].id : cameras[0].id;

                    html5QrCode.start(
                        cameraId,
                        {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            aspectRatio: 1.0
                        },
                        onScanSuccess,
                        onScanFailure
                    ).catch(err => {
                        console.error("Failed to start QR scanner:", err);
                        showError("Kamera konnte nicht gestartet werden: " + err);
                    });
                } else {
                    showError("Keine Kamera gefunden!");
                }
            }).catch(err => {
                console.error("Failed to get cameras:", err);
                showError("Kamera-Zugriff verweigert oder nicht verfügbar: " + err);
            });
        }

        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger mt-3';
            errorDiv.innerHTML = '<strong>Fehler:</strong> ' + message;
            document.getElementById('qr-reader').appendChild(errorDiv);
        }

        function onScanFailure(error) {
            // Ignore scan failures (happens continuously while scanning)
        }

        function onScanSuccess(decodedText, decodedResult) {
            // QR code now contains only the qr_code string (not JSON)
            scannedData = {
                qr_code: decodedText,
                name: 'Checkpoint ' + decodedText
            };

            document.getElementById('scannedName').textContent = scannedData.name;
            document.getElementById('scannedInfo').style.display = 'block';
            document.getElementById('checkpointName').textContent = scannedData.name;

            // Stop scanner and move to next step
            html5QrCode.stop();
            setTimeout(() => {
                goToStep(2);
            }, 1500);
        }

        function goToStep(step) {
            console.log('Going to step:', step);
            document.querySelectorAll('.step').forEach(s => {
                s.classList.remove('active');
                s.style.display = 'none';
            });
            const stepElement = document.getElementById('step' + step);
            if (stepElement) {
                stepElement.classList.add('active');
                stepElement.style.display = 'block';
                console.log('Step ' + step + ' is now visible');
            } else {
                console.error('Step element not found:', 'step' + step);
            }
        }

        // Signature Canvas - Mouse Events
        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', draw);
        canvas.addEventListener('mouseup', stopDrawing);
        canvas.addEventListener('mouseout', stopDrawing);

        // Signature Canvas - Touch Events
        canvas.addEventListener('touchstart', handleTouchStart);
        canvas.addEventListener('touchmove', handleTouchMove);
        canvas.addEventListener('touchend', stopDrawing);

        function handleTouchStart(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            lastX = touch.clientX - rect.left;
            lastY = touch.clientY - rect.top;
            isDrawing = true;
        }

        function handleTouchMove(e) {
            e.preventDefault();
            if (!isDrawing) return;
            const touch = e.touches[0];
            const rect = canvas.getBoundingClientRect();
            const x = touch.clientX - rect.left;
            const y = touch.clientY - rect.top;

            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.stroke();

            lastX = x;
            lastY = y;
        }

        function startDrawing(e) {
            isDrawing = true;
            const rect = canvas.getBoundingClientRect();
            lastX = e.clientX - rect.left;
            lastY = e.clientY - rect.top;
        }

        function draw(e) {
            if (!isDrawing) return;
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            ctx.beginPath();
            ctx.moveTo(lastX, lastY);
            ctx.lineTo(x, y);
            ctx.strokeStyle = '#000';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
            ctx.stroke();

            lastX = x;
            lastY = y;
        }

        function stopDrawing() {
            isDrawing = false;
        }

        function clearSignature() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        async function saveRecord() {
            const signatureData = canvas.toDataURL('image/png');
            const notes = document.getElementById('notes').value;

            if (!scannedData) {
                alert('Fehler: Kein QR-Code gescannt!');
                return;
            }

            console.log('Saving record with QR code:', scannedData.qr_code);

            try {
                const response = await fetch('/cleaning/record/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        qr_code: scannedData.qr_code,
                        signature_data: signatureData,
                        notes: notes || ''
                    })
                });

                console.log('Response status:', response.status);
                const responseText = await response.text();
                console.log('Response text:', responseText);

                let result;
                try {
                    result = JSON.parse(responseText);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    alert('Fehler: Server-Antwort ist kein gültiges JSON.\nAntwort: ' + responseText.substring(0, 200));
                    return;
                }

                if (result.success) {
                    console.log('Save successful!');
                    goToStep(3);
                } else {
                    alert('Fehler: ' + result.message);
                }
            } catch (error) {
                console.error('Save error:', error);
                alert('Fehler beim Speichern: ' + error.message);
            }
        }

        function resetForm() {
            scannedData = null;
            clearSignature();
            document.getElementById('notes').value = '';
            document.getElementById('scannedInfo').style.display = 'none';
            document.getElementById('manualInput').style.display = 'none';
            document.getElementById('manualQrCode').value = '';

            // Restart scanner
            startQrScanner();

            goToStep(1);
        }

        function toggleManualInput() {
            const manualInput = document.getElementById('manualInput');
            if (manualInput.style.display === 'none') {
                manualInput.style.display = 'block';
            } else {
                manualInput.style.display = 'none';
            }
        }

        function submitManualQrCode() {
            const qrCodeInput = document.getElementById('manualQrCode').value.trim();
            if (!qrCodeInput) {
                alert('Bitte QR-Code eingeben!');
                return;
            }

            // Create mock scanned data
            scannedData = {
                qr_code: qrCodeInput,
                name: 'Checkpoint ' + qrCodeInput
            };

            console.log('Manual QR Code submitted:', scannedData);
            document.getElementById('scannedName').textContent = scannedData.name;
            document.getElementById('scannedInfo').style.display = 'block';
            document.getElementById('checkpointName').textContent = scannedData.name;

            // Try to stop scanner if it exists and is running, but don't let errors block us
            if (html5QrCode) {
                try {
                    html5QrCode.stop().catch(err => {
                        // Ignore errors - scanner might not be running
                        console.log("Scanner stop ignored:", err);
                    });
                } catch (err) {
                    // Ignore synchronous errors too
                    console.log("Scanner stop error ignored:", err);
                }
            }

            // Move to next step immediately
            console.log('Moving to step 2...');
            setTimeout(() => {
                goToStep(2);
            }, 100);
        }
    </script>
</body>
</html>
