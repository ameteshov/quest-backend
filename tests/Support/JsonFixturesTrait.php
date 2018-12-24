<?php

namespace Tests\Support;

trait JsonFixturesTrait
{
    use TestClassNameTrait;

    public function getJsonFixture(string $filename): array
    {
        $path = base_path("tests/Fixtures/{$this->getTestClassName()}/$filename");

        if (!is_file($path) || !is_readable($path)) {
            throw new \LogicException("File path {$path} not readable or not exists");
        }

        return json_decode(file_get_contents($path), true);
    }
}
