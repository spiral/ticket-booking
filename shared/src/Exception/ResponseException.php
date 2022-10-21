<?php

declare(strict_types=1);

namespace Spiral\Shared\Exception;

use Spiral\RoadRunner\GRPC\Exception\GRPCException;

class ResponseException extends GRPCException
{
    public static function createFromStatus(\stdClass $status): self
    {
        return new self(
            message: $status->details,
            code: $status->code,
            details: $status->metadata
        );
    }
}
