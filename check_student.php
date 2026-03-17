<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check school unit
use App\Models\SchoolUnit;
$schools = SchoolUnit::all();
echo "School Units:\n";
foreach ($schools as $s) {
    echo "ID {$s->id}: {$s->name}\n";
}

// Try to find by name containing 'pelita'
$school = SchoolUnit::where('name', 'like', '%pelita%')->first();
echo "School Unit Check:\n";
if ($school) {
    echo "Found: ID {$school->id}, Name: {$school->name}\n";
} else {
    echo "Not found\n";
}

// Check student
use App\Models\Student;
$student = Student::find(2);
echo "\nStudent Check:\n";
if ($student) {
    echo "Found: ID {$student->id}, Name: {$student->name}, School ID: {$student->school_id}\n";
} else {
    echo "Student ID 2 not found\n";
}

// Check students in the school
if ($school) {
    $students = Student::where('school_id', $school->id)->get();
    echo "\nStudents in {$school->name}:\n";
    foreach ($students as $s) {
        echo "- ID {$s->id}: {$s->name}\n";
    }
}
