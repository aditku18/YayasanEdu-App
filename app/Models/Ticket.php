<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'foundation_id',
        'user_id',
        'subject',
        'description',
        'priority',
        'category',
        'status',
        'ticket_number',
        'resolution',
        'closed_at',
        'closed_by'
    ];

    protected $casts = [
        'closed_at' => 'datetime'
    ];

    public function foundation()
    {
        return $this->belongsTo(Foundation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }

    public function closer()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function isOpen()
    {
        return in_array($this->status, ['open', 'in_progress']);
    }
}
