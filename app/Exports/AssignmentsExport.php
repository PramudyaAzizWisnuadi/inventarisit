<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssignmentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $assignments;

    public function __construct(Collection $assignments)
    {
        $this->assignments = $assignments;
    }

    public function collection(): Collection
    {
        return $this->assignments;
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Kode Aset', 'Nama Aset', 'Kategori', 'Cabang', 'Lokasi',
            'Penanggung Jawab', 'Departemen', 'Status', 'Catatan', 'Diserahkan Oleh'
        ];
    }

    public function map($asg): array
    {
        return [
            $asg->assigned_at?->format('d/m/Y') ?? '-',
            $asg->asset?->asset_code ?? '-',
            $asg->asset?->name ?? '-',
            $asg->asset?->category?->name ?? '-',
            $asg->asset?->location?->branch?->name ?? '-',
            $asg->asset?->location?->name ?? '-',
            $asg->assigned_to,
            $asg->department ?? '-',
            $asg->status,
            $asg->notes ?? '-',
            $asg->assignedBy?->name ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
