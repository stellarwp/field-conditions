<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions\Contracts;

use IteratorAggregate;
use JsonSerializable;
use StellarWP\FieldConditions\Condition;

interface ConditionSet extends IteratorAggregate, JsonSerializable
{
    /**
     * Returns all conditions in the set.
     *
     * @unreleased
     *
     * @return array<Condition>
     */
    public function getConditions(): array;

    /**
     * Returns true if all conditions in the set pass.
     *
     * @unreleased
     *
     * @param array<string, mixed> $values
     */
    public function passes(array $values): bool;

    /**
     * Returns true if any condition in the set fails.
     *
     * @unreleased
     *
     * @param array<string, mixed> $values
     */
    public function fails(array $values): bool;
}