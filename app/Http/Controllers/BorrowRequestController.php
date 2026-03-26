<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\BorrowRequestDetail;
use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AuditLog;

class BorrowRequestController extends Controller
{
    public function index()
    {
        $query = BorrowRequest::with('details.asset', 'user')->latest();
        if (Auth::user()->role && Auth::user()->role->name === 'User') {
            $query->where('user_id', Auth::id());
        }
        $requests = $query->get();
        return view('borrow_requests.index', compact('requests'));
    }

    public function create()
    {
        $assets = Asset::whereIn('status', ['Tersedia', 'available', 'Aktif'])->get();
        return view('borrow_requests.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrower_name' => 'required|string|max:255',
            'asset_ids' => 'required|array|min:1',
            'asset_ids.*' => 'exists:assets,id',
            'notes' => 'nullable|string'
        ]);

        $latest = BorrowRequest::where('request_number', 'like', 'BRW-' . date('Ym') . '-%')->orderBy('id', 'desc')->first();
        $nextNum = $latest ? intval(substr($latest->request_number, -3)) + 1 : 1;
        $reqNum = 'BRW-' . date('Ym') . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $borrowRequest = BorrowRequest::create([
            'user_id' => Auth::id(),
            'borrower_name' => $validated['borrower_name'],
            'request_number' => $reqNum,
            'status' => 'approved', // Langsung berstatus disetujui
            'request_date' => now(),
            'notes' => $validated['notes'] ?? null
        ]);

        foreach ($validated['asset_ids'] as $assetId) {
            BorrowRequestDetail::create([
                'borrow_request_id' => $borrowRequest->id,
                'asset_id' => $assetId
            ]);
            
            $asset = Asset::find($assetId);
            if ($asset) {
                $asset->update(['status' => 'Dipinjam']);
                
                \App\Models\AssetAssignment::create([
                    'asset_id' => $asset->id,
                    'assigned_to' => $borrowRequest->borrower_name,
                    'department' => '-',
                    'assigned_by' => Auth::id(),
                    'assigned_at' => now(),
                    'status' => 'Aktif',
                    'notes' => 'Dari request: ' . $borrowRequest->request_number,
                ]);
            }
        }

        AuditLog::record('create', 'Dibuat & Otomatis Disetujui: ' . $borrowRequest->request_number);

        return redirect()->route('borrow-requests.index')->with('success', 'Peminjaman berhasil dicatat, aset berstatus Dipinjam.');
    }

    public function show(BorrowRequest $borrow_request)
    {
        return view('borrow_requests.show', compact('borrow_request'));
    }

    public function return(BorrowRequest $borrow_request)
    {
        foreach ($borrow_request->details as $detail) {
            if ($detail->asset) {
                $detail->asset->update(['status' => 'Tersedia']);
            }
            $detail->update(['returned_at' => now()]);
            
            \App\Models\AssetAssignment::where('asset_id', $detail->asset_id)
                ->where('status', 'Aktif')
                ->update([
                    'status' => 'Dikembalikan',
                    'returned_at' => now()
                ]);
        }
        
        $borrow_request->update(['status' => 'returned']);
        return back()->with('success', 'Seluruh aset dikembalikan.');
    }

    public function printBast(BorrowRequest $borrow_request)
    {
        return view('borrow_requests.print_bast', compact('borrow_request'));
    }
}
