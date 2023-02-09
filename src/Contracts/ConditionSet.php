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
     * Constructs the set with the given conditions
     *
     * @unreleased
     *
     * @param C ...$conditions
     *
     * @return void
     */
    public function __construct(...$conditions);

    /**
     * Returns all conditions in the set.
     *
     * @unreleased
     *
     * @return array<C>
     */
    public function getConditions(): array;

    /**
     * Add one or more conditions to the set;
     *
     * @unreleased
     *
     * @param C ...$conditions
     *
     * @return void
     */
    public function append(...$conditions);

    /**
     * @unreleased
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function where($condition, string $comparisonOperator = null, $value = null): self;

    /**
     * @unreleased
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function and($condition, string $comparisonOperator = null, $value = null): self;

    /**
     * @unreleased
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function or($condition, string $comparisonOperator = null, $value = null): self;

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
