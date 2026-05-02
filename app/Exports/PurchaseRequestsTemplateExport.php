<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PurchaseRequestsTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return [
            'kode_pengadaan', 'nama_pemohon', 'keterangan', 'total_estimasi', 
            'tanggal_pengajuan', 'nama_barang', 'spesifikasi', 'merk', 
            'jumlah', 'satuan', 'harga_estimasi', 'total_harga'
        ];
    }

    public function array(): array
    {
        return [
            [
                'PR-LEGACY-001', 'Budi Santoso', 'Pengadaan Laptop Staff IT', 
                '15000000', '2025-12-01', 'Laptop Lenovo Thinkpad', 
                'Core i7, 16GB RAM', 'Lenovo', '1', 'Unit', '15000000', '15000000'
            ]
        ];
    }
}
