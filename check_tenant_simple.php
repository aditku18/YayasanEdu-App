<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use artisan command to run in tenant context
echo "Checking tenant data for hidayattul-amin:\n\n";

// Check tenant database name
$tenantId = 'tenant-yayasan-hidayattul-amin';
echo "Tenant ID: $tenantId\n";

// Try to get tenant database info using DB
use Illuminate\Support\Facades\DB;
try {
    $tenant = DB::table('tenants')->where('id', $tenantId)->first();
    if ($tenant) {
        echo "Tenant found in central DB\n";
        $dbName = 'tenant' . $tenant->id;
        echo "Expected database name: $dbName\n";
        
        // Try to connect directly to tenant database
        config(['database.connections.tenant_check.database' => $dbName]);
        DB::connection('tenant_check')->reconnect();
        
        // Check school_units
        $schoolCount = DB::connection('tenant_check')->table('school_units')->count();
        echo "School Units count: $schoolCount\n";
        
        if ($schoolCount > 0) {
            $schools = DB::connection('tenant_check')->table('school_units')->get();
            echo "School Units:\n";
            foreach ($schools as $school) {
                echo "- ID: {$school->id}, Name: {$school->name}\n";
            }
        }
        
        // Check students
        $studentCount = DB::connection('tenant_check')->table('students')->count();
        echo "\nStudents count: $studentCount\n";
        
        if ($studentCount > 0) {
            $students = DB::connection('tenant_check')->table('students')->limit(5)->get();
            echo "First 5 students:\n";
            foreach ($students as $student) {
                echo "- ID: {$student->id}, Name: {$student->name}, School ID: {$student->school_id}\n";
            }
            
            // Check specifically for student ID 2
            $student2 = DB::connection('tenant_check')->table('students')->where('id', 2)->first();
            if ($student2) {
                echo "\nStudent ID 2 found: {$student2->name}\n";
            } else {
                echo "\nStudent ID 2 not found\n";
            }
        }
    } else {
        echo "Tenant not found in central DB\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
