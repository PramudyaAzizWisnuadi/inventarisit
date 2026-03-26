<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DistributionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $distribution;

    public function __construct(Collection $distribution)
    {
        $this->distribution = $distribution;
    }

    public function collection(): Collection
    {
        $data = collect();
        foreach ($this->distribution as $branch) {
            foreach ($branch->locations as $location) {
                $data->push((object)[
                    'branch_name' => $branch->name,
                    'branch_code' => $branch->code,
                    'location_name' => $location->name,
                    'building' => $location->building,
                    'floor' => $location->floor,
                    'room' => $location->room,
                    'assets_count' => $location->assets_count,
                    'total_value' => $location->assets_sum_purchase_price,
                ]);
            }
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'Cabang', 'Kode Cabang', 'Lokasi', 'Gedung', 'Lantai', 'Ruangan', 'Jumlah Aset', 'Total Nilai (Rp)'
        ];
    }

    public function map($row): array
    {
        return [
            $row->branch_name,
            $row->branch_code,
            $row->location_name,
            $row->building ?? '-',
            $row->floor ?? '-',
            $row->room ?? '-',
            $row->assets_count,
            $row->total_value ? number_format($row->total_value, 0, ',', '.') : '0',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
