<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions\Contracts;

use JsonSerializable;

interface Condition extends JsonSerializable
{
    const LOGICAL_OPERATORS = ['and', 'or'];

    /**
     * Returns the logical operator for this condition.
     *
     * @since 1.0.0
     *
     * @return 'and'|'or'
     */
    public function getLogicalOperator(): string;

    /**
     * Returns true if the condition passes.
     *
     * @param array<string, mixed> $values
     */
    public function passes(array $values): bool;

    /**
     * Returns true if the condition fails.
     *
     * @param array<string, mixed> $values
     */
    public function fails(array $values): bool;
}
