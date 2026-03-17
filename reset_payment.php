<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$domain = Stancl\Tenancy\Database\Models\Domain::where('domain', 'yab2.localhost')->first();
$tenant = App\Models\Tenant::find($domain->tenant_id);
tenancy()->initialize($tenant);

$a = App\Models\PPDBApplicant::find(5);
echo "Before: status={$a->status}, payment_status={$a->payment_status}\n";

$a->update(['payment_status' => 'unpaid']);
$a->refresh();

echo "After: status={$a->status}, payment_status={$a->payment_status}\n";
echo "Done!\n";
