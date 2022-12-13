<?php

declare(strict_types=1);

namespace App\Application\Command;

use Spiral\Cqrs\Attribute\CommandHandler;
use Spiral\Mailer\MailerInterface;
use Spiral\Mailer\Message;

final class SendEmailHandler
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    #[CommandHandler]
    public function __invoke(SendEmailCommand $command): void
    {
        $this->mailer->send(
            new Message(
                subject: $command->template,
                to: $command->email,
                data: $command->data
            )
        );
    }
}
