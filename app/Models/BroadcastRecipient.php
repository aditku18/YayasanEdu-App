<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BroadcastRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'broadcast_id',
        'foundation_id',
        'status',
        'sent_at',
        'read_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime'
    ];

    public function broadcast()
    {
        return $this->belongsTo(Broadcast::class);
    }

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }
}
