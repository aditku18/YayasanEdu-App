<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CashTransaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'type',
        'category',
        'reference_id',
        'reference_type',
        'amount',
        'transaction_date',
        'description',
        'payment_method',
        'bank_name',
        'account_number',
        'recipient_name',
        'attachment',
        'recorded_by',
        'school_unit_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'recorded_by');
    }

    public function schoolUnit(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SchoolUnit::class, 'school_unit_id');
    }

    public function scopeCashIn($query)
    {
        return $query->where('type', 'cash_in');
    }

    public function scopeCashOut($query)
    {
        return $query->where('type', 'cash_out');
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_unit_id', $schoolId);
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public static function getTypes(): array
    {
        return [
            'cash_in' => 'Kas Masuk',
            'cash_out' => 'Kas Keluar',
        ];
    }

    public static function getCategories(): array
    {
        return [
            // Kas Masuk
            'pembayaran_spp' => 'Pembayaran SPP',
            'pembayaran_tagihan' => 'Pembayaran Tagihan',
            'pembayaran_pendaftaran' => 'Pendaftaran',
            'dana_sekolah' => 'Dana Sekolah',
            'lainnya_masuk' => 'Lainnya',
            // Kas Keluar
            'gaji_tunjangan' => 'Gaji & Tunjangan',
            'atk' => 'Alat Tulis Kantor',
            'pemeliharaan' => 'Pemeliharaan',
            'utilitas' => 'Utilitas',
            'kegagalan_siswa' => 'KEGIATAN SISWA',
            'lainnya_keluar' => 'Lainnya',
        ];
    }

    public static function generateTransactionNumber(): string
    {
        $prefix = 'KAS';
        $date = now()->format('Ym');
        $lastTransaction = self::where('transaction_number', 'like', "{$prefix}{$date}%")
            ->orderBy('transaction_number', 'desc')
            ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public static function getPaymentMethods(): array
    {
        return [
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'other' => 'Lainnya',
        ];
    }
}
