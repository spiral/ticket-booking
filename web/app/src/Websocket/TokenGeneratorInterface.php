<?php

declare(strict_types=1);

namespace App\Websocket;

interface TokenGeneratorInterface
{
    public function generate(?int $userId): string;
}
