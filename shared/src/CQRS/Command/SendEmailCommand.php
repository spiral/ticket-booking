<?php

declare(strict_types=1);

namespace Spiral\Shared\CQRS\Command;

use Spiral\Cqrs\CommandInterface;

final class SendEmailCommand implements CommandInterface
{
    public function __construct(
        public readonly string $template,
        public readonly string $email,
        public readonly array $data
    ) {
    }
}
