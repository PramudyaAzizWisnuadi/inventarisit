<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Distribusi Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        
        .branch-section { margin-bottom: 30px; }
        .branch-header { background: #f0f0f0; padding: 10px; border-left: 5px solid #6366f1; margin-bottom: 10px; }
        .branch-name { font-size: 14px; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background: #f8fafc; border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        td { border: 1px solid #e2e8f0; padding: 8px; vertical-align: top; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Distribusi Aset per Cabang</h1>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    @foreach($distribution as $branch)
    <div class="branch-section">
        <div class="branch-header">
            <span class="branch-name">{{ $branch->name }} ({{ $branch->code }})</span>
            <div style="float: right; font-size: 10px;">
                Total Aset: {{ $branch->locations->sum('assets_count') }} | 
                Total Nilai: Rp {{ number_format($branch->locations->sum('assets_sum_purchase_price'), 0, ',', '.') }}
            </div>
            <div style="clear: both;"></div>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%">Lokasi</th>
                    <th class="text-center" style="width: 20%">Jumlah Aset</th>
                    <th class="text-right" style="width: 30%">Total Nilai (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($branch->locations as $loc)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $loc->name }}</div>
                        <div style="color: #666; font-size: 9px;">Building: {{ $loc->building }} | Floor: {{ $loc->floor }} | Room: {{ $loc->room }}</div>
                    </td>
                    <td class="text-center">{{ $loc->assets_count }}</td>
                    <td class="text-right">{{ $loc->assets_sum_purchase_price ? number_format($loc->assets_sum_purchase_price, 0, ',', '.') : '0' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data lokasi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="footer">
        Halaman 1 dari 1 — InventarisIT Systems
    </div>
</body>
</html>
