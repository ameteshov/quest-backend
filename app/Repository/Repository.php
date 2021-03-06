<?php

namespace App\Repository;

use App\Support\Interfaces\ModelRepositoryInterface;
use App\Support\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Builder;
use tests\Mockery\Adapter\Phpunit\EmptyTestCase;

class Repository implements ModelRepositoryInterface
{
    use SearchTrait;

    protected $model;

    public function find(int $id, ?array $with = []): array
    {
        $entity = $this->first(['id' => $id], $with);

        return empty($entity) ? [] : $entity->toArray();
    }

    public function get(array $with = []): array
    {
        $collection = $this->getQuery()
            ->with($with)
            ->get();

        return empty($collection) ? [] : $collection->toArray();
    }

    public function count(?array $where = [])
    {
        return $this->getQuery()
            ->where($where)
            ->count();
    }

    public function findBy(array $conditions, ?array $with = []): array
    {
        $entity = $this->first($conditions, $with);

        return empty($entity) ? [] : $entity->toArray();
    }

    public function create(array $entityData, $hydrationArray = true)
    {
        $entity = new $this->model;

        return $this->persist($entity, $entityData, true, $hydrationArray);
    }

    public function update(int $id, array $data, $force = false): array
    {
        $entity = $this->first(['id' => $id]);

        return $this->persist($entity, $data, $force);
    }

    public function updateBy(array $where, array $data, bool $force = false): array
    {
        $entity = $this->first($where);

        return $this->persist($entity, $data, $force);
    }

    public function updateMany(array $where, array $data): void
    {
        $this->getQuery()
            ->where($where)
            ->update($data);
    }

    public function delete($id): void
    {
        if (is_array($id)) {
            $this->getQuery()
                ->where($id)
                ->delete();

            return;
        }

        $this->getQuery()
            ->where(['id' => $id])
            ->delete();
    }

    public function exists(array $data): bool
    {
        return $this->getQuery()->where($data)->exists();
    }

    protected function setModel(string $modelClass): void
    {
        $this->model = $modelClass;
    }

    protected function getQuery(): Builder
    {
        return (new $this->model)->query();
    }

    protected function first(array $fields, ?array $with = [])
    {
        $query = $this->getQuery()->where($fields);

        if ([] !== $with) {
            $query->with($with);
        }

        return $query->first();
    }

    protected function persist($entity, $data, $force = false, $hydrationArray = true)
    {
        if ($force) {
            $entity->forceFill($data);
        } else {
            $entity->fill($data);
        }

        $entity->save();

        if ($hydrationArray) {
            return $entity->refresh()->toArray();
        }

        return $entity->refresh();
    }
}
