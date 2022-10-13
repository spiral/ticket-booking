<?php

declare(strict_types=1);

namespace App\Exception;

use Google\Rpc\ResourceInfo;
use Spiral\RoadRunner\GRPC\Exception\GRPCExceptionInterface;

class UserAlreadyExistsException extends \DomainException implements GRPCExceptionInterface
{
    public function getDetails(): array
    {
        return [
            new ResourceInfo([
                'resource_type' => 'user',
                'description' => 'User with given email address exists.',
            ]),
        ];
    }
}
