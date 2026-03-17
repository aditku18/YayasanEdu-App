<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'group'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }

    public static function findByGroup($group)
    {
        return static::where('group', $group)->get();
    }

    public static function getGroups()
    {
        return static::distinct()->pluck('group')->filter()->sort();
    }
}
