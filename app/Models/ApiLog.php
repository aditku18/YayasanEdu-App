<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_integration_id',
        'endpoint',
        'method',
        'request_headers',
        'request_body',
        'response_code',
        'response_body',
        'status',
        'error_message',
        'duration_ms'
    ];

    protected $casts = [
        'request_headers' => 'array',
        'request_body' => 'array',
        'response_body' => 'array'
    ];

    public function apiIntegration()
    {
        return $this->belongsTo(ApiIntegration::class);
    }

    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
