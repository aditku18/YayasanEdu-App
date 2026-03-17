<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'description',
        'version',
        'category',
        'developer',
        'price',
        'is_available_in_marketplace',
        'status',
        'featured_label',
        'features',
        'requirements',
        'documentation_url'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available_in_marketplace' => 'boolean',
        'features' => 'array',
        'requirements' => 'array'
    ];

    public function installations()
    {
        return $this->hasMany(PluginInstallation::class);
    }

    // Reviews feature - temporarily disabled until PluginReview model is created
    // public function reviews()
    // {
    //     return $this->hasMany(PluginReview::class);
    // }

    // public function developer()
    // {
    //     return $this->belongsTo(User::class, 'developer_id');
    // }
}
