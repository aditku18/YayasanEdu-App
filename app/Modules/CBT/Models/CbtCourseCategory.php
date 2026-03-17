<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtCourseCategory extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_course_categories';
    
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'icon',
        'parent_id',
        'order_index'
    ];

    protected $casts = [
        'order_index' => 'integer'
    ];

    /**
     * Get the tenant that owns the category.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(CbtCourseCategory::class, 'parent_id');
    }

    /**
     * Get all subcategories.
     */
    public function subcategories(): HasMany
    {
        return $this->hasMany(CbtCourseCategory::class, 'parent_id')->orderBy('order_index');
    }

    /**
     * Get all courses in this category.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(CbtCourse::class, 'category_id');
    }

    /**
     * Get published courses count.
     */
    public function getPublishedCoursesCountAttribute(): int
    {
        return $this->courses()->published()->count();
    }

    /**
     * Generate unique slug.
     */
    public static function generateSlug($name, $tenantId = null)
    {
        $slug = \Str::slug($name);
        
        $query = self::where('slug', 'like', "{$slug}%");
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $count = $query->count();
        
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }
        
        return $slug;
    }

    /**
     * Check if category has subcategories.
     */
    public function hasSubcategories(): bool
    {
        return $this->subcategories()->count() > 0;
    }

    /**
     * Get all children (recursive).
     */
    public function getAllChildren(): array
    {
        $children = [];
        
        foreach ($this->subcategories as $subcategory) {
            $children[] = $subcategory;
            $children = array_merge($children, $subcategory->getAllChildren());
        }
        
        return $children;
    }

    /**
     * Scope a query to filter by tenant.
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope a query to get root categories (no parent).
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index')->orderBy('name');
    }
}
