<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$locations = \App\Models\Location::with('branch')->get();
foreach ($locations as $l) {
    echo "ID: {$l->id}, Name: {$l->name}, Branch: " . ($l->branch ? $l->branch->name : 'NULL') . "\n";
}
