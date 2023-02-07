<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions\Contracts;

use IteratorAggregate;
use JsonSerializable;

interface ConditionSet extends IteratorAggregate, JsonSerializable
{
    /**
     * Constructs the set with the given conditions
     *
     * @unreleased
     *
     * @param Condition ...$conditions
     *
     * @return void
     */
    public function __construct(...$conditions);

    /**
     * Returns all conditions in the set.
     *
     * @unreleased
     *
     * @return array<Condition>
     */
    public function getConditions(): array;

    /**
     * Add one or more conditions to the set;
     *
     * @unreleased
     *
     * @param Condition ...$conditions
     *
     * @return void
     */
    public function addConditions(...$conditions);

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

    /**
     * Returns the conditions in array form for JSON serialization.
     *
     * @unreleased
     *
     * @return list<array>
     */
    public function jsonSerialize(): array;
}
