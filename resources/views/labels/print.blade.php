<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Label Aset — InventarisIT</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    @page { margin: 0.8cm; size: A4 landscape; }
    body { background: #f1f5f9; font-family: 'Helvetica', sans-serif; margin: 0; padding: 0; }
    
    /* Interactive Preview Header */
    .no-print {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .print-container { padding: 20px; }

    /* Match PDF Styles */
    .label-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
    }
    
    .label-td {
        width: 25%;
        border: 1px dotted #ccc;
        padding: 2mm;
        vertical-align: top;
    }

    .label-card {
        width: 100%;
        height: auto;
        min-height: 2.8cm;
        position: relative;
    }
    
    .qr-box {
        float: left;
        width: 1.8cm;
    }
    .qr-box img {
        width: 1.8cm;
        height: 1.8cm;
    }
    .info-box {
        float: left;
        width: 3.8cm;
        padding-left: 2mm;
    }
    .label-header { 
        font-size: 7pt; 
        font-weight: bold; 
        color: #6366f1; 
        text-transform: uppercase; 
        margin-bottom: 1mm;
    }
    .label-code { 
        font-size: 9pt; 
        font-weight: bold; 
        font-family: monospace;
    }
    .label-name { 
        font-size: 8pt; 
        font-weight: bold;
        margin-top: 1mm;
        height: 2.4em;
        overflow: hidden;
    }
    .label-info { 
        font-size: 7pt; 
        color: #666;
        margin-top: 0.5mm;
    }
    .clear { clear: both; }

    @media print {
        body { background: #fff; }
        .no-print { display: none !important; }
        .print-container { padding: 0; }
        .label-table { border: none; }
        .label-td { border: 1px dotted #ccc; }
    }
</style>
</head>
<body>
<div class="no-print">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url()->previous() == route('labels.print') ? route('labels.index') : url()->previous() }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <strong class="small">Preview Label ({{ count($assets) }} aset)</strong>
    </div>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-sm btn-primary">
            <i class="bi bi-printer me-1"></i>Cetak
        </button>
    </div>
</div>

<div class="print-container">
    <table class="label-table">
        @foreach($assets->chunk(4) as $chunk)
        <tr>
            @foreach($chunk as $asset)
            <td class="label-td">
                <div class="label-card">
                    <div class="qr-box">
                        <img src="data:image/svg+xml;base64, {!! base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(120)->margin(0)->generate($asset->asset_code)) !!} ">
                    </div>
                    <div class="info-box">
                        <div class="label-header">INVENTARIS IT</div>
                        <div class="label-code">{{ $asset->asset_code }}</div>
                        <div class="label-name">{{ Str::limit($asset->name, 40) }}</div>
                        <div class="label-info">{{ $asset->category?->name }}</div>
                        <div class="label-info">Lokasi: {{ $asset->location?->name ?? '-' }}</div>
                        <div class="label-info">{{ now()->format('d/m/Y') }}</div>
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
            @endforeach
            {{-- Fill empty cells if chunk is not full --}}
            @if($chunk->count() < 4)
                @for($i = 0; $i < (4 - $chunk->count()); $i++)
                    <td class="label-td"></td>
                @endfor
            @endif
        </tr>
        @endforeach
    </table>
</div>
</body>
</html>
