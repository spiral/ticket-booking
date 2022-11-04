<?php

declare(strict_types=1);

namespace App\Websocket;

use Firebase\JWT\JWT;

class JwtTokenGenerator implements TokenGeneratorInterface
{
    public function __construct(
        private readonly string $key,
        private readonly string $alg = 'HS256'
    ) {
    }

    public function generate(?int $userId): string
    {
        $payload = $userId === null
            ? ['sub' => '', ['info' => ['type' => 'anonymous']]]
            : ['sub' => (string)$userId, ['info' => ['type' => 'user']]];

//        $payload['channels'] = ['public'];
//
//        if ($userId !== null) {
//            $payload['channels'][] = '#user.' . $userId;
//        }

        return JWT::encode($payload, $this->key, $this->alg);
    }
}
