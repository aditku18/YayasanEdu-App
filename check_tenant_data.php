<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if we're in tenant context
echo "Current Database: " . config('database.default') . "\n";
echo "Database Name: " . config('database.connections.mysql.database') . "\n\n";

// Check tenants table
use Illuminate\Support\Facades\DB;
try {
    $tenants = DB::table('tenants')->get();
    echo "Tenants:\n";
    foreach ($tenants as $tenant) {
        echo "- ID: {$tenant->id}, Domain: {$tenant->id}\n";
    }
} catch (Exception $e) {
    echo "Error checking tenants: " . $e->getMessage() . "\n";
}

// Check if student table exists and has data
try {
    $studentCount = DB::table('students')->count();
    echo "\nStudents count: $studentCount\n";
    
    if ($studentCount > 0) {
        $students = DB::table('students')->limit(5)->get();
        echo "First 5 students:\n";
        foreach ($students as $student) {
            echo "- ID: {$student->id}, Name: {$student->name}, School ID: {$student->school_id}\n";
        }
    }
} catch (Exception $e) {
    echo "Error checking students: " . $e->getMessage() . "\n";
}

// Check school_units table
try {
    $schoolCount = DB::table('school_units')->count();
    echo "\nSchool Units count: $schoolCount\n";
    
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
