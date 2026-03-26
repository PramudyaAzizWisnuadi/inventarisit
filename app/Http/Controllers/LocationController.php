<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        $query = Location::with('branch')->withCount('assets');

        if (in_array($sort, ['name', 'building', 'floor', 'room', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name');
        }

        $locations = $query->get();
        $branches = Branch::where('is_active', true)->get();
        return view('settings.locations.index', compact('locations', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'building' => 'nullable|string|max:255',
        ]);
        $loc = Location::create($request->all());
        AuditLog::record('create', "Lokasi ditambahkan: {$loc->name}");
        return back()->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function update(Request $request, Location $location)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
            'building' => 'nullable|string|max:255',
        ]);
        $location->update($request->all());
        AuditLog::record('update', "Lokasi diperbarui: {$location->name}");
        return back()->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        AuditLog::record('delete', "Lokasi dihapus: {$location->name}");
        return back()->with('success', 'Lokasi berhasil dihapus.');
    }
}
