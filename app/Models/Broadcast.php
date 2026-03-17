<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'target_type',
        'send_at',
        'status',
        'is_urgent',
        'created_by',
        'sent_at'
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_urgent' => 'boolean'
    ];

    public function recipients()
    {
        return $this->hasMany(BroadcastRecipient::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled' && $this->send_at && $this->send_at->isFuture();
    }
}
