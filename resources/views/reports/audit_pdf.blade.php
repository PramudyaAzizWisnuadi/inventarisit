<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Audit</title>
<style>
    body { font-family: Arial, sans-serif; font-size: 9pt; }
    .header { text-align: center; margin-bottom: 16px; border-bottom: 2px solid #6366f1; padding-bottom: 8px; }
    .header h2 { margin: 0; font-size: 14pt; color: #6366f1; }
    .header p { margin: 2px 0; font-size: 8pt; color: #64748b; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #1e293b; color: #fff; padding: 5px 8px; font-size: 8pt; text-align: left; }
    td { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; font-size: 7.5pt; }
    tr:nth-child(even) { background: #f8fafc; }
    .footer { margin-top: 16px; font-size: 7pt; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="header">
    <h2>Laporan Audit Log Sistem</h2>
    <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Total: {{ count($logs) }} log</p>
</div>
<table>
    <thead>
        <tr><th>Waktu</th><th>Pengguna</th><th>Aksi</th><th>Deskripsi</th><th>IP Address</th></tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
    <tr>
        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
        <td>{{ $log->user_name ?? 'System' }}</td>
        <td>{{ ucfirst($log->action) }}</td>
        <td>{{ $log->description }}</td>
        <td>{{ $log->ip_address }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<div class="footer">InventarisIT — Audit Log — Dokumen ini merupakan rekaman resmi sistem</div>
</body>
</html>
