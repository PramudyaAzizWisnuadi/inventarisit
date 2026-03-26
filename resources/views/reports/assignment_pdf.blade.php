<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Penugasan Aset</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 9pt; }
    .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #10b981; padding-bottom: 8px; }
    .header h2 { margin: 0; font-size: 14pt; color: #10b981; }
    .header p { margin: 2px 0; font-size: 8pt; color: #64748b; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #10b981; color: #fff; padding: 5px 8px; font-size: 8pt; text-align: left; }
    td { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; font-size: 8pt; }
    tr:nth-child(even) { background: #f8fafc; }
    .footer { margin-top: 16px; font-size: 7pt; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h2>Laporan Riwayat Penugasan Aset</h2>
    <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Total: {{ count($assignments) }} penugasan</p>
</div>
<table>
    <thead>
        <tr><th>#</th><th>Kode Aset</th><th>Nama Aset</th><th>Ditugaskan Ke</th><th>Dept</th><th>Oleh</th><th>Tgl Mulai</th><th>Tgl Kembali</th><th>Status</th></tr>
    </thead>
    <tbody>
    @foreach($assignments as $i => $a)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $a->asset?->asset_code }}</td>
        <td>{{ $a->asset?->name }}</td>
        <td>{{ $a->assigned_to }}</td>
        <td>{{ $a->department ?? '-' }}</td>
        <td>{{ $a->assignedBy?->name ?? '-' }}</td>
        <td>{{ $a->assigned_at->format('d/m/Y') }}</td>
        <td>{{ $a->returned_at?->format('d/m/Y') ?? '-' }}</td>
        <td>{{ $a->status }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="footer">InventarisIT — Laporan Penugasan Aset</div>
</body>
</html>
