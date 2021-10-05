<?php

namespace App\Util;

use App\Util\Contracts\HandlesTransactions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Repository
 *
 * @package App\Util
 */
abstract class Repository
{
    use HandlesTransactions;

    private ?object $filter = null;

    public function setFilter(object $filter): static
    {
        $this->filter = $filter;
        return $this;
    }

    public function getFilter(): ?object
    {
        return $this->filter;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->query()->get();
    }

    /**
     * @param array $conditions
     *
     * @return array<Builder>|Collection
     */
    public function findBy(array $conditions): Collection|array
    {
        return $this->conditionalQuery($conditions)->get();
    }

    public function findIn(string $column, array $values): Collection|array
    {
        return $this->query()->whereIn($column, $values)->get();
    }

    /**
     * @param array $conditions
     *
     * @return Model|Builder|null
     */
    public function findOneBy(array $conditions): Model|Builder|null
    {
        return $this->conditionalQuery($conditions)->first();
    }

    /**
     * @param int|null $perPage
     * @param array $columns
     * @param string $pageName
     * @param int|null $page
     *
     * @return LengthAwarePaginator
     */
    public function paginate(
        ?int $perPage = null,
        array $columns = ['*'],
        string $pageName = 'page',
        ?int $page = null
    ): LengthAwarePaginator {
        return $this->query()->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * @param mixed ...$select
     *
     * @return Builder
     */
    public function query(...$select): Builder
    {
        $query = $this->newModel()->newQuery();

        if ($select) {
            $query->select($select);
        }

        if ($this->getFilter()) {
            $this->filter($query);
        }

        return $query;
    }

    abstract protected function filter(Builder $query): void;

    /**
     * @param array $conditions
     * @param mixed ...$select
     *
     * @return Builder
     */
    protected function conditionalQuery(array $conditions, ...$select): Builder
    {
        $query = $this->query($select ?: '*');

        foreach ($conditions as $column => $value) {
            $query->where($column, '=', $value);
        }

        return $query;
    }

    /**
     * @return string
     */
    abstract protected function model(): string;

    /**
     * @param array $attributes
     *
     * @return Model
     */
    protected function newModel(array $attributes = []): Model
    {
        $modelClass = $this->model();
        return new $modelClass($attributes);
    }
}
