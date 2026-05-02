<?php

namespace App\Imports;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseRequestsImport implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $user_id = auth()->id() ?? 1; // Fallback for testing from CLI
            
            // Group the rows by the request_number (kode pengadaan)
            $groupedByRequest = $rows->groupBy(function($row) {
                return $row['kode_pengadaan'] ?? null;
            });

            foreach ($groupedByRequest as $requestNumber => $requestRows) {
                if (empty($requestNumber)) continue;

                // Take the header info from the first row of this group
                $firstRow = $requestRows->first();
                
                // Parse date (Excel date can be an integer or formatted string)
                $requestDate = null;
                if (!empty($firstRow['tanggal_pengajuan'])) {
                    if (is_numeric($firstRow['tanggal_pengajuan'])) {
                        $requestDate = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($firstRow['tanggal_pengajuan'])->format('Y-m-d');
                    } else {
                        try {
                            $requestDate = \Carbon\Carbon::parse($firstRow['tanggal_pengajuan'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $requestDate = now()->format('Y-m-d');
                        }
                    }
                } else {
                    $requestDate = now()->format('Y-m-d');
                }

                // Parse Total Harga logic (User asked for the column "total_estimasi" as header total)
                $totalPriceStr = preg_replace('/[^0-9.]/', '', $firstRow['total_estimasi'] ?? '0');
                $totalPrice = floatval($totalPriceStr);

                // Setup Notes
                $notes = "Imported from legacy system";
                if (!empty($firstRow['keterangan'])) {
                    $notes = $firstRow['keterangan'];
                }
                if (!empty($firstRow['nama_pemohon'])) {
                    $notes = "Pemohon: " . $firstRow['nama_pemohon'] . "\n" . $notes;
                }

                // Create or find PurchaseRequest header
                $purchaseRequest = PurchaseRequest::firstOrCreate(
                    ['request_number' => $requestNumber],
                    [
                        'user_id' => $user_id,
                        'total_price' => $totalPrice, // Initialize or use provided total price
                        'status' => 'received', // Default logic: Historical data is already received
                        'request_date' => $requestDate,
                        'notes' => $notes,
                    ]
                );

                $calculatedTotal = 0;

                // Create lines (Details)
                foreach ($requestRows as $row) {
                    if (empty($row['nama_barang'])) continue;

                    $priceStr = preg_replace('/[^0-9.]/', '', $row['harga_estimasi'] ?? '0');
                    $price = floatval($priceStr);
                    $qty = isset($row['jumlah']) ? intval($row['jumlah']) : 1; 

                    $subtotal = $price * $qty;
                    $calculatedTotal += $subtotal;

                    $specification = $row['spesifikasi'] ?? null;
                    if (!empty($row['satuan'])) {
                        $specification .= ($specification ? ', Satuan: ' : 'Satuan: ') . $row['satuan'];
                    }

                    PurchaseRequestDetail::create([
                        'purchase_request_id' => $purchaseRequest->id,
                        'item_name' => $row['nama_barang'],
                        'brand' => $row['merk'] ?? null,
                        'qty' => $qty,
                        'price' => $price,
                        'subtotal' => $subtotal,
                        'specification' => $specification,
                    ]);
                }

                // If newly imported, calculate missing totals and auto-generate Assets
                if ($purchaseRequest->wasRecentlyCreated) {
                    if ($totalPrice == 0) {
                        $purchaseRequest->update(['total_price' => $calculatedTotal]);
                    }

                    // Auto-generate Assets based on Procurement details
                    $purchaseRequest->load('details');
                    foreach ($purchaseRequest->details as $detail) {
                        for ($i = 0; $i < $detail->qty; $i++) {
                            \App\Models\Asset::create([
                                'asset_code'     => \App\Models\Asset::generateCode(),
                                'name'           => $detail->item_name,
                                'brand'          => $detail->brand,
                                'purchase_date'  => $purchaseRequest->request_date,
                                'purchase_price' => $detail->price,
                                'condition'      => 'Baik',
                                'status'         => 'Digunakan',
                                'specifications' => $detail->specification,
                                'notes'          => "Berasal dari Pengadaan (Import Historis): " . $purchaseRequest->request_number,
                            ]);
                        }
                    }
                }
            }
            
            DB::commit();
            AuditLog::record('create', 'Berhasil melakukan import data pengadaan barang', null);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase Import failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
