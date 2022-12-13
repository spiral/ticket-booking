<?php

declare(strict_types=1);

namespace App\Services\Mappers;

use Carbon\Carbon;
use Google\Protobuf\Timestamp;

final class TimestampFactory
{
    public static function now(): Timestamp
    {
        return self::fromDateTimeInterface(Carbon::now());
    }

    public static function fromTimestamp(int $timestamp): Timestamp
    {
        return self::fromDateTimeInterface(Carbon::createFromTimestamp($timestamp));
    }

    public static function fromDateTimeInterface(\DateTimeInterface $dateTime): Timestamp
    {
        $timestamp = new Timestamp();
        $timestamp->fromDateTime(\DateTime::createFromInterface($dateTime));

        return $timestamp;
    }
}
