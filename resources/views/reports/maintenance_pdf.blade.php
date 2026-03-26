<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Pemeliharaan</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 9pt; }
    .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #f59e0b; padding-bottom: 8px; }
    .header h2 { margin: 0; font-size: 14pt; color: #d97706; }
    .header p { margin: 2px 0; font-size: 8pt; color: #64748b; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #d97706; color: #fff; padding: 5px 8px; font-size: 8pt; text-align: left; }
    td { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; font-size: 8pt; }
    tr:nth-child(even) { background: #f8fafc; }
    .footer { margin-top: 16px; font-size: 7pt; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h2>Laporan Pemeliharaan Aset IT</h2>
    <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Total Biaya: Rp {{ number_format($totalCost,0,',','.') }}</p>
</div>
<table>
    <thead>
        <tr><th>#</th><th>Kode Aset</th><th>Nama Aset</th><th>Tipe</th><th>Teknisi</th><th>Jadwal</th><th>Selesai</th><th>Biaya</th><th>Status</th></tr>
    </thead>
    <tbody>
    @foreach($maintenances as $i => $m)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $m->asset?->asset_code }}</td>
        <td>{{ $m->asset?->name }}</td>
        <td>{{ $m->type }}</td>
        <td>{{ $m->technician ?? '-' }}</td>
        <td>{{ $m->scheduled_at?->format('d/m/Y') ?? '-' }}</td>
        <td>{{ $m->completed_at?->format('d/m/Y') ?? '-' }}</td>
        <td style="text-align:right">{{ $m->cost ? number_format($m->cost,0,',','.') : '-' }}</td>
        <td>{{ $m->status }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="footer">InventarisIT — Laporan Pemeliharaan Aset</div>
</body>
</html>
