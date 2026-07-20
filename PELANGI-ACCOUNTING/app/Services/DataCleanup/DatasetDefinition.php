<?php

namespace App\Services\DataCleanup;

final class DatasetDefinition
{
    /**
     * @param  list<array{table: string, fk: string}>  $children
     * @param  list<string>  $cascadeRelated
     * @param  list<array{table: string, column: string, company_scoped?: bool}>  $nullify
     * @param  list<array{table: string, column: string, label: string}>  $nullifyBlockers
     */
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $group,
        public readonly string $description,
        public readonly bool $danger,
        public readonly int $order,
        public readonly ?string $model = null,
        public readonly ?string $handler = null,
        public readonly array $children = [],
        public readonly array $cascadeRelated = [],
        public readonly array $nullify = [],
        public readonly array $nullifyBlockers = [],
    ) {}
}
