<?php

declare(strict_types=1);

namespace App\Application\Command\Centrifugo;

use Spiral\Cqrs\CommandInterface;

final class GenerateAuthenticationTokenCommand implements CommandInterface
{
    public function __construct(
        public readonly ?int $userId
    ) {
    }
}
