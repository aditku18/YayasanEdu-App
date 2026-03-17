<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'student_id',
        'bill_type_id',
        'academic_year_id',
        'classroom_id',
        'month',
        'description',
        'amount',
        'discount',
        'final_amount',
        'paid_amount',
        'remaining_amount',
        'due_date',
        'status',
        'payment_method',
        'notes',
        'created_by',
        'school_unit_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Student::class, 'student_id');
    }

    public function billType(): BelongsTo
    {
        return $this->belongsTo(BillType::class, 'bill_type_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(\App\Models\AcademicYear::class, 'academic_year_id');
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(\App\Models\ClassRoom::class, 'classroom_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['unpaid', 'overdue']);
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('due_date', '<', now())
                  ->whereIn('status', ['unpaid', 'partial']);
            });
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    public function scopeForMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    public static function getStatuses(): array
    {
        return [
            'unpaid' => 'Belum Bayar',
            'partial' => 'Dibayar Sebagian',
            'paid' => 'Lunas',
            'overdue' => 'Jatuh Tempo',
            'cancelled' => 'Dibatalkan',
        ];
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'virtual_account' => 'Virtual Account',
            'qris' => 'QRIS',
            'other' => 'Lainnya',
        ];
    }

    public function updatePaymentStatus(): void
    {
        if ($this->paid_amount >= $this->final_amount) {
            $this->update(['status' => 'paid', 'remaining_amount' => 0]);
        } elseif ($this->paid_amount > 0) {
            $this->update(['status' => 'partial', 'remaining_amount' => $this->final_amount - $this->paid_amount]);
        }
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ym');
        $lastInvoice = self::where('invoice_number', 'like', "{$prefix}{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
