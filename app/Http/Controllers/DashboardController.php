<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\SoftwareLicense;
use App\Models\Maintenance;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $stats = [
            'total_hardware'   => Asset::count(),
            'total_software'   => SoftwareLicense::count(),
            'digunakan'        => Asset::where('status', 'Digunakan')->count(),
            'tersedia'         => Asset::where('status', 'Tersedia')->count(),
            'dalam_perbaikan'  => Asset::where('status', 'Dalam Perbaikan')->count(),
            'sw_expired'       => SoftwareLicense::where('status', 'Expired')->count(),
            'sw_expiring_soon' => SoftwareLicense::where('status', 'Aktif')
                ->whereNotNull('expiry_date')
                ->whereDate('expiry_date', '<=', now()->addDays(30))
                ->count(),
            'total_nilai'      => Asset::sum('purchase_price'),
        ];

        // Chart: Assets by Category
        $assetsByCategory = Asset::selectRaw('categories.name as name, count(*) as total')
            ->join('categories', 'assets.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->get();

        // Chart: Assets by Location (grouped by Branch)
        $assetsByLocation = Asset::selectRaw('branches.name as branch_name, locations.name as name, count(*) as total')
            ->join('locations', 'assets.location_id', '=', 'locations.id')
            ->leftJoin('branches', 'locations.branch_id', '=', 'branches.id')
            ->groupBy('branches.name', 'locations.name')
            ->orderBy('branches.name')
            ->get();

        // Chart: Assets by Status
        $assetsByStatus = Asset::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get();

        // Recent assets
        $recentAssets = Asset::with(['category', 'location'])
            ->latest()
            ->take(5)
            ->get();

        // Upcoming maintenance
        $upcomingMaintenance = Maintenance::with('asset')
            ->where('status', 'Dijadwalkan')
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        // Expiring warranties (within 60 days)
        $expiringWarranty = Asset::with('category')
            ->whereNotNull('warranty_expiry')
            ->whereDate('warranty_expiry', '>=', now())
            ->whereDate('warranty_expiry', '<=', now()->addDays(60))
            ->orderBy('warranty_expiry')
            ->take(5)
            ->get();

        // Recent audit logs
        $recentLogs = AuditLog::latest()->take(8)->get();

        return view('dashboard.index', compact(
            'stats', 'assetsByCategory', 'assetsByLocation', 'assetsByStatus',
            'recentAssets', 'upcomingMaintenance', 'expiringWarranty', 'recentLogs'
        ));
    }
}
