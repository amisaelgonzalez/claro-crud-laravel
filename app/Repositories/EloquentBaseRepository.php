<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentBaseRepository implements EloquentBaseRepositoryInterface
{
	/**
	 * The repository model
	 */
	protected Model $model;

	/**
	 * The query builder
	 */
	protected Builder $query;

    /**
     * Alias for the query offset
     */
    protected ?int $offset;

	/**
	 * Alias for the query limit
	 */
	protected ?int $take;

	/**
	 * Array of related models to eager load
	 */
	protected array $with = [];

	/**
	 * Array of one or more where clause parameters
	 */
	protected array $wheres = [];

	/**
	 * Array of one or more where in clause parameters
	 */
	protected array $whereIns = [];

	/**
	 * Array of one or more ORDER BY column/value pairs
	 */
	protected array $orderBys = [];

	/**
	 * Array of scope methods to call on the model
	 */
	protected array $scopes = [];

	/**
	 * Get all the model records in the database
	 */
	public function all(): array
	{
		$this->newQuery()->eagerLoad();

		$models = $this->query->get();

		// $this->unsetClauses();

		return $models->toArray();
	}

	/**
	 * Count the number of specified model records in the database
	 */
	public function count(): int
	{
		return collect($this->get())->count();
	}

	/**
	 * Create a new model record in the database
	 */
	public function create(array $data): array
	{
		// $this->unsetClauses();

		return $this->model->create($data)->toArray();
	}

	/**
	 * Create one or more new model records in the database
	 */
	public function createMany(array $data): array
	{
		$models = new Collection();

		foreach($data as $d) {
			$models->push($this->create($d));
		}

		return $models->toArray();
	}

	/**
	 * Delete one or more model records from the database
	 */
	public function delete(): bool
	{
		$this->newQuery()->setClauses()->setScopes();

		$result = $this->query->delete();

		// $this->unsetClauses();

		return $result;
	}

	/**
	 * Delete the specified model record from the database
	 *
	 * @throws \Exception
	 */
	public function deleteById(int|string $id): ?bool
	{
		// $this->unsetClauses();

        $this->newQuery()->eagerLoad();

		$model = $this->query->findOrFail($id);

		return $model->delete();
	}

	/**
	 * Delete multiple records
	 */
	public function deleteByIds(array $ids): int
	{
		return $this->model->destroy($ids);
	}

	/**
	 * Get the first specified model record from the database
	 */
	public function first(): array
	{
		$this->newQuery()->eagerLoad()->setClauses()->setScopes();

		$model = $this->query->firstOrFail();

		// $this->unsetClauses();

		return $model->toArray();
	}

	/**
	 * Get all the specified model records in the database
	 */
	public function get(): array
	{
		$this->newQuery()->eagerLoad()->setClauses()->setScopes();

		$models = $this->query->get();

		// $this->unsetClauses();

		return $models->toArray();
	}

	/**
	 * Get the specified model record from the database
	 */
	public function getById(int|string $id): array
	{
		// $this->unsetClauses();

		$this->newQuery()->eagerLoad();

		return $this->query->findOrFail($id)->toArray();
	}

	/**
	 * Set the query offset
	 */
	public function offset(int $offset): self
	{
		$this->offset = $offset;

		return $this;
	}

	/**
	 * Set the query limit
	 */
	public function limit(int $limit): self
	{
		$this->take = $limit;

		return $this;
	}

	/**
	 * Set an ORDER BY clause
	 */
	public function orderBy(string $column, string $direction = 'asc'): self
	{
		$this->orderBys[] = compact('column', 'direction');

		return $this;
	}

	/**
	 * Update the specified model record in the database
	 */
	public function updateById(int|string $id, array $data): array
	{
		// $this->unsetClauses();

        $this->newQuery()->eagerLoad();

		$model = $this->query->findOrFail($id);

        $model->update($data);

		return $model->toArray();
	}

	/**
	 * Add a simple where clause to the query
	 */
	public function where(string|\Closure $column, ?string $operator = null, ?string $value = null, ?string $boolean = 'and'): self
	{
        if (is_string($column) && func_num_args() === 2) {
            $value      = $operator;
            $operator   = '=';
        }

		$this->wheres[] = compact('column', 'value', 'operator', 'boolean');

		return $this;
	}

    /**
	 * Add a simple or where clause to the query
	 */
    public function orWhere(string|\Closure $column, ?string $operator = null, ?string $value = null): self
    {
        if (func_num_args() === 2) {
            $value      = $operator;
            $operator   = '=';
        }

        return $this->where($column, $operator, $value, 'or');
    }

	/**
	 * Add a simple where in clause to the query
	 */
	public function whereIn(string $column, mixed $values): self
	{
		$values = is_array($values) ? $values : array($values);

		$this->whereIns[] = compact('column', 'values');

		return $this;
	}

	/**
	 * Set Eloquent relationships to eager load
	 */
	public function with(array|string $relations): self
	{
		if (is_string($relations)) $relations = func_get_args();

		$this->with = $relations;

		return $this;
	}

    /**
	 * Reset the query clause parameter arrays
	 */
	public function reset(): self
	{
        return $this->unsetClauses();
	}

	/**
	 * Create a new instance of the model's query builder
	 */
	protected function newQuery(): self
	{
		$this->query = $this->model->newQuery();

		return $this;
	}

	/**
	 * Add relationships to the query builder to eager load
	 */
	protected function eagerLoad(): self
	{
		foreach($this->with as $relation) {
			$this->query->with($relation);
		}

		return $this;
	}

	/**
	 * Set clauses on the query builder
	 */
	protected function setClauses(): self
	{
		foreach($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value'], $where['boolean']);
		}

		foreach($this->whereIns as $whereIn) {
			$this->query->whereIn($whereIn['column'], $whereIn['values']);
		}

		foreach($this->orderBys as $orders) {
			$this->query->orderBy($orders['column'], $orders['direction']);
		}

		if(isset($this->take) and ! is_null($this->take)) {
			$this->query->take($this->take);
		}

		return $this;
	}

	/**
	 * Set query scopes
	 */
	protected function setScopes(): self
	{
		foreach($this->scopes as $method => $args) {
			$this->query->$method(implode(', ', $args));
		}

		return $this;
	}

	/**
	 * Reset the query clause parameter arrays
	 */
	protected function unsetClauses(): self
	{
		$this->wheres   = [];
		$this->whereIns = [];
		$this->scopes   = [];
		$this->take     = null;

		return $this;
	}
}
