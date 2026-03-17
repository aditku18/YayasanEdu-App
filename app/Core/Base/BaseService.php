<?php

namespace App\Core\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

/**
 * Base Service - Parent class for all services in the application
 * 
 * Provides common CRUD operations and query building
 * that can be inherited by domain-specific services.
 */
abstract class BaseService
{
    /**
     * The model instance
     *
     * @var Model
     */
    protected $model;

    /**
     * The relationships to eager load
     *
     * @var array
     */
    protected $relationships = [];

    /**
     * The default pagination limit
     *
     * @var int
     */
    protected $perPage = 15;

    /**
     * Create a new service instance.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     *
     * @param array $columns
     * @return Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query()->get($columns);
    }

    /**
     * Get all records with relationships
     *
     * @param array $relationships
     * @param array $columns
     * @return Collection
     */
    public function allWith(array $relationships, array $columns = ['*']): Collection
    {
        return $this->query()->with($relationships)->get($columns);
    }

    /**
     * Get paginated records
     *
     * @param int|null $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(?int $perPage = null, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?? $this->perPage;
        
        return $this->query()->paginate($perPage, $columns);
    }

    /**
     * Get paginated records with relationships
     *
     * @param array $relationships
     * @param int|null $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginateWith(array $relationships, ?int $perPage = null, array $columns = ['*']): LengthAwarePaginator
    {
        $perPage = $perPage ?? $this->perPage;
        
        return $this->query()->with($relationships)->paginate($perPage, $columns);
    }

    /**
     * Find a record by ID
     *
     * @param int|string $id
     * @param array $columns
     * @return Model|null
     */
    public function find($id, array $columns = ['*']): ?Model
    {
        return $this->query()->find($id, $columns);
    }

    /**
     * Find a record by ID with relationships
     *
     * @param int|string $id
     * @param array $relationships
     * @param array $columns
     * @return Model|null
     */
    public function findWith($id, array $relationships, array $columns = ['*']): ?Model
    {
        return $this->query()->with($relationships)->find($id, $columns);
    }

    /**
     * Find a record by a specific column
     *
     * @param string $column
     * @param mixed $value
     * @return Model|null
     */
    public function findBy(string $column, $value): ?Model
    {
        return $this->query()->where($column, $value)->first();
    }

    /**
     * Find multiple records by IDs
     *
     * @param array $ids
     * @param array $columns
     * @return Collection
     */
    public function findMany(array $ids, array $columns = ['*']): Collection
    {
        return $this->query()->find($ids, $columns);
    }

    /**
     * Find first record or create new
     *
     * @param array $attributes
     * @return Model
     */
    public function firstOrCreate(array $attributes): Model
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * Find first record or instantiate
     *
     * @param array $attributes
     * @return Model
     */
    public function firstOrNew(array $attributes): Model
    {
        return $this->model->firstOrNew($attributes);
    }

    /**
     * Get the first record or fail
     *
     * @param array $columns
     * @return Model
     */
    public function first(array $columns = ['*']): Model
    {
        return $this->query()->firstOrFail($columns);
    }

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record
     *
     * @param int|string $id
     * @param array $data
     * @return Model
     */
    public function update($id, array $data): Model
    {
        $record = $this->findOrFail($id);
        $record->update($data);
        
        return $record->fresh();
    }

    /**
     * Update multiple records
     *
     * @param array $ids
     * @param array $data
     * @return int
     */
    public function updateMany(array $ids, array $data): int
    {
        return $this->query()->whereIn('id', $ids)->update($data);
    }

    /**
     * Delete a record
     *
     * @param int|string $id
     * @return bool
     */
    public function delete($id): bool
    {
        $record = $this->findOrFail($id);
        
        return $record->delete();
    }

    /**
     * Delete multiple records
     *
     * @param array $ids
     * @return int
     */
    public function deleteMany(array $ids): int
    {
        return $this->query()->whereIn('id', $ids)->delete();
    }

    /**
     * Soft delete a record
     *
     * @param int|string $id
     * @return bool
     */
    public function softDelete($id): bool
    {
        $record = $this->findOrFail($id);
        
        return $record->delete();
    }

    /**
     * Restore a soft deleted record
     *
     * @param int|string $id
     * @return Model
     */
    public function restore($id): Model
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        $record->restore();
        
        return $record;
    }

    /**
     * Permanently delete a record
     *
     * @param int|string $id
     * @return bool
     */
    public function forceDelete($id): bool
    {
        $record = $this->model->withTrashed()->findOrFail($id);
        
        return $record->forceDelete();
    }

    /**
     * Find or fail
     *
     * @param int|string $id
     * @param array $columns
     * @return Model
     */
    public function findOrFail($id, array $columns = ['*']): Model
    {
        return $this->query()->findOrFail($id, $columns);
    }

    /**
     * Get count
     *
     * @return int
     */
    public function count(): int
    {
        return $this->query()->count();
    }

    /**
     * Check if record exists
     *
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    public function exists(string $column, $value): bool
    {
        return $this->query()->where($column, $value)->exists();
    }

    /**
     * Check if record doesn't exist
     *
     * @param string $column
     * @param mixed $value
     * @return bool
     */
    public function doesntExist(string $column, $value): bool
    {
        return !$this->exists($column, $value);
    }

    /**
     * Get model instance
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Set model instance
     *
     * @param Model $model
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
        
        return $this;
    }

    /**
     * Create a new query builder instance
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query()
    {
        $query = $this->model->newQuery();

        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        return $query;
    }

    /**
     * Set relationships to eager load
     *
     * @param array $relationships
     * @return $this
     */
    public function with(array $relationships)
    {
        $this->relationships = $relationships;
        
        return $this;
    }

    /**
     * Set pagination per page
     *
     * @param int $perPage
     * @return $this
     */
    public function perPage(int $perPage)
    {
        $this->perPage = $perPage;
        
        return $this;
    }

    /**
     * Apply custom scope
     *
     * @param callable $callback
     * @return Collection
     */
    public function applyScope(callable $callback): Collection
    {
        return $callback($this->query());
    }

    /**
     * Handle transaction
     *
     * @param callable $callback
     * @param string|null $message
     * @return mixed
     * @throws Exception
     */
    public function transaction(callable $callback, ?string $message = null)
    {
        return \DB::transaction($callback);
    }

    /**
     * Get latest records
     *
     * @param int $limit
     * @return Collection
     */
    public function latest(int $limit = 10): Collection
    {
        return $this->query()->latest()->limit($limit)->get();
    }

    /**
     * Get records between dates
     *
     * @param string $column
     * @param string $from
     * @param string $to
     * @return Collection
     */
    public function dateRange(string $column, string $from, string $to): Collection
    {
        return $this->query()->whereBetween($column, [$from, $to])->get();
    }
}
