<?php

declare(strict_types=1);

namespace App\Application\Command\Centrifugo;

use App\Websocket\TokenGeneratorInterface;
use Spiral\Cqrs\Attribute\CommandHandler;

final class GenerateAuthenticationTokenHandler
{
    public function __construct(
        private readonly TokenGeneratorInterface $generator
    ) {
    }

    #[CommandHandler]
    public function __invoke(GenerateAuthenticationTokenCommand $command): string
    {
        return $this->generator->generate($command->userId);
    }
}
