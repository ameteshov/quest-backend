<?php

namespace App\Repository;

use App\Model\Questionnaire;

class QuestionnaireRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(Questionnaire::class);
    }

    public function search(?array $filters = [])
    {
        return $this->getSearchQuery($filters)
            ->with()
            ->getResult();
    }

    public function addRecipient($id, $data)
    {
        $entity = $this->first(['id' => $id]);

        $entity->results()->create($data);
    }

    public function findByHash(string $hash)
    {
        $result = $this->getQuery()
            ->whereHas('results', $this->getSearchHashQueryCallback($hash))
            ->with(['results' => $this->getResultFilterCallback($hash)])
            ->first();

        return (null === $result) ? null : $result->toArray();
    }

    public function isAvailableForRecipient(string $hash)
    {
        return $this->getQuery()
            ->whereHas('results', $this->getSearchHashQueryCallback($hash))
            ->exists();
    }

    protected function getSearchHashQueryCallback(string $hash)
    {
        return function ($query) use ($hash) {
            $query->where('is_passed', false)
                ->where('access_hash', $hash)
                ->whereRaw('DATE_ADD(expired_at, INTERVAL ? HOUR) > now()', [config('defaults.forms.ttl')]);
        };
    }

    protected function getResultFilterCallback(string $hash)
    {
        return function ($query) use ($hash) {
            $query->where('access_hash', $hash);
        };
    }
}
