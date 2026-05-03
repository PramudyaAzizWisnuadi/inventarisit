<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class LabelController extends Controller
{
    public function index()
    {
        $assets = Asset::with(['category', 'location.branch'])->orderBy('asset_code')->get();
        return view('labels.index', compact('assets'));
    }

    public function print(Request $request)
    {
        $ids    = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal satu aset untuk dicetak labelnya.');
        }

        $assets = Asset::with(['category', 'location.branch'])->whereIn('id', $ids)->orderBy('asset_code')->get();

        AuditLog::record('print', 'Cetak label aset: ' . $assets->pluck('asset_code')->join(', '));

        $format = $request->input('format', 'html');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('labels.print_pdf', compact('assets'))
                ->setPaper([0, 0, 226.77, 141.73]); // ~8x5cm label size
            return $pdf->stream('label-aset-' . now()->format('Ymd') . '.pdf');
        }

        return view('labels.print', compact('assets'));
    }
}
