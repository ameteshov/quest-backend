<?php

namespace App\Service;

class Service
{
    protected $repository;

    public function __call($name, $arguments)
    {
        if (method_exists($this->repository, $name)) {
            return call_user_func_array([$this->repository, $name], $arguments);
        }

        throw new \BadMethodCallException("Method {$name} not found in {$this->repository}");
    }

    protected function setRepository(string $repositoryClass): void
    {
        $this->repository = app($repositoryClass);
    }
}
