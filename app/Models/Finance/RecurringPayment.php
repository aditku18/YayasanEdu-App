<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringPayment extends Model
{
    protected $fillable = [
        'user_id',
        'payment_token_id',
        'invoice_template_id',
        'description',
        'amount',
        'frequency',
        'frequency_value',
        'next_charge_date',
        'last_charge_date',
        'end_date',
        'total_charges',
        'max_charges',
        'status',
        'last_gateway_response',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'next_charge_date' => 'date',
        'last_charge_date' => 'date',
        'end_date' => 'date',
        'last_gateway_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function paymentToken(): BelongsTo
    {
        return $this->belongsTo(PaymentToken::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPaused(): bool
    {
        return $this->status === 'paused';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isDue(): bool
    {
        return $this->next_charge_date && $this->next_charge_date->isPast();
    }

    public function hasReachedMaxCharges(): bool
    {
        return $this->max_charges && $this->total_charges >= $this->max_charges;
    }

    public function getFrequencyText(): string
    {
        $frequencies = [
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'quarterly' => 'Per Kuartal',
            'yearly' => 'Tahunan',
        ];

        $text = $frequencies[$this->frequency] ?? $this->frequency;
        
        if ($this->frequency_value > 1) {
            $text .= " (setiap {$this->frequency_value} " . 
                     ($this->frequency === 'daily' ? 'hari' : 
                      ($this->frequency === 'weekly' ? 'minggu' : 
                       ($this->frequency === 'monthly' ? 'bulan' : 'tahun'))) . ")";
        }
        
        return $text;
    }

    public function calculateNextChargeDate(): \Carbon\Carbon
    {
        $nextDate = $this->next_charge_date->copy();
        
        switch ($this->frequency) {
            case 'daily':
                $nextDate->addDays($this->frequency_value);
                break;
            case 'weekly':
                $nextDate->addWeeks($this->frequency_value);
                break;
            case 'monthly':
                $nextDate->addMonths($this->frequency_value);
                break;
            case 'quarterly':
                $nextDate->addQuarters($this->frequency_value);
                break;
            case 'yearly':
                $nextDate->addYears($this->frequency_value);
                break;
        }
        
        return $nextDate;
    }
}
