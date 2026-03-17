<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'foundation_id',
        'plan_id',
        'amount',
        'type',
        'status',
        'description',
        'payment_method',
        'transaction_id',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payment()
    {
        return $this->belongsTo(PlatformPayment::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
