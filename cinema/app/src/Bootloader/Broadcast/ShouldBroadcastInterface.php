<?php

declare(strict_types=1);

namespace App\Broadcast;

interface ShouldBroadcastInterface
{
    public function getBroadcastTopics(): iterable|string|\Stringable;

    public function getPayload(): array;
}
