<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "asset_url = " . config('app.asset_url') . "\n";

// stripped path version
$original = 'storage/ppdb/documents/E36BVmZG7XXJt3RcM3ehib58oGVyKzJpVpXUTaFU.png';
$path = ltrim($original, '/');
if (Illuminate\Support\Str::startsWith($path, 'storage/')) {
    $path = substr($path, strlen('storage/'));
}
echo "original path: $original\n";
echo "stripped path: $path\n";
echo "tenant_asset output = " . tenant_asset($original) . "\n";

// replicate route() call with stripped path manually
$manual = route('stancl.tenancy.asset', ['path' => $path]);
echo "manual route with stripped path = $manual\n";

echo "asset('ppdb/documents/...') = " . asset('ppdb/documents/E36BVmZG7XXJt3RcM3ehib58oGVyKzJpVpXUTaFU.png') . "\n";
