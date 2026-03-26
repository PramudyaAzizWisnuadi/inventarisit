<?php

namespace App\Http\Controllers;

use App\Models\SoftwareLicense;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class SoftwareController extends Controller
{
    public function index(Request $request)
    {
        $query = SoftwareLicense::with(['category', 'vendor']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('software_name', 'ilike', "%$s%")
                  ->orWhere('license_code', 'ilike', "%$s%")
                  ->orWhere('publisher', 'ilike', "%$s%");
            });
        }
        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('license_type')) $query->where('license_type', $request->license_type);

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['license_code', 'software_name', 'publisher', 'license_type', 'status', 'expiry_date', 'max_users'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $licenses   = $query->get();
        $categories = Category::where('type', 'software')->get();

        return view('software.index', compact('licenses', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('type', 'software')->get();
        $vendors    = Vendor::all();
        return view('software.create', compact('categories', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'software_name' => 'required|string|max:255',
            'license_type'  => 'required',
            'purchase_date' => 'nullable|date',
            'expiry_date'   => 'nullable|date',
            'max_users'     => 'required|integer|min:1',
        ]);

        $data = $request->except('_token');
        $data['license_code'] = SoftwareLicense::generateCode();

        $sw = SoftwareLicense::create($data);

        AuditLog::record('create', "Lisensi software ditambahkan: {$sw->software_name} ({$sw->license_code})", $sw);

        return redirect()->route('software.index')->with('success', 'Lisensi software berhasil ditambahkan.');
    }

    public function show(SoftwareLicense $software)
    {
        $software->load(['category', 'vendor']);
        return view('software.show', compact('software'));
    }

    public function edit(SoftwareLicense $software)
    {
        $categories = Category::where('type', 'software')->get();
        $vendors    = Vendor::all();
        return view('software.edit', compact('software', 'categories', 'vendors'));
    }

    public function update(Request $request, SoftwareLicense $software)
    {
        $request->validate([
            'software_name' => 'required|string|max:255',
            'license_type'  => 'required',
            'max_users'     => 'required|integer|min:1',
        ]);

        $old = $software->toArray();
        $software->update($request->except(['_token', '_method']));

        AuditLog::record('update', "Lisensi software diperbarui: {$software->software_name}", $software, $old, $software->fresh()->toArray());

        return redirect()->route('software.index')->with('success', 'Lisensi software berhasil diperbarui.');
    }

    public function destroy(SoftwareLicense $software)
    {
        AuditLog::record('delete', "Lisensi software dihapus: {$software->software_name}", $software, $software->toArray());
        $software->delete();
        return redirect()->route('software.index')->with('success', 'Lisensi software berhasil dihapus.');
    }
}
