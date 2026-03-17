<?php

namespace App\Core\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Core\Traits\ScopeActive;

/**
 * Base Model - Parent class for all models in the application
 * 
 * Provides common functionality:
 * - UUID support
 * - Active scope
 * - Common date casting
 * - Soft deletes support
 */
abstract class BaseModel extends Model
{
    use HasUuids, ScopeActive;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        // Add common hidden attributes here
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        // Default to UUID if the model has uuid column
        if (in_array('uuid', $this->getFillable())) {
            return 'uuid';
        }
        
        return parent::getRouteKeyName();
    }

    /**
     * Scope a query to only include active records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $column
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query, string $column = 'is_active', $value = true)
    {
        return $query->where($column, $value);
    }

    /**
     * Scope a query to order by latest.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order by oldest.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Check if the model has a specific relationship loaded.
     *
     * @param string $relation
     * @return bool
     */
    public function hasRelation(string $relation): bool
    {
        return $this->relationLoaded($relation);
    }

    /**
     * Get the model's relationships.
     *
     * @return array
     */
    public function getRelationshipNames(): array
    {
        $methods = get_class_methods($this);
        $relations = [];

        foreach ($methods as $method) {
            if (method_exists($this, $method) && 
                !$this->$method() instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                continue;
            }
            $relations[] = $method;
        }

        return $relations;
    }

    /**
     * Fill the model with an array of attributes but check for fillable first.
     *
     * @param array $attributes
     * @return $this
     */
    public function fillIfAllowed(array $attributes)
    {
        $fillable = $this->getFillable();
        $allowed = array_intersect_key($attributes, array_flip($fillable));
        
        return parent::fill($allowed);
    }

    /**
     * Get the display name for the model.
     * Override in child models for custom display names.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->title ?? 'Unnamed';
    }

    /**
     * Check if the model is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        if (isset($this->is_active)) {
            return (bool) $this->is_active;
        }
        
        if (isset($this->status)) {
            return $this->status === 'active';
        }
        
        return true;
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->isActive() ? 'Active' : 'Inactive';
    }

    /**
     * Boot the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // Add global scopes or observers here if needed
    }
}
