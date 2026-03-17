<?php

namespace App\Modules\Student\Services;

use App\Core\Base\BaseService;
use App\Modules\Student\Models\Student;
use Illuminate\Support\Facades\DB;

/**
 * Student Service
 * 
 * Business logic for Student module.
 */
class StudentService extends BaseService
{
    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        $this->setModel(new Student());
    }

    /**
     * Create a new student.
     *
     * @param array $data
     * @return Student
     */
    public function create(array $data): Student
    {
        // Generate NIS if not provided
        if (empty($data['nis'])) {
            $data['nis'] = $this->generateNis($data['school_unit_id'] ?? null);
        }

        return DB::transaction(function () use ($data) {
            $student = $this->create($data);

            // Assign to classroom if provided
            if (!empty($data['classroom_id'])) {
                $student->classroom_id = $data['classroom_id'];
                $student->save();
            }

            return $student;
        });
    }

    /**
     * Import students from array.
     *
     * @param array $students
     * @param int|null $schoolUnitId
     * @return array
     */
    public function importFromArray(array $students, ?int $schoolUnitId = null): array
    {
        $created = 0;
        $updated = 0;
        $errors = [];

        foreach ($students as $index => $studentData) {
            try {
                // Check if student exists by NIS
                $existing = $this->model->where('nis', $studentData['nis'] ?? '')->first();

                if ($existing) {
                    // Update existing
                    $existing->update($studentData);
                    $updated++;
                } else {
                    // Create new
                    if ($schoolUnitId) {
                        $studentData['school_unit_id'] = $schoolUnitId;
                    }
                    $this->create($studentData);
                    $created++;
                }
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'errors' => $errors,
        ];
    }

    /**
     * Get students by school unit.
     *
     * @param int $schoolUnitId
     * @param array $relations
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getBySchoolUnit(int $schoolUnitId, array $relations = []): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->query()
            ->with($relations)
            ->where('school_unit_id', $schoolUnitId)
            ->paginate($this->perPage);
    }

    /**
     * Get students by classroom.
     *
     * @param int $classroomId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByClassroom(int $classroomId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where('classroom_id', $classroomId)
            ->orderBy('name')
            ->get();
    }

    /**
     * Search students.
     *
     * @param string $search
     * @param int|null $schoolUnitId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $search, ?int $schoolUnitId = null): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            })
            ->when($schoolUnitId, fn($q) => $q->where('school_unit_id', $schoolUnitId))
            ->limit(20)
            ->get();
    }

    /**
     * Get students with outstanding balance.
     *
     * @param int $schoolUnitId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getWithOutstandingBalance(int $schoolUnitId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->query()
            ->where('school_unit_id', $schoolUnitId)
            ->whereHas('invoices', function ($query) {
                $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
            })
            ->with(['invoices' => function ($query) {
                $query->whereIn('status', ['unpaid', 'partial', 'overdue']);
            }])
            ->get();
    }

    /**
     * Generate NIS number.
     *
     * @param int|null $schoolUnitId
     * @return string
     */
    protected function generateNis(?int $schoolUnitId): string
    {
        $prefix = $schoolUnitId ? str_pad($schoolUnitId, 3, '0', STR_PAD_LEFT) : '001';
        $year = date('y');
        
        $lastStudent = $this->model
            ->where('school_unit_id', $schoolUnitId)
            ->whereYear('created_at', date('Y'))
            ->orderByDesc('id')
            ->first();

        $sequence = $lastStudent ? (int)substr($lastStudent->nis, -4) + 1 : 1;
        
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Transfer student to another classroom.
     *
     * @param int $studentId
     * @param int $classroomId
     * @return Student
     */
    public function transferToClass(int $studentId, int $classroomId): Student
    {
        $student = $this->findOrFail($studentId);
        $student->update(['classroom_id' => $classroomId]);
        
        return $student->fresh();
    }

    /**
     * Activate student.
     *
     * @param int $studentId
     * @return Student
     */
    public function activate(int $studentId): Student
    {
        $student = $this->findOrFail($studentId);
        $student->update(['is_active' => true, 'status' => 'active']);
        
        return $student->fresh();
    }

    /**
     * Deactivate student (alumni/withdrawn).
     *
     * @param int $studentId
     * @param string $reason
     * @return Student
     */
    public function deactivate(int $studentId, string $reason = 'withdrawn'): Student
    {
        $student = $this->findOrFail($studentId);
        $student->update(['is_active' => false, 'status' => $reason]);
        
        return $student->fresh();
    }

    /**
     * Get student statistics.
     *
     * @param int $schoolUnitId
     * @return array
     */
    public function getStatistics(int $schoolUnitId): array
    {
        $total = $this->model->where('school_unit_id', $schoolUnitId)->count();
        $active = $this->model->where('school_unit_id', $schoolUnitId)->where('is_active', true)->count();
        $inactive = $total - $active;

        $byGender = $this->model->where('school_unit_id', $schoolUnitId)
            ->select('gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('gender')
            ->get();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'by_gender' => $byGender,
        ];
    }
}
