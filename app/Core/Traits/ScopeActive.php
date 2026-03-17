<?php

namespace App\Core\Traits;

/**
 * Trait ScopeActive
 * 
 * Provides common scope methods for filtering active/inactive records.
 * Use this trait in models that have is_active or status fields.
 */
trait ScopeActive
{
    /**
     * Scope a query to only include active records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to only include records with specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include pending records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include published records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('is_active', true);
    }

    /**
     * Scope a query to only include draft records.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to include records that are either active or have specific statuses.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $statuses
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatuses($query, array $statuses)
    {
        return $query->whereIn('status', $statuses);
    }

    /**
     * Scope a query to filter by active status column name.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $column
     * @param bool $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsActive($query, string $column = 'is_active', bool $value = true)
    {
        return $query->where($column, $value);
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
            return in_array($this->status, ['active', 'published', 'approved']);
        }
        
        return true;
    }

    /**
     * Check if the model is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return !$this->isActive();
    }

    /**
     * Check if the model has a specific status.
     *
     * @param string $status
     * @return bool
     */
    public function hasStatus(string $status): bool
    {
        return isset($this->status) && $this->status === $status;
    }

    /**
     * Activate the model.
     *
     * @return bool
     */
    public function activate(): bool
    {
        if (isset($this->is_active)) {
            return $this->update(['is_active' => true]);
        }
        
        if (isset($this->status)) {
            return $this->update(['status' => 'active']);
        }
        
        return false;
    }

    /**
     * Deactivate the model.
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        if (isset($this->is_active)) {
            return $this->update(['is_active' => false]);
        }
        
        if (isset($this->status)) {
            return $this->update(['status' => 'inactive']);
        }
        
        return false;
    }

    /**
     * Toggle the active status.
     *
     * @return bool
     */
    public function toggleActive(): bool
    {
        return $this->update(['is_active' => !$this->is_active]);
    }
}
