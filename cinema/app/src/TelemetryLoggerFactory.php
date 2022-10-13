<?php

declare(strict_types=1);

namespace App;

use Cycle\Database\Driver\DriverInterface;
use Cycle\Database\LoggerFactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Spiral\Telemetry\ClockInterface;
use Spiral\Telemetry\TracerInterface;

class TelemetryLoggerFactory implements LoggerFactoryInterface
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function getLogger(DriverInterface $driver = null): LoggerInterface
    {
        return new class($this->container, $driver::class) implements LoggerInterface {
            public function __construct(
                private readonly ContainerInterface $container,
                private readonly string $driverClass,
            ) {
            }

            public function emergency(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function alert(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function critical(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function error(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function warning(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function notice(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function info(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function debug(\Stringable|string $message, array $context = []): void
            {
                $this->log(__METHOD__, $message, $context);
            }

            public function log($level, \Stringable|string $message, array $context = []): void
            {
                $tracer = $this->container->get(TracerInterface::class);
                $clock = $this->container->get(ClockInterface::class);

                \assert($tracer instanceof TracerInterface);
                \assert($clock instanceof ClockInterface);

                $nanosPerSecond = 1_000_000_000;
                $startTime = $clock->now() - (int)(($context['elapsed'] ?? 0) * $nanosPerSecond);

                $context['message'] = (string)$message;
                $context['level'] = $level;

                $tracer->trace(
                    name: \sprintf('DB [%s]', $this->driverClass),
                    callback: fn() => true,
                    attributes: $context,
                    startTime: $startTime
                );
            }
        };
    }
}
