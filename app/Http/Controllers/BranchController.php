<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        $query = Branch::withCount('locations');
        
        if (in_array($sort, ['name', 'code', 'manager', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name');
        }

        $branches = $query->get();
        return view('settings.branches.index', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:branches,code',
            'address' => 'nullable|string',
            'manager' => 'nullable|string|max:255',
        ]);

        $branch = Branch::create($request->all());

        AuditLog::record('create', "Cabang baru dibuat: {$branch->name} ({$branch->code})", $branch);

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:branches,code,' . $branch->id,
            'address' => 'nullable|string',
            'manager' => 'nullable|string|max:255',
        ]);

        $old = $branch->toArray();
        $branch->update($request->all());

        AuditLog::record('update', "Cabang diperbarui: {$branch->name}", $branch, $old, $branch->fresh()->toArray());

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        if ($branch->locations()->count() > 0) {
            return back()->with('error', 'Cabang tidak bisa dihapus karena masih memiliki lokasi.');
        }

        AuditLog::record('delete', "Cabang dihapus: {$branch->name}", $branch);
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
