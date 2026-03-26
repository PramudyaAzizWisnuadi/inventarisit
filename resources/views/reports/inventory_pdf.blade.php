<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Inventaris Hardware</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 9pt; color: #1e293b; }
    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #6366f1; padding-bottom: 10px; }
    .header h2 { margin: 0; font-size: 14pt; color: #6366f1; }
    .header p { margin: 2px 0; font-size: 8pt; color: #64748b; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #6366f1; color: #fff; padding: 6px 8px; font-size: 8pt; text-align: left; }
    td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; font-size: 8pt; }
    tr:nth-child(even) { background: #f8fafc; }
    .total-row { font-weight: bold; background: #ede9fe; }
    .footer { margin-top: 20px; font-size: 7pt; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h2>Laporan Inventaris Hardware IT</h2>
    <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    <p>Total Aset: {{ count($assets) }} | Total Nilai: Rp {{ number_format($totalValue,0,',','.') }}</p>
</div>
<table>
    <thead>
        <tr>
            <th>#</th><th>Kode</th><th>Nama Aset</th><th>Merek/Model</th>
            <th>Kategori</th><th>Lokasi</th><th>Kondisi</th><th>Status</th>
            <th>Tgl Beli</th><th>Harga (Rp)</th><th>Garansi</th>
        </tr>
    </thead>
    <tbody>
    @foreach($assets as $i => $a)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $a->asset_code }}</td>
        <td>{{ $a->name }}</td>
        <td>{{ $a->brand }} {{ $a->model }}</td>
        <td>{{ $a->category?->name ?? '-' }}</td>
        <td>{{ $a->location?->name ?? '-' }}</td>
        <td>{{ $a->condition }}</td>
        <td>{{ $a->status }}</td>
        <td>{{ $a->purchase_date?->format('d/m/Y') ?? '-' }}</td>
        <td style="text-align:right">{{ $a->purchase_price ? number_format($a->purchase_price,0,',','.') : '-' }}</td>
        <td>{{ $a->warranty_expiry?->format('d/m/Y') ?? '-' }}</td>
    </tr>
    @endforeach
    <tr class="total-row">
        <td colspan="9" style="text-align:right;">TOTAL NILAI</td>
        <td style="text-align:right;">{{ number_format($totalValue,0,',','.') }}</td>
        <td></td>
    </tr>
    </tbody>
</table>
<div class="footer">InventarisIT — Sistem Manajemen Inventaris Perangkat IT | Laporan ini digenerate otomatis oleh sistem</div>
</body>
</html>
