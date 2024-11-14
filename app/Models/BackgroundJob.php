<?php

namespace App\Models;

class BackgroundJob
{
    public function __construct(
        public string $id,
        public string $class,
        public string $method,
        public array $parameters = [],
        public int $priority = 0,
        public int $attempts = 0,
        // Number of seconds after which to run
        public int $runAt = 0
    ) {}

    /**
     * Convert job to array form
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'class' => $this->class,
            'method' => $this->method,
            'parameters' => $this->parameters,
            'priority' => $this->priority,
            'attempts' => $this->attempts,
            'run_at' => $this->runAt
        ];
    }
}
