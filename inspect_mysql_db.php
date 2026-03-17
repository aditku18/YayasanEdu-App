<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$dbManager = app(Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class);
echo "Class: " . get_class($dbManager) . "\n";
echo "Methods:\n";
print_r(get_class_methods($dbManager));
