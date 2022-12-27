<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
	public function all(): array;

	public function count(): int;

	public function create(array $data): array;

	public function createMany(array $data): array;

	public function delete(): bool;

	public function deleteById(int|string $id): ?bool;

	public function deleteByIds(array $ids): int;

	public function first(): array;

	public function get(): array;

	public function getById(int|string $id): array;

	public function offset(int $offset): self;

    public function limit(int $limit): self;

	public function orderBy(string $column, string $value): self;

	// public function update(): bool;

    public function updateById(int|string $id, array $data): array;

    public function where(string|\Closure $column, ?string $operator, ?string $value = null, ?string $boolean): self;

    public function orWhere(string|\Closure $column, ?string $operator, ?string $value = null): self;

	public function whereIn(string $column, mixed $value): self;

	public function with(array|string $relations): self;

    public function reset(): self;
}
