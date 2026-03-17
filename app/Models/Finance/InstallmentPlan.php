<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallmentPlan extends Model
{
    protected $fillable = [
        'plan_number',
        'student_id',
        'invoice_id',
        'total_amount',
        'total_installments',
        'amount_per_installment',
        'first_due_date',
        'status',
        'notes',
        'created_by',
        'school_unit_id',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_per_installment' => 'decimal:2',
        'first_due_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(InstallmentPayment::class, 'installment_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    public static function getStatuses(): array
    {
        return [
            'active' => 'Aktif',
            'completed' => 'Lunas',
            'defaulted' => 'Melanggar',
            'cancelled' => 'Dibatalkan',
        ];
    }

    public static function generatePlanNumber(): string
    {
        $prefix = 'CICIL';
        $date = now()->format('Ym');
        $lastPlan = self::where('plan_number', 'like', "{$prefix}{$date}%")
            ->orderBy('plan_number', 'desc')
            ->first();

        if ($lastPlan) {
            $lastNumber = (int) substr($lastPlan->plan_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getTotalPaid(): float
    {
        return $this->payments()->where('status', 'paid')->sum('amount_paid');
    }

    public function getRemainingAmount(): float
    {
        return $this->total_amount - $this->getTotalPaid();
    }

    public function getNextInstallment(): ?InstallmentPayment
    {
        return $this->payments()
            ->where('status', 'pending')
            ->orderBy('installment_number')
            ->first();
    }

    public function checkCompletion(): void
    {
        if ($this->getTotalPaid() >= $this->total_amount) {
            $this->update(['status' => 'completed']);
        }
    }
}
