<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Vendor;
use App\Models\Branch;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'location.branch', 'vendor']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'ilike', "%$s%")
                  ->orWhere('asset_code', 'ilike', "%$s%")
                  ->orWhere('serial_number', 'ilike', "%$s%")
                  ->orWhere('brand', 'ilike', "%$s%");
            });
        }
        if ($request->filled('category_id'))  $query->where('category_id', $request->category_id);
        if ($request->filled('branch_id')) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }
        if ($request->filled('location_id'))  $query->where('location_id', $request->location_id);
        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('condition'))    $query->where('condition', $request->condition);

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['asset_code', 'name', 'status', 'condition', 'warranty_expiry', 'created_at', 'brand', 'model'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        if ($request->format === 'excel') {
            $assets = $query->get();
            AuditLog::record('export', 'Export data aset hardware (Excel)');
            return Excel::download(new AssetsExport($assets), 'aset-hardware-' . now()->format('Ymd') . '.xlsx');
        }

        $assets     = $query->get();
        $categories = Category::where('type', 'hardware')->get();
        $branches   = Branch::all();
        $locations  = Location::all();

        return view('assets.index', compact('assets', 'categories', 'branches', 'locations'));
    }

    public function create()
    {
        $categories = Category::where('type', 'hardware')->get();
        $branches   = Branch::all();
        $locations  = Location::all();
        $vendors    = Vendor::all();
        return view('assets.create', compact('categories', 'branches', 'locations', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'nullable|exists:categories,id',
            'location_id'   => 'nullable|exists:locations,id',
            'vendor_id'     => 'nullable|exists:vendors,id',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'purchase_price'  => 'nullable|numeric|min:0',
            'condition'       => 'required|in:Baik,Kurang Baik,Rusak',
            'status'          => 'required',
            'photo'           => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['photo', '_token']);
        $data['asset_code'] = Asset::generateCode();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset = Asset::create($data);

        AuditLog::record('create', "Aset baru ditambahkan: {$asset->name} ({$asset->asset_code})", $asset, [], $asset->toArray());

        return redirect()->route('assets.show', $asset)->with('success', 'Aset berhasil ditambahkan.');
    }

    public function show(Asset $asset)
    {
        $asset->load(['category', 'location', 'vendor', 'assignments.assignedBy', 'maintenances.creator']);
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        $categories = Category::where('type', 'hardware')->get();
        $branches   = Branch::all();
        $locations  = Location::all();
        $vendors    = Vendor::all();
        return view('assets.edit', compact('asset', 'categories', 'branches', 'locations', 'vendors'));
    }

    public function update(Request $request, Asset $asset)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'nullable|exists:categories,id',
            'location_id'   => 'nullable|exists:locations,id',
            'vendor_id'     => 'nullable|exists:vendors,id',
            'purchase_date' => 'nullable|date',
            'warranty_expiry' => 'nullable|date',
            'purchase_price'  => 'nullable|numeric|min:0',
            'condition'       => 'required|in:Baik,Kurang Baik,Rusak',
            'status'          => 'required',
            'photo'           => 'nullable|image|max:2048',
        ]);

        $old  = $asset->toArray();
        $data = $request->except(['photo', '_token', '_method']);

        if ($request->hasFile('photo')) {
            if ($asset->photo) Storage::disk('public')->delete($asset->photo);
            $data['photo'] = $request->file('photo')->store('assets/photos', 'public');
        }

        $asset->update($data);

        AuditLog::record('update', "Aset diperbarui: {$asset->name} ({$asset->asset_code})", $asset, $old, $asset->fresh()->toArray());

        return redirect()->route('assets.show', $asset)->with('success', 'Aset berhasil diperbarui.');
    }

    public function destroy(Asset $asset)
    {
        AuditLog::record('delete', "Aset dihapus: {$asset->name} ({$asset->asset_code})", $asset, $asset->toArray());
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Aset berhasil dihapus.');
    }

    public function label(Asset $asset)
    {
        $asset->load(['category', 'location']);
        $assets = collect([$asset]);
        return view('labels.print', compact('assets'));
    }

    public function bulkLabel(Request $request)
    {
        $ids    = $request->input('ids', []);
        $assets = Asset::with(['category', 'location'])->whereIn('id', $ids)->get();
        return view('labels.print', compact('assets'));
    }
}
