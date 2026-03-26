<?php

namespace App\Http\Controllers;

use App\Models\AssetAssignment;
use App\Models\Asset;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Request $request)
    {
        $query = AssetAssignment::with(['asset', 'assignedBy']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('assigned_to', 'ilike', "%$s%")
                  ->orWhere('department', 'ilike', "%$s%")
                  ->orWhereHas('asset', fn($q2) => $q2->where('name', 'ilike', "%$s%")->orWhere('asset_code', 'ilike', "%$s%"));
            });
        }
        if ($request->filled('status')) $query->where('status', $request->status);

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['assigned_to', 'department', 'status', 'assigned_at', 'returned_at', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $assignments = $query->get();
        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        $assets = Asset::where('status', '!=', 'Dihapus')->orderBy('name')->get();
        return view('assignments.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id'    => 'required|exists:assets,id',
            'assigned_to' => 'required|string|max:255',
            'assigned_at' => 'required|date',
        ]);

        // Close any active assignments for this asset
        AssetAssignment::where('asset_id', $request->asset_id)
            ->where('status', 'Aktif')
            ->update(['status' => 'Dikembalikan', 'returned_at' => now()]);

        $assignment = AssetAssignment::create([
            'asset_id'    => $request->asset_id,
            'assigned_to' => $request->assigned_to,
            'department'  => $request->department,
            'assigned_by' => auth()->id(),
            'assigned_at' => $request->assigned_at,
            'status'      => 'Aktif',
            'notes'       => $request->notes,
        ]);

        Asset::find($request->asset_id)->update(['status' => 'Digunakan']);

        AuditLog::record('create', "Aset ditugaskan ke {$request->assigned_to}", $assignment->asset);

        return redirect()->route('assignments.index')->with('success', 'Aset berhasil ditugaskan.');
    }

    public function return(AssetAssignment $assignment)
    {
        $assignment->update(['status' => 'Dikembalikan', 'returned_at' => now()]);
        $assignment->asset->update(['status' => 'Tersedia']);

        AuditLog::record('update', "Aset dikembalikan dari {$assignment->assigned_to}", $assignment->asset);

        return back()->with('success', 'Aset berhasil dikembalikan.');
    }

    public function destroy(AssetAssignment $assignment)
    {
        $assignment->delete();
        return back()->with('success', 'Data penugasan dihapus.');
    }
}
