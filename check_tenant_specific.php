<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Stancl\Tenancy\Database\Models\Tenant;
use Illuminate\Support\Facades\DB;

// Initialize tenancy for hidayattul-amin tenant
$tenant = Tenant::find('tenant-yayasan-hidayattul-amin');

if (!$tenant) {
    echo "Tenant hidayattul-amin not found!\n";
    exit(1);
}

echo "Found tenant: {$tenant->id}\n";
$tenant->run(function() {
    echo "Switched to tenant database: " . config('database.connections.mysql.database') . "\n\n";

// Check school_units in tenant database
    try {
        $schoolCount = DB::table('school_units')->count();
        echo "School Units count: $schoolCount\n";
        
        if ($schoolCount > 0) {
            $schools = DB::table('school_units')->get();
            echo "School Units:\n";
            foreach ($schools as $school) {
                echo "- ID: {$school->id}, Name: {$school->name}\n";
            }
        }
    } catch (Exception $e) {
        echo "Error checking school units: " . $e->getMessage() . "\n";
    }

    // Check students in tenant database
    try {
        $studentCount = DB::table('students')->count();
        echo "\nStudents count: $studentCount\n";
        
        if ($studentCount > 0) {
            $students = DB::table('students')->limit(5)->get();
            echo "First 5 students:\n";
            foreach ($students as $student) {
                echo "- ID: {$student->id}, Name: {$student->name}, School ID: {$student->school_id}\n";
            }
            
            // Check specifically for student ID 2
            $student2 = DB::table('students')->where('id', 2)->first();
            if ($student2) {
                echo "\nStudent ID 2 found: {$student2->name}\n";
            } else {
                echo "\nStudent ID 2 not found\n";
            }
        }
    } catch (Exception $e) {
        echo "Error checking students: " . $e->getMessage() . "\n";
    }
});
