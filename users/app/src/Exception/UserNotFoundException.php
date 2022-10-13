<?php

declare(strict_types=1);

namespace App\Exception;

use Google\Rpc\ResourceInfo;
use Spiral\RoadRunner\GRPC\Exception\GRPCExceptionInterface;

class UserNotFoundException extends \DomainException implements GRPCExceptionInterface
{
    public function __construct(string $message = "", int $code = 409, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getDetails(): array
    {
        return [
          new ResourceInfo([
              'resource_type' => 'user',
              'description' => 'User with given data was not found.'
          ])
        ];
    }
}
