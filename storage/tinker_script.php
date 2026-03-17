<?php
$tenant = \App\Models\Tenant::create(['id' => 'yayasan-abc']);
$tenant->domains()->create(['domain' => 'abc.localhost']);
\App\Models\Foundation::create([
    'tenant_id' => 'yayasan-abc',
    'name' => 'Yayasan ABC',
    'email' => 'admin@yayasan-abc.com',
    'status' => 'active'
]);
echo "Tenant yayasan-abc created successfully.\n";
