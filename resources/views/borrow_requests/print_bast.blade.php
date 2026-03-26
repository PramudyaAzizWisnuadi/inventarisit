<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>BAST - {{ $borrow_request->request_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 40px; color: #333; }
        .header { text-align: center; border-bottom: 2px double #333; padding-bottom: 20px; margin-bottom: 30px; }
        .header h3 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; }
        .content { margin-bottom: 40px; line-height: 1.6; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 20px; margin-bottom: 30px; }
        .table-data th, .table-data td { border: 1px solid #333; padding: 10px; text-align: left; }
        .table-data th { background: #f4f4f4; }
        .signature-section { display: flex; justify-content: space-around; margin-top: 60px; text-align: center; }
        .sig-box { width: 40%; }
        .sig-space { height: 100px; }
        .sig-name { font-weight: bold; text-decoration: underline; }
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h3>BERITA ACARA SERAH TERIMA (BAST) ASET IT</h3>
        <p>Nomor Dokumen: BAST/{{ date('Y') }}/{{ \Carbon\Carbon::parse($borrow_request->request_date)->format('m') }}/{{ $borrow_request->id }}</p>
        <p>Referensi Pengajuan: {{ $borrow_request->request_number }}</p>
    </div>

    <div class="content">
        <p>Pada hari ini, tanggal <strong>{{ date('d F Y') }}</strong>, telah dilakukan serah terima hak guna/peminjaman inventaris aset IT dengan rincian peminjam:</p>
        
        <table style="width:100%; margin-bottom: 20px;">
            <tr><td width="200">Nama Peminjam</td><td>: <strong>{{ $borrow_request->borrower_name }}</strong></td></tr>
            <tr><td>Keterangan Surat</td><td>: {{ $borrow_request->notes ?? 'Peminjaman Inventaris Kantor' }}</td></tr>
        </table>

        <p>Adapun rincian perangkat/aset yang dipinjam adalah sebagai berikut:</p>

        <table class="table-data">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th style="width: 120px;">Kode Aset</th>
                    <th>Nama & Merk</th>
                    <th>Spesifikasi</th>
                    <th>S/N</th>
                    <th>Cabang & Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrow_request->details as $idx => $detail)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold;">{{ $detail->asset->asset_code ?? '-' }}</td>
                    <td>{{ $detail->asset->name ?? '-' }} <br> <small>{{ $detail->asset->brand ?? '-' }}</small></td>
                    <td>{{ $detail->asset->specifications ?? '-' }}</td>
                    <td>{{ $detail->asset->serial_number ?? '-' }}</td>
                    <td>
                        {{ $detail->asset->location->branch->name ?? 'Pusat' }}<br>
                        <small class="text-muted">{{ $detail->asset->location->name ?? '-' }}</small>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>Syarat & Ketentuan Peminjaman:</strong></p>
        <ol>
            <li>Peminjam bertanggung jawab penuh atas keamanan dan keutuhan aset yang dipinjam.</li>
            <li>Aset hanya digunakan untuk kepentingan pekerjaan dan tidak untuk disalahgunakan.</li>
            <li>Kerusakan yang diakibatkan oleh kelalaian (jatuh, terkena air, dsb) dapat diklaim sebagai tanggung jawab peminjam.</li>
            <li>Aset wajib dikembalikan apabila karyawan mengalami mutasi, <i>resign</i>, atau masa peminjaman telah habis/ditarik oleh departemen IT.</li>
        </ol>
        
        <p>Demikian Berita Acara Serah Terima ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature-section">
        <div class="sig-box">
            <p><strong>Yang Menyerahkan (Admin IT),</strong></p>
            <div class="sig-space"></div>
            <p class="sig-name">..................................</p>
            <p>Tanggal: ......................</p>
        </div>
        <div class="sig-box">
            <p><strong>Yang Menerima (Karyawan/Peminjam),</strong></p>
            <div class="sig-space"></div>
            <p class="sig-name">{{ $borrow_request->borrower_name }}</p>
            <p>Tanggal: ......................</p>
        </div>
    </div>

    <div style="margin-top: 50px; border-top: 1px dashed #ccc; padding-top: 10px; text-align: right;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Print BAST</button>
    </div>
</body>
</html>
