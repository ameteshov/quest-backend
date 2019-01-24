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
            ->first();

        return (null === $result) ? null : $result->toArray();
    }

    protected function getSearchHashQueryCallback($hash)
    {
        return function ($query) use ($hash) {
            $query->where('is_passed', false)
                ->where('access_hash', $hash)
                ->whereRaw('DATE_ADD(expired_at, INTERVAL ? HOUR) > now()', [config('defaults.forms.ttl')]);
        };
    }
}
