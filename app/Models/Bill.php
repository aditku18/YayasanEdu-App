<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'due_date',
        'vendor',
        'description',
        'status',
        'user_id',
        'tenant_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'paid' => 'green',
            'overdue' => 'red',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Dibayar',
            'overdue' => 'Terlambat',
            default => 'Tidak Diketahui',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeByVendor($query, $vendor)
    {
        return $query->where('vendor', 'like', "%{$vendor}%");
    }

    public function scopeDueBetween($query, $start, $end)
    {
        return $query->whereBetween('due_date', [$start, $end]);
    }

    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsOverdue()
    {
        $this->update(['status' => 'overdue']);
    }
}
