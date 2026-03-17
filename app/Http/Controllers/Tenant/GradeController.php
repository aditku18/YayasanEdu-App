<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\GradeComponent;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    /**
     * Display grade dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        // Get active academic year
        $academicYear = AcademicYear::where('is_active', true)->first();
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
            
        $selectedYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : ($academicYear?->id);
            
        // Get grade components for selected year
        $gradeComponents = GradeComponent::with(['subject', 'classRoom', 'academicYear'])
            ->where('school_unit_id', $schoolId)
            ->when($selectedYearId, function($q) use ($selectedYearId) {
                $q->where('academic_year_id', $selectedYearId);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type');
            
        // Get subjects
        $subjects = Subject::where('school_unit_id', $schoolId)->get();
        
        // Get classrooms
        $classRooms = ClassRoom::where('school_unit_id', $schoolId)
            ->withCount('students')
            ->get();
            
        return view('yayasan.penilaian.index', compact(
            'gradeComponents',
            'subjects',
            'classRooms',
            'academicYears',
            'selectedYearId',
            'academicYear',
            'schoolSlug'
        ));
    }

    /**
     * Create new grade component form
     */
    public function createComponent(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
            
        $subjects = Subject::where('school_unit_id', $schoolId)->get();
        
        $classRooms = ClassRoom::where('school_unit_id', $schoolId)->get();
        
        return view('yayasan.penilaian.create-component', compact(
            'academicYears',
            'subjects',
            'classRooms',
            'schoolSlug'
        ));
    }

    /**
     * Store new grade component
     */
    public function storeComponent(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'type' => 'required|in:daily,assignment,midterm,final,project',
            'weight' => 'required|integer|min:0|max:100',
            'max_score' => 'required|integer|min:1',
            'semester' => 'nullable|string|max:20',
            'academic_year_id' => 'required|exists:academic_years,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'class_room_id' => 'nullable|exists:class_rooms,id',
        ]);
        
        $validated['school_unit_id'] = $schoolId;
        
        GradeComponent::create($validated);
        
        return redirect()->route('tenant.school.grades.index')
            ->with('success', 'Komponen nilai berhasil ditambahkan.');
    }

    /**
     * Input grades for students
     */
    public function inputGrades(Request $request, GradeComponent $gradeComponent)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        // Ensure component belongs to user's school
        if ($gradeComponent->school_unit_id != $schoolId) {
            abort(403);
        }
        
        // Get students in the class
        $students = Student::where('school_unit_id', $schoolId)
            ->where('class_room_id', $gradeComponent->class_room_id)
            ->where('status', 'active')
            ->get();
            
        // Get existing grades for this component
        $existingGrades = Grade::where('grade_component_id', $gradeComponent->id)
            ->pluck('score', 'student_id')
            ->toArray();
            
        return view('yayasan.penilaian.input-grades', compact(
            'gradeComponent',
            'students',
            'existingGrades',
            'schoolSlug'
        ));
    }

    /**
     * Store grades for students
     */
    public function storeGrades(Request $request, GradeComponent $gradeComponent)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        // Ensure component belongs to user's school
        if ($gradeComponent->school_unit_id != $schoolId) {
            abort(403);
        }
        
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'nullable|numeric|min:0|max:' . $gradeComponent->max_score,
        ]);
        
        $students = Student::where('school_unit_id', $schoolId)
            ->where('class_room_id', $gradeComponent->class_room_id)
            ->where('status', 'active')
            ->get();
            
        $studentIds = $students->pluck('id')->toArray();
        
        foreach ($validated['grades'] as $studentId => $score) {
            if (!in_array($studentId, $studentIds)) {
                continue;
            }
            
            if ($score === null || $score === '') {
                // Skip empty scores
                continue;
            }
            
            Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'grade_component_id' => $gradeComponent->id,
                    'subject_id' => $gradeComponent->subject_id,
                ],
                [
                    'school_unit_id' => $schoolId,
                    'class_room_id' => $gradeComponent->class_room_id,
                    'academic_year_id' => $gradeComponent->academic_year_id,
                    'score' => $score,
                    'entered_by' => $user->id,
                ]
            );
        }
        
        return redirect()->route('tenant.school.grades.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * View student report card
     */
    public function raport(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        // Get students
        $students = Student::where('school_unit_id', $schoolId)
            ->where('status', 'active')
            ->with('classRoom')
            ->get();
            
        $classRooms = ClassRoom::where('school_unit_id', $schoolId)->get();
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
            
        return view('yayasan.penilaian.raport', compact(
            'students',
            'classRooms',
            'academicYears',
            'schoolSlug'
        ));
    }

    /**
     * View student grades detail
     */
    public function studentGrades(Request $request, Student $student)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        // Ensure student belongs to user's school
        if ($student->school_unit_id != $schoolId) {
            abort(403);
        }
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : null;
            
        // Get grades with components
        $grades = Grade::with(['gradeComponent', 'subject', 'academicYear'])
            ->where('student_id', $student->id)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->orderBy('academic_year_id')
            ->get()
            ->groupBy('subject_id');
            
        // Calculate averages by type
        $averages = [];
        foreach ($grades as $subjectId => $subjectGrades) {
            $byType = $subjectGrades->groupBy('gradeComponent.type');
            foreach ($byType as $type => $typeGrades) {
                $avg = $typeGrades->avg('score');
                $averages[$subjectId][$type] = $avg;
            }
        }
        
        return view('yayasan.penilaian.student-grades', compact(
            'student',
            'grades',
            'averages',
            'schoolSlug'
        ));
    }

    /**
     * Delete grade component
     */
    public function destroyComponent(GradeComponent $gradeComponent)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        // Ensure component belongs to user's school
        if ($gradeComponent->school_unit_id != $schoolId) {
            abort(403);
        }
        
        // Delete associated grades first
        $gradeComponent->grades()->delete();
        $gradeComponent->delete();
        
        return redirect()->route('tenant.school.grades.index')
            ->with('success', 'Komponen nilai berhasil dihapus.');
    }

    /**
     * Show import form
     */
    public function importForm(Request $request)
    {
        $user = auth()->user();
        $schoolSlug = $user->schoolUnit?->slug;
        
        return view('yayasan.penilaian.import', compact('schoolSlug'));
    }

    /**
     * Rekap Nilai per Kelas
     */
    public function rekapNilai(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : AcademicYear::where('is_active', true)->first()?->id;
        
        $classRoomId = $request->filled('class_room') ? $request->class_room : null;
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $classRooms = ClassRoom::where('school_unit_id', $schoolId)->withCount('students')->get();
        $subjects = Subject::where('school_unit_id', $schoolId)->get();
        
        $students = Student::where('school_unit_id', $schoolId)
            ->where('status', 'active')
            ->when($classRoomId, function($q) use ($classRoomId) {
                $q->where('class_room_id', $classRoomId);
            })
            ->with('classRoom')
            ->get();
        
        // Get grades for all students
        $grades = Grade::where('school_unit_id', $schoolId)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->when($classRoomId, function($q) use ($classRoomId) {
                $q->where('class_room_id', $classRoomId);
            })
            ->with(['gradeComponent', 'subject'])
            ->get()
            ->groupBy('student_id');
        
        // Calculate averages per student per subject
        $studentGrades = [];
        foreach ($students as $student) {
            $studentGradeList = $grades->get($student->id, collect());
            $bySubject = $studentGradeList->groupBy('subject_id');
            
            foreach ($bySubject as $subjectId => $subjectGrades) {
                $byType = $subjectGrades->groupBy('gradeComponent.type');
                $avg = $subjectGrades->avg('score');
                $studentGrades[$student->id][$subjectId] = [
                    'avg' => round($avg, 2),
                    'by_type' => $byType->map(fn($g) => round($g->avg('score'), 2))
                ];
            }
        }
        
        $selectedYearId = $academicYearId;
        $selectedClassRoom = $classRoomId;
        
        return view('yayasan.penilaian.rekap-nilai', compact(
            'students',
            'subjects',
            'classRooms',
            'academicYears',
            'selectedYearId',
            'selectedClassRoom',
            'studentGrades',
            'schoolSlug'
        ));
    }

    /**
     * Analisis Hasil Belajar
     */
    public function analisis(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : AcademicYear::where('is_active', true)->first()?->id;
        
        $subjectId = $request->filled('subject') ? $request->subject : null;
        
        $academicYears = AcademicYear::orderBy('name', 'desc')->get();
        $subjects = Subject::where('school_unit_id', $schoolId)->get();
        $classRooms = ClassRoom::where('school_unit_id', $schoolId)->get();
        
        // Get grades
        $grades = Grade::where('school_unit_id', $schoolId)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->when($subjectId, function($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            })
            ->with(['gradeComponent', 'subject', 'student'])
            ->get();
        
        // Statistics per subject
        $statistics = [];
        $bySubject = $grades->groupBy('subject_id');
        
        foreach ($bySubject as $subjId => $subjectGrades) {
            $scores = $subjectGrades->pluck('score');
            $statistics[$subjId] = [
                'count' => $scores->count(),
                'avg' => round($scores->avg(), 2),
                'min' => $scores->min(),
                'max' => $scores->max(),
                'median' => round($scores->sort()->median(), 2),
                'std_dev' => round($this->calculateStdDev($scores), 2),
            ];
        }
        
        // Statistics per class
        $byClass = $grades->groupBy('class_room_id');
        $classStats = [];
        foreach ($byClass as $classId => $classGrades) {
            $scores = $classGrades->pluck('score');
            $classStats[$classId] = [
                'count' => $scores->count(),
                'avg' => round($scores->avg(), 2),
                'min' => $scores->min(),
                'max' => $scores->max(),
            ];
        }
        
        $selectedYearId = $academicYearId;
        $selectedSubject = $subjectId;
        
        return view('yayasan.penilaian.analisis', compact(
            'subjects',
            'classRooms',
            'academicYears',
            'selectedYearId',
            'selectedSubject',
            'statistics',
            'classStats',
            'schoolSlug'
        ));
    }

    /**
     * Calculate standard deviation
     */
    private function calculateStdDev($scores)
    {
        if ($scores->count() < 2) return 0;
        $mean = $scores->avg();
        $squaredDiffs = $scores->map(fn($s) => pow($s - $mean, 2));
        return sqrt($squaredDiffs->sum() / $scores->count());
    }

    /**
     * Export Raport to PDF
     */
    public function exportRaport(Request $request, Student $student)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        // Ensure student belongs to user's school
        if ($student->school_unit_id != $schoolId) {
            abort(403);
        }
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : AcademicYear::where('is_active', true)->first()?->id;
        
        // Get grades
        $grades = Grade::with(['gradeComponent', 'subject', 'academicYear'])
            ->where('student_id', $student->id)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->get()
            ->groupBy('subject_id');
        
        // Calculate averages
        $averages = [];
        foreach ($grades as $subjectId => $subjectGrades) {
            $byType = $subjectGrades->groupBy('gradeComponent.type');
            $avg = $subjectGrades->avg('score');
            $averages[$subjectId] = [
                'avg' => round($avg, 2),
                'by_type' => $byType->map(fn($g) => round($g->avg('score'), 2))
            ];
        }
        
        $academicYear = AcademicYear::find($academicYearId);
        
        // Return view for PDF (using dompdf or similar)
        return view('yayasan.penilaian.raport-pdf', compact(
            'student',
            'grades',
            'averages',
            'academicYear'
        ));
    }

    /**
     * Import Grades from Excel
     */
    public function importGrades(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        $schoolSlug = $user->schoolUnit?->slug;
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);
        
        // Process Excel file
        $file = $request->file('file');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        $imported = 0;
        $errors = [];
        
        // Skip header row
        array_shift($rows);
        
        foreach ($rows as $index => $row) {
            if (empty($row[0])) continue;
            
            try {
                $nis = $row[0];
                $subjectId = $row[1];
                $score = $row[2];
                $type = $row[3] ?? 'daily';
                
                $student = Student::where('school_unit_id', $schoolId)
                    ->where('nis', $nis)
                    ->first();
                
                if (!$student) {
                    $errors[] = "Siswa dengan NIS $nis tidak ditemukan";
                    continue;
                }
                
                // Find or create grade component
                $component = GradeComponent::where('school_unit_id', $schoolId)
                    ->where('subject_id', $subjectId)
                    ->where('type', $type)
                    ->first();
                
                if (!$component) {
                    $errors[] = "Komponen nilai tidak ditemukan untuk NIS $nis";
                    continue;
                }
                
                Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'grade_component_id' => $component->id,
                        'subject_id' => $subjectId,
                    ],
                    [
                        'school_unit_id' => $schoolId,
                        'class_room_id' => $student->class_room_id,
                        'academic_year_id' => $component->academic_year_id,
                        'score' => $score,
                        'entered_by' => $user->id,
                    ]
                );
                
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Error pada baris " . ($index + 2) . ": " . $e->getMessage();
            }
        }
        
        return redirect()->route('tenant.school.grades.index')
            ->with('success', "Berhasil import $imported nilai.")
            ->with('errors', $errors);
    }

    /**
     * Export Grades to Excel
     */
    public function exportGrades(Request $request)
    {
        $user = auth()->user();
        $schoolId = $user->school_unit_id;
        
        $academicYearId = $request->filled('academic_year') 
            ? $request->academic_year 
            : AcademicYear::where('is_active', true)->first()?->id;
        
        $classRoomId = $request->filled('class_room') ? $request->class_room : null;
        
        $grades = Grade::where('school_unit_id', $schoolId)
            ->when($academicYearId, function($q) use ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            })
            ->when($classRoomId, function($q) use ($classRoomId) {
                $q->where('class_room_id', $classRoomId);
            })
            ->with(['student', 'subject', 'gradeComponent'])
            ->get();
        
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'NIS');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Mata Pelajaran');
        $sheet->setCellValue('D1', 'Jenis Nilai');
        $sheet->setCellValue('E1', 'Nilai');
        
        $row = 2;
        foreach ($grades as $grade) {
            $sheet->setCellValue('A' . $row, $grade->student?->nis);
            $sheet->setCellValue('B' . $row, $grade->student?->name);
            $sheet->setCellValue('C' . $row, $grade->subject?->name);
            $sheet->setCellValue('D' . $row, $grade->gradeComponent?->type);
            $sheet->setCellValue('E' . $row, $grade->score);
            $row++;
        }
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'nilai-export-' . date('YmdHis') . '.xlsx';
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }
}
