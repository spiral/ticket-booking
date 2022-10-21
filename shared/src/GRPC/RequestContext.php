<?php

declare(strict_types=1);

namespace Spiral\Shared\GRPC;

use Spiral\RoadRunner\GRPC\ContextInterface;

final class RequestContext implements ContextInterface
{
    private array $values = [];

    /**
     * @param array<string, mixed> $values
     */
    public function __construct(
        array $values = []
    ) {
        $this->setValues($values);
    }

    public function withTelemetry(?array $context): ContextInterface
    {
        $metadata = $this->getValue('metadata', []);
        $metadata['telemetry'] = [\json_encode($context)];

        return $this->withMetadata($metadata);
    }

    public function getTelemetry(): array
    {
        $value = $this->getValue('metadata', [])['telemetry'][0] ?? null;

        if ($value !== null) {
            return (array) \json_decode($value, true);
        }

        return [];
    }

    /**
     * Add value to the metadata.
     */
    public function withToken(?string $token, string $key = 'token'): ContextInterface
    {
        if ($token === null) {
            return $this;
        }

        $metadata = $this->getValue('metadata', []);
        $metadata[$key] = [$token];

        return $this->withMetadata($metadata);
    }

    /**
     * Get token from the metadata.
     */
    public function getToken(string $key = 'token'): ?string
    {
        return $this->getValue('metadata', [])[$key][0] ?? null;
    }

    /**
     * Set metadata to the context.
     */
    public function withMetadata(array $metadata): ContextInterface
    {
        return $this->withValue('metadata', $metadata);
    }

    /**
     * Set options to the context.
     */
    public function withOptions(array $metadata): ContextInterface
    {
        return $this->withValue('options', $metadata);
    }

    /**
     * Add value to the context.
     */
    public function withValue(string $key, $value): ContextInterface
    {
        $ctx = clone $this;
        $ctx->values[$key] = $value;

        return $ctx;
    }

    /**
     * Get value from the context.
     */
    public function getValue(string $key, $default = null): mixed
    {
        return $this->values[$key] ?? $default;
    }

    /**
     * Get all values from the context.
     */
    public function getValues(): array
    {
        return $this->values;
    }

    private function setValues(array $values)
    {
        $metadata = [];
        $system = [
            'grpc-accept-encoding',
            'content-type',
            'user-agent',
            \Spiral\RoadRunner\GRPC\ResponseHeaders::class,
        ];

        foreach ($values as $key => $value) {
            if (!\str_starts_with($key, ':') && !\in_array($key, $system)) {
                $metadata[$key] = $value;
                continue;
            }

            $this->values[$key] = $value;
        }

        $this->values['metadata'] = $metadata;
    }
}
