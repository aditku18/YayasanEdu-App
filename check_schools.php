<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Schools in tenant database:\n";
echo "============================\n";

$schools = DB::table('school_units')->select('id', 'name', 'slug', 'status')->get();

foreach ($schools as $s) {
    echo $s->id . '. ' . $s->name . ' -> slug: ' . ($s->slug ?? 'NULL') . ' -> status: ' . $s->status . "\n";
}

if ($schools->isEmpty()) {
    echo "No schools found!\n";
}
