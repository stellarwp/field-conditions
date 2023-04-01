<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions\Concerns;

use Closure;
use StellarWP\FieldConditions\Config;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\Contracts\ConditionSet;
use StellarWP\FieldConditions\FieldCondition;
use StellarWP\FieldConditions\NestedCondition;

/**
 * @template C of Condition
 * @template-extends ConditionSet<C>
 */
trait HasConditions
{
    /**
     * @var array<C>
     */
    protected $conditions = [];

    /**
     * Append condition instances to the end of the conditions array.
     *
     * @since 1.0.0
     *
     * @param Condition ...$conditions
     */
    public function append(...$conditions)
    {
        foreach ($conditions as $condition) {
            $this->validateCondition($condition);
            $this->conditions[] = $condition;
        }
    }

    /**
     * Returns all internal conditions.
     *
     * @since 1.0.0
     *
     * @return array<C>
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Returns whether the condition set has any conditions.
     *
     * @since 1.1.0
     */
    public function hasConditions(): bool
    {
        return ! empty($this->conditions);
    }

    /**
     * @since 1.1.1 return ConditionSet type for PHP 7.0 compatibility
     * @since 1.0.0
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function where($condition, string $comparisonOperator = null, $value = null): ConditionSet
    {
        return $this->and($condition, $comparisonOperator, $value);
    }

    /**
     * @since 1.1.1 return ConditionSet type for PHP 7.0 compatibility
     * @since 1.0.0
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function and($condition, string $comparisonOperator = null, $value = null): ConditionSet
    {
        $this->conditions[] = $this->createCondition($condition, $comparisonOperator, $value, 'and');

        return $this;
    }

    /**
     * @since 1.1.1 return ConditionSet type for PHP 7.0 compatibility
     * @since 1.0.0
     *
     * @param string|C|Closure $condition
     * @param string|null $comparisonOperator
     * @param mixed|null $value
     */
    public function or($condition, string $comparisonOperator = null, $value = null): ConditionSet
    {
        $this->conditions[] = $this->createCondition($condition, $comparisonOperator, $value, 'or');

        return $this;
    }

    /**
     * @param C|Closure|string $condition
     * @param string|null $comparisonOperator
     * @param mixed $value
     * @param string $logicalOperator
     *
     * @return Condition|FieldCondition|NestedCondition
     */
    private function createCondition($condition, string $comparisonOperator = null, $value = null, string $logicalOperator = null)
    {
        $baseConditionClass = static::getBaseConditionClass();
        if ($condition instanceof $baseConditionClass) {
            return $condition;
        }

        if ($condition instanceof Closure) {
            $nestedCondition = new NestedCondition([], $logicalOperator);
            $condition($nestedCondition);
            return $nestedCondition;
        }

        return new FieldCondition($condition, $comparisonOperator, $value, $logicalOperator);
    }

    /**
     * @since 1.0.0
     */
    public function passes(array $values): bool
    {
        return array_reduce(
            $this->conditions,
            static function ($carry, Condition $condition) use ($values) {
                return $condition->getLogicalOperator() === 'and'
                    ? $carry && $condition->passes($values)
                    : $carry || $condition->passes($values);
            },
            true
        );
    }

    /**
     * @since 1.0.0
     */
    public function fails(array $values): bool
    {
        return ! $this->passes($values);
    }

    /**
     * Returns the Condition interface/class used as the base for this ConditionSet. By default, this is Condition,
     * but this allows for creating a ConditionSet that only accepts a specific type of Condition.
     *
     * @since 1.0.0
     *
     * @return class-string<C>
     */
    protected static function getBaseConditionClass(): string
    {
        return Condition::class;
    }

    /**
     * Validates the condition based on the base condition class.
     *
     * @since 1.0.0
     *
     * @param $condition
     *
     * @return void
     */
    protected function validateCondition($condition)
    {
        $baseConditionClass = static::getBaseConditionClass();
        if ( ! $condition instanceof $baseConditionClass) {
            Config::throwInvalidArgumentException(
                sprintf(
                    'Condition must be an instance of %s, %s given.',
                    $baseConditionClass,
                    is_object($condition) ? get_class($condition) : gettype($condition)
                )
            );
        }
    }

    /**
     * Validates the conditions based on the base condition class.
     *
     * @since 1.0.0
     */
    protected function validateConditions(array $conditions)
    {
        foreach ($conditions as $condition) {
            $this->validateCondition($condition);
        }
    }
}
