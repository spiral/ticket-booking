<?php

declare(strict_types=1);

namespace Spiral\Shared\Generators;

/**
 * @internal
 */
final class CompileResult
{
    public function __construct(
        private readonly array $files,
        private readonly array $services
    ) {
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getServices(): array
    {
        return $this->services;
    }
}
