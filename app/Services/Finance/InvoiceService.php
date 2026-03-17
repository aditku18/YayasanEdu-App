<?php

namespace App\Services\Finance;

use App\Models\Finance\BillType;
use App\Models\Finance\Invoice;
use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class InvoiceService
{
    public function listInvoicesForSchool(int $schoolId, array $filters = []): LengthAwarePaginator
    {
        $query = Invoice::forSchool($schoolId)->with(['student', 'billType', 'academicYear']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['bill_type_id'])) {
            $query->where('bill_type_id', $filters['bill_type_id']);
        }

        if (!empty($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }

    public function getBillTypesForSchool(int $schoolId): Collection
    {
        return BillType::forSchool($schoolId)->active()->get();
    }

    public function createSingleInvoice(int $schoolId, array $data): Invoice
    {
        $finalAmount = $data['amount'] - ($data['discount'] ?? 0);

        return Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'student_id' => $data['student_id'],
            'bill_type_id' => $data['bill_type_id'],
            'academic_year_id' => $data['academic_year_id'] ?? null,
            'month' => $data['month'] ?? null,
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'discount' => $data['discount'] ?? 0,
            'final_amount' => $finalAmount,
            'remaining_amount' => $finalAmount,
            'due_date' => $data['due_date'] ?? null,
            'status' => 'unpaid',
            'created_by' => Auth::id(),
            'school_unit_id' => $schoolId,
        ]);
    }

    public function generateMassInvoices(
        int $schoolId,
        int $billTypeId,
        string $month,
        ?int $academicYearId = null,
        ?string $dueDate = null,
        ?int $classroomId = null
    ): int {
        $billType = BillType::findOrFail($billTypeId);

        $studentsQuery = Student::forSchool($schoolId)->where('status', 'active');

        if ($classroomId) {
            $studentsQuery->where('classroom_id', $classroomId);
        }

        $students = $studentsQuery->get();

        $createdCount = 0;

        foreach ($students as $student) {
            $exists = Invoice::where('student_id', $student->id)
                ->where('bill_type_id', $billType->id)
                ->where('month', $month)
                ->exists();

            if ($exists) {
                continue;
            }

            Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'student_id' => $student->id,
                'bill_type_id' => $billType->id,
                'academic_year_id' => $academicYearId,
                'month' => $month,
                'amount' => $billType->default_amount ?? 0,
                'discount' => 0,
                'final_amount' => $billType->default_amount ?? 0,
                'remaining_amount' => $billType->default_amount ?? 0,
                'due_date' => $dueDate,
                'status' => 'unpaid',
                'created_by' => Auth::id(),
                'school_unit_id' => $schoolId,
            ]);

            $createdCount++;
        }

        return $createdCount;
    }
}

