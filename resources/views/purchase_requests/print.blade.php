<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Pengadaan - {{ $purchase_request->request_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; color: #333; line-height: 1.3; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 15px; }
        .header h3 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .header p { margin: 3px 0 0; font-size: 12px; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table-data th, .table-data td { border: 1px solid #333; padding: 4px 6px; text-align: left; vertical-align: top; }
        .table-data th { background: #f4f4f4; width: 130px; font-weight: bold; }
        .signature-section { display: flex; justify-content: space-between; margin-top: 20px; text-align: center; }
        .sig-box { width: 30%; }
        .sig-space { height: 50px; }
        .sig-name { font-weight: bold; text-decoration: underline; }
        @media print {
            @page { size: A4 portrait; margin: 1cm; }
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3>FORM PENGAJUAN PENGADAAN BARANG (PURCHASE REQUEST)</h3>
        <p style="margin: 5px 0 0;">Nomor: {{ $purchase_request->request_number }}</p>
    </div>

    <table class="table-data">
        <tr>
            <th style="width: 200px;">Tanggal Pengajuan</th>
            <td>{{ \Carbon\Carbon::parse($purchase_request->request_date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Pemohon</th>
            <td>{{ $purchase_request->user->name }}</td>
        </tr>
    </table>

    <h4 style="margin-bottom: 5px;">Rincian Barang yang Diajukan:</h4>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Nama Barang</th>
                <th>Spesifikasi</th>
                <th style="width: 100px;">Merk</th>
                <th style="width: 50px; text-align: center;">Qty</th>
                <th style="width: 120px;">Harga Satuan</th>
                <th style="width: 120px;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchase_request->details as $idx => $detail)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td style="font-weight: bold;">{{ $detail->item_name }}</td>
                <td>{{ $detail->specification ?? '-' }}</td>
                <td>{{ $detail->brand ?? '-' }}</td>
                <td style="text-align: center;">{{ $detail->qty }}</td>
                <td>Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">TOTAL KESELURUHAN:</td>
                <td style="font-weight: bold; font-size: 14px;">Rp {{ number_format($purchase_request->total_price, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="table-data">
        <tr>
            <th style="width: 200px;">Catatan / Keterangan</th>
            <td>{{ $purchase_request->notes ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status Sistem</th>
            <td>{{ strtoupper(str_replace('_', ' ', $purchase_request->status)) }}</td>
        </tr>
    </table>

    <div class="signature-section">
        <div class="sig-box">
            <p>Dibuat Oleh (Pemohon),</p>
            <div class="sig-space"></div>
            <p class="sig-name">{{ $purchase_request->user->name }}</p>
            <p>Tanggal: ......................</p>
        </div>
        <div class="sig-box">
            <p>Disetujui Oleh (Manager),</p>
            <div class="sig-space"></div>
            <p class="sig-name">..................................</p>
            <p>Tanggal: ......................</p>
        </div>
        <div class="sig-box">
            <p>Mengetahui (Direksi),</p>
            <div class="sig-space"></div>
            <p class="sig-name">..................................</p>
            <p>Tanggal: ......................</p>
        </div>
    </div>

    <div style="margin-top: 50px; border-top: 1px dashed #ccc; padding-top: 10px; text-align: right;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Print Dokumen</button>
    </div>
</body>
</html>
