<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$db = app(Stancl\Tenancy\Database\DatabaseManager::class);
echo "Class: " . get_class($db) . "\n";
echo "Methods:\n";
print_r(get_class_methods($db));
