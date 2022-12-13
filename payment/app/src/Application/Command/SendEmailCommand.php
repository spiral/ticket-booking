<?php

declare(strict_types=1);

namespace App\Application\Command;

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
