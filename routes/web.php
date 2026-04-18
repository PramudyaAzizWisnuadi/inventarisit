<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Login
Route::get('/login', function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return view('auth.login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        \App\Models\AuditLog::record('login', 'Login ke sistem');
        return redirect()->route('dashboard');
    }
    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
})->name('login.post');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \App\Models\AuditLog::record('logout', 'Logout dari sistem');
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Hardware Assets
    Route::resource('assets', AssetController::class);
    Route::get('assets/{asset}/label', [AssetController::class, 'label'])->name('assets.label');

    // Software Licenses
    Route::resource('software', SoftwareController::class);

    // Assignments
    Route::resource('assignments', AssignmentController::class)->except(['edit', 'update', 'show']);
    Route::patch('assignments/{assignment}/return', [AssignmentController::class, 'return'])->name('assignments.return');

    // Maintenance
    Route::resource('maintenance', MaintenanceController::class);

    // Labels
    Route::get('labels', [LabelController::class, 'index'])->name('labels.index');
    Route::post('labels/print', [LabelController::class, 'print'])->name('labels.print');

    // Scan Barcode
    Route::get('scan', [App\Http\Controllers\ScanController::class, 'index'])->name('scan.index');
    Route::get('scan/search', [App\Http\Controllers\ScanController::class, 'search'])->name('scan.search');

    // Borrow Requests
    Route::resource('borrow-requests', App\Http\Controllers\BorrowRequestController::class);
    Route::post('borrow-requests/{borrow_request}/approve', [App\Http\Controllers\BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::post('borrow-requests/{borrow_request}/reject', [App\Http\Controllers\BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::post('borrow-requests/{borrow_request}/return', [App\Http\Controllers\BorrowRequestController::class, 'return'])->name('borrow-requests.return');
    Route::get('borrow-requests/{borrow_request}/print-bast', [App\Http\Controllers\BorrowRequestController::class, 'printBast'])->name('borrow-requests.print-bast');

    // Purchase Requests
    Route::resource('purchase-requests', App\Http\Controllers\PurchaseRequestController::class);
    Route::post('purchase-requests/{purchase_request}/approve-manager', [App\Http\Controllers\PurchaseRequestController::class, 'approveManager'])->name('purchase-requests.approve-manager');
    Route::post('purchase-requests/{purchase_request}/approve-director', [App\Http\Controllers\PurchaseRequestController::class, 'approveDirector'])->name('purchase-requests.approve-director');
    Route::post('purchase-requests/{purchase_request}/reject', [App\Http\Controllers\PurchaseRequestController::class, 'reject'])->name('purchase-requests.reject');
    Route::post('purchase-requests/{purchase_request}/process', [App\Http\Controllers\PurchaseRequestController::class, 'process'])->name('purchase-requests.process');
    Route::post('purchase-requests/{purchase_request}/complete', [App\Http\Controllers\PurchaseRequestController::class, 'complete'])->name('purchase-requests.complete');
    Route::get('purchase-requests/{purchase_request}/print', [App\Http\Controllers\PurchaseRequestController::class, 'print'])->name('purchase-requests.print');

    // Asset Disposals
    Route::resource('asset-disposals', App\Http\Controllers\AssetDisposalController::class)->except(['edit', 'update', 'show']);

    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('reports/software', [ReportController::class, 'software'])->name('reports.software');
    Route::get('reports/assignment', [ReportController::class, 'assignment'])->name('reports.assignment');
    Route::get('reports/maintenance', [ReportController::class, 'maintenance'])->name('reports.maintenance');
    Route::get('reports/distribution', [ReportController::class, 'distribution'])->name('reports.distribution');
    Route::get('reports/audit', [ReportController::class, 'audit'])->name('reports.audit');

    // Settings
    Route::resource('settings/categories', CategoryController::class)->names('categories')->except(['show', 'create', 'edit']);
    Route::resource('settings/branches', BranchController::class)->names('branches')->except(['show', 'create', 'edit']);
    Route::resource('settings/locations', LocationController::class)->names('locations')->except(['show', 'create', 'edit']);
    Route::resource('settings/vendors', VendorController::class)->names('vendors')->except(['show', 'create', 'edit']);

    // User & Role Management (Admin only / Permission based)
    Route::middleware('permission:manage-users')->group(function () {
        Route::resource('settings/users', UserController::class);
    });
    
    Route::middleware('permission:manage-roles')->group(function () {
        Route::resource('settings/roles', RoleController::class);
    });
});
