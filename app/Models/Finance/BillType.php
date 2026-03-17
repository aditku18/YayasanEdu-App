<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'default_amount',
        'is_active',
        'school_unit_id',
    ];

    protected $casts = [
        'default_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'bill_type_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    public static function getTypes(): array
    {
        return [
            'monthly' => 'Bulanan (SPP)',
            'one_time' => 'Sekali Bayar',
            'recurring' => 'Berulang',
        ];
    }
}
