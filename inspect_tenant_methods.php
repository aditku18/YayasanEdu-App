<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$tenant = App\Models\Tenant::find('tenant-yayasan-hidayattul-amin') ?? new App\Models\Tenant(['id' => 'test']);
echo "Class: " . get_class($tenant) . "\n";
echo "Has database() method: " . (method_exists($tenant, 'database') ? 'Yes' : 'No') . "\n";
if (method_exists($tenant, 'database')) {
    echo "Database object class: " . get_class($tenant->database()) . "\n";
    echo "Database object methods:\n";
    print_r(get_class_methods($tenant->database()));
}
