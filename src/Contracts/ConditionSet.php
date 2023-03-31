<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions\Contracts;

use Closure;
use IteratorAggregate;
use JsonSerializable;

/**
 * @template C of Condition
 */
interface ConditionSet extends IteratorAggregate, JsonSerializable
{
    /**
     * Returns all conditions in the set.
     *
     * @since 1.0.0
     *
     * @return array<C>
     */
    public function getConditions(): array;

    /**
     * Returns true if the set has conditions.
     *
     * @since 1.1.0
     */
    public function hasConditions(): bool;

    /**
     * Add one or more conditions to the set;
     *
     * @since 1.0.0
     *
     * @param C ...$conditions
     *
     * @return void
     */
    public function append(...$conditions);

    /**
     * @since 1.0.0
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function where($condition, string $comparisonOperator = null, $value = null): self;

    /**
     * @since 1.0.0
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function and($condition, string $comparisonOperator = null, $value = null): self;

    /**
     * @since 1.0.0
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function or($condition, string $comparisonOperator = null, $value = null): self;

    /**
     * Returns true if all conditions in the set pass.
     *
     * @since 1.0.0
     *
     * @param array<string, mixed> $values
     */
    public function passes(array $values): bool;

    /**
     * Returns true if any condition in the set fails.
     *
     * @since 1.0.0
     *
     * @param array<string, mixed> $values
     */
    public function fails(array $values): bool;

    /**
     * Returns the conditions in array form for JSON serialization.
     *
     * @since 1.0.0
     *
     * @return list<array>
     */
    public function jsonSerialize(): array;
}
