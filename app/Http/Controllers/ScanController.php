<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('scan.index');
    }

    public function search(Request $request)
    {
        $code = $request->query('code');
        
        if (!$code) {
            return response()->json(['error' => 'Kode tidak valid'], 400);
        }

        $asset = Asset::with(['category', 'location', 'vendor', 'user'])
            ->where('asset_code', $code)
            ->orWhere('serial_number', $code)
            ->first();

        if (!$asset) {
            return response()->json(['error' => 'Aset tidak ditemukan'], 404);
        }

        return view('scan.details', compact('asset'));
    }
}
