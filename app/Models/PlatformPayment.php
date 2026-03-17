<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlatformPayment extends Model
{
    use HasFactory;

    protected $connection = 'central';

    protected $fillable = [
        'foundation_id',
        'subscription_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'paid_at',
        'gateway_response'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'gateway_response' => 'array'
    ];

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function isSuccessful()
    {
        return $this->status === 'success';
    }
}
