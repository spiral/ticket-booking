<?php

declare(strict_types=1);

namespace Spiral\Shared\Broadcasting;

use Stringable;

interface ShouldBroadcastInterface
{
    public function getEventName(): string;

    public function getBroadcastTopics(): iterable|string|Stringable;

    public function getPayload(): array;
}
