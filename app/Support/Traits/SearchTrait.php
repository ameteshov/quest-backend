<?php

namespace App\Support\Traits;

trait SearchTrait
{
    protected $searchQuery;
    protected $filters;
    protected $perPage;

    public function getSearchQuery(array $filters): self
    {
        $this->searchQuery = $this->getQuery();
        $this->filters = $filters;

        return $this;
    }

    public function filterBy(string $field, ?string $filterKey = null): self
    {
        $filterKey = $filterKey ?? $field;

        if (!empty($this->filters[$field])) {
            $this->searchQuery->where($field, $this->filters[$filterKey]);
        }

        return $this;
    }

    public function getResult(): array
    {
        if (array_has($this->filters, 'all')) {
            return [
                'data' => $this->getSearchResult()
            ];
        }

        return $this->getPaginatedSearchResult();
    }

    public function with(): self
    {
        if (array_has($this->filters, 'with')) {
            $this->searchQuery->with(array_get($this->filters, 'with', []));
        }

        return $this;
    }

    protected function getSearchResult(): array
    {
        $result = $this->searchQuery->get();

        return empty($result) ? [] : $result->toArray();
    }

    protected function getPaginatedSearchResult(): array
    {
        return $this->searchQuery
            ->paginate()
            ->toArray();
    }
}
