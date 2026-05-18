<?php

namespace App\Api\Config;

/**
 * Typed accessor for listing-field-map.php configuration.
 */
class ListingFieldMap
{
    private array $config;

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? require dirname(__DIR__) . '/Config/listing-field-map.php';
    }

    public function postType(): string
    {
        return $this->config['post_type'] ?? 'property';
    }

    /**
     * @return array<string, string|array<string, mixed>>
     */
    public function fields(): array
    {
        return $this->config['fields'] ?? [];
    }

    public function defaultScalar(): string
    {
        return $this->config['defaults']['scalar'] ?? '';
    }

    /**
     * @return string[]
     */
    public function arrayFields(): array
    {
        return $this->config['array_fields'] ?? [];
    }

    public function isArrayField(string $externalField): bool
    {
        return in_array($externalField, $this->arrayFields(), true);
    }
}
