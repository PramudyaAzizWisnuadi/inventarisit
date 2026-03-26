<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Asset;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with(['asset', 'creator']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('technician', 'ilike', "%$s%")
                  ->orWhereHas('asset', fn($q2) => $q2->where('name', 'ilike', "%$s%"));
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('type'))   $query->where('type', $request->type);

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['type', 'status', 'scheduled_at', 'completed_at', 'cost', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $maintenances = $query->get();
        $assets = Asset::orderBy('name')->get();

        return view('maintenance.index', compact('maintenances', 'assets'));
    }

    public function create()
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenance.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id'     => 'required|exists:assets,id',
            'type'         => 'required',
            'scheduled_at' => 'nullable|date',
            'cost'         => 'nullable|numeric|min:0',
        ]);

        $data = $request->except('_token');
        $data['created_by'] = auth()->id();

        $maintenance = Maintenance::create($data);

        if ($request->status === 'Dalam Proses') {
            Asset::find($request->asset_id)->update(['status' => 'Dalam Perbaikan']);
        }

        AuditLog::record('create', "Pemeliharaan dijadwalkan untuk aset ID {$request->asset_id}", $maintenance->asset);

        return redirect()->route('maintenance.index')->with('success', 'Jadwal pemeliharaan berhasil ditambahkan.');
    }

    public function show(Maintenance $maintenance)
    {
        $maintenance->load(['asset', 'creator']);
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        $assets = Asset::orderBy('name')->get();
        return view('maintenance.edit', compact('maintenance', 'assets'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $old = $maintenance->toArray();
        $maintenance->update($request->except(['_token', '_method']));

        if ($request->status === 'Selesai' && $maintenance->asset) {
            $maintenance->asset->update(['status' => 'Tersedia', 'condition' => 'Baik']);
        }

        AuditLog::record('update', "Pemeliharaan diperbarui untuk aset: {$maintenance->asset?->name}", $maintenance->asset, $old, $maintenance->fresh()->toArray());

        return redirect()->route('maintenance.show', $maintenance)->with('success', 'Pemeliharaan berhasil diperbarui.');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenance.index')->with('success', 'Data pemeliharaan dihapus.');
    }
}
