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

    public function process(PurchaseRequest $purchase_request)
    {
        $purchase_request->update(['status' => 'purchasing']);
        return back()->with('success', 'Status diubah menjadi Sedang Dibeli.');
    }

    public function complete(PurchaseRequest $purchase_request)
    {
        $purchase_request->update(['status' => 'received']);
        return back()->with('success', 'Barang telah diterima.');
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
}
