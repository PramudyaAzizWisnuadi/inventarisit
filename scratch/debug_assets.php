<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$assets = \App\Models\Asset::with('location.branch')->take(20)->get();
foreach ($assets as $a) {
    echo "ID: {$a->id}, Code: {$a->asset_code}, Status: {$a->status}, Location: " . ($a->location ? $a->location->name : 'NULL') . ", Branch: " . ($a->location && $a->location->branch ? $a->location->branch->name : 'NULL') . "\n";
}
