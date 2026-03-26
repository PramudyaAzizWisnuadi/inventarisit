<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        $query = Vendor::withCount(['assets', 'softwareLicenses']);
        
        if (in_array($sort, ['name', 'email', 'phone', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name');
        }

        $vendors = $query->get();
        return view('settings.vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $vendor = Vendor::create($request->all());
        AuditLog::record('create', "Vendor ditambahkan: {$vendor->name}");
        return back()->with('success', 'Vendor berhasil ditambahkan.');
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $vendor->update($request->all());
        AuditLog::record('update', "Vendor diperbarui: {$vendor->name}");
        return back()->with('success', 'Vendor berhasil diperbarui.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        AuditLog::record('delete', "Vendor dihapus: {$vendor->name}");
        return back()->with('success', 'Vendor berhasil dihapus.');
    }
}
