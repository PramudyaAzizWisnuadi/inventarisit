<?php

namespace App\Http\Controllers;

use App\Models\AssetDisposal;
use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AssetDisposalController extends Controller
{
    public function index()
    {
        $disposals = AssetDisposal::with('asset')->latest()->get();
        return view('asset_disposals.index', compact('disposals'));
    }

    public function create()
    {
        // Only show assets that aren't already disposed
        $assets = Asset::where('status', '!=', 'Dihapus')->get();
        return view('asset_disposals.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_ids' => 'required|array',
            'asset_ids.*' => 'exists:assets,id',
            'disposal_type' => 'required|in:sold,donated,trashed,lost',
            'disposal_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        foreach ($validated['asset_ids'] as $assetId) {
            AssetDisposal::create([
                'asset_id' => $assetId,
                'disposal_type' => $validated['disposal_type'],
                'disposal_date' => $validated['disposal_date'],
                'notes' => $validated['notes']
            ]);
            
            $asset = Asset::find($assetId);
            $asset->update(['status' => 'Dihapus']);
            
            AuditLog::record('create', 'Aset ' . $asset->asset_code . ' dimusnahkan. Tipe: ' . $validated['disposal_type']);
        }

        return redirect()->route('asset-disposals.index')->with('success', count($validated['asset_ids']) . ' catatan pemusnahan aset berhasil disimpan.');
    }
}
