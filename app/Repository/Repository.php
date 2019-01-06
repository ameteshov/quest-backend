<?php

namespace App\Repository;

use App\Support\Interfaces\ModelRepositoryInterface;
use App\Support\Traits\SearchTrait;
use Illuminate\Database\Eloquent\Builder;

class Repository implements ModelRepositoryInterface
{
    use SearchTrait;

    protected $model;

    public function find(int $id, ?array $with = []): array
    {
        $entity = $this->first(['id' => $id], $with);

        return empty($entity) ? [] : $entity->toArray();
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

    public function create(array $entityData): array
    {
        $entity = new $this->model;

        return $this->persist($entity, $entityData, true);
    }

    public function update(int $id, array $data, $force = false): array
    {
        $entity = $this->first(['id' => $id]);

        return $this->persist($entity, $data, $force);
    }

    public function updateBy(array $where, array $data): array
    {
        $entity = $this->first($where);

        return $this->persist($entity, $data);
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
