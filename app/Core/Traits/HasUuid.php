<?php

namespace App\Core\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasUuid
 * 
 * Provides UUID generation functionality for models.
 * Use this trait in models that need UUID as primary key.
 */
trait HasUuid
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the UUID key name.
     *
     * @return string
     */
    public function getUuidName(): string
    {
        return 'uuid';
    }

    /**
     * Get the route key name.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * Find model by UUID.
     *
     * @param string $uuid
     * @return Model|null
     */
    public static function findByUuid(string $uuid): ?self
    {
        return static::where('uuid', $uuid)->first();
    }

    /**
     * Find model by UUID or fail.
     *
     * @param string $uuid
     * @return Model
     */
    public static function findByUuidOrFail(string $uuid): self
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    /**
     * Generate a new UUID.
     *
     * @return string
     */
    public static function generateUuid(): string
    {
        return (string) Str::uuid();
    }

    /**
     * Check if a UUID exists.
     *
     * @param string $uuid
     * @return bool
     */
    public static function uuidExists(string $uuid): bool
    {
        return static::where('uuid', $uuid)->exists();
    }

    /**
     * Get the UUID attribute.
     *
     * @return string|null
     */
    public function getUuidAttribute(): ?string
    {
        return $this->attributes['uuid'] ?? null;
    }

    /**
     * Set the UUID attribute.
     *
     * @param string $value
     * @return void
     */
    public function setUuidAttribute(string $value): void
    {
        $this->attributes['uuid'] = $value;
    }
}
