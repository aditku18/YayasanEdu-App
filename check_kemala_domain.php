<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Tenant;
use App\Models\Domain;

$tenant = Tenant::find('tenant-yayasan-kemala-bhayangkari');

if ($tenant) {
    echo "Tenant ID: " . $tenant->id . "\n";
    echo "Domains:\n";
    
    $domains = $tenant->domains;
    foreach ($domains as $domain) {
        echo "- " . $domain->domain . "\n";
    }
} else {
    echo "Tenant not found\n";
}
