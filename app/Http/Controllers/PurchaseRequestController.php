<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $query = PurchaseRequest::with('user', 'details')->latest();
        if (Auth::user()->role && Auth::user()->role->name === 'User') {
            $query->where('user_id', Auth::id());
        }
        $requests = $query->get();
        return view('purchase_requests.index', compact('requests'));
    }

    public function create()
    {
        return view('purchase_requests.create');
    }

    public function store(Request $request)
    {
        // Bersihkan titik (pemisah ribuan) dari input harga sebelum validasi
        if ($request->has('items')) {
            $items = $request->items;
            foreach ($items as $key => $item) {
                if (isset($item['price'])) {
                    $items[$key]['price'] = str_replace('.', '', $item['price']);
                }
            }
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.specification' => 'nullable|string|max:255',
            'items.*.brand' => 'nullable|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);

        $totalPrice = 0;
        foreach($validated['items'] as $item) {
            $totalPrice += ($item['qty'] * $item['price']);
        }

        $latest = PurchaseRequest::where('request_number', 'like', 'PRC-' . date('Ym') . '-%')->orderBy('id', 'desc')->first();
        $nextNum = $latest ? intval(substr($latest->request_number, -3)) + 1 : 1;
        $reqNum = 'PRC-' . date('Ym') . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $purchaseRequest = PurchaseRequest::create([
            'user_id' => Auth::id(),
            'request_number' => $reqNum,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'request_date' => now(),
            'notes' => $validated['notes'] ?? null
        ]);

        foreach ($validated['items'] as $item) {
            PurchaseRequestDetail::create([
                'purchase_request_id' => $purchaseRequest->id,
                'item_name' => $item['item_name'],
                'specification' => $item['specification'] ?? null,
                'brand' => $item['brand'] ?? null,
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price']
            ]);
        }

        AuditLog::record('create', 'Dibuat: ' . $purchaseRequest->request_number);

        return redirect()->route('purchase-requests.index')->with('success', 'Pengajuan pengadaan berhasil dikirim.');
    }

    public function show(PurchaseRequest $purchase_request)
    {
        return view('purchase_requests.show', compact('purchase_request'));
    }

    public function approveManager(PurchaseRequest $purchase_request)
    {
        $purchase_request->update(['status' => 'approved_manager']);
        return back()->with('success', 'Pengajuan telah disetujui oleh Manager.');
    }

    public function approveDirector(PurchaseRequest $purchase_request)
    {
        $purchase_request->update(['status' => 'approved_director']);
        return back()->with('success', 'Pengajuan telah disetujui oleh Direksi.');
    }

    public function process(PurchaseRequest $purchase_request)
    {
        $purchase_request->update(['status' => 'purchasing']);
        return back()->with('success', 'Status diubah menjadi Sedang Dibeli.');
    }

    public function complete(PurchaseRequest $purchase_request)
    {
        $purchase_request->update(['status' => 'received']);
        
        // Auto-generate Assets based on Procurement details
        $purchase_request->load('details');
        foreach ($purchase_request->details as $detail) {
            for ($i = 0; $i < $detail->qty; $i++) {
                \App\Models\Asset::create([
                    'asset_code'     => \App\Models\Asset::generateCode(),
                    'name'           => $detail->item_name,
                    'brand'          => $detail->brand,
                    'purchase_date'  => $purchase_request->request_date,
                    'purchase_price' => $detail->price,
                    'condition'      => 'Baik',
                    'status'         => 'Tersedia',
                    'specifications' => $detail->specification,
                    'notes'          => "Berasal dari Pengadaan: " . $purchase_request->request_number,
                ]);
            }
        }
        
        AuditLog::record('update', "Penyelesaian Pengadaan & Generate Stok Otomatis: {$purchase_request->request_number}");

        return back()->with('success', 'Barang penerimaan divalidasi dan otomatis masuk ke inventaris Asset.');
    }

    public function reject(PurchaseRequest $purchase_request, Request $request)
    {
        $purchase_request->update([
            'status' => 'rejected',
            'notes' => $purchase_request->notes . "\nDitolak: " . $request->reason
        ]);
        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function print(PurchaseRequest $purchase_request)
    {
        return view('purchase_requests.print', compact('purchase_request'));
    }

    public function importTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PurchaseRequestsTemplateExport, 'template_import_pengadaan.xlsx');
    }

    public function importData(Request $request)
    {
        $request->validate([
            'file_import' => 'required|mimes:csv,txt,xlsx,xls|max:5120'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\PurchaseRequestsImport, $request->file('file_import'));
            return redirect()->route('purchase-requests.index')->with('success', 'Data pengadaan berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('purchase-requests.index')->with('error', 'Gagal impor data: ' . $e->getMessage());
        }
    }

    public function edit(PurchaseRequest $purchase_request)
    {
        if ($purchase_request->status === 'received') {
            return redirect()->route('purchase-requests.show', $purchase_request)
                ->with('error', 'Pengadaan yang sudah selesai tidak dapat diubah.');
        }

        return view('purchase_requests.edit', compact('purchase_request'));
    }

    public function update(Request $request, PurchaseRequest $purchase_request)
    {
        if ($purchase_request->status === 'received') {
            return redirect()->route('purchase-requests.show', $purchase_request)
                ->with('error', 'Pengadaan yang sudah selesai tidak dapat diubah.');
        }

        // Bersihkan titik (pemisah ribuan) dari input harga sebelum validasi
        if ($request->has('items')) {
            $items = $request->items;
            foreach ($items as $key => $item) {
                if (isset($item['price'])) {
                    $items[$key]['price'] = str_replace('.', '', $item['price']);
                }
            }
            $request->merge(['items' => $items]);
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.specification' => 'nullable|string|max:255',
            'items.*.brand' => 'nullable|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'receipt_photo' => 'nullable|image|max:1024'
        ]);

        $totalPrice = 0;
        foreach($validated['items'] as $item) {
            $totalPrice += ($item['qty'] * $item['price']);
        }

        $data = [
            'total_price' => $totalPrice,
            'notes' => $validated['notes'] ?? $purchase_request->notes
        ];

        if ($request->hasFile('receipt_photo')) {
            if ($purchase_request->receipt_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($purchase_request->receipt_photo);
            }
            $data['receipt_photo'] = $request->file('receipt_photo')->store('purchases/receipts', 'public');
        }

        $purchase_request->update($data);

        $purchase_request->details()->delete();
        foreach ($validated['items'] as $item) {
            PurchaseRequestDetail::create([
                'purchase_request_id' => $purchase_request->id,
                'item_name' => $item['item_name'],
                'specification' => $item['specification'] ?? null,
                'brand' => $item['brand'] ?? null,
                'qty' => $item['qty'],
                'price' => $item['price'],
                'subtotal' => $item['qty'] * $item['price']
            ]);
        }

        AuditLog::record('update', 'Realisasi/Edit Pengadaan: ' . $purchase_request->request_number);

        return redirect()->route('purchase-requests.show', $purchase_request)
            ->with('success', 'Data realisasi pengadaan dan bukti nota berhasil diperbarui.');
    }
}
