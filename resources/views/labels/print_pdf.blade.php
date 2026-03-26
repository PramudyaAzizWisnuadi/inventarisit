<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0.8cm; size: A4 landscape; }
    body { margin: 0; padding: 0; font-family: 'Helvetica', sans-serif; background: #fff; }
    
    .label-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .label-td {
        width: 25%;
        border: 1px dotted #ccc; /* Garis potong halus */
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
        height: 2.4em; /* Limit height */
        overflow: hidden;
    }
    .label-info { 
        font-size: 7pt; 
        color: #666;
        margin-top: 0.5mm;
    }
    .clear { clear: both; }
</style>
</head>
<body>
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
</body>
</html>
