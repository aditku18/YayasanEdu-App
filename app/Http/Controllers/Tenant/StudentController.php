<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SchoolUnit;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Get school ID from route or auth user.
     */
    private function getSchoolId(Request $request): ?int
    {
        // Check if school slug is provided in route
        $schoolSlug = $request->route('school');
        if ($schoolSlug) {
            $school = SchoolUnit::where('slug', $schoolSlug)->first();
            return $school?->id;
        }
        
        // Fall back to user's assigned school
        return auth()->user()->school_unit_id;
    }

    /**
     * Get school slug from route if available.
     */
    private function getSchoolSlug(Request $request): ?string
    {
        return $request->route('school');
    }

    /**
     * Get redirect route based on school slug.
     */
    private function getStudentIndexRoute(Request $request): string
    {
        $schoolSlug = $this->getSchoolSlug($request);
        if ($schoolSlug) {
            return 'tenant.school.students.index';
        }
        return 'tenant.students.index';
    }

    /**
     * Get redirect route with parameters.
     */
    private function getRedirectRoute(Request $request, string $routeName, $params = []): \Illuminate\Http\RedirectResponse
    {
        $schoolSlug = $this->getSchoolSlug($request);
        if ($schoolSlug) {
            return redirect()->route($routeName, array_merge(['school' => $schoolSlug], $params));
        }
        return redirect()->route($routeName, $params);
    }

    public function index(Request $request)
    {
        $schoolId = $this->getSchoolId($request);
        $schoolSlug = $this->getSchoolSlug($request);
        
        // If no school found, return empty
        if (!$schoolId) {
            return view('yayasan.siswa', [
                'students' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20),
                'schoolSlug' => null,
                'maleCount' => 0,
                'femaleCount' => 0
            ]);
        }
        
        $query = Student::with('school', 'classRoom')->where('school_id', $schoolId);

        $students = $query->paginate(20);
        
        // Get counts
        $maleCount = Student::where('school_id', $schoolId)->where('gender', 'L')->count();
        $femaleCount = Student::where('school_id', $schoolId)->where('gender', 'P')->count();
        
        return view('yayasan.siswa', compact('students', 'schoolSlug', 'maleCount', 'femaleCount'));
    }

    /**
     * Download a simple CSV template for bulk student import.
     */
    public function downloadTemplate()
    {
        // Column keys for the CSV
        $keys = ['nik','name','nis','nisn','gender','birth_place','birth_date',
                 'address','father_name','mother_name','guardian_name',
                 'parent_name','parent_phone','status'];

        // Placeholder values (baris placeholder - akan dilewati saat import)
        $placeholder_row = [
            '(16 digit NIK KTP/KK)',
            '(Nama sesuai akta - WAJIB)',
            '(Nomor Induk Siswa lokal)',
            '(Nomor Induk Siswa Nasional)',
            '(L atau P)',
            '(Kota/Kabupaten)',
            '(Format: YYYY-MM-DD, contoh: 2008-05-20)',
            '(Jalan, RT/RW, Kelurahan, Kecamatan)',
            '(Nama lengkap ayah)',
            '(Nama lengkap ibu)',
            '(Nama wali jika bukan orang tua)',
            '(Nama kontak utama yang bisa dihubungi)',
            '(Format: 08xx-xxxx-xxxx)',
            '(Aktif / Lulus / Pindah / Drop Out)',
        ];

        // Example data (baris contoh - akan dilewati saat import)
        $example = [
            '3201234567890001',
            'Ahmad Fauzi Ramadhan',
            '2024001',
            '0123456789',
            'L',
            'Bandung',
            '2008-05-20',
            'Jl. Merdeka No. 10, RT 02/RW 03, Kel. Sukajadi, Kec. Sukajadi',
            'Fauzi Hidayat',
            'Siti Rahayu',
            '',
            'Fauzi Hidayat',
            '081234567890',
            'Aktif',
        ];

        $filename = 'template_import_siswa.csv';
        $httpHeaders = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($keys, $placeholder_row, $example) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM untuk Excel UTF-8
            fputcsv($file, $keys, ',', '"');           // Row 1: column headers
            fputcsv($file, $placeholder_row, ',', '"'); // Row 2: placeholders
            fputcsv($file, $example, ',', '"');         // Row 3: example data
            // Row 4+: blank rows for user to fill
            fputcsv($file, array_fill(0, 14, ''));
            fputcsv($file, array_fill(0, 14, ''));
            fputcsv($file, array_fill(0, 14, ''));
            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $httpHeaders);
    }

    /**
     * Handle uploaded CSV file for importing student data.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $file = $request->file('file');
        // Validate extension manually
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['csv', 'txt'])) {
            return back()->withErrors(['file' => 'Format file tidak didukung. Gunakan file .csv']);
        }

        $path = $file->getRealPath();

        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->withErrors(['file' => 'Gagal membaca file.']);
        }

        // Read header row
        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->withErrors(['file' => 'File kosong atau format tidak valid.']);
        }

        // Normalize header: trim + lowercase + strip BOM
        $header = array_map(function($h) {
            // Remove UTF-8 BOM if present on first column
            $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
            return strtolower(trim($h));
        }, $header);

        $firstData = null; // will be set to first real data row

        $schoolId  = auth()->user()->school_unit_id;
        
        // Debug: Check if school_id exists
        if (empty($schoolId)) {
            return back()->withErrors(['file' => 'Error: User tidak memiliki school_unit_id. Silakan hubungi administrator.']);
        }
        
        $imported  = 0;
        $skipped   = 0;
        $errors    = [];
        $rowNumber = 2; // header is row 1

        // Cache classroom name → id map for this school
        $classroomMap = \App\Models\ClassRoom::where('school_id', $schoolId)
            ->pluck('id', 'name')
            ->mapWithKeys(fn($id, $name) => [strtolower(trim($name)) => $id])
            ->toArray();

        $allowedColumns = [
            'nik', 'name', 'nis', 'nisn', 'gender',
            'birth_place', 'birth_date', 'address',
            'father_name', 'mother_name', 'guardian_name',
            'parent_name', 'parent_phone', 'status',
        ];

        $processRow = function (array $row) use (
            $header, $allowedColumns, $schoolId, $classroomMap,
            &$imported, &$skipped, &$errors, &$rowNumber
        ) {
            $data = [];
            foreach ($header as $i => $col) {
                if (in_array($col, $allowedColumns)) {
                    $data[$col] = isset($row[$i]) ? trim($row[$i]) : null;
                }
            }

            // Require at least a name
            if (empty($data['name'])) {
                $skipped++;
                $rowNumber++;
                return;
            }

            // Handle classroom_name → classroom_id
            $classNameIdx = array_search('classroom_name', $header);
            if ($classNameIdx !== false) {
                $rawClass = strtolower(trim($row[$classNameIdx] ?? ''));
                if ($rawClass !== '' && isset($classroomMap[$rawClass])) {
                    $data['classroom_id'] = $classroomMap[$rawClass];
                }
            }

            // Default status
            if (empty($data['status'])) {
                $data['status'] = 'Aktif';
            }

            // Validate gender
            if (!empty($data['gender']) && !in_array(strtoupper($data['gender']), ['L', 'P'])) {
                $errors[] = "Baris {$rowNumber}: gender tidak valid ({$data['gender']}).";
                $skipped++;
                $rowNumber++;
                return;
            }
            if (!empty($data['gender'])) {
                $data['gender'] = strtoupper($data['gender']);
            } else {
                $data['gender'] = 'L';
            }

            // Blank to null
            foreach ($data as $k => $v) {
                if ($v === '') $data[$k] = null;
            }

            $data['school_id'] = $schoolId;

            try {
                Student::create($data);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                $skipped++;
            }
            $rowNumber++;
        };

        // Process all data rows; skip empty rows and placeholder rows (text dalam tanda kurung)
        while (($row = fgetcsv($handle)) !== false) {
            // Skip completely empty rows
            if (count(array_filter($row, fn($v) => trim($v) !== '')) === 0) {
                $rowNumber++;
                continue;
            }
            
            // Skip placeholder rows - check if any cell starts with '(' (placeholder pattern)
            $hasPlaceholder = false;
            foreach ($row as $cell) {
                if (trim($cell) !== '' && str_starts_with(trim($cell), '(')) {
                    $hasPlaceholder = true;
                    break;
                }
            }
            if ($hasPlaceholder) {
                $rowNumber++;
                continue;
            }
            
            $processRow($row);
        }

        fclose($handle);

        $message = "Import selesai: {$imported} siswa berhasil ditambahkan.";
        if ($skipped > 0) {
            $message .= " {$skipped} baris dilewati (tidak ada nama atau data tidak valid).";
        }

        if (!empty($errors)) {
            return back()->with('error', $message)->withErrors(['import' => implode(' | ', array_slice($errors, 0, 5))]);
        }

        return back()->with('success', $message);
    }

    /**
     * Show form to manually add a student.
     */
    public function create(Request $request)
    {
        $schoolId = $this->getSchoolId($request);
        $schoolSlug = $this->getSchoolSlug($request);
        
        if (!$schoolId) {
            return back()->with('error', 'Tidak dapat menemukan unit sekolah.');
        }
        
        $classrooms = \App\Models\ClassRoom::where('school_id', $schoolId)->get();
        return view('yayasan.students.create', compact('classrooms', 'schoolSlug'));
    }

    /**
     * Store newly created student.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'nullable|string|max:100',
            'name' => 'required|string|max:255',
            'nis' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:50',
            'gender' => 'required|in:L,P',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'parent_name' => 'nullable|string|max:255',
            'parent_phone' => 'nullable|string|max:20',
            'classroom_id' => 'nullable|exists:class_rooms,id',
            'status' => 'required|string',
        ]);

        $validated['school_id'] = $this->getSchoolId($request);
        Student::create($validated);

        return $this->getRedirectRoute($request, 'tenant.students.index')->with('success', 'Siswa berhasil ditambah.');
    }

    public function show(Request $request, $student)
    {
        // Handle both Student model instance and string ID
        if (!$student instanceof Student) {
            $student = Student::findOrFail($student);
        }
        
        $schoolSlug = $this->getSchoolSlug($request);
        $student->load('school');
        return view('yayasan.students.show', compact('student', 'schoolSlug'));
    }

    public function edit(Request $request, $student)
    {
        // Handle both Student model instance and string ID
        if (!$student instanceof Student) {
            $student = Student::findOrFail($student);
        }
        
        $schoolSlug = $this->getSchoolSlug($request);
        $schoolId = auth()->user()->school_unit_id;
        $classrooms = \App\Models\ClassRoom::where('school_id', $schoolId)->get();
        return view('yayasan.students.edit', compact('student', 'classrooms', 'schoolSlug'));
    }

    public function update(Request $request, $student)
    {
        // Handle both Student model instance and string ID
        if (!$student instanceof Student) {
            $student = Student::findOrFail($student);
        }
        $validated = $request->validate([
            'nik'           => 'nullable|string|max:100',
            'name'          => 'required|string|max:255',
            'nis'           => 'nullable|string|max:50',
            'nisn'          => 'nullable|string|max:50',
            'gender'        => 'required|in:L,P',
            'birth_place'   => 'nullable|string|max:100',
            'birth_date'    => 'nullable|date',
            'address'       => 'nullable|string',
            'father_name'   => 'nullable|string|max:255',
            'mother_name'   => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'parent_name'   => 'nullable|string|max:255',
            'parent_phone'  => 'nullable|string|max:20',
            'classroom_id'  => 'nullable|exists:class_rooms,id',
            'status'        => 'required|string',
        ]);

        $student->update($validated);

        return $this->getRedirectRoute($request, 'tenant.students.show', $student)->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Request $request, $student)
    {
        // Handle both Student model instance and string ID
        if (!$student instanceof Student) {
            $student = Student::findOrFail($student);
        }
        
        $student->delete();
        return $this->getRedirectRoute($request, 'tenant.students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
