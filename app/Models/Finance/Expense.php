<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'expense_number',
        'expense_category_id',
        'academic_year_id',
        'description',
        'amount',
        'expense_date',
        'payment_method',
        'vendor_name',
        'vendor_phone',
        'invoice_number',
        'receipt',
        'status',
        'approval_level',
        'requested_by',
        'approved_by',
        'approved_at',
        'approval_notes',
        'notes',
        'school_unit_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(\App\Models\AcademicYear::class, 'academic_year_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    public static function getStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'paid' => 'Dibayar',
        ];
    }

    public static function getApprovalLevels(): array
    {
        return [
            'staff' => 'Staff',
            'manager' => 'Manager',
            'director' => 'Direktur',
        ];
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'other' => 'Lainnya',
        ];
    }

    public static function generateExpenseNumber(): string
    {
        $prefix = 'EXP';
        $date = now()->format('Ym');
        $lastExpense = self::where('expense_number', 'like', "{$prefix}{$date}%")
            ->orderBy('expense_number', 'desc')
            ->first();

        if ($lastExpense) {
            $lastNumber = (int) substr($lastExpense->expense_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function approve(?int $approvedBy = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $approvedBy ?? auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);
    }

    public function reject(?int $approvedBy = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $approvedBy ?? auth()->id(),
            'approved_at' => now(),
            'approval_notes' => $notes,
        ]);
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => 'paid']);
    }
}
