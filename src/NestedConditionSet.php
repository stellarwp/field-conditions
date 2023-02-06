<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions;

use ArrayIterator;
use StellarWP\FieldConditions\Contracts\Condition;
use StellarWP\FieldConditions\Contracts\ConditionSet;

class NestedConditionSet implements ConditionSet
{
    /**
     * @var Condition[]
     */
    protected $conditions = [];

    /**
     * @unreleased
     */
    public function __construct(Condition ...$conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @unreleased
     *
     * @return Condition[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function passes(array $values): bool
    {
        return array_reduce(
            $this->conditions,
            static function (bool $passes, Condition $condition) use ($values) {
                return $condition->getLogicalOperator() === 'and'
                    ? $passes && $condition->passes($values)
                    : $passes || $condition->passes($values);
            },
            true
        );
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function fails(array $values): bool
    {
        return ! $this->passes($values);
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->conditions);
    }

    /**
     * @inheritDoc
     *
     * @unreleased
     */
    public function jsonSerialize()
    {
        return $this->conditions;
    }
}