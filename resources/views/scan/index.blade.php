@extends('layouts.app')

@section('title', 'Scan Barcode')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden mb-4">
            <div class="card-header bg-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 bg-primary bg-opacity-10 rounded text-primary">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold">Pemindai Barcode</h5>
                        <p class="text-muted small mb-0">Arahkan kamera ke QR Code / Barcode aset</p>
                    </div>
                </div>
            </div>
            <div class="card-body p-0 position-relative">
                <!-- Scanner Container -->
                <div id="reader" style="width: 100%; min-height: 300px; background: #000;"></div>
                
                <!-- Loading Overlay -->
                <div id="scan-loading" class="position-absolute top-0 start-0 w-100 h-100 bg-white d-none d-flex flex-column align-items-center justify-content-center" style="z-index: 10;">
                    <div class="spinner-border text-primary mb-2" role="status"></div>
                    <span class="text-muted">Mencari aset...</span>
                </div>
            <div class="card-footer px-4 py-3 bg-white border-top">
                <div class="input-group">
                    <input type="text" id="manual-code" class="form-control" placeholder="Masukkan Kode Aset Manual (contoh: HW-2024-001)" aria-label="Manual Code">
                    <button class="btn btn-primary" type="button" id="btn-manual-search">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Result Container -->
        <div id="scan-result" class="d-none">
            <!-- Asset details will be loaded here via AJAX -->
        </div>

        <div class="alert alert-info border-0 shadow-sm mb-4">
            <div class="d-flex gap-3">
                <i class="bi bi-info-circle-fill fs-4 text-info"></i>
                <div>
                    <h6 class="fw-bold mb-1">Tips Pemindaian</h6>
                    <ul class="mb-0 small ps-3">
                        <li>Pastikan pencahayaan cukup terang.</li>
                        <li>Jaga jarak kamera agar kode fokus dan tidak buram.</li>
                        <li>Gunakan browser modern seperti Chrome atau Safari.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Styling for html5-qrcode UI elements to match project design */
    #reader {
        border: none !important;
    }
    #reader video {
        object-fit: cover !important;
    }
    #reader__dashboard_section_csr button {
        background-color: var(--accent) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 16px !important;
        color: white !important;
        font-weight: 500 !important;
        margin-top: 10px !important;
    }
    #reader__dashboard_section_csr select {
        border-radius: 8px !important;
        border: 1px solid #e2e8f0 !important;
        padding: 6px !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const html5QrCode = new Html5Qrcode("reader");
        const scanResultContainer = document.getElementById('scan-result');
        const scanLoading = document.getElementById('scan-loading');
        const scanStatus = document.getElementById('scan-status');

        let isProcessing = false;

        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        const performSearch = (code) => {
            if (isProcessing) return;
            
            isProcessing = true;
            html5QrCode.pause(); 
            
            showLoading(true);
            
            fetch(`{{ route('scan.search') }}?code=${encodeURIComponent(code)}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Terjadi kesalahan sistem');
                        });
                    }
                    return response.text();
                })
                .then(html => {
                    scanResultContainer.innerHTML = html;
                    scanResultContainer.classList.remove('d-none');
                    scanResultContainer.scrollIntoView({ behavior: 'smooth' });
                    scanStatus.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Aset ditemukan';
                })
                .catch(error => {
                    Toast.fire({
                        icon: 'error',
                        title: error.message
                    });
                    isProcessing = false;
                    html5QrCode.resume();
                    scanStatus.innerHTML = '<i class="bi bi-camera me-1"></i> Siap memindai';
                })
                .finally(() => {
                    showLoading(false);
                });
        };

        const onScanSuccess = (decodedText, decodedResult) => {
            if (window.navigator.vibrate) {
                window.navigator.vibrate(100);
            }
            performSearch(decodedText);
        };

        // Manual Search Logic
        document.getElementById('btn-manual-search').addEventListener('click', function() {
            const code = document.getElementById('manual-code').value.trim();
            if (code) {
                performSearch(code);
            } else {
                Toast.fire({
                    icon: 'warning',
                    title: 'Silakan masukkan kode aset terlebih dahulu'
                });
            }
        });

        document.getElementById('manual-code').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const code = this.value.trim();
                if (code) performSearch(code);
            }
        });

        function showLoading(show) {
            if (show) {
                scanLoading.classList.remove('d-none');
            } else {
                scanLoading.classList.add('d-none');
            }
        }

        // Check for HTTPS/Localhost requirement
        if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
            scanStatus.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-danger me-1"></i> HTTPS Diperlukan! Kamera hanya dapat diakses melalui koneksi aman (HTTPS).';
            console.error("Camera access requires HTTPS.");
            return;
        }

        // Start Scanner
        const startCamera = () => {
            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess
            ).then(() => {
                scanStatus.innerHTML = '<i class="bi bi-camera me-1"></i> Kamera aktif, siap memindai';
            }).catch(err => {
                scanStatus.innerHTML = `
                    <div class="text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal akses kamera</div>
                    <button class="btn btn-sm btn-outline-primary" onclick="location.reload()">Coba Lagi</button>
                `;
                console.error("Unable to start scanning.", err);
            });
        };

        // Try direct access first (more robust for some mobile browsers)
        startCamera();
    });

    // Function called from detail close button (dynamic)
    function resetScanner() {
        document.getElementById('scan-result').classList.add('d-none');
        document.getElementById('scan-result').innerHTML = '';
        window.location.reload(); // Hard reset for camera state if needed
    }
</script>
@endpush
