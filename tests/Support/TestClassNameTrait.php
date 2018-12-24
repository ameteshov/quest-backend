<?php

namespace Tests\Support;

trait TestClassNameTrait
{
    protected $testClassName;

    protected function setTestClassName(): void
    {
        $class = get_class($this);

        $exploded = explode('\\', $class);

        $this->testClassName = last($exploded);
    }

    protected function getTestClassName(): string
    {
        if (empty($this->testClassName)) {
            $this->setTestClassName();
        }

        return $this->testClassName;
    }
}
