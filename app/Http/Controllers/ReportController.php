<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\SoftwareLicense;
use App\Models\AssetAssignment;
use App\Models\Maintenance;
use App\Models\Category;
use App\Models\Location;
use App\Models\Branch;
use App\Models\AuditLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AssetsExport;
use App\Exports\DistributionExport;
use App\Exports\AssignmentsExport;

class ReportController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $locations  = Location::all();
        $branches   = Branch::where('is_active', true)->get();
        return view('reports.index', compact('categories', 'locations', 'branches'));
    }

    public function inventory(Request $request)
    {
        $query = Asset::with(['category', 'location.branch', 'vendor']);

        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('branch_id')) {
            $query->whereHas('location', function($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            });
        }
        if ($request->filled('location_id')) $query->where('location_id', $request->location_id);
        if ($request->filled('status'))      $query->where('status', $request->status);
        if ($request->filled('condition'))   $query->where('condition', $request->condition);
        if ($request->filled('date_from'))   $query->whereDate('purchase_date', '>=', $request->date_from);
        if ($request->filled('date_to'))     $query->whereDate('purchase_date', '<=', $request->date_to);

        $sort = $request->get('sort', 'asset_code');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = ['asset_code', 'name', 'purchase_date', 'status', 'condition', 'purchase_price'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('asset_code');
        }

        $assets = $query->get();
        $categories = Category::all();
        $branches   = Branch::where('is_active', true)->get();
        $locations  = Location::all();
        $totalValue = $assets->sum('purchase_price');

        if ($request->format === 'pdf') {
            AuditLog::record('export', 'Export laporan inventaris hardware (PDF)');
            $pdf = Pdf::loadView('reports.inventory_pdf', compact('assets', 'totalValue', 'request'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-inventaris-hardware-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->format === 'excel') {
            AuditLog::record('export', 'Export laporan inventaris hardware (Excel)');
            return Excel::download(new AssetsExport($assets), 'inventaris-hardware-' . now()->format('Ymd') . '.xlsx');
        }

        return view('reports.inventory', compact('assets', 'categories', 'locations', 'branches', 'totalValue'));
    }

    public function software(Request $request)
    {
        $query = SoftwareLicense::with(['category', 'vendor']);

        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('license_type')) $query->where('license_type', $request->license_type);

        $sort = $request->get('sort', 'software_name');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = ['software_name', 'license_code', 'expiry_date', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('software_name');
        }

        $licenses = $query->get();

        if ($request->format === 'pdf') {
            AuditLog::record('export', 'Export laporan software (PDF)');
            $pdf = Pdf::loadView('reports.software_pdf', compact('licenses', 'request'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-software-' . now()->format('Ymd') . '.pdf');
        }

        return view('reports.software', compact('licenses'));
    }

    public function assignment(Request $request)
    {
        $query = AssetAssignment::with(['asset.category', 'asset.location.branch', 'assignedBy']);

        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('assigned_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('assigned_at', '<=', $request->date_to);

        $sort = $request->get('sort', 'assigned_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['assigned_at', 'returned_at', 'status', 'assigned_to'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderByDesc('assigned_at');
        }

        $assignments = $query->get();

        if ($request->format === 'pdf') {
            AuditLog::record('export', 'Export laporan penugasan aset (PDF)');
            $pdf = Pdf::loadView('reports.assignment_pdf', compact('assignments', 'request'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-penugasan-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->format === 'excel') {
            AuditLog::record('export', 'Export laporan penugasan aset (Excel)');
            return Excel::download(new AssignmentsExport($assignments), 'laporan-penugasan-' . now()->format('Ymd') . '.xlsx');
        }

        return view('reports.assignment', compact('assignments'));
    }

    public function maintenance(Request $request)
    {
        $query = Maintenance::with(['asset', 'creator']);

        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('date_from')) $query->whereDate('scheduled_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('scheduled_at', '<=', $request->date_to);

        $sort = $request->get('sort', 'scheduled_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['scheduled_at', 'completed_at', 'status', 'cost'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderByDesc('scheduled_at');
        }

        $maintenances = $query->get();
        $totalCost    = $maintenances->sum('cost');

        if ($request->format === 'pdf') {
            AuditLog::record('export', 'Export laporan pemeliharaan (PDF)');
            $pdf = Pdf::loadView('reports.maintenance_pdf', compact('maintenances', 'totalCost', 'request'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pemeliharaan-' . now()->format('Ymd') . '.pdf');
        }

        return view('reports.maintenance', compact('maintenances', 'totalCost'));
    }

    public function distribution(Request $request)
    {
        $query = Branch::with(['locations' => function($q) {
            $q->withCount('assets');
            $q->withSum('assets', 'purchase_price');
            $q->orderBy('name');
        }])->where('is_active', true);

        if ($request->filled('branch_id')) {
            $query->where('id', $request->branch_id);
        }

        $distribution = $query->orderBy('name')->get();
        $branches = Branch::where('is_active', true)->orderBy('name')->get();

        if ($request->format === 'pdf') {
            AuditLog::record('export', 'Export laporan distribusi aset per cabang (PDF)');
            $pdf = Pdf::loadView('reports.distribution_pdf', compact('distribution', 'request'))
                ->setPaper('a4', 'portrait');
            return $pdf->download('laporan-distribusi-aset-' . now()->format('Ymd') . '.pdf');
        }

        if ($request->format === 'excel') {
            AuditLog::record('export', 'Export laporan distribusi aset per cabang (Excel)');
            return Excel::download(new DistributionExport($distribution), 'distribusi-aset-' . now()->format('Ymd') . '.xlsx');
        }

        return view('reports.distribution', compact('distribution', 'branches'));
    }

    public function audit(Request $request)
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $query = AuditLog::with('user');

        if ($request->filled('action'))    $query->where('action', $request->action);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('search'))    $query->where('description', 'ilike', '%' . $request->search . '%');

        $allowedSorts = ['action', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        $logs = $query->get();

        if ($request->format === 'pdf') {
            $allLogs = $query->get();
            AuditLog::record('export', 'Export laporan audit log (PDF)');
            $pdf = Pdf::loadView('reports.audit_pdf', ['logs' => $allLogs, 'request' => $request])
                ->setPaper('a4', 'landscape');
            return $pdf->download('laporan-audit-' . now()->format('Ymd') . '.pdf');
        }

        return view('reports.audit', compact('logs'));
    }
}
