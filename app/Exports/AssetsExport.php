<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $assets;

    public function __construct(Collection $assets)
    {
        $this->assets = $assets;
    }

    public function collection(): Collection
    {
        return $this->assets;
    }

    public function headings(): array
    {
        return [
            'Kode Aset', 'Nama Aset', 'Cabang', 'Lokasi', 'Merek', 'Model', 'Serial Number',
            'Kategori', 'Vendor', 'Kondisi', 'Status',
            'Tanggal Pembelian', 'Harga Pembelian', 'Garansi Sampai',
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->asset_code,
            $asset->name,
            $asset->location?->branch?->name ?? '-',
            $asset->location?->name ?? '-',
            $asset->brand ?? '-',
            $asset->model ?? '-',
            $asset->serial_number ?? '-',
            $asset->category?->name ?? '-',
            $asset->vendor?->name ?? '-',
            $asset->condition,
            $asset->status,
            $asset->purchase_date?->format('d/m/Y') ?? '-',
            $asset->purchase_price ? number_format($asset->purchase_price, 0, ',', '.') : '-',
            $asset->warranty_expiry?->format('d/m/Y') ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
